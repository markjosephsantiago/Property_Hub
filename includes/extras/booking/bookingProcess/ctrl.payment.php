<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

session_start();
include '../../../includes/conn.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $reservation_id = $_POST['reservation_id'] ?? null;
    $amount = $_POST['amount'] ?? 0;
    $method = $_POST['payment_method'] ?? '';

    if (!$reservation_id || !$method) {
        echo "<script>alert('Missing payment information.'); window.history.back();</script>";
        exit();
    }

    $sql = "INSERT INTO tbl_payment (reservation_id, amount, payment_method, payment_status, date_paid)
            VALUES (?, ?, ?, 'Paid', NOW())";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ids", $reservation_id, $amount, $method);

    if ($stmt->execute()) {
        $payment_id = $stmt->insert_id;

        // mark reservation as paid
        $update = $conn->prepare("UPDATE tbl_reservations SET status = 'paid' WHERE reservation_id = ?");
        $update->bind_param("i", $reservation_id);
        $update->execute();

        // fetch payment + user info
        $query = "
            SELECT p.*, r.guestName, r.email, r.checkin, r.checkout, r.duration_days, 
                   r.confirmation_code, rm.room_number, rm.room_type
            FROM tbl_payment p
            JOIN tbl_reservations r ON p.reservation_id = r.reservation_id
            JOIN tbl_rooms rm ON r.room_id = rm.room_id
            WHERE p.payment_id = ?";
        $fetch = $conn->prepare($query);
        $fetch->bind_param("i", $payment_id);
        $fetch->execute();
        $payment = $fetch->get_result()->fetch_assoc();

        // include PHPMailer
        require '../../../includes/PHPMailer/Exception.php';
        require '../../../includes/PHPMailer/PHPMailer.php';
        require '../../../includes/PHPMailer/SMTP.php';

        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'your_email@gmail.com'; // ⚠️ palitan ng sender email
            $mail->Password = 'your_app_password';   // ⚠️ Gmail App Password
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('your_email@gmail.com', 'Property Hub');
            $mail->addAddress($payment['guestEmail'], $payment['guestName']);

            $mail->isHTML(true);
            $mail->Subject = 'Payment Receipt - Property Hub';
            $mail->Body = "
                <h2>Payment Receipt</h2>
                <p>Dear {$payment['guestName']},</p>
                <p>Thank you for your payment. Here are your booking details:</p>
                <table border='1' cellspacing='0' cellpadding='8'>
                    <tr><td><b>Confirmation Code:</b></td><td>{$payment['confirmation_code']}</td></tr>
                    <tr><td><b>Room:</b></td><td>{$payment['room_number']} ({$payment['room_type']})</td></tr>
                    <tr><td><b>Check-in:</b></td><td>{$payment['checkin']}</td></tr>
                    <tr><td><b>Check-out:</b></td><td>{$payment['checkout']}</td></tr>
                    <tr><td><b>Duration:</b></td><td>{$payment['duration_days']} days</td></tr>
                    <tr><td><b>Amount Paid:</b></td><td>₱" . number_format($payment['amount'], 2) . "</td></tr>
                    <tr><td><b>Payment Method:</b></td><td>{$payment['payment_method']}</td></tr>
                    <tr><td><b>Date Paid:</b></td><td>{$payment['date_paid']}</td></tr>
                </table>
                <p>We look forward to serving you.<br><b>Property Hub Team</b></p>
            ";

            $mail->send();
        } catch (Exception $e) {
            error_log("Email not sent: {$mail->ErrorInfo}");
        }

        $_SESSION['payment_success_id'] = $payment_id;
        header("Location: ../payment.success.php");
        exit();
    } else {
        echo "<script>
                alert('Error saving payment: " . $conn->error . "');
                window.history.back();
              </script>";
    }

    $stmt->close();
    $conn->close();
}
?>
