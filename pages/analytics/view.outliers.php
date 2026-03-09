<?php
$outliersFile = 'outliers.csv';

if (!file_exists($outliersFile)) {
    echo "<p class='no-file'>No outlier file found. Please run <code>dbscan.outliers.py</code> first.</p>";
    exit;
}

$outliers = array_map('str_getcsv', file($outliersFile));
$headers = array_shift($outliers);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Outlier Bookings</title>
  <?php include "../../includes/link.php"; ?>
  <link rel="stylesheet" href="../../css/custom.css">
</head>

<body class="outlier-page">
  <div class="outlier-container">
    <h3 class="outlier-title">
      <i class="fas fa-exclamation-triangle me-2"></i> Anomalous Booking Identification
    </h3>

    <div class="table-responsive">
      <table class="table outlier-table">
        <thead>
          <tr>
            <?php foreach ($headers as $header) echo "<th>$header</th>"; ?>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($outliers as $row): ?>
            <tr>
              <?php foreach ($row as $value) echo "<td>$value</td>"; ?>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</body>
</html>
