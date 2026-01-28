<?php
require "../../includes/conn.php";

// Today’s date
$today = date('Y-m-d');

// Find all guests who should have checked out
$sql = "SELECT reservation_id, room_id 
        FROM tbl_reservations 
        WHERE status = 'checked_in' AND checkout <= ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $today);
$stmt->execute();
$result = $stmt->get_result();

$checkedOutCount = 0;

while ($row = $result->fetch_assoc()) {
    $reservation_id = $row['reservation_id'];
    $room_id = $row['room_id'];

    // Update reservation to checked_out
    $updateRes = $conn->prepare("UPDATE tbl_reservations SET status='checked_out' WHERE reservation_id=?");
    $updateRes->bind_param("i", $reservation_id);
    $updateRes->execute();
    $updateRes->close();

    // Update room to Available
    $updateRoom = $conn->prepare("UPDATE tbl_rooms SET status='Available' WHERE room_id=?");
    $updateRoom->bind_param("i", $room_id);
    $updateRoom->execute();
    $updateRoom->close();

    $checkedOutCount++;
}

$stmt->close();
$conn->close();

// ✅ Display alert if there were auto-checkouts
if ($checkedOutCount > 0) {
    echo "
    <div id='autoCheckoutAlert' class='alert alert-success alert-dismissible fade show position-fixed' 
         style='top: 20px; right: 20px; z-index: 1050; min-width: 300px;' role='alert'>
        <strong>Auto Checkout Complete!</strong><br>
        {$checkedOutCount} guest(s) checked out successfully on {$today}.
        <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
            <span aria-hidden='true'>&times;</span>
        </button>
    </div>

    <script>
        // Auto-hide after 5 seconds
        setTimeout(() => {
            const alertBox = document.getElementById('autoCheckoutAlert');
            if (alertBox) {
                alertBox.classList.remove('show');
                alertBox.classList.add('hide');
            }
        }, 5000);
    </script>
    ";
}
?>
