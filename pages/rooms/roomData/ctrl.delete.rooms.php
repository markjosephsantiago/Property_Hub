<?php
session_start();
require '../../../includes/conn.php';

$room_id = $_GET['room_id'];
mysqli_query($conn, "DELETE FROM tbl_rooms WHERE room_id = $room_id");

$_SESSION['success'] = "Room deleted!";
header("Location: ../list.rooms.php");
exit();
?>
