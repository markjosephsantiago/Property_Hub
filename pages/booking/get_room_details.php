<?php
require '../../includes/conn.php'; // adjust path if needed

if (isset($_GET['room_id'])) {
    $room_id = intval($_GET['room_id']);
    $query = "SELECT price, capacity FROM tbl_rooms WHERE room_id = $room_id";
    $result = $conn->query($query);

    if ($result && $result->num_rows > 0) {
        echo json_encode($result->fetch_assoc());
    } else {
        echo json_encode(['price' => 0, 'capacity' => 0]);
    }
}
?>
