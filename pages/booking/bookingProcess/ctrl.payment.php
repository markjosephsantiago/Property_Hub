<?php
session_start();
require '../../../includes/conn.php';

// ✅ Ensure valid POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['error'] = "Invalid request method.";
    header("Location: ../booking/payment.form.php");
    exit();
}

$reservation_id  = $_POST['reservation_id'] ?? '';
$payment_method  = $_POST['payment_method'] ?? '';
$amount          = $_POST['amount'] ?? 0;

// ✅ Basic validation
if (empty($reservation_id) || empty($payment_method) || $amount <= 0) {
    $_SESSION['error'] = "Incomplete payment details.";
    header("Location: ../booking/payment.form.php?reservation_id=" . $reservation_id);
    exit();
}

// 🔍 Verify reservation exists
$stmt = $conn->prepare("SELECT * FROM tbl_reservations WHERE reservation_id = ?");
$stmt->bind_param("i", $reservation_id);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows === 0) {
    $_SESSION['error'] = "Reservation not found.";
    header("Location: ../booking/booking.form.php");
    exit();
}

$booking = $res->fetch_assoc();

// ✅ Insert payment record (no reference column)
$insert = $conn->prepare("
    INSERT INTO tbl_payment (reservation_id, payment_method, amount, payment_date, payment_status)
    VALUES (?, ?, ?, NOW(), ?)
");
$status = 'paid';
$insert->bind_param("isds", $reservation_id, $payment_method, $amount, $status);
$insert->execute();

// ✅ Update reservation status to "pending"
$update = $conn->prepare("UPDATE tbl_reservations SET status = 'pending' WHERE reservation_id = ?");
$update->bind_param("i", $reservation_id);
$update->execute();

$_SESSION['success'] = "Payment successfully recorded for reservation #$reservation_id.";

// ✅ Redirect to confirmation page
header("Location: ../checkout.receipt.php?reservation_id=" . $reservation_id);
exit();
?>
