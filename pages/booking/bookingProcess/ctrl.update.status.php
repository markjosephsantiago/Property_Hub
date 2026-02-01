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
$action = $_GET['action'] ?? null;

if (!$id || !$action) {
    $_SESSION['error'] = "Invalid request.";
    header("Location: ../status.list.php");
    exit();
}

try {
    $reservation = new Reservation($conn);
    $msg = "";
    $status = "";

    switch ($action) {
        case 'checkin':
            $new_room_id = $_POST['room_id'] ?? null;
            $reservation->checkIn($id, $new_room_id);
            $msg = "Guest checked in successfully!";
            $status = 'checkin';
            break;

        case 'checkout':
            // NOTE: Usually checkout is handled by ctrl.checkout.php, 
            // but if this controller is called for checkout, we use the class method.
            $reservation->checkOut($id);
            $msg = "Guest checked out successfully!";
            $status = 'checkout';
            break;

        default:
            throw new Exception("Invalid action.");
    }

    $_SESSION['success'] = $msg;

} catch (Exception $e) {
    $_SESSION['error'] = "Error updating status: " . $e->getMessage();
}

header("Location: ../status.list.php?filter=" . $status);
exit();
?>
