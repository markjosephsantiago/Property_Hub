<?php
session_start();
include('../../includes/conn.php');

if (!isset($_SESSION['user_id'])) {
  header("Location: ../login.php");
  exit();
}

$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM tbl_user WHERE user_id = '$user_id' LIMIT 1";
$result = mysqli_query($conn, $sql);
$user = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Settings</title>
  <?php include('../../includes/link.php'); ?>
  <style>

  </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
  <div class="wrapper">
    <?php include('../../includes/navbar.php'); ?>

    <div class="content-wrapper p-4">
      <section class="content-header">
        <h1>Account Settings</h1>
      </section>

      <section class="content">
        <!-- Flash messages -->
        <?php if (isset($_SESSION['success'])): ?>
          <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle mr-1"></i>
            <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
        <?php elseif (isset($_SESSION['error'])): ?>
          <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle mr-1"></i>
            <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
        <?php endif; ?>

        <!-- Settings card -->
        <div class="settings-card">
          <h3 class="mb-3"><i class="fas fa-key mr-2"></i>Change Password</h3>
          <form action="usersData/ctrl.update.password.php" method="POST">
            <div class="form-group">
              <label>Current Password</label>
              <input type="password" name="current_password" class="form-control" required>
            </div>
            <div class="form-group">
              <label>New Password</label>
              <input type="password" name="new_password" class="form-control" required>
            </div>
            <div class="form-group">
              <label>Confirm New Password</label>
              <input type="password" name="confirm_password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary mt-2">
              <i class="fas fa-save mr-1"></i> Update Password
            </button>
          </form>
        </div>
      </section>
    </div>
  </div>
</body>
</html>
