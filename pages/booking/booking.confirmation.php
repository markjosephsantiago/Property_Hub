<?php
session_start();
require '../../includes/conn.php';

// ✅ Optional check kung may confirmation code sa URL
if (!isset($_GET['code'])) {
    $_SESSION['error'] = "No booking confirmation code found.";
    header("Location: booking.form.php");
    exit();
}

$confirmation_code = $_GET['code'];

// 🔍 Fetch booking details
$stmt = $conn->prepare("
    SELECT r.*, rm.room_number, rm.room_type, rm.price 
    FROM tbl_reservations r
    JOIN tbl_rooms rm ON r.room_id = rm.room_id
    WHERE r.confirmation_code = ?
    LIMIT 1
");
$stmt->bind_param("s", $confirmation_code);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $_SESSION['error'] = "Booking not found.";
    header("Location: booking.form.php");
    exit();
}

$booking = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Booking Confirmation</title>
    <link rel="stylesheet" href="../../dist/css/adminlte.min.css">
    <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
    <style>
        body {
            background: #f4f6f9;
        }
        .confirmation-card {
            max-width: 650px;
            margin: 50px auto;
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            padding: 30px;
        }
        .confirmation-header {
            text-align: center;
            margin-bottom: 25px;
        }
        .confirmation-header h2 {
            color: #28a745;
            font-weight: 700;
        }
        .details-table th {
            width: 40%;
            background-color: #f8f9fa;
        }
    </style>
</head>
<body>
    <div class="confirmation-card">
        <div class="confirmation-header">
            <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
            <h2>Booking Confirmed!</h2>
            <p>Your booking has been successfully submitted.</p>
        </div>

        <table class="table table-bordered details-table">
            <tr><th>Guest Name</th><td><?= htmlspecialchars($booking['guestName']) ?></td></tr>
            <tr><th>Email</th><td><?= htmlspecialchars($booking['email']) ?></td></tr>
            <tr><th>Contact</th><td><?= htmlspecialchars($booking['contact']) ?></td></tr>
            <tr><th>Room</th><td>Room <?= htmlspecialchars($booking['room_number']) ?> - <?= htmlspecialchars($booking['room_type']) ?></td></tr>
            <tr><th>Check-in</th><td><?= date("F d, Y h:i A", strtotime($booking['checkin'])) ?></td></tr>
            <tr><th>Check-out</th><td><?= date("F d, Y h:i A", strtotime($booking['checkout'])) ?></td></tr>
            <tr><th>Duration</th><td><?= $booking['duration'] ?> hour(s)</td></tr>
            <tr><th>Total Price</th><td>₱<?= number_format($booking['price'] * ($booking['duration'] / 24), 2) ?></td></tr>
            <tr><th>Status</th><td><span class="badge badge-warning text-uppercase"><?= $booking['status'] ?></span></td></tr>
            <tr><th>Confirmation Code</th><td><strong><?= $booking['confirmation_code'] ?></strong></td></tr>
        </table>

        <div class="text-center mt-4">
            <a href="../../index.php" class="btn btn-success"><i class="fas fa-home"></i> Back to Dashboard</a>
            <a href="booking.form.php" class="btn btn-outline-secondary"><i class="fas fa-plus"></i> New Booking</a>
            <a href="../food/food.menu.php?reservation_id=<?= $booking['reservation_id'] ?>&room_id=<?= $booking['room_id'] ?>"
            class="btn btn-warning ml-2">
                <i class="fas fa-utensils"></i> Order Food (Room Service)
            </a>
        </div>
    </div>
</body>
</html>
