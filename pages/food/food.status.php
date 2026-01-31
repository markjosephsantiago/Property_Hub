<?php
session_start();
require '../../includes/conn.php';

// 🔐 Allow Admin / Employee only
if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], ['Admin', 'Employee'])) {
    die("Unauthorized access");
}

// ✅ Validate inputs
if (!isset($_GET['id'], $_GET['status'])) {
    die("Invalid request");
}

$order_id = intval($_GET['id']);
$status   = $_GET['status'];

$allowed = ['preparing', 'served'];

if (!in_array($status, $allowed)) {
    die("Invalid status");
}

// 🔄 Update status
$stmt = $conn->prepare("
    UPDATE tbl_food_orders
    SET order_status = ?
    WHERE order_id = ?
");
$stmt->bind_param("si", $status, $order_id);
$stmt->execute();

// 🔙 Back to list
header("Location: food.orders.list.php");
exit;
