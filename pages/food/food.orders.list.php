<?php
session_start();
require '../../includes/conn.php';

// 🔐 Role protection
if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], ['Admin', 'Employee'])) {
    header("Location: ../../login.php");
    exit;
}

// 🔹 Fetch food orders
$orders = $conn->query("
    SELECT 
        fo.order_id,
        fo.guest_name,
        fo.order_total,
        fo.order_status,
        fo.created_at,
        rm.room_number
    FROM tbl_food_orders fo
    JOIN tbl_rooms rm ON fo.room_id = rm.room_id
    ORDER BY fo.order_id DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Food Orders</title>

<link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
<link rel="stylesheet" href="../../dist/css/adminlte.min.css">
</head>

<body class="hold-transition sidebar-mini">
<div class="wrapper">

<!-- 🔹 Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
    </li>
  </ul>
</nav>

<!-- 🔹 Content Wrapper -->
<div class="content-wrapper">

<!-- Header -->
<section class="content-header">
  <div class="container-fluid">
    <h1>🍽️ Food Orders</h1>
  </div>
</section>

<!-- Main content -->
<section class="content">
<div class="container-fluid">

<div class="card">
<div class="card-header bg-info">
  <h3 class="card-title text-white">Food Orders List</h3>
</div>

<div class="card-body">
<table class="table table-bordered table-hover">
<thead class="thead-dark">
<tr>
  <th>#</th>
  <th>Guest</th>
  <th>Room</th>
  <th>Total</th>
  <th>Status</th>
  <th>Date</th>
  <th>Action</th>
</tr>
</thead>

<tbody>
<?php if ($orders->num_rows > 0): ?>
<?php while ($row = $orders->fetch_assoc()): ?>
<tr>
  <td><?= $row['order_id'] ?></td>
  <td><?= htmlspecialchars($row['guest_name']) ?></td>
  <td>Room <?= $row['room_number'] ?></td>
  <td>₱<?= number_format($row['order_total'], 2) ?></td>

  <td>
    <?php if ($row['order_status'] == 'preparing'): ?>
      <span class="badge badge-warning">Preparing</span>
    <?php elseif ($row['order_status'] == 'served'): ?>
      <span class="badge badge-success">Served</span>
    <?php else: ?>
      <span class="badge badge-secondary"><?= ucfirst($row['order_status']) ?></span>
    <?php endif; ?>
  </td>

  <td><?= date("M d, Y h:i A", strtotime($row['created_at'])) ?></td>

  <td>
    <?php if ($row['order_status'] == 'pending'): ?>
      <a href="food.status.php?id=<?= $row['order_id'] ?>&status=preparing"
        class="btn btn-warning btn-sm"
        onclick="return confirm('Start preparing this order?')">
        <i class="fas fa-fire"></i> Prepare
      </a>

    <?php elseif ($row['order_status'] == 'preparing'): ?>
      <a href="food.status.php?id=<?= $row['order_id'] ?>&status=served"
        class="btn btn-success btn-sm"
        onclick="return confirm('Mark this order as SERVED?')">
        <i class="fas fa-check"></i> Served
      </a>

    <?php else: ?>
      <button class="btn btn-secondary btn-sm" disabled>
        <i class="fas fa-check"></i> Done
      </button>
    <?php endif; ?>
  </td>
</tr>
<?php endwhile; ?>
<?php else: ?>
<tr>
  <td colspan="7" class="text-center text-muted">
    No food orders found
  </td>
</tr>
<?php endif; ?>
</tbody>
</table>
</div>
</div>

</div>
</section>

</div>

<!-- Footer -->
<footer class="main-footer text-center">
  <strong>Property Hub</strong> © <?= date('Y') ?>
</footer>

</div>

<script src="../../plugins/jquery/jquery.min.js"></script>
<script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../../dist/js/adminlte.min.js"></script>

</body>
</html>
