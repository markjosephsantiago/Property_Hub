<?php
session_start();
require '../../includes/conn.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Invalid request");
}

$reservation_id = $_POST['reservation_id'];
$room_id = $_POST['room_id'];
$quantities = $_POST['quantity']; // food_id => qty

if (!$reservation_id || !$room_id || empty($quantities)) {
    $_SESSION['error'] = "Invalid food order.";
    header("Location: food.menu.php");
    exit();
}

// 🔹 Get guest name from reservation
$res = $conn->prepare("SELECT guestName FROM tbl_reservations WHERE reservation_id = ?");
$res->bind_param("i", $reservation_id);
$res->execute();
$resData = $res->get_result()->fetch_assoc();

$guest_name = $resData['guestName'] ?? 'Guest';

$order_total = 0;
$order_items = [];

// 🔹 Compute total + prepare items
foreach ($quantities as $food_id => $qty) {
    if ($qty > 0) {
        $food = $conn->query(
            "SELECT food_name, price 
             FROM tbl_food_menu 
             WHERE food_id = " . intval($food_id)
        )->fetch_assoc();

        if ($food) {
            $subtotal = $food['price'] * $qty;
            $order_total += $subtotal;

            $order_items[] = [
                'name' => $food['food_name'],
                'price' => $food['price'],
                'qty' => $qty,
                'subtotal' => $subtotal
            ];
        }
    }
}

// 🚫 No items ordered
if (empty($order_items)) {
    $_SESSION['error'] = "Please select at least one food item.";
    header("Location: food.menu.php?reservation_id=$reservation_id&room_id=$room_id");
    exit();
}

$conn->begin_transaction();

try {
    // 1️⃣ INSERT PARENT ORDER
    $stmt = $conn->prepare("
        INSERT INTO tbl_food_orders 
        (reservation_id, room_id, guest_name, order_total, order_status)
        VALUES (?, ?, ?, ?, 'pending')
    ");
    $stmt->bind_param("iisd", $reservation_id, $room_id, $guest_name, $order_total);
    $stmt->execute();

    $order_id = $conn->insert_id;

    // 2️⃣ INSERT CHILD ITEMS
    $itemStmt = $conn->prepare("
        INSERT INTO tbl_food_order_items
        (order_id, food_name, food_price, quantity, subtotal)
        VALUES (?, ?, ?, ?, ?)
    ");

    foreach ($order_items as $item) {
        $itemStmt->bind_param(
            "isdid",
            $order_id,
            $item['name'],
            $item['price'],
            $item['qty'],
            $item['subtotal']
        );
        $itemStmt->execute();
    }

    $conn->commit();

    $_SESSION['success'] = "🍽️ Food order placed successfully!";
    header("Location: food.success.php?reservation _id=" . $reservation_id);
    exit();

} catch (Exception $e) {
    $conn->rollback();
    $_SESSION['error'] = "Food order failed.";
    header("Location: food.menu.php");
    exit();
}
