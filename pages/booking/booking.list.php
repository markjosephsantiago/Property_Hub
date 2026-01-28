<?php
session_start();
require '../../includes/conn.php';

// ✅ Check kung may access
if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], ['Admin', 'Employee'])) {
    $_SESSION['error'] = "Access denied.";
    header("Location: ../dashboard/index.php");
    exit();
}

$sql = "SELECT r.*, u.username, rm.room_number 
        FROM tbl_reservations r
        JOIN tbl_user u ON u.user_id = r.user_id
        JOIN tbl_rooms rm ON rm.room_id = r.room_id
        ORDER BY r.checkin DESC";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Booking List</title>
    <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="../../dist/css/adminlte.min.css">
    <?php include '../../includes/back.button.php';?>
</head>
<body class="hold-transition sidebar-mini">

<div class="container mt-5">
    <h3 class="mb-4">Booking List</h3>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= $_SESSION['success']; unset($_SESSION['success']); ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= $_SESSION['error']; unset($_SESSION['error']); ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    <?php endif; ?>

    <table class="table table-bordered table-striped">
        <thead class="thead-dark">
            <tr>
                <th>Booking ID</th>
                <th>Guest Name</th>
                <th>Room</th>
                <th>Check-in</th>
                <th>Check-out</th>
                <th>Duration</th>
                <th>Status</th>
                <th class="text-center">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if (mysqli_num_rows($result) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <?php
                    $status = strtolower(trim($row['status']));
                    ?>
                    <tr>
                        <td><?= $row['reservation_id'] ?></td>
                        <td><?= htmlspecialchars($row['username']) ?></td>
                        <td><?= htmlspecialchars($row['room_number']) ?></td>
                        <td><?= $row['checkin'] ?></td>
                        <td><?= $row['checkout'] ?></td>
                        <td><?= $row['duration'] ?? 'N/A' ?></td>
                        <td>
                        <?php
                        switch ($status) {
                            case 'confirmed':
                                echo '<span class="badge badge-success">Confirmed</span>';
                                break;
                            case 'cancelled':
                                echo '<span class="badge badge-secondary">Cancelled</span>';
                                break;
                            case 'checked in':
                                echo '<span class="badge badge-info">Checked In</span>';
                                break;
                            case 'checked out':
                                echo '<span class="badge badge-dark">Checked Out</span>';
                                break;
                            default:
                                echo '<span class="badge badge-warning">Pending</span>';
                                break;
                        }
                        ?>
                        </td>
                        <td class="text-center">
                            <?php if (in_array($_SESSION['role'], ['Admin', 'Employee'])): ?>

                            <!-- Confirm -->
                            <?php if ($status == 'pending'): ?>
                                <a href="bookingProcess/ctrl.confirm.booking.php?id=<?= $row['reservation_id'] ?>&source=booking" 
                                class="btn btn-sm btn-success"
                                onclick="return confirm('Confirm this booking?');">
                                Confirm
                                </a>
                            <?php endif; ?>

                            <!-- Check-in -->
                            <?php if ($status == 'confirmed'): ?>
                                <a href="bookingProcess/ctrl.update.status.php?id=<?= $row['reservation_id'] ?>&action=checkin&source=booking" 
                                class="btn btn-sm btn-primary"
                                onclick="return confirm('Mark as Checked In?');">
                                Check In
                                </a>
                            <?php endif; ?>

                            <!-- Check-out -->
                            <?php if ($status == 'checked in'): ?>
                                <a href="bookingProcess/ctrl.update.status.php?id=<?= $row['reservation_id'] ?>&action=checkout&source=booking" 
                                class="btn btn-sm btn-info"
                                onclick="return confirm('Mark as Checked Out?');">
                                Check Out
                                </a>
                            <?php endif; ?>

                            <!-- Cancel -->
                            <?php if (!in_array($status, ['cancelled', 'checked out'])): ?>
                                <a href="bookingProcess/ctrl.cancel.booking.php?id=<?= $row['reservation_id'] ?>&source=booking" 
                                class="btn btn-sm btn-danger"
                                onclick="return confirm('Cancel this booking?');">
                                Cancel
                                </a>
                            <?php endif; ?>


                                <!-- 🗑️ Delete button -->
                                <a href="bookingProcess/ctrl.delete.booking.php?id=<?= $row['reservation_id'] ?>&source=booking" 
                                   class="btn btn-sm btn-outline-secondary"
                                   onclick="return confirm('Are you sure you want to delete this booking?');">
                                   Delete
                                </a>

                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="8" class="text-center">No bookings found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script src="../../plugins/jquery/jquery.min.js"></script>
<script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script>
  // ✅ Save scroll position before leaving the page
  window.addEventListener("beforeunload", function () {
    localStorage.setItem("scrollPosition", window.scrollY);
  });

  // ✅ Restore scroll position after page load
  window.addEventListener("load", function () {
    if (localStorage.getItem("scrollPosition") !== null) {
      window.scrollTo(0, localStorage.getItem("scrollPosition"));
    }
  });
</script>
</body>
</html>
