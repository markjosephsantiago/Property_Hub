<?php
include "../../includes/conn.php";
include "../../includes/session.php"; // optional kung may session ka

// Get reservations that are pending and ready for check-in
$sql = "SELECT r.reservation_id, r.guestName, rm.room_number, rm.room_type, r.checkin, r.checkout, r.status
        FROM tbl_reservations r
        JOIN tbl_rooms rm ON r.room_id = rm.room_id
        WHERE r.status = 'pending'
        ORDER BY r.checkin ASC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Check-In List</title>
    <link rel="stylesheet" href="../../dist/css/adminlte.min.css">
    <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

<?php include "../../includes/navbar.php"; ?>
<?php include "../../includes/sidebar.php"; ?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <h4><i class="fas fa-door-open"></i> Guest Check-In List</h4>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">
                    <h3 class="card-title"><i class="fas fa-user-check"></i> Pending Guests for Check-In</h3>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        <thead class="bg-light">
                            <tr>
                                <th>#</th>
                                <th>Guest</th>
                                <th>Room</th>
                                <th>Check-In</th>
                                <th>Check-Out</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($result->num_rows > 0): $count = 1; while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= $count++; ?></td>
                                <td><?= htmlspecialchars($row['guestName']); ?></td>
                                <td><?= htmlspecialchars($row['room_number'] . " (" . $row['room_type'] . ")"); ?></td>
                                <td><?= date('M d, Y', strtotime($row['checkin'])); ?></td>
                                <td><?= date('M d, Y', strtotime($row['checkout'])); ?></td>
                                <td>
                                    <a href="bookingProcess/ctrl.checkin.php?id=<?= $row['reservation_id']; ?>" 
                                       class="btn btn-success btn-sm">
                                       <i class="fas fa-door-open"></i> Check In
                                    </a>
                                </td>
                            </tr>
                            <?php endwhile; else: ?>
                            <tr><td colspan="6" class="text-center">No guests for check-in</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>

</div>
<script src="../../plugins/jquery/jquery.min.js"></script>
<script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../../dist/js/adminlte.min.js"></script>
</body>
</html>
