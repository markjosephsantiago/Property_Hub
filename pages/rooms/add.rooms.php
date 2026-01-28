<?php
session_start();
require '../../includes/conn.php';

if (isset($_SESSION['room_added'])) {
    echo "<script>alert('Room successfully added!');</script>";
    unset($_SESSION['room_added']);
}

if (isset($_SESSION['room_exist'])) {
    echo "<script>alert('room already exists!');</script>";
    unset($_SESSION['room_exist']);
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Room</title>
    <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="../../dist/css/adminlte.min.css">
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
<div class="container mt-5" style="max-width: 400px;">
    <h3>Add New Room</h3>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
    <?php elseif (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <form class="form" action="roomData/ctrl.add.rooms.php" method="POST" enctype="multipart/form-data">
        <div class="form-group mb-3">
            <label>Room Number</label>
            <input type="text" name="room_number" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Room Type</label>
            <input type="text" name="room_type" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Capacity</label>
            <input type="number" name="capacity" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Price (₱)</label>
            <input type="number" step="0.01" name="price" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Status</label>
            <select name="status" class="form-control" required>
                <option value="available">Available</option>
                <option value="occupied">Occupied</option>
                <option value="maintenance">Maintenance</option>
            </select>
        </div>

        <!-- 🔥 New: Upload field -->
        <div class="form-group">
            <label>Room Image</label>
            <input type="file" name="room_image" class="form-control" accept="image/*">
        </div>

        <button type="submit" class="btn btn-primary">Add Room</button>
    </form>
</div>
</body>
</html>
