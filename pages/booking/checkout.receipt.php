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
// 🔹 Totals
// Consistent Logic: Duration is days.
$room_total = $booking['price'] * $booking['duration']; 
$grand_total = $room_total + $food_total;

$tendered = $_GET['tendered'] ?? 0;
$change = $_GET['change'] ?? 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Transaction Receipt | Property Hub</title>
    <link rel="stylesheet" href="../../dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body { background-color: #f4f6f9; font-family: 'Source Sans Pro', sans-serif; }
        .receipt-container { max-width: 600px; margin: 40px auto; background: #fff; padding: 30px; border-radius: 10px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); position: relative; }
        .receipt-header { text-align: center; border-bottom: 2px dashed #eee; padding-bottom: 20px; margin-bottom: 20px; }
        .receipt-header h2 { margin: 0; color: #333; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; }
        .receipt-header p { margin: 5px 0 0; color: #777; font-size: 14px; }
        .receipt-section { margin-bottom: 20px; }
        .receipt-section h6 { text-transform: uppercase; color: #999; font-size: 12px; font-weight: 700; margin-bottom: 10px; border-bottom: 1px solid #f0f0f0; padding-bottom: 5px; }
        .table-receipt th { border-top: none; color: #555; font-weight: 600; }
        .table-receipt td { border-top: none; text-align: right; color: #333; }
        .total-row { border-top: 2px solid #333 !important; font-size: 1.2rem; font-weight: 700; }
        .footer-info { font-size: 13px; color: #888; margin-top: 30px; border-top: 1px solid #f0f0f0; padding-top: 15px; }
        .no-print-zone { margin-top: 20px; text-align: center; }
        @media print {
            .no-print-zone, .preloader, .main-footer { display: none !important; }
            .receipt-container { box-shadow: none; margin: 0 auto; width: 100%; border: none; }
            body { background: #fff; }
        }
    </style>
</head>
<body>

<div class="container">
    <div class="receipt-container">
        <div class="receipt-header">
            <h2>PROPERTY HUB</h2>
            <p>Official Transaction Receipt</p>
            <div class="mt-2 text-muted" style="font-size: 12px;">
                Reference: <strong>#<?= htmlspecialchars($booking['confirmation_code']) ?></strong><br>
                Date: <?= date("F j, Y g:i A") ?>
            </div>
        </div>

        <div class="receipt-section">
            <h6>Guest Information</h6>
            <div class="row">
                <div class="col-6"><strong><?= htmlspecialchars($booking['guestName']) ?></strong></div>
                <div class="col-6 text-right text-muted"><?= htmlspecialchars($booking['email']) ?></div>
            </div>
        </div>

        <div class="receipt-section">
            <h6>Stay Details</h6>
            <table class="table table-receipt mb-0">
                <tr>
                    <th class="pl-0">Room</th>
                    <td><?= htmlspecialchars($booking['room_number']) ?> (<?= htmlspecialchars($booking['room_type']) ?>)</td>
                </tr>
                <tr>
                    <th class="pl-0">Check-in</th>
                    <td><?= date("M d, Y", strtotime($booking['checkin'])) ?></td>
                </tr>
                <tr>
                    <th class="pl-0">Check-out</th>
                    <td><?= date("M d, Y", strtotime($booking['checkout'])) ?></td>
                </tr>
                <tr>
                    <th class="pl-0">Duration</th>
                    <td><?= $booking['duration'] ?> Night(s)</td>
                </tr>
            </table>
        </div>

        <div class="receipt-section">
            <h6>Charges & Payments</h6>
            <table class="table table-receipt">
                <tr>
                    <th class="pl-0">Room Charge</th>
                    <td>₱<?= number_format($room_total, 2) ?></td>
                </tr>
                <tr>
                    <th class="pl-0">Food Orders</th>
                    <td>₱<?= number_format($food_total, 2) ?></td>
                </tr>
                <tr class="total-row">
                    <th class="pl-0 p-3">Grand Total</th>
                    <td class="p-3">₱<?= number_format($grand_total, 2) ?></td>
                </tr>
                <?php if ($tendered > 0): ?>
                    <tr>
                        <th class="pl-0 pt-3">Cash Tendered</th>
                        <td class="pt-3">₱<?= number_format($tendered, 2) ?></td>
                    </tr>
                    <tr>
                        <th class="pl-0">Change</th>
                        <td><strong class="text-success">₱<?= number_format($change, 2) ?></strong></td>
                    </tr>
                <?php endif; ?>
            </table>
        </div>

        <div class="footer-info text-center">
            <p>Thank you for choosing Property Hub!<br>Please keep this receipt for your records.</p>
        </div>

        <div class="no-print-zone">
            <hr>
            <div class="d-flex justify-content-between align-items-center mb-3">
                <span class="text-muted small">Redirecting in <b id="timer">20</b>s...</span>
                <div>
                    <button onclick="window.print()" class="btn btn-outline-primary btn-sm mx-1">
                        <i class="fas fa-print"></i> Print
                    </button>
                    <a href="status.list.php" class="btn btn-secondary btn-sm mx-1">Back Now</a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let timeLeft = 20;
    const timerElement = document.getElementById('timer');
    
    const countdown = setInterval(() => {
        timeLeft--;
        if (timerElement) timerElement.textContent = timeLeft;
        
        if (timeLeft <= 0) {
            clearInterval(countdown);
            window.location.href = "status.list.php";
        }
    }, 1000);
</script>

</body>
</html>
