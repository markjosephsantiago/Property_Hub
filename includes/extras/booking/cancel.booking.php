<?php
session_start();
require '../../includes/conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['booking_id'])) {
    $booking_id = $_POST['booking_id'];

    $stmt = $conn->prepare("UPDATE tbl_reservations SET status = 'cancelled' WHERE reservation_id = ?");
    $stmt->bind_param("i", $booking_id);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Booking cancelled successfully.";
    } else {
        $_SESSION['error'] = "Failed to cancel booking.";
    }

    header("Location: ../booking/my_bookings.php");
    exit();
} else {
    $_SESSION['error'] = "Invalid request.";
    header("Location: ../booking/my_bookings.php");
    exit();
}
