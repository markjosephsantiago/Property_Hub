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
// We use r.room_type as the primary "Booked Category" and rm.room_type as the "Assigned Room's Type"
$stmt = $conn->prepare("
    SELECT r.*, rm.room_number, rm.room_type AS assigned_type, rm.price AS room_price
    FROM tbl_reservations r
    LEFT JOIN tbl_rooms rm ON r.room_id = rm.room_id
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
                        <dt>Assigned Room</dt> 
                        <dd id="display_room">
                            <?php 
                                if ($booking['room_number']) {
                                    echo "Room " . htmlspecialchars($booking['room_number']) . " (" . htmlspecialchars($booking['assigned_type']) . ")";
                                } else {
                                    echo '<span class="text-danger">Not yet assigned</span> (' . htmlspecialchars($booking['room_type']) . ')';
                                }
                            ?>
                        </dd>
                        <dt>Check-in Date</dt> <dd><?= date("F d, Y", strtotime($booking['checkin'])) ?></dd>
                        <dt>Price Rate</dt> <dd id="display_price">₱<?= number_format($booking['room_price'] ?? 0, 2) ?></dd>
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
// Fetch available rooms for swapping (Same Type + [Available OR Current Room])
// We filter strictly: status must be 'available' OR it must be the room currently assigned to this booking.
$roomQuery = $conn->prepare("
    SELECT * FROM tbl_rooms 
    WHERE room_type = ? 
    AND (status = 'available' OR room_id = ?)
    AND status != 'maintenance'
    ORDER BY room_number ASC
");
$roomQuery->bind_param("si", $booking['room_type'], $booking['room_id']);
$roomQuery->execute();
$rooms = $roomQuery->get_result();
?>
                    <select name="room_id" id="room_select" class="form-control" required onchange="updateRoomDetails()">
                        <?php 
                        $first_available_found = false;
                        while ($r = $rooms->fetch_assoc()): 
                            $is_current = ($r['room_id'] == $booking['room_id']);
                            $is_occupied = ($r['status'] === 'occupied');
                            
                            $status_text = ucfirst($r['status']);
                            $disabled = "";
                            
                            if ($is_occupied && !$is_current) {
                                continue; 
                            }
                            
                            if ($is_occupied && $is_current) {
                                $status_text = "Occupied by another guest";
                                $disabled = "disabled"; 
                            }

                            // Auto-select logic: if no room is assigned, select the first available one
                            $selected = "";
                            if ($booking['room_id']) {
                                if ($is_current) $selected = "selected";
                            } else {
                                if (!$first_available_found && $r['status'] === 'available') {
                                    $selected = "selected";
                                    $first_available_found = true;
                                }
                            }
                        ?>
                            <option value="<?= $r['room_id'] ?>" 
                                <?= $selected ?> 
                                <?= $disabled ?>
                                data-number="<?= $r['room_number'] ?>"
                                data-type="<?= htmlspecialchars($r['room_type']) ?>"
                                data-price="<?= number_format($r['price'], 2) ?>"
                            >
                                Room <?= $r['room_number'] ?> 
                                (<?= $status_text ?>) 
                                - Price: ₱<?= number_format($r['price'], 2) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                    <small class="text-muted">Only available rooms of the same type are shown. You must swap if the current room is occupied.</small>
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
<script>
function updateRoomDetails() {
    const select = document.getElementById('room_select');
    const selectedOption = select.options[select.selectedIndex];
    
    // Only update if a valid room (not the placeholder) is selected
    if (selectedOption && selectedOption.value && selectedOption.value !== "") {
        const roomNum = selectedOption.getAttribute('data-number');
        const roomType = selectedOption.getAttribute('data-type');
        const roomPrice = selectedOption.getAttribute('data-price');
        
        document.getElementById('display_room').innerHTML = `Room ${roomNum} (${roomType})`;
        document.getElementById('display_price').innerText = `₱${roomPrice}`;
    }
}

// Ensure JS is ready
document.addEventListener('DOMContentLoaded', function() {
    updateRoomDetails();
});
</script>
</body>
</html>
