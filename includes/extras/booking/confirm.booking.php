<?php
session_start();
require '../../includes/conn.php';

if ($_SESSION['role'] !== 'Admin') {
    $_SESSION['error'] = "Access denied.";
    header("Location: ../admin/admin_bookings.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $booking_id = $_POST['booking_id'] ?? null;

    if (!$booking_id) {
        $_SESSION['error'] = "No booking selected.";
        header("Location: ../admin/admin_bookings.php");
        exit();
    }

    $stmt = $conn->prepare("UPDATE tbl_reservations SET status = 'confirmed' WHERE reservation_id = ?");
    $stmt->bind_param("i", $booking_id);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Booking confirmed.";
    } else {
        $_SESSION['error'] = "Error: " . $conn->error;
    }

    header("Location: ../admin/admin_bookings.php");
    exit();
}
?>
