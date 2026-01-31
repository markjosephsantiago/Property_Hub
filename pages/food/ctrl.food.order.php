<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
require '../../includes/conn.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

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

// Get guest name from reservation
$res = $conn->prepare("SELECT guestName FROM tbl_reservations WHERE reservation_id = ?");
$res->bind_param("i", $reservation_id);
$res->execute();
$resData = $res->get_result()->fetch_assoc();

$guest_name = $resData['guestName'] ?? 'Guest';

$order_total = 0;
$order_items = [];

// Compute total + prepare items
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

// No items ordered
if (empty($order_items)) {
    $_SESSION['error'] = "Please select at least one food item.";
    header("Location: food.menu.php?reservation_id=$reservation_id&room_id=$room_id");
    exit();
}

$conn->begin_transaction();

try {
    // INSERT FOOD ORDER
    $stmt = $conn->prepare("
        INSERT INTO tbl_food_orders 
        (user_id, reservation_id, room_id, guest_name, order_total, order_status)
        VALUES (?, ?, ?, ?, ?, 'pending')
    ");
    $stmt->bind_param("iiisd",
        $user_id,
        $reservation_id,
        $room_id,
        $guest_name,
        $order_total
    );
    $stmt->execute();

    $order_id = $conn->insert_id;


    // INSERT ORDER ITEMS
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

    // 🔔 Insert notification for Admin & Employee
    $notif = $conn->prepare("
        INSERT INTO tbl_notifications (user_role, message, link)
        VALUES (?, ?, ?)
    ");

    $msg  = "New food order from Room $room_id";
    $link = "../food/food.orders.list.php";

    foreach (['admin','employee'] as $role) {
        $notif->bind_param("sss", $role, $msg, $link);
        $notif->execute();
    }

    $conn->commit();

    $_SESSION['food_status']  = 'success';
    $_SESSION['food_message'] = '🍽️ Food order placed successfully!';
    header("Location: food.menu.php?reservation_id=$reservation_id&room_id=$room_id");
    exit();

} catch (Exception $e) {
    $conn->rollback();

    $_SESSION['food_status']  = 'error';
    $_SESSION['food_message'] = '❌ Food order failed.';
    header("Location: food.menu.php?reservation_id=$reservation_id&room_id=$room_id");
    exit();
}
