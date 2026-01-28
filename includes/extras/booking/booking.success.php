<?php
session_start();
$data = $_SESSION['booking_success'] ?? null;

if (!$data) {
    header("Location: add.booking.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Booking Success</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap / AdminLTE -->
  <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="../../dist/css/adminlte.min.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700">

  <style>
    body {
      background: #f4f6f9;
    }
    .card {
      margin-top: 50px;
      border-radius: 15px;
      box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    .btn-home {
      border-radius: 30px;
    }
    .room-img {
      max-width: 100%;
      border-radius: 10px;
      margin-bottom: 15px;
    }
  </style>
</head>
<body class="hold-transition">

<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="card">
        <div class="card-header bg-success text-white text-center">
          <h3 class="card-title mb-0"><i class="fas fa-check-circle"></i> Booking Confirmed!</h3>
        </div>
          <div class="card-body text-left">

            <!-- Room Image if available -->
            <?php if (!empty($data['room_image'])): ?>
              <img src="../../uploads/rooms/<?= htmlspecialchars($data['room_image']) ?>" class="room-img" alt="Room Image">
            <?php endif; ?>

            <p><strong>Name:</strong> <?= htmlspecialchars($data['guestName']) ?></p>
            <p><strong>Check-in:</strong> <?= htmlspecialchars($data['checkin']) ?></p>
            <p><strong>Check-out:</strong> <?= htmlspecialchars($data['checkout']) ?></p>
            <p><strong>Duration:</strong> <?= htmlspecialchars($data['duration_days']) ?> night(s)</p>
            <p><strong>Confirmation Code:</strong> <span class="text-primary"><?= htmlspecialchars($data['confirmation_code']) ?></span></p>
            <p><strong>Room Number:</strong> <?= htmlspecialchars($data['room_number']) ?></p>
            <p><strong>Room Type:</strong> <?= htmlspecialchars($data['room_type']) ?></p>
            <p><strong>Price:</strong> ₱<?= number_format($data['price'], 2) ?></p>
            <p><strong>Status:</strong> 
              <?php 
              $status = $data['status'] ?? 'pending'; 
              ?>
              <span class="<?= $status === 'confirmed' ? 'text-success' : ($status === 'cancelled' ? 'text-danger' : 'text-warning') ?>">
                  <?= htmlspecialchars(ucfirst($status)) ?>
              </span>
            </p>
            <hr>
            <h5><strong>Total Price:</strong> ₱<?= number_format($data['total_price'], 2) ?></h5>

            <a href="../../home.php" class="btn btn-primary btn-home"><i class="fas fa-home"></i> Back to Home</a>
          </div>
      </div>
    </div>
  </div>
</div>

<!-- JS -->
<script src="../../plugins/jquery/jquery.min.js"></script>
<script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../../dist/js/adminlte.min.js"></script>
</body>
</html>
