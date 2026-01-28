<?php
include "../../../includes/conn.php";

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Update reservation
    $conn->query("UPDATE tbl_reservations SET status='checked_in' WHERE reservation_id=$id");

    // Update room status
    $room = $conn->query("SELECT room_id FROM tbl_reservations WHERE reservation_id=$id")->fetch_assoc();
    $conn->query("UPDATE tbl_rooms SET status='Occupied' WHERE room_id={$room['room_id']}");

    echo "<script>alert('Guest checked in successfully!'); window.location.href='../checkin.list.php';</script>";
}
?>
