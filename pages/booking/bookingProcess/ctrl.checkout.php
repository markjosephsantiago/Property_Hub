<?php
session_start();
require '../../../includes/conn.php';
require '../../../includes/class.reservation.php';

ini_set('display_errors', 1);
error_reporting(E_ALL);

$reservation_id = $_POST['reservation_id'] ?? $_GET['reservation_id'] ?? null;

if (!$reservation_id) {
    $_SESSION['error'] = "Invalid checkout request.";
    header("Location: ../status.list.php");
    exit();
}

try {
    $reservation = new Reservation($conn);
    $total_bill = $reservation->checkOut($reservation_id);

    // Handle Amount Tendered (Cash)
    $amount_tendered = $_POST['amount_tendered'] ?? 0;
    $change = 0;
    
    if ($amount_tendered > 0) {
        $change = $amount_tendered - $total_bill;
        // Optional: Update payment record with amount_paid if column exists, 
        // but for now we just pass it to receipt as requested for "deduction" display.
    }

    header("Location: ../checkout.receipt.php?reservation_id=" . $reservation_id . "&tendered=" . $amount_tendered . "&change=" . $change);
    exit();

} catch (Exception $e) {
    $_SESSION['error'] = "Checkout failed: " . $e->getMessage();
    header("Location: ../status.list.php");
    exit();
}
?>
