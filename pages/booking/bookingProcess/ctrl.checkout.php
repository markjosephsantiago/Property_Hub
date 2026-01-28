<?php
session_start();
require '../../includes/conn.php';

$reservation_id = $_GET['reservation_id'] ?? null;

if (!$reservation_id) {
    $_SESSION['error'] = "Invalid checkout request.";
    header("Location: booking.list.php");
    exit();
}

$conn->begin_transaction();

try {
    // 1️⃣ Get reservation + room info
    $stmt = $conn->prepare("
        SELECT r.*, rm.price, rm.room_id
        FROM tbl_reservations r
        JOIN tbl_rooms rm ON r.room_id = rm.room_id
        WHERE r.reservation_id = ?
    ");
    $stmt->bind_param("i", $reservation_id);
    $stmt->execute();
    $booking = $stmt->get_result()->fetch_assoc();

    if (!$booking) {
        throw new Exception("Reservation not found");
    }

    // 2️⃣ Compute room total
    $room_total = $booking['price'] * ($booking['duration'] / 24);

    // 3️⃣ Compute food total
    $foodStmt = $conn->prepare("
        SELECT SUM(order_total) AS food_total
        FROM tbl_food_orders
        WHERE reservation_id = ?
        AND order_status != 'cancelled'
    ");
    $foodStmt->bind_param("i", $reservation_id);
    $foodStmt->execute();
    $food_total = $foodStmt->get_result()->fetch_assoc()['food_total'] ?? 0;

    // 4️⃣ Grand total
    $grand_total = $room_total + $food_total;

    // 5️⃣ Update reservation
    $update = $conn->prepare("
        UPDATE tbl_reservations
        SET status = 'checked_out',
            total_bill = ?
        WHERE reservation_id = ?
    ");
    $update->bind_param("di", $grand_total, $reservation_id);
    $update->execute();

    // 6️⃣ Mark food orders as served
    $conn->prepare("
        UPDATE tbl_food_orders
        SET order_status = 'served'
        WHERE reservation_id = ?
    ")->bind_param("i", $reservation_id)->execute();

    // 7️⃣ Free the room
    $roomUpdate = $conn->prepare("
        UPDATE tbl_rooms
        SET status = 'available'
        WHERE room_id = ?
    ");
    $roomUpdate->bind_param("i", $booking['room_id']);
    $roomUpdate->execute();

    $conn->commit();

    $_SESSION['success'] = "Checkout successful! Total bill: ₱" . number_format($grand_total, 2);
    header("Location: checkout.receipt.php?reservation_id=" . $reservation_id);
    exit();

} catch (Exception $e) {
    $conn->rollback();
    $_SESSION['error'] = "Checkout failed.";
    header("Location: booking.list.php");
    exit();
}
