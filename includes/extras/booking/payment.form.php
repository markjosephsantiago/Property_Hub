<?php
session_start();
require '../../includes/conn.php';

// ✅ Check if reservation_id is provided
if (!isset($_GET['reservation_id'])) {
    $_SESSION['error'] = "Reservation not found.";
    header("Location: ../booking/add.booking.php");
    exit();
}

$reservation_id = $_GET['reservation_id'];

// ✅ Get reservation details
$query = "SELECT r.*, rm.room_number, rm.price 
          FROM tbl_reservations r
          LEFT JOIN tbl_rooms rm ON r.room_id = rm.room_id
          WHERE r.reservation_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $reservation_id);
$stmt->execute();
$reservation = $stmt->get_result()->fetch_assoc();

if (!$reservation) {
    $_SESSION['error'] = "Reservation not found.";
    header("Location: ../booking/add.booking.php");
    exit();
}

// ✅ Compute total amount
$total_amount = $reservation['price'] * $reservation['duration_days'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Form</title>
    <link rel="stylesheet" href="../../../dist/css/adminlte.min.css">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

    <section class="content p-5">
        <div class="container">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Reservation Payment</h3>
                </div>
                <form action="bookingProcess/ctrl.payment.php" method="POST">
                    <div class="card-body">
                        <input type="hidden" name="reservation_id" value="<?= $reservation_id ?>">

                        <div class="form-group">
                            <label>Guest Name:</label>
                            <input type="text" class="form-control" value="<?= htmlspecialchars($reservation['guestName']) ?>" readonly>
                        </div>

                        <div class="form-group">
                            <label>Room Number:</label>
                            <input type="text" class="form-control" value="<?= htmlspecialchars($reservation['room_number']) ?>" readonly>
                        </div>

                        <div class="form-group">
                            <label>Check-in:</label>
                            <input type="text" class="form-control" value="<?= htmlspecialchars($reservation['checkin']) ?>" readonly>
                        </div>

                        <div class="form-group">
                            <label>Check-out:</label>
                            <input type="text" class="form-control" value="<?= htmlspecialchars($reservation['checkout']) ?>" readonly>
                        </div>

                        <div class="form-group">
                            <label>Duration (Days):</label>
                            <input type="text" class="form-control" value="<?= htmlspecialchars($reservation['duration_days']) ?>" readonly>
                        </div>

                        <div class="form-group">
                            <label>Total Amount (₱):</label>
                            <input type="text" name="amount" class="form-control" value="<?= number_format($total_amount, 2) ?>">
                        </div>

                        <div class="form-group">
                            <label>Payment Method:</label>
                            <select name="payment_method" class="form-control" required>
                                <option value="">-- Select Method --</option>
                                <option value="Cash">Cash</option>
                                <option value="GCash">GCash</option>
                                <option value="Credit Card">Credit Card</option>
                            </select>
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-success">Submit Payment</button>
                        <a href="../my_bookings.php" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </section>

</div>
</body>
</html>
