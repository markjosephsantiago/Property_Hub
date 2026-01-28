<?php
require '../../includes/conn.php';
session_start();

// Get room_id safely
if (!isset($_GET['room_id'])) {
    $_SESSION['error'] = "Missing room ID.";
    header("Location: list.rooms.php");
    exit();
}

$room_id = $_GET['room_id'];

// Secure fetch
$stmt = $conn->prepare("SELECT * FROM tbl_rooms WHERE room_id = ?");
$stmt->bind_param("i", $room_id);
$stmt->execute();
$result = $stmt->get_result();
$room = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
  <title>Update Room</title>
  <!-- AdminLTE -->
  <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="../../dist/css/adminlte.min.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700">

  <style>
    .card {
      margin-top: 50px;
      box-shadow: 0 0 20px rgba(0,0,0,0.1);
    }
    .form-control:focus {
      border-color: #007bff;
      box-shadow: none;
    }
    .preview-img {
      width: 150px;
      height: 100px;
      object-fit: cover;
      margin-top: 10px;
      border-radius: 5px;
      border: 1px solid #ddd;
    }
  </style>
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
  <div class="content-wrapper" style="min-height: 100vh; background-color: #f4f6f9;">

    <div class="container py-5">
      <div class="row justify-content-center">
        <div class="col-md-8">
          <div class="card">
            <div class="card-header bg-primary text-white">
              <h3 class="card-title">Update Room</h3>
            </div>
            <div class="card-body">

              <?php if (isset($_SESSION['success'])): ?>
                  <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
              <?php elseif (isset($_SESSION['error'])): ?>
                  <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
              <?php endif; ?>

              <form action="roomData/ctrl.edit.rooms.php" method="POST" enctype="multipart/form-data">
                <!-- Hidden field for room ID -->
                <input type="hidden" name="room_id" value="<?= $room_id ?>">

                <div class="form-group">
                  <label for="room_number">Room Number</label>
                  <input type="text" name="room_number" class="form-control" value="<?= htmlspecialchars($room['room_number']) ?>" required>
                </div>

                <div class="form-group">
                  <label for="room_type">Room Type</label>
                  <input type="text" name="room_type" class="form-control" value="<?= htmlspecialchars($room['room_type']) ?>" required>
                </div>

                <div class="form-group">
                  <label for="capacity">Capacity</label>
                  <input type="number" name="capacity" class="form-control" value="<?= $room['capacity'] ?>" required>
                </div>

                <div class="form-group">
                  <label for="price">Price (₱)</label>
                  <input type="number" name="price" step="0.01" class="form-control" value="<?= $room['price'] ?>" required>
                </div>

                <div class="form-group">
                  <label for="status">Status</label>
                  <select name="status" class="form-control" required>
                    <option value="available" <?= $room['status'] === 'available' ? 'selected' : '' ?>>Available</option>
                    <option value="occupied" <?= $room['status'] === 'occupied' ? 'selected' : '' ?>>Occupied</option>
                    <option value="maintenance" <?= $room['status'] === 'maintenance' ? 'selected' : '' ?>>Maintenance</option>
                  </select>
                </div>

                <div class="form-group">
                  <label for="room_image">Room Image</label>
                  <input type="file" name="room_image" class="form-control">
                  <?php if (!empty($room['room_image'])): ?>
                      <img src="../../uploads/rooms/<?= htmlspecialchars($room['room_image']) ?>" 
                           alt="Room Image" class="preview-img">
                  <?php endif; ?>
                </div>

                <div class="text-right">
                  <a href="list.rooms.php" class="btn btn-secondary">Cancel</a>
                  <button type="submit" class="btn btn-primary">Update Room</button>
                </div>
              </form>

            </div>
          </div>
        </div>
      </div>
    </div>

  </div>
</div>

<!-- AdminLTE Scripts -->
<script src="../../plugins/jquery/jquery.min.js"></script>
<script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../../dist/js/adminlte.min.js"></script>
</body>
</html>
