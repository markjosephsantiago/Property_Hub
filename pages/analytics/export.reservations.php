<?php
include '../../includes/conn.php';

// 1. Save locally for the AI Recommendation engine
$local_file = 'reservations.csv';
$fp = fopen($local_file, 'w');
fputcsv($fp, ['reservation_id', 'guest_count', 'checkin', 'checkout', 'duration_days', 'room_type']);

// 2. Stream to browser for user download
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="reservations.csv"');
$output = fopen('php://output', 'w');
fputcsv($output, ['reservation_id', 'guest_count', 'checkin', 'checkout', 'duration_days', 'room_type']);

// Get data from database
$query = "SELECT reservation_id, guest_count, checkin, checkout, duration, room_type FROM tbl_reservations";
$result = mysqli_query($conn, $query);

while ($row = mysqli_fetch_assoc($result)) {
    fputcsv($fp, $row);    // Write to server
    fputcsv($output, $row); // Write to browser
}

fclose($fp);
fclose($output);
exit;
