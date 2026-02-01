<?php
session_start();
require '../../../includes/conn.php';
require '../../../includes/class.reservation.php';

// Role check
if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], ['Admin', 'Employee'])) {
    $_SESSION['error'] = "Access denied.";
    header("Location: ../status.list.php");
    exit();
}

$booking_id = $_POST['booking_id'] ?? $_GET['id'] ?? null;

if (!$booking_id) {
    $_SESSION['error'] = "Invalid booking ID.";
    header("Location: ../status.list.php");
    exit();
}

try {
    $reservation = new Reservation($conn);
    $reservation->delete($booking_id);
    $_SESSION['success'] = "Booking deleted successfully!";
} catch (Exception $e) {
    $_SESSION['error'] = "Failed to delete booking: " . $e->getMessage();
}

header("Location: ../status.list.php");
exit();
?>
