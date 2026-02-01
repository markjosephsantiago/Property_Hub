<?php
$host = 'localhost';
$db = 'pms_db';
$user = 'root';
$pass = '';

date_default_timezone_set('Asia/Manila');

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ensure MySQL session matches PHP timezone
$conn->query("SET time_zone = '+08:00'");

// Auto-migration: Ensure room_type exists and room_id is nullable
$check_col = $conn->query("SHOW COLUMNS FROM tbl_reservations LIKE 'room_type'");
if ($check_col->num_rows == 0) {
    $conn->query("ALTER TABLE tbl_reservations ADD COLUMN room_type VARCHAR(50) AFTER room_id");
}
$conn->query("ALTER TABLE tbl_reservations MODIFY COLUMN room_id INT NULL");

?>

