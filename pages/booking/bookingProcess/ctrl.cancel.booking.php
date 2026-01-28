<?php
session_start();
require '../../../includes/conn.php';

// ✅ Access control
if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], ['Admin', 'Employee'])) {
    $_SESSION['error'] = "Access denied.";
    header("Location: ../booking.list.php");
    exit();
}

// ✅ Check kung may data
$booking_id = $_POST['booking_id'] ?? null;
$source = $_POST['source'] ?? 'booking';

if (!$booking_id) {
    $_SESSION['error'] = "Booking not found.";
    if ($source === 'status') {
        header("Location: ../status.list.php");
    } else {
        header("Location: ../booking.list.php");
    }
    exit();
}

// ✅ Cancel booking
$stmt = $conn->prepare("UPDATE tbl_reservations SET status = 'cancelled' WHERE reservation_id = ?");
$stmt->bind_param("i", $booking_id);

if ($stmt->execute()) {
    $_SESSION['success'] = "Booking cancelled successfully!";
} else {
    $_SESSION['error'] = "Failed to cancel booking.";
}

// ✅ Redirect back
if ($source === 'status') {
    header("Location: ../status.list.php");
} else {
    header("Location: ../booking.list.php");
}
exit();
?>
