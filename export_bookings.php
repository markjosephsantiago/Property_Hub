<?php
require 'includes/conn.php';

$query = "SELECT room_id, checkin, checkout FROM tbl_reservations WHERE status = 'confirmed'";
$result = mysqli_query($conn, $query);

$fp = fopen('booking_data.csv', 'w');
fputcsv($fp, ['room_id', 'checkin', 'checkout']);

while ($row = mysqli_fetch_assoc($result)) {
    fputcsv($fp, $row);
}

fclose($fp);
echo "Data exported.";
?>
