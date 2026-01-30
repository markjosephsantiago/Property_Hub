<?php
session_start();
require '../../includes/conn.php';

$reservation_id = $_GET['reservation_id'] ?? null;

if (!$reservation_id) {
    die("Invalid receipt request.");
}

// 🔹 Reservation + Room
$stmt = $conn->prepare("
    SELECT r.*, rm.room_number, rm.room_type, rm.price
    FROM tbl_reservations r
    JOIN tbl_rooms rm ON rm.room_id = r.room_id
    WHERE r.reservation_id = ?
");
$stmt->bind_param("i", $reservation_id);
$stmt->execute();
$booking = $stmt->get_result()->fetch_assoc();

if (!$booking) {
    die("Reservation not found.");
}

// 🔹 Food total
$foodStmt = $conn->prepare("
    SELECT SUM(order_total) AS food_total
    FROM tbl_food_orders
    WHERE reservation_id = ?
    AND order_status != 'cancelled'
");
$foodStmt->bind_param("i", $reservation_id);
$foodStmt->execute();
$food_total = $foodStmt->get_result()->fetch_assoc()['food_total'] ?? 0;

// 🔹 Totals
$room_total = $booking['price'] * ($booking['duration'] / 24);
$grand_total = $room_total + $food_total;
?>
<!DOCTYPE html>
<html>
<head>
    <title>Checkout Receipt</title>
    <link rel="stylesheet" href="../../dist/css/adminlte.min.css">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="card">
        <div class="card-header bg-success text-white">
            <h4>🧾 Checkout Receipt</h4>
        </div>

        <div class="card-body">
            <p><strong>Guest:</strong> <?= htmlspecialchars($booking['guestName']) ?></p>
            <p><strong>Room:</strong> Room <?= $booking['room_number'] ?> (<?= $booking['room_type'] ?>)</p>

            <hr>

            <h5>Charges</h5>
            <table class="table table-bordered">
                <tr>
                    <th>Room Charge</th>
                    <td>₱<?= number_format($room_total, 2) ?></td>
                </tr>
                <tr>
                    <th>Food Orders</th>
                    <td>₱<?= number_format($food_total, 2) ?></td>
                </tr>
                <tr class="table-success">
                    <th>Total Bill</th>
                    <th>₱<?= number_format($grand_total, 2) ?></th>
                </tr>
            </table>

            <p><strong>Check-in:</strong> <?= date("M d, Y h:i A", strtotime($booking['checkin'])) ?></p>
            <p><strong>Check-out:</strong> <?= date("M d, Y h:i A", strtotime($booking['checkout'])) ?></p>
        </div>

        <div class="card-footer text-right">
            <a href="status.list.php" class="btn btn-secondary">Back</a>
            <button onclick="window.print()" class="btn btn-primary">🖨 Print</button>
        </div>
    </div>
</div>

</body>
</html>
