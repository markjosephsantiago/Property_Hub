<?php
// pages/analytics/train_model.php

// 1. Establish connection
$connPath = __DIR__ . '/../../includes/conn.php';
if (file_exists($connPath)) {
    require_once $connPath;
} else {
    if (!isset($conn)) {
        die("Database connection not found.");
    }
}

// 2. Export Data to CSV
$csvFile = __DIR__ . '/reservations.csv';
$fp = fopen($csvFile, 'w');
if ($fp) {
    fputcsv($fp, ['reservation_id', 'guest_count', 'checkin', 'checkout', 'duration_days', 'room_type']);

    $query = "SELECT reservation_id, guest_count, checkin, checkout, duration, room_type FROM tbl_reservations";
    $result = mysqli_query($conn, $query);

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            fputcsv($fp, $row);
        }
    }
    fclose($fp);
}

// 3. Run Python Script
$pythonScript = __DIR__ . '/dbscan.recommend.py';
// Using absolute path for portability across environments (WAMP/CMS)
$pythonPath = 'C:\\Users\\Zaimon\\AppData\\Local\\Microsoft\\WindowsApps\\PythonSoftwareFoundation.Python.3.13_qbz5n2kfra8p0\\python.exe';
$command = "\"$pythonPath\" \"$pythonScript\" 2>&1"; 
$output = shell_exec($command);

// 4. Output Result
if (defined('STDIN')) {
    echo $output;
} else {
    echo "DBSCAN Training Output: " . $output;
}
?>
