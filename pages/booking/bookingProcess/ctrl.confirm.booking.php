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

$id = $_GET['id'] ?? null;
$source = $_GET['source'] ?? 'booking';

if (!$id) {
    $_SESSION['error'] = "Invalid request.";
    header("Location: ../status.list.php");
    exit();
}

try {
    $reservation = new Reservation($conn);
    $reservation->confirm($id);
    $_SESSION['success'] = "Booking confirmed successfully!";
} catch (Exception $e) {
    $_SESSION['error'] = "Error confirming booking: " . $e->getMessage();
}

header("Location: ../status.list.php");
exit();
?>
