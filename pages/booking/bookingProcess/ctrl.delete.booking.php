<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require '../../../includes/conn.php';

// 🔐 Role check
if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], ['Admin', 'Employee'])) {
    $_SESSION['error'] = "Access denied.";
    header("Location: ../booking.list.php");
    exit();
}

// ✅ Get booking ID (from POST or GET)
$booking_id = $_POST['booking_id'] ?? $_GET['id'] ?? null;
$source = $_POST['source'] ?? $_GET['source'] ?? 'booking';

if (!$booking_id) {
    $_SESSION['error'] = "Invalid booking ID.";
    header("Location: ../booking.list.php");
    exit();
}

// ✅ Delete booking record
$stmt = $conn->prepare("DELETE FROM tbl_reservations WHERE reservation_id = ?");
$stmt->bind_param("i", $booking_id);

if ($stmt->execute()) {
    $_SESSION['success'] = "Booking deleted successfully!";
} else {
    $_SESSION['error'] = "Failed to delete booking. " . $stmt->error;
}

$stmt->close();

// ✅ Redirect based on source
if ($source === 'status') {
    header("Location: ../status.list.php");
} else {
    header("Location: ../booking.list.php");
}
exit();
