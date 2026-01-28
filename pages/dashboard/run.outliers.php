<?php
// Step 1: Export reservation data to CSV
exec("php export.reservations.php");

// Step 2: Run DBSCAN Python script
exec("python dbscan.outliers.py");

// Step 3: Redirect to results page
header("Location: ../analytics/view.outliers.php");
exit;
?>
