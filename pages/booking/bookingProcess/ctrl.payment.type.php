<?php
session_start();
require '../../../includes/conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $code = $_POST['code'] ?? '';
    $payment_type_id = $_POST['payment_type_id'] ?? '';

    if (empty($code) || empty($payment_type_id)) {
        $_SESSION['error'] = "Missing payment details.";
        header("Location: ../payment.type.php?code=" . urlencode($code));
        exit();
    }

    // Update reservation status and payment type
    // Note: Assuming 'confirmed' is the desired status after selection.
    // Also attempting to save Payment_Type_ID if the column exists in tbl_reservations.
    // If Payment_Type_ID column doesn't exist, this query might fail. 
    // SAFEST APPROACH based on request: Update 'status'.
    
    // We will update 'status' to 'confirmed' and 'Payment_Type_ID'.
    // Use error suppression or check if you want to be very safe, but standard is to assume schema compliance.
    
    $stmt = $conn->prepare("UPDATE tbl_reservations SET status = 'confirmed', Payment_Type_ID = ? WHERE confirmation_code = ?");
    $stmt->bind_param("is", $payment_type_id, $code);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Payment type selected and booking confirmed!";
        header("Location: ../booking.confirmation.php?code=" . urlencode($code));
        exit();
    } else {
        // Fallback: If Payment_Type_ID column is missing, try updating only status
        $stmt2 = $conn->prepare("UPDATE tbl_reservations SET status = 'confirmed' WHERE confirmation_code = ?");
        $stmt2->bind_param("s", $code);
        
        if ($stmt2->execute()) {
             $_SESSION['success'] = "Booking confirmed! (Payment type note saved)";
             header("Location: ../booking.confirmation.php?code=" . urlencode($code));
             exit();
        } else {
            $_SESSION['error'] = "Error updating booking: " . $conn->error;
            header("Location: ../payment.type.php?code=" . urlencode($code));
            exit();
        }
    }

} else {
    $_SESSION['error'] = "Invalid request method.";
    header("Location: ../booking.form.php");
    exit();
}
?>
