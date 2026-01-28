<?php
session_start();
require '../../includes/conn.php';

if (!isset($_SESSION['payment_success_id'])) {
    header("Location: my_bookings.php");
    exit();
}

$payment_id = $_SESSION['payment_success_id'];
unset($_SESSION['payment_success_id']);

$query = "
    SELECT p.*, r.guestName, r.checkin, r.checkout, r.duration_days, r.confirmation_code, rm.room_number, rm.room_type
    FROM tbl_payment p
    JOIN tbl_reservations r ON p.reservation_id = r.reservation_id
    JOIN tbl_rooms rm ON r.room_id = rm.room_id
    WHERE p.payment_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $payment_id);
$stmt->execute();
$payment = $stmt->get_result()->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Receipt</title>
    <link rel="stylesheet" href="../../dist/css/adminlte.min.css">
</head>
<body class="hold-transition sidebar-mini">

<div class="container mt-5">
    <div class="card card-outline card-success">
        <div class="card-header text-center bg-success text-white">
            <h3 class="card-title">Payment Receipt</h3>
        </div>
        <div class="card-body">
            <h4 class="text-center mb-4">Thank you, <?= htmlspecialchars($payment['guestName']) ?>!</h4>
            <p class="text-center text-muted">Your payment has been successfully recorded.</p>

            <table class="table table-bordered">
                <tr><th>Payment ID</th><td><?= $payment['payment_id'] ?></td></tr>
                <tr><th>Confirmation Code</th><td><?= $payment['confirmation_code'] ?></td></tr>
                <tr><th>Room</th><td><?= htmlspecialchars($payment['room_number']) ?> (<?= htmlspecialchars($payment['room_type']) ?>)</td></tr>
                <tr><th>Check-in</th><td><?= $payment['checkin'] ?></td></tr>
                <tr><th>Check-out</th><td><?= $payment['checkout'] ?></td></tr>
                <tr><th>Duration</th><td><?= $payment['duration_days'] ?> days</td></tr>
                <tr><th>Amount Paid</th><td>₱<?= number_format($payment['amount'], 2) ?></td></tr>
                <tr><th>Payment Method</th><td><?= $payment['payment_method'] ?></td></tr>
                <tr><th>Date Paid</th><td><?= $payment['date_paid'] ?></td></tr>
            </table>

            <div class="text-center mt-4">
                <a href="my_bookings.php" class="btn btn-primary">Back to My Bookings</a>
                <button class="btn btn-secondary" onclick="window.print()">Print Receipt</button>
            </div>
        </div>
    </div>
</div>

</body>
</html>
