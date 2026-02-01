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
  <style>
    .content-wrapper { background-color: #f8f9fa !important; }
    .card-modern {
      border: none;
      border-top: 4px solid #dc143c;
      box-shadow: 0 4px 12px rgba(0,0,0,0.08);
      border-radius: 8px;
    }
    .card-modern .card-header {
      background-color: #fff;
      color: #333;
      border-bottom: 1px solid #eee;
    }
    .card-modern .card-title { font-weight: 700; color: #dc143c; }
    .status-pill {
      border-radius: 50px;
      padding: 0.5em 1.2em;
      font-weight: 600;
      text-transform: uppercase;
      font-size: 11px;
    }
    .badge-available { background-color: #28a745; color: white; }
    .badge-occupied { background-color: #dc3545; color: white; }
    .badge-maintenance { background-color: #ffc107; color: black; }
    
    .btn-filter {
      border-radius: 30px;
      margin-right: 10px;
      font-weight: 600;
      letter-spacing: 0.5px;
      transition: all 0.3s;
    }
    .btn-filter:hover { transform: translateY(-2px); box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
    
    .btn-action {
      border-radius: 6px;
      margin: 2px;
      width: 34px;
      height: 34px;
      padding: 0;
      border: none;
      display: inline-flex;
      align-items: center;
      justify-content: center;
    }
    .table thead th {
      border-top: none;
      background-color: #f1f3f5;
      color: #495057;
      font-weight: 700;
      font-size: 13px;
      text-transform: uppercase;
    }
    .table td { vertical-align: middle; }
    .btn-add {
      background-color: #dc143c;
      color: white;
      border-radius: 30px;
      font-weight: 600;
      padding: 8px 20px;
    }
    .btn-add:hover { background-color: #b2112f; color: white; }
  </style>
</head>
<body class="hold-transition sidebar-mini">
    <div class="wrapper">
    <?php require '../../includes/navbar.php'; ?>
    <?php require '../../includes/sidebar.php'; ?>

    <div class="content-wrapper">
      <section class="content-header p-4">
        <div class="container-fluid">
          <div class="row align-items-center">
            <div class="col-sm-6">
              <h1 style="font-weight: 700; color: #333;">Room Inventory</h1>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="../dashboard/index.php">Dashboard</a></li>
                <li class="breadcrumb-item active">Rooms</li>
              </ol>
            </div>
          </div>
        </div>
      </section>

      <div class="container-fluid px-4">
        <div class="card card-modern">
          <div class="card-header d-flex align-items-center justify-content-between p-3">
            <h3 class="card-title m-0">
              <i class="fas fa-door-open mr-2"></i> Manage Resort Rooms
            </h3>
            <div class="card-tools ml-auto">
              <a href="add.rooms.php" class="btn btn-add">
                <i class="fas fa-plus-circle mr-1"></i> Add New Room
              </a>
            </div>
          </div>
          
          <div class="card-body">
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show">
                  <i class="fas fa-check-circle mr-2"></i> <?= $_SESSION['success']; unset($_SESSION['success']); ?>
                  <button type="button" class="close" data-dismiss="alert">&times;</button>
                </div>
            <?php elseif (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                  <i class="fas fa-exclamation-triangle mr-2"></i> <?= $_SESSION['error']; unset($_SESSION['error']); ?>
                  <button type="button" class="close" data-dismiss="alert">&times;</button>
                </div>
            <?php endif; ?>

            <div class="mb-4 d-flex flex-wrap">
              <a href="list.rooms.php" 
                class="btn btn-filter <?= ($status_filter == '') ? 'btn-secondary' : 'btn-outline-secondary' ?>">
                <i class="fas fa-list mr-1"></i> All Rooms
              </a>
              <a href="list.rooms.php?status=available" 
                class="btn btn-filter <?= ($status_filter == 'available') ? 'btn-success' : 'btn-outline-success' ?>">
                <i class="fas fa-check mr-1"></i> Available
              </a>
              <a href="list.rooms.php?status=occupied" 
                class="btn btn-filter <?= ($status_filter == 'occupied') ? 'btn-danger' : 'btn-outline-danger' ?>">
                <i class="fas fa-user-lock mr-1"></i> Occupied
              </a>
              <a href="list.rooms.php?status=maintenance" 
                class="btn btn-filter <?= ($status_filter == 'maintenance') ? 'btn-warning' : 'btn-outline-warning' ?>">
                <i class="fas fa-tools mr-1"></i> Maintenance
              </a>
            </div>

            <div class="table-responsive">
              <table class="table table-hover mb-0">
                <thead>
                  <tr>
                    <th><i class="fas fa-hashtag mr-1"></i> Room No</th>
                    <th><i class="fas fa-bed mr-1"></i> Type</th>
                    <th><i class="fas fa-users mr-1"></i> Capacity</th>
                    <th><i class="fas fa-tag mr-1"></i> Price</th>
                    <th><i class="fas fa-signal mr-1"></i> Status</th>
                    <th class="text-center">Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if (mysqli_num_rows($query) == 0): ?>
                    <tr>
                      <td colspan="6" class="text-center py-5 text-muted">No rooms found for this category.</td>
                    </tr>
                  <?php endif; ?>
                  
                  <?php while ($room = mysqli_fetch_assoc($query)): 
                    $badge_class = 'badge-available';
                    $icon = 'fa-check';
                    
                    if ($room['status'] === 'occupied') {
                        $badge_class = 'badge-occupied';
                        $icon = 'fa-user-lock';
                    } elseif ($room['status'] === 'maintenance') {
                        $badge_class = 'badge-maintenance';
                        $icon = 'fa-tools';
                    }
                  ?>
                  <tr>
                    <td class="font-weight-bold">#<?= htmlspecialchars($room['room_number']) ?></td>
                    <td><?= htmlspecialchars($room['room_type']) ?></td>
                    <td><?= $room['capacity'] ?> Guests</td>
                    <td class="text-success font-weight-bold">₱<?= number_format($room['price'], 2) ?></td>
                    <td>
                      <span class="status-pill <?= $badge_class ?>">
                        <i class="fas <?= $icon ?> mr-1"></i> <?= ucfirst($room['status']) ?>
                      </span>
                    </td>
                    <td class="text-center">
                      <a href="edit.rooms.php?room_id=<?= $room['room_id'] ?>" class="btn-action bg-primary" title="Edit Room">
                        <i class="fas fa-edit"></i>
                      </a>
                      <a href="roomData/ctrl.delete.rooms.php?room_id=<?= $room['room_id'] ?>" class="btn-action bg-danger" title="Delete Room" onclick="return confirm('Are you sure you want to delete this room?')">
                        <i class="fas fa-trash-alt"></i>
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
</div>

<!-- Scripts -->
<script src="../../plugins/jquery/jquery.min.js"></script>
<script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../../dist/js/adminlte.min.js"></script>
</body>
</html>
