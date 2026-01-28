<?php
session_start();
require_once __DIR__ . '/../../includes/conn.php';
require_once __DIR__ . '/../../classes/guest.php';

$guest = new Guest($conn);
$result = $guest->readAll();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>PMS | Guest List</title>
  <?php require '../../includes/link.php'; ?>
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
            <h1>Guest List</h1>
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
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Guests</h3>
        </div>

        <div class="card-body">
          <a href="guest.add.php" class="btn btn-success mb-3">Add Guest</a>
          <table id="example1" class="table table-bordered table-striped">
            <thead>
              <tr>
                <th>#</th>
                <th>Full Name</th>
                <th>Email</th>
                <th>Contact</th>
                <th>Address</th>
                <th>ID Type</th>
                <th>ID Number</th>
                <th>Date Created</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
            <?php if ($result && $result->num_rows > 0): ?>
              <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                  <td><?= htmlspecialchars($row['guest_id']); ?></td>
                  <td><?= htmlspecialchars($row['first_name'] . ' ' . ($row['middle_name'] ?? '') . ' ' . $row['last_name']); ?></td>
                  <td><?= htmlspecialchars($row['email']); ?></td>
                  <td><?= htmlspecialchars($row['contact']); ?></td>
                  <td><?= htmlspecialchars($row['address']); ?></td>
                  <td><?= htmlspecialchars($row['id_type']); ?></td>
                  <td><?= htmlspecialchars($row['id_number']); ?></td>
                  <td><?= htmlspecialchars($row['created_at']); ?></td>
                  <td>
                    <a href="guest.edit.php?id=<?= urlencode($row['guest_id']); ?>" class="btn btn-sm btn-primary">Edit</a>
                    <a href="guest.delete.php?id=<?= urlencode($row['guest_id']); ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this guest?')">Delete</a>
                  </td>
                </tr>
              <?php endwhile; ?>
            <?php else: ?>
              <tr><td colspan="9" class="text-center">No guests found.</td></tr>
            <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </section>
  </div>

  <?php require '../../includes/footer.php'; ?>
  <aside class="control-sidebar control-sidebar-dark"></aside>
</div>

<?php require '../../includes/script.php'; ?>
</body>
</html>
