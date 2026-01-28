<?php
session_start();
require '../../includes/conn.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add Booking</title>
  <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="../../dist/css/adminlte.min.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700">
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

  <style>
    body {
      background: #f4f6f9;
    }
    .booking-form {
      max-width: 900px;
      margin: 30px auto;
      background: #fff;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    .form-header {
      margin-bottom: 25px;
      text-align: center;
    }
    .form-header h2 {
      font-weight: bold;
      color: #333;
    }
    .select2-container--default .select2-selection--single {
      height: 50px;
      border-radius: 8px;
      border: 1px solid #ced4da;
      padding: 10px;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
      line-height: 28px;
    }
    .select2-container .select2-selection--single .select2-selection__arrow {
      height: 48px;
      right: 10px;
    }
  </style>
</head>
<body>

<div class="container">
  <div class="booking-form">
    <div class="form-header">
      <h2>Add Booking</h2>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
      <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
    <?php elseif (isset($_SESSION['error'])): ?>
      <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <form action="bookingProcess/ctrl.booking.php" method="POST">
      <div class="form-row">
        <div class="form-group col-md-6">
          <label for="guestName">Full Name</label>
          <input type="text" name="guestName" class="form-control" required>
        </div>
        <div class="form-group col-md-6">
          <label for="email">Email Address</label>
          <input type="email" name="email" class="form-control" required>
        </div>
      </div>

      <div class="form-row">
        <div class="form-group col-md-6">
          <label for="contact">Contact Number</label>
          <input type="text" name="contact" class="form-control" required>
        </div>
        <div class="form-group col-md-6">
          <label for="guest_count">Number of Guests</label>
          <input type="number" name="guest_count" class="form-control" min="1" required>
        </div>
      </div>

      <div class="form-group">
        <label for="room_id">Select Room</label>
        <select name="room_id" class="form-control" required>
          <option value="">-- Choose a Room --</option>
          <?php
          $query = $conn->query("SELECT * FROM tbl_rooms WHERE status = 'available'");
          if ($query->num_rows > 0):
            while ($row = $query->fetch_assoc()):
          ?>
            <option value="<?= $row['room_id'] ?>">
              Room <?= $row['room_number'] ?> - <?= htmlspecialchars($row['room_type']) ?> (₱<?= number_format($row['price'], 2) ?>)
            </option>
          <?php
            endwhile;
          else:
            echo "<option disabled>No available rooms</option>";
          endif;
          ?>
        </select>
      </div>

      <div class="form-row">
        <div class="form-group col-md-6">
          <label for="checkin">Check-in Date</label>
          <input type="date" name="checkin" class="form-control" required>
        </div>
        <div class="form-group col-md-6">
          <label for="checkout">Check-out Date</label>
          <input type="date" name="checkout" class="form-control" required>
        </div>
      </div>

      <div class="form-group text-right">
        <a href="../../home.php" class="btn btn-secondary">Cancel</a>
        <button type="submit" class="btn btn-primary">Confirm Booking</button>
      </div>
    </form>
  </div>
</div>

<script src="../../plugins/jquery/jquery.min.js"></script>
<script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../../dist/js/adminlte.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
  // Custom template with image
  function formatRoom (room) {
    if (!room.id) { return room.text; }
    var img = $(room.element).data('image');
    return $(
      '<span><img src="'+ img +'" style="width:40px;height:30px;object-fit:cover;margin-right:8px;border-radius:4px;">' +
      room.text + '</span>'
    );
  }

  $('#room_id').select2({
    templateResult: formatRoom,
    templateSelection: formatRoom,
    width: '100%'
  });
</script>
</body>
</html>
