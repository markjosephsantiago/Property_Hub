<?php
session_start();
require __DIR__ . '/../../includes/conn.php';
require __DIR__ . '/../../classes/guest.php';

// Initialize Guest object
$guest = new Guest($conn);
$guestResult = $guest->readAll();

if (!$guestResult) {
    die("Query failed: " . $conn->error);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>PMS | Guest List</title>
  <?php require '../../includes/link.php'; ?>
  <style>
    .card-primary.card-outline {
        border-top: 3px solid #dc143c;
    }
  </style>
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
  <?php require '../../includes/navbar.php'; ?>
  <?php require '../../includes/sidebar.php'; ?>

  <div class="content-wrapper">
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Guest List</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="../dashboard/index.php">Home</a></li>
              <li class="breadcrumb-item active">Guest List</li>
            </ol>
          </div>
        </div>
      </div>
    </section>

    <section class="content">
      <div class="container-fluid">
        <div class="card card-primary card-outline">
          <div class="card-header">
            <h3 class="card-title">All Registered Guests</h3>
            <div class="card-tools">
              <a href="guest.add.php" class="btn btn-sm btn-danger">
                <i class="fas fa-plus"></i> Add New Guest
              </a>
            </div>
          </div>
          <div class="card-body">
            <table id="guestTable" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Guest Name</th>
                  <th>Email</th>
                  <th>Contact</th>
                  <th>Address</th>
                  <th>ID Info</th>
                  <th>Registered</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php if ($guestResult && $guestResult->num_rows > 0): ?>
                  <?php while ($row = $guestResult->fetch_assoc()): ?>
                    <tr>
                      <td><?= htmlspecialchars($row['guest_id']); ?></td>
                      <td>
                        <strong><?= htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?></strong>
                        <?php if (!empty($row['middle_name'])): ?>
                          <br><small class="text-muted"><?= htmlspecialchars($row['middle_name']); ?></small>
                        <?php endif; ?>
                      </td>
                      <td><?= htmlspecialchars($row['email']); ?></td>
                      <td><?= htmlspecialchars($row['contact']); ?></td>
                      <td><?= htmlspecialchars($row['address'] ?: 'N/A'); ?></td>
                      <td>
                         <small>
                           <b>Type:</b> <?= htmlspecialchars($row['id_type'] ?: 'N/A'); ?><br>
                           <b>No:</b> <?= htmlspecialchars($row['id_number'] ?: 'N/A'); ?>
                         </small>
                      </td>
                      <td><?= date('M d, Y', strtotime($row['created_at'])); ?></td>
                      <td>
                        <div class="btn-group">
                          <a href="guest.edit.php?id=<?= $row['guest_id']; ?>" class="btn btn-sm btn-info" title="Edit">
                            <i class="fas fa-edit"></i>
                          </a>
                          <a href="guest.delete.php?id=<?= $row['guest_id']; ?>" 
                             class="btn btn-sm btn-danger" 
                             title="Delete"
                             onclick="return confirm('Are you sure you want to delete this guest?')">
                            <i class="fas fa-trash"></i>
                          </a>
                        </div>
                      </td>
                    </tr>
                  <?php endwhile; ?>
                <?php else: ?>
                  <tr>
                    <td colspan="8" class="text-center py-4">
                      <div class="text-muted">
                        <i class="fas fa-users-slash fa-3x mb-3"></i>
                        <p>No guests found in the database.</p>
                      </div>
                    </td>
                  </tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </section>
  </div>

  <?php require '../../includes/footer.php'; ?>
</div>

<?php require '../../includes/script.php'; ?>
<script>
  $(function () {
    $("#guestTable").DataTable({
      "responsive": true, 
      "lengthChange": false, 
      "autoWidth": false,
      "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
    }).buttons().container().appendTo('#guestTable_wrapper .col-md-6:eq(0)');
  });
</script>
</body>
</html>
