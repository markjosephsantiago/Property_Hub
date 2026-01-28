<?php
require '../../includes/conn.php';
date_default_timezone_set('Asia/Manila');

$currentDateTime = date('Y-m-d H:i:s');

$query = "
    SELECT reservation_id, room_id 
    FROM tbl_reservations 
    WHERE status = 'confirmed' 
    AND checkout <= '$currentDateTime'
";

$result = $conn->query($query);
$updated = 0;

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $reservation_id = $row['reservation_id'];
        $room_id = $row['room_id'];

        $conn->query("UPDATE tbl_reservations SET status = 'checked_out' WHERE reservation_id = '$reservation_id'");

        $conn->query("UPDATE tbl_rooms SET status = 'available' WHERE room_id = '$room_id'");

        $updated++;
    }
}

if ($updated > 0) {
    echo "
    <div class='alert alert-info alert-dismissible fade show' role='alert' style='position:fixed; top:20px; right:20px; z-index:9999; width:300px;'>
        <strong>Auto Checkout:</strong> $updated guest(s) automatically checked out.
        <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
            <span aria-hidden='true'>&times;</span>
        </button>
    </div>
    <script>
        setTimeout(() => {
            document.querySelector('.alert').style.display = 'none';
        }, 4000);
    </script>
    ";
}
?>
