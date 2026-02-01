<?php
session_start();
require '../../../includes/conn.php';
require '../../../includes/class.reservation.php';

// Access control
if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], ['Admin', 'Employee'])) {
    $_SESSION['error'] = "Access denied.";
    header("Location: ../status.list.php");
    exit();
}

$booking_id = $_POST['booking_id'] ?? $_GET['id'] ?? null;
$source = $_POST['source'] ?? $_GET['source'] ?? 'booking';

if (!$booking_id) {
    $_SESSION['error'] = "Booking not found.";
    header("Location: ../status.list.php");
    exit();
}

try {
    $reservation = new Reservation($conn);
    $reservation->cancel($booking_id);
    $_SESSION['success'] = "Booking cancelled successfully!";
} catch (Exception $e) {
    $_SESSION['error'] = "Failed to cancel booking: " . $e->getMessage();
}

header("Location: ../status.list.php");
exit();
?>
