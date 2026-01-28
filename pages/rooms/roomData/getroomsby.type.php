<?php
require '../../../includes/conn.php'; // adjust path kung ibang folder structure

if (isset($_POST['room_type'])) {
    $roomType = mysqli_real_escape_string($conn, $_POST['room_type']);

    // kunin lahat ng available rooms base sa type
    $query = "SELECT room_id, room_number 
              FROM tbl_rooms 
              WHERE room_type = '$roomType' AND status = 'available'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        echo '<option value="">-- Select Room Number --</option>';
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<option value="'.$row['room_id'].'">'.$row['room_number'].'</option>';
        }
    } else {
        echo '<option value="">No available rooms</option>';
    }
}
?>
