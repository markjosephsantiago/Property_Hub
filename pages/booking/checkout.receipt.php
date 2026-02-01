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
<!-- ... -->
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
                <?php if ($tendered > 0): ?>
                    <tr>
                        <th>Amount Tendered (Cash)</th>
                        <td>₱<?= number_format($tendered, 2) ?></td>
                    </tr>
                    <tr>
                        <th>Change</th>
                        <td><strong>₱<?= number_format($change, 2) ?></strong></td>
                    </tr>
                <?php endif; ?>
            </table>

            <p><strong>Check-in:</strong> <?= date("M d, Y h:i A", strtotime($booking['checkin'])) ?></p>
            <p><strong>Check-out:</strong> <?= date("M d, Y h:i A", strtotime($booking['checkout'])) ?></p>
        </div>

        <div class="card-footer text-right">
            <span class="text-muted mr-3">Redirecting in <b id="timer">20</b>s...</span>
            <a href="status.list.php" class="btn btn-secondary">Back Now</a>
            <button onclick="window.print()" class="btn btn-primary">🖨 Print</button>
        </div>
    </div>
</div>

<script>
    let timeLeft = 20;
    const timerElement = document.getElementById('timer');
    
    const countdown = setInterval(() => {
        timeLeft--;
        timerElement.textContent = timeLeft;
        
        if (timeLeft <= 0) {
            clearInterval(countdown);
            window.location.href = "status.list.php";
        }
    }, 1000);
</script>

</body>
</html>
