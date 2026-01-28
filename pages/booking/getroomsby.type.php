<?php
include '../../includes/conn.php';

if (isset($_POST['room_type'])) {
    $room_type = mysqli_real_escape_string($conn, $_POST['room_type']);
    $query = "SELECT room_id, room_number 
              FROM tbl_rooms 
              WHERE LOWER(room_type) = LOWER('$room_type') 
              AND status = 'available'";
    
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        echo '<option value="">-- Choose Room Number --</option>';
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<option value="'.$row['room_id'].'">'.$row['room_number'].'</option>';
        }
    } else {
        echo '<option value="">No available rooms</option>';
    }
}
?>
