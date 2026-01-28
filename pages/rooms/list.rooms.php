<?php
session_start();
require '../../includes/conn.php';

$status_filter = isset($_GET['status']) ? $_GET['status'] : '';

if ($status_filter == 'available') {
    $query = mysqli_query($conn, "SELECT * FROM tbl_rooms WHERE status = 'available'");
} elseif ($status_filter == 'occupied') {
    $query = mysqli_query($conn, "SELECT * FROM tbl_rooms WHERE status = 'occupied'");
} elseif ($status_filter == 'maintenance') {
    $query = mysqli_query($conn, "SELECT * FROM tbl_rooms WHERE status = 'maintenance'");
} else {
    $query = mysqli_query($conn, "SELECT * FROM tbl_rooms");
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Room List</title>
  <!-- AdminLTE + Bootstrap -->
  <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="../../dist/css/adminlte.min.css">
  <!-- <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700"> -->
</head>
<body class="hold-transition sidebar-mini">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="../dashboard/index.php">Dashboard</a></li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
<div class="wrapper">

  <!-- Main content -->
  <div class="content-wrapper" style="min-height: 100vh; background-color: #f6f4f9ff;">
    <div class="container py-5">
      <div class="card">
        <div class="card-header bg-primary text-white">
          <h3 class="card-title">Room List</h3>
          <a href="add.rooms.php" class="btn btn-light btn-sm float-right">
            <i class="fas fa-plus"></i> Add Room
          </a>
        </div>
        <div class="card-body">
          <?php if (isset($_SESSION['success'])): ?>
              <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
          <?php elseif (isset($_SESSION['error'])): ?>
              <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
          <?php endif; ?>

          <a href="list.rooms.php?status=available" 
            class="btn <?= ($status_filter == 'available') ? 'btn-success' : 'btn-outline-success' ?>">Available</a>
          <a href="list.rooms.php?status=occupied" 
            class="btn <?= ($status_filter == 'occupied') ? 'btn-danger' : 'btn-outline-danger' ?>">Occupied</a>
          <a href="list.rooms.php?status=maintenance" 
            class="btn <?= ($status_filter == 'maintenance') ? 'btn-warning' : 'btn-outline-warning' ?>">Maintenance</a>

          <table class="table table-bordered table-striped">
            <thead class="thead-dark">
              <tr>
                <th>Room No</th>
                <th>Type</th>
                <th>Capacity</th>
                <th>Price</th>
                <th>Status</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php while ($room = mysqli_fetch_assoc($query)): ?>
              <tr>
                <td><?= htmlspecialchars($room['room_number']) ?></td>
                <td><?= htmlspecialchars($room['room_type']) ?></td>
                <td><?= $room['capacity'] ?></td>
                <td>₱<?= number_format($room['price'], 2) ?></td>
                <td>
                  <span class="badge 
                    <?= $room['status'] === 'available' ? 'badge-success' : ($room['status'] === 'occupied' ? 'badge-warning' : 'badge-secondary') ?>">
                    <?= ucfirst($room['status']) ?>
                  </span>
                </td>
                <td>
                  <a href="edit.rooms.php?room_id=<?= $room['room_id'] ?>" class="btn btn-sm btn-primary">
                    <i class="fas fa-edit"></i> Edit
                  </a>
                  <a href="roomData/ctrl.delete.rooms.php?room_id=<?= $room['room_id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this room?')">
                    <i class="fas fa-trash-alt"></i> Delete
                  </a>
                </td>
              </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

</div>

<!-- Scripts -->
<script src="../../plugins/jquery/jquery.min.js"></script>
<script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../../dist/js/adminlte.min.js"></script>
</body>
</html>
