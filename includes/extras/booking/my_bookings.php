<?php
session_start();
require '../../includes/conn.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    header("Location: ../login.php");
    exit();
}

$result = mysqli_query($conn, "SELECT r.*, rm.room_number 
    FROM tbl_reservations r
    JOIN tbl_rooms rm ON rm.room_id = r.room_id
    WHERE r.user_id = $user_id
    ORDER BY r.checkin DESC");

if (!$result) {
    echo "SQL Error: " . mysqli_error($conn);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Bookings</title>
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
<div class="container mt-5">
    <h3>My Bookings</h3>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Room</th>
                <th>Check-in</th>
                <th>Check-out</th>
                <th>Code</th>
                <th>Status / Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (mysqli_num_rows($result) == 0): ?>
                <tr><td colspan="5" class="text-center">No bookings found for your account.</td></tr>
            <?php else: ?>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?= htmlspecialchars($row['room_number']) ?></td>
                    <td><?= $row['checkin'] ?></td>
                    <td><?= $row['checkout'] ?></td>
                    <td><?= $row['confirmation_code'] ?></td>
                    <td>
                        <!-- Status Badge -->
                        <?php if ($row['status'] === 'confirmed'): ?>
                            <span class="badge badge-success">Confirmed</span><br>
                            <form action="cancel.booking.php" method="POST" style="display:inline;">
                                <input type="hidden" name="booking_id" value="<?= $row['reservation_id'] ?>">
                                <button type="submit" class="btn btn-sm btn-danger mt-1"
                                        onclick="return confirm('Cancel this booking?');">Cancel</button>
                            </form>

                        <?php elseif ($row['status'] === 'pending'): ?>
                            <span class="badge badge-warning">Pending</span><br>
                            
                            <form action="confirm.booking.php" method="POST" style="display:inline;">
                                <input type="hidden" name="booking_id" value="<?= $row['reservation_id'] ?>">
                                <button type="submit" class="btn btn-sm btn-success mt-1">Confirm</button>
                            </form>

                            <a href="payment.form.php?reservation_id=<?= $row['reservation_id'] ?>" 
                            class="btn btn-sm btn-primary mt-1">
                            <i class="fas fa-money-bill"></i> Pay Now
                            </a>

                            <form action="cancel.booking.php" method="POST" style="display:inline;">
                                <input type="hidden" name="booking_id" value="<?= $row['reservation_id'] ?>">
                                <button type="submit" class="btn btn-sm btn-danger mt-1"
                                        onclick="return confirm('Cancel this booking?');">Cancel</button>
                            </form>

                        <?php elseif ($row['status'] === 'cancelled'): ?>
                            <span class="badge badge-secondary">Cancelled</span>

                        <?php else: ?>
                            <span class="badge badge-dark"><?= htmlspecialchars($row['status']) ?></span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endwhile; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
</body>
</html>
