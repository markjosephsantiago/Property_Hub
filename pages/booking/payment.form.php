<?php
session_start();
require '../../includes/conn.php';

// ✅ Check kung may reservation_id sa URL
if (!isset($_GET['reservation_id'])) {
    $_SESSION['error'] = "No reservation found.";
    header("Location: booking.form.php");
    exit();
}

$reservation_id = $_GET['reservation_id'];

// 🔍 Fetch booking details
$stmt = $conn->prepare("
    SELECT r.*, rm.room_number, rm.room_type, rm.price 
    FROM tbl_reservations r
    JOIN tbl_rooms rm ON r.room_id = rm.room_id
    WHERE r.reservation_id = ?
");
$stmt->bind_param("i", $reservation_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $_SESSION['error'] = "Reservation not found.";
    header("Location: booking.form.php");
    exit();
}

$booking = $result->fetch_assoc();

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

// ✅ Compute total price (room charge + food orders)
$room_total = ($booking['price'] / 24) * $booking['duration'];
$total_price = $room_total + $food_total;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Form</title>
    <link rel="stylesheet" href="../../dist/css/adminlte.min.css">
    <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
    <style>
        body {
            background: #f4f6f9;
        }
        .payment-card {
            max-width: 600px;
            margin: 50px auto;
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            padding: 25px;
        }
        .payment-header {
            text-align: center;
            margin-bottom: 25px;
        }
        .payment-header h3 {
            font-weight: 700;
            color: #007bff;
        }
        .summary-table th {
            width: 40%;
            background-color: #f8f9fa;
        }
    </style>
</head>
<body>
    <div class="payment-card">
        <div class="payment-header">
            <i class="fas fa-credit-card fa-3x text-primary mb-3"></i>
            <h3>Payment Information</h3>
            <p>Please review your booking details before payment.</p>
        </div>

        <table class="table table-bordered summary-table">
            <tr><th>Guest Name</th><td><?= htmlspecialchars($booking['guestName']) ?></td></tr>
            <tr><th>Room</th><td>Room <?= htmlspecialchars($booking['room_number']) ?> - <?= htmlspecialchars($booking['room_type']) ?></td></tr>
            <tr><th>Check-in</th><td><?= date("F d, Y h:i A", strtotime($booking['checkin'])) ?></td></tr>
            <tr><th>Check-out</th><td><?= date("F d, Y h:i A", strtotime($booking['checkout'])) ?></td></tr>
            <tr><th>Duration</th><td><?= $booking['duration'] ?> hour(s)</td></tr>
            <tr><th>Room Charge</th><td><strong>₱<?= number_format($room_total, 2) ?></strong></td></tr>
            <tr><th>Food Orders</th><td><strong>₱<?= number_format($food_total, 2) ?></strong></td></tr>
            <tr style="background-color: #e8f5e9;"><th>Total Price</th><td><strong style="color: #2e7d32;">₱<?= number_format($total_price, 2) ?></strong></td></tr>
        </table>

        <form action="bookingProcess/ctrl.payment.php" method="POST">

            <input type="hidden" name="reservation_id" value="<?= $reservation_id ?>">
            <input type="hidden" name="amount" value="<?= $total_price ?>">

            <div class="form-group">
                <label>Payment Method</label>
                <select name="payment_method" class="form-control" required>
                    <option value="">Select payment method</option>
                    <option value="Cash">Cash</option>
                    <option value="GCash">GCash</option>
                    <option value="Online Transfer">Online Transfer</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary btn-block">
                Confirm Payment
            </button>

        </form>

        <div class="text-center mt-3">
            <a href="../booking.confirmation.php?code=<?= urlencode($booking['confirmation_code']) ?>" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Back to Confirmation
            </a>
        </div>
    </div>
</body>
</html>
