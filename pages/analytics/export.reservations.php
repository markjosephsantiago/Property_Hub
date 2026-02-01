<?php
include '../../includes/conn.php';

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="reservations.csv"');

$output = fopen('php://output', 'w');

// CSV header
fputcsv($output, ['reservation_id', 'guest_count', 'checkin', 'checkout', 'duration_days']);

// Get data from database
$query = "SELECT reservation_id, guest_count, checkin, checkout, duration FROM tbl_reservations";
$result = mysqli_query($conn, $query);

while ($row = mysqli_fetch_assoc($result)) {
    fputcsv($output, $row);
}

fclose($output);
exit;
