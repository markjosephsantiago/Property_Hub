<?php
require 'includes/conn.php';
$csv = array_map('str_getcsv', file('booking_clusters.csv'));
array_shift($csv); // remove headers

foreach ($csv as $row) {
    list($room_id, $checkin, $checkout, $cluster) = $row;
    // You could store this in a separate table
    mysqli_query($conn, "INSERT INTO tbl_booking_clusters (room_id, checkin, checkout, cluster_label) 
                         VALUES ('$room_id', '$checkin', '$checkout', '$cluster')");
}
echo "Clusters imported.";
?>
