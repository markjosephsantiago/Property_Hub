<?php
include "../../includes/conn.php";

if (isset($_GET['run'])) {
    $command = "\"C:/Users/santi/AppData/Local/Programs/Python/Python313/python.exe\" C:/wamp64/www/Property_Hub/dbscan.py";
    $output = [];
    $return_var = 0;
    exec($command . " 2>&1", $output, $return_var);
    echo implode("<br>", $output);
    exit;
}

$sql = "SELECT r.reservation_id, r.guestName, r.guest_count, 
               DATEDIFF(r.checkout, r.checkin) AS duration_days, 
               r.cluster_label, rm.room_number
        FROM tbl_reservations r
        JOIN tbl_rooms rm ON rm.room_id = r.room_id
        ORDER BY r.cluster_label ASC";
$result = $conn->query($sql);

$reservations = [];
while ($row = $result->fetch_assoc()) {
    $reservations[] = $row;
}

$cluster_sql = "SELECT cluster_label, COUNT(*) as count 
                FROM tbl_reservations 
                GROUP BY cluster_label 
                ORDER BY cluster_label ASC";
$cluster_res = $conn->query($cluster_sql);

$labels = [];
$counts = [];
while ($row = $cluster_res->fetch_assoc()) {
    $labels[] = $row['cluster_label'];
    $counts[] = $row['count'];
}

header("Content-Type: application/json");
echo json_encode([
    "reservations" => $reservations,
    "clusters" => [
        "labels" => $labels,
        "counts" => $counts
    ]
]);
