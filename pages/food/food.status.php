<?php
require '../../includes/conn.php';

$order_id = $_GET['id'];
$status = $_GET['status'];

$allowed = ['served', 'preparing'];

if (!in_array($status, $allowed)) {
    die("Invalid status");
}

$stmt = $conn->prepare("
    UPDATE tbl_food_orders
    SET order_status = ?
    WHERE order_id = ?
");
$stmt->bind_param("si", $status, $order_id);
$stmt->execute();

header("Location: food.orders.list.php");
exit;
