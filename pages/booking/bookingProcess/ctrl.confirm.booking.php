<?php
session_start();
require '../../../includes/conn.php';

// ✅ Show all errors for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// ✅ Role check
if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], ['Admin', 'Employee'])) {
    $_SESSION['error'] = "Access denied.";
    header("Location: ../booking.list.php");
    exit();
}

// ✅ Accept GET since link uses GET parameter
if (isset($_GET['id'])) {
    $booking_id = intval($_GET['id']);

    $conn->begin_transaction();
    try {
        // 🔹 Update reservation status
        $update = $conn->prepare("UPDATE tbl_reservations SET status = 'confirmed' WHERE reservation_id = ?");
        $update->bind_param("i", $booking_id);
        $update->execute();

        // 🔹 Get room ID
        $query = $conn->prepare("SELECT room_id FROM tbl_reservations WHERE reservation_id = ?");
        $query->bind_param("i", $booking_id);
        $query->execute();
        $room = $query->get_result()->fetch_assoc();

        // 🔹 Update room to occupied
        if ($room) {
            $room_id = $room['room_id'];
            $roomUpdate = $conn->prepare("UPDATE tbl_rooms SET status = 'occupied' WHERE room_id = ?");
            $roomUpdate->bind_param("i", $room_id);
            $roomUpdate->execute();
        }

        $conn->commit();
        $_SESSION['success'] = "Booking confirmed successfully! Room marked as occupied.";
    } catch (Exception $e) {
        $conn->rollback();
        $_SESSION['error'] = "Error confirming booking: " . $e->getMessage();
    }

    header("Location: ../status.list.php");
    exit();
} else {
    $_SESSION['error'] = "Invalid booking ID.";
    header("Location: ../status.list.php");
    exit();
}
?>
