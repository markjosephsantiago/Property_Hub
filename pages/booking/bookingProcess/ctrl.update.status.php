<?php
session_start();
require '../../../includes/conn.php';

// Access control
if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], ['Admin', 'Employee'])) {
    $_SESSION['error'] = "Access denied.";
    header("Location: ../booking.list.php");
    exit();
}

$id = $_GET['id'] ?? null;
$action = $_GET['action'] ?? null;

if (!$id || !$action) {
    $_SESSION['error'] = "Invalid request.";
    header("Location: ../booking.list.php");
    exit();
}

$conn->begin_transaction();

try {
    // Get room id for this reservation
    $roomQuery = $conn->prepare("SELECT room_id FROM tbl_reservations WHERE reservation_id = ?");
    $roomQuery->bind_param("i", $id);
    $roomQuery->execute();
    $room = $roomQuery->get_result()->fetch_assoc();
    $room_id = $room['room_id'] ?? null;

    if (!$room_id) {
        throw new Exception("Room not found for this booking.");
    }

    switch ($action) {
        case 'checkin':
            $status = 'checkin';
            $roomStatus = 'occupied';
            $msg = "Guest checked in successfully!";
            break;

        case 'checkout':
            $status = 'checkout';
            $roomStatus = 'available';
            $msg = "Guest checked out successfully!";
            break;

        default:
            throw new Exception("Invalid action.");
    }

    // Update reservation status
    $updateBooking = $conn->prepare("UPDATE tbl_reservations SET status = ? WHERE reservation_id = ?");
    $updateBooking->bind_param("si", $status, $id);
    $updateBooking->execute();

    // Update room status
    $updateRoom = $conn->prepare("UPDATE tbl_rooms SET status = ? WHERE room_id = ?");
    $updateRoom->bind_param("si", $roomStatus, $room_id);
    $updateRoom->execute();

    $conn->commit();
    $_SESSION['success'] = $msg;

} catch (Exception $e) {
    $conn->rollback();
    $_SESSION['error'] = "Error updating status: " . $e->getMessage();
}

header("Location: ../status.list.php?filter=?");
exit();
?>
