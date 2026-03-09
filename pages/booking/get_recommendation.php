<?php
header('Content-Type: application/json');

$guest_count = isset($_GET['guest_count']) ? (int)$_GET['guest_count'] : 0;
$duration = isset($_GET['duration']) ? (int)$_GET['duration'] : 0;

$jsonFile = '../analytics/recommendations.json';

if (!file_exists($jsonFile)) {
    echo json_encode(['recommendation' => null, 'message' => 'No analysis data found.']);
    exit();
}

// 1. Get Recommendations
$recommendations = json_decode(file_get_contents($jsonFile), true);

// 2. Fetch room capacities to validate recommendations
include '../../includes/conn.php'; 
$capacity_query = "SELECT room_type, MAX(capacity) as max_cap FROM tbl_rooms GROUP BY room_type";
$cap_result = mysqli_query($conn, $capacity_query);
$room_capacities = [];
while ($row = mysqli_fetch_assoc($cap_result)) {
    $room_capacities[$row['room_type']] = (int)$row['max_cap'];
}

$best_match = null;

foreach ($recommendations as $rec) {
    if ($guest_count >= $rec['min_guests'] && $guest_count <= $rec['max_guests'] &&
        $duration >= $rec['min_duration'] && $duration <= $rec['max_duration']) {
        
        $rec_room = $rec['recommended_room'];
        // VALIDATION: Ensure room capacity is sufficient
        if (isset($room_capacities[$rec_room]) && $room_capacities[$rec_room] >= $guest_count) {
            $best_match = $rec_room;
            break;
        }
    }
}

// Fallback 1: Closest Match by Guest Count
if (!$best_match) {
    $min_diff = 999;
    foreach ($recommendations as $rec) {
        $rec_room = $rec['recommended_room'];
        
        // Skip if capacity is insufficient
        if (!isset($room_capacities[$rec_room]) || $room_capacities[$rec_room] < $guest_count) {
            continue;
        }

        $diff = abs($guest_count - (($rec['min_guests'] + $rec['max_guests']) / 2));
        if ($diff < $min_diff) {
            $min_diff = $diff;
            $best_match = $rec_room;
        }
    }
}

// Fallback 2: "Best Fit" Database Lookup
if (!$best_match) {
    $fallback_query = "SELECT room_type FROM tbl_rooms 
                       WHERE status != 'maintenance' AND capacity >= $guest_count 
                       GROUP BY room_type 
                       ORDER BY capacity ASC, price ASC 
                       LIMIT 1";
    
    $fallback_result = mysqli_query($conn, $fallback_query);
    if ($fallback_result && mysqli_num_rows($fallback_result) > 0) {
        $row = mysqli_fetch_assoc($fallback_result);
        $best_match = $row['room_type'];
    }
}

// Check for warning (if all rooms of recommended type are occupied)
$warning = false;
if ($best_match) {
    $avail_query = "SELECT COUNT(*) as count FROM tbl_rooms WHERE room_type = '$best_match' AND status = 'available'";
    $avail_result = mysqli_query($conn, $avail_query);
    if ($avail_result) {
        $avail_row = mysqli_fetch_assoc($avail_result);
        if ($avail_row['count'] == 0) {
            $warning = true;
        }
    }
}

echo json_encode(['recommendation' => $best_match, 'warning' => $warning]);
