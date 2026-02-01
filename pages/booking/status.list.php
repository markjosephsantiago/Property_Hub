<?php
require '../../includes/conn.php';
session_start();
date_default_timezone_set('Asia/Manila');

$filter = $_GET['filter'] ?? 'all';
$search_code = $_GET['search_code'] ?? '';
$title = "All Reservations";

// Base Query
$sql = "SELECT * FROM tbl_reservations";
$conditions = [];

if (!empty($search_code)) {
    // If searching, we prioritize the search result but can still respect status if needed. 
    // Usually search overrides status filters for ease of finding a specific record.
    $conditions[] = "confirmation_code LIKE '%" . $conn->real_escape_string($search_code) . "%'";
    $title = "Search Result: " . htmlspecialchars($search_code);
} else {
    // Apply Status Filter
    switch ($filter) {
        case 'checkin':
            $title = "All Check-Ins";
            $conditions[] = "status = 'checkin'";
            break;
        case 'checkout':
            $title = "All Check-Outs";
            $conditions[] = "status = 'checkout'";
            break;
        case 'confirmed':
            $title = "All Confirmed Reservations";
            $conditions[] = "status = 'confirmed'";
            break;
        case 'new':
            $title = "New Reservations";
            $conditions[] = "status = 'pending'";
            break;
        case 'cancelled':
            $title = "Cancelled Reservations";
            $conditions[] = "status = 'cancelled'";
            break;
        default:
            // All reservations
            break;
    }
}

// Build Query
if (!empty($conditions)) {
    $sql .= " WHERE " . implode(" AND ", $conditions);
}

// Default ordering
$sql .= " ORDER BY reservation_id DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title><?= htmlspecialchars($title) ?> | Franciscan Reservation</title>
  <link rel="stylesheet" href="../../dist/css/adminlte.min.css">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper p-4">
  <head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="../../dist/css/adminlte.min.css">
    <?php include '../../includes/back.button.php';?>
</head>

  <h3 class="mb-4"><?= htmlspecialchars($title) ?></h3>

  <!-- Search Bar for Confirm Code (Admin/Employee) -->
  <?php if (isset($_SESSION['role']) && in_array($_SESSION['role'], ['Admin', 'Employee'])): ?>
    <form action="" method="GET" class="mb-3">
        <div class="input-group" style="width: 300px;">
            <?php if(!empty($filter) && $filter != 'all'): ?>
                <input type="hidden" name="filter" value="<?= htmlspecialchars($filter) ?>">
            <?php endif; ?>
            <input type="text" name="search_code" class="form-control" placeholder="Confirmation Code..." value="<?= htmlspecialchars($search_code) ?>">
            <div class="input-group-append">
                <button class="btn btn-primary" type="submit">
                    <i class="fas fa-search"></i>
                </button>
            </div>
            <?php if (!empty($search_code)): ?>
                <div class="input-group-append">
                    <a href="status.list.php" class="btn btn-outline-secondary" title="Clear Search">
                        <i class="fas fa-times"></i>
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </form>
  <?php endif; ?>

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
                  <a href="checkin.form.php?reservation_id=<?= $row['reservation_id'] ?>" 
                    class="btn btn-sm btn-primary">Check In</a>
                <?php endif; ?>

                <!-- ✅ Check-out button -->
                <?php if ($row['status'] == 'checkin'): ?>
                  <a href="payment.form.php?reservation_id=<?= $row['reservation_id'] ?>&action=checkout&source=status" 
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
