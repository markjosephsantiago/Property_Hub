<?php
session_start();
require '../../includes/conn.php';

// Check ID
if (!isset($_GET['reservation_id'])) {
    $_SESSION['error'] = "Reservation ID missing.";
    header("Location: status.list.php");
    exit();
}

$id = $_GET['reservation_id'];

// Fetch Details
$stmt = $conn->prepare("
    SELECT r.*, rm.room_number, rm.room_type 
    FROM tbl_reservations r
    JOIN tbl_rooms rm ON r.room_id = rm.room_id
    WHERE r.reservation_id = ?
");
$stmt->bind_param("i", $id);
$stmt->execute();
$booking = $stmt->get_result()->fetch_assoc();

if (!$booking) {
    $_SESSION['error'] = "Reservation not found.";
    header("Location: status.list.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Check-in Guest</title>
    <link rel="stylesheet" href="../../dist/css/adminlte.min.css">
    <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
</head>
<body class="hold-transition sidebar-mini">
<div class="container mt-5">
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">Check-in Guest</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <dl>
                        <dt>Guest Name</dt> <dd><?= htmlspecialchars($booking['guestName']) ?></dd>
                        <dt>Room</dt> <dd>Room <?= $booking['room_number'] ?> (<?= $booking['room_type'] ?>)</dd>
                        <dt>Check-in Date</dt> <dd><?= date("F d, Y", strtotime($booking['checkin'])) ?></dd>
                        <dt>Duration</dt> <dd><?= $booking['duration'] ?> Days</dd>
                    </dl>
                </div>
                <div class="col-md-6">
                    <div class="alert alert-info">
                        <h5><i class="icon fas fa-info"></i> Standard Procedure</h5>
                        <ul>
                            <li>Verify Guest ID</li>
                            <li>Collect Security Deposit (if applicable)</li>
                            <li>Hand over room keys</li>
                        </ul>
                    </div>
                </div>
            </div>

            <hr>

            <form action="bookingProcess/ctrl.update.status.php?id=<?= $id ?>&action=checkin" method="POST">
                <!-- Additional fields can be added here if needed -->
<?php
// Fetch available rooms for swapping (Same Type + Available + Current Room)
// Exclude 'maintenance' status strictly.
$roomQuery = $conn->prepare("
    SELECT * FROM tbl_rooms 
    WHERE room_type = ? 
    AND (status = 'available' OR room_id = ?)
    AND status != 'maintenance'
");
$roomQuery->bind_param("si", $booking['room_type'], $booking['room_id']);
$roomQuery->execute();
$rooms = $roomQuery->get_result();
?>
<!-- ... (inside form) ... -->
                <div class="form-group">
                    <label>Assign Room (Current: Room <?= $booking['room_number'] ?>)</label>
                    <select name="room_id" class="form-control" required>
                        <?php while ($r = $rooms->fetch_assoc()): ?>
                            <option value="<?= $r['room_id'] ?>" <?= $r['room_id'] == $booking['room_id'] ? 'selected' : '' ?>>
                                Room <?= $r['room_number'] ?> 
                                (<?= ucfirst($r['status']) ?>) 
                                - Price: ₱<?= number_format($r['price'], 2) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                    <small class="text-muted">You can swap to another available room of the same type.</small>
                </div>

                <div class="form-group">
                    <label>Notes / Special Requests (Optional)</label>
                    <textarea name="notes" class="form-control" rows="3" placeholder="Enter any check-in notes..."></textarea>
                </div>

                <div class="form-check mb-4">
                    <input type="checkbox" class="form-check-input" id="verifyCheck" required>
                    <label class="form-check-label" for="verifyCheck">I confirm that I have verified the guest's identity.</label>
                </div>

                <button type="submit" class="btn btn-success btn-lg btn-block">
                    <i class="fas fa-check"></i> Complete Check-in
                </button>
            </form>
        </div>
        <div class="card-footer">
            <a href="status.list.php" class="btn btn-secondary">Cancel</a>
        </div>
    </div>
</div>
</body>
</html>
