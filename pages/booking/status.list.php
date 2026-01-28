<?php
require '../../includes/conn.php';
session_start();
date_default_timezone_set('Asia/Manila');

$filter = $_GET['filter'] ?? 'all';
$title = "All Reservations";

switch ($filter) {
  case 'checkin':
    $title = "All Check-Ins";
    $query = "SELECT * FROM tbl_reservations WHERE status = 'checkin' ORDER BY checkin DESC";
    break;
  case 'checkout':
    $title = "All Check-Outs";
    $query = "SELECT * FROM tbl_reservations WHERE status = 'checkout' ORDER BY checkout DESC";
    break;
  case 'confirmed':
    $title = "All Confirmed Reservations";
    $query = "SELECT * FROM tbl_reservations WHERE status = 'confirmed' ORDER BY reservation_id DESC";
    break;
  case 'new':
    $title = "New Reservations";
    $query = "SELECT * FROM tbl_reservations WHERE status = 'pending' ORDER BY reservation_id DESC";
    break;
  case 'cancelled':
    $title = "Cancelled Reservations";
    $query = "SELECT * FROM tbl_reservations WHERE status = 'cancelled' ORDER BY reservation_id DESC";
    break;
  default:
    $title = "All Reservations";
    $query = "SELECT * FROM tbl_reservations ORDER BY reservation_id DESC";
    break;
}

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title><?= htmlspecialchars($title) ?> | Property Hub</title>
  <link rel="stylesheet" href="../../dist/css/adminlte.min.css">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper p-4">

  <h3 class="mb-4"><?= htmlspecialchars($title) ?></h3>

  <?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <?= htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?>
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
  <?php elseif (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <?= htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
  <?php endif; ?>

  <table class="table table-bordered table-hover">
    <thead class="thead-dark">
      <tr>
        <th>#</th>
        <th>Guest Name</th>
        <th>Email</th>
        <th>Contact</th>
        <th>Room</th>
        <th>Check-in</th>
        <th>Check-out</th>
        <th>Status</th>
        <th class="text-center">Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php if ($result && $result->num_rows > 0): ?>
        <?php $i = 1; while ($row = $result->fetch_assoc()): ?>
          <?php
          switch ($row['status']) {
            case 'pending': $badge = 'badge-warning'; break;
            case 'approved': $badge = 'badge-primary'; break;
            case 'confirmed': $badge = 'badge-success'; break;
            case 'checkin': $badge = 'badge-info'; break;
            case 'checkout': $badge = 'badge-secondary'; break;
            case 'cancelled': $badge = 'badge-danger'; break;
            default: $badge = 'badge-light';
          }
          ?>
          <tr>
            <td class="text-center"><?= $i++ ?></td>
            <td><?= htmlspecialchars($row['guestName'] ?? '—') ?></td>
            <td><?= htmlspecialchars($row['email'] ?? '—') ?></td>
            <td><?= htmlspecialchars($row['contact'] ?? '—') ?></td>
            <td class="text-center"><?= htmlspecialchars($row['room_id'] ?? '-') ?></td>
            <td><?= date('M d, Y h:i A', strtotime($row['checkin'])) ?></td>
            <td><?= date('M d, Y h:i A', strtotime($row['checkout'])) ?></td>
            <td class="text-center">
              <span class="badge <?= $badge ?>"><?= ucfirst($row['status']) ?></span>
            </td>
            <td class="text-center">
              <?php if (in_array($_SESSION['role'], ['Admin', 'Employee'])): ?>

                <!-- ✅ Confirm button -->
                <?php if ($row['status'] == 'pending'): ?>
                  <a href="bookingProcess/ctrl.confirm.booking.php?id=<?= $row['reservation_id'] ?>&source=status" 
                    class="btn btn-sm btn-success">Confirm</a>
                <?php endif; ?>

                <!-- ✅ Check-in button -->
                <?php if ($row['status'] == 'confirmed'): ?>
                  <a href="bookingProcess/ctrl.update.status.php?id=<?= $row['reservation_id'] ?>&action=checkin&source=status" 
                    class="btn btn-sm btn-primary">Check In</a>
                <?php endif; ?>

                <!-- ✅ Check-out button -->
                <?php if ($row['status'] == 'checkin'): ?>
                  <a href="bookingProcess/ctrl.update.status.php?id=<?= $row['reservation_id'] ?>&action=checkout&source=status" 
                    class="btn btn-sm btn-info">Check Out</a>
                <?php endif; ?>

                <!-- ❌ Cancel button (POST form) -->
                <?php if (!in_array($row['status'], ['cancelled', 'checkout'])): ?>
                  <form action="bookingProcess/ctrl.cancel.booking.php" method="POST" style="display:inline;">
                    <input type="hidden" name="booking_id" value="<?= $row['reservation_id'] ?>">
                    <input type="hidden" name="source" value="status">
                    <button type="submit" class="btn btn-sm btn-danger"
                      onclick="return confirm('Are you sure you want to cancel this booking?');">
                      Cancel
                    </button>
                  </form>
                <?php endif; ?>

                <!-- 🗑️ Delete button (POST form) -->
                <form action="bookingProcess/ctrl.delete.booking.php" method="POST" style="display:inline;">
                  <input type="hidden" name="booking_id" value="<?= $row['reservation_id'] ?>">
                  <input type="hidden" name="source" value="status">
                  <button type="submit" class="btn btn-sm btn-outline-secondary"
                    onclick="return confirm('Are you sure you want to delete this booking?');">
                    Delete
                  </button>
                </form>

              <?php endif; ?>
            </td>
          </tr>

        <?php endwhile; ?>
      <?php else: ?>
        <tr>
          <td colspan="9" class="text-center text-muted py-4">No reservations found.</td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>

</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
