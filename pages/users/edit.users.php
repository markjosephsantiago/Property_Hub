<?php
session_start();
require '../../includes/conn.php';

if (isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];
} else {
    $user_id = $_SESSION['user_id'];
}

// Fetch user data using mysqli
$query = mysqli_query($conn, "SELECT tbl_user.*, tbl_roles.role FROM tbl_user 
                              LEFT JOIN tbl_roles ON tbl_user.role_id = tbl_roles.role_id 
                              WHERE user_id = '$user_id'");
$user = mysqli_fetch_assoc($query);

if (!$user) {
    die('User not found.');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Property Hub | Edit User</title>
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
            <h1>Edit User</h1>
          </div>
        </div>
      </div>
    </section>

    <section class="content">
      <div class="container-fluid">
        <div class="row justify-content-center">
          <div class="col-md-8">
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">User Info</h3>
              </div>

              <form method="POST" action="usersData/ctrl.update.users.php">
                <input type="hidden" name="user_id" value="<?= $user['user_id'] ?>">

                <div class="card-body">
                  <div class="row">
                    <div class="form-group col-md-4">
                      <label for="firstName">First Name</label>
                      <input type="text" class="form-control" name="firstName" value="<?= $user['firstName'] ?>" required>
                    </div>
                    <div class="form-group col-md-4">
                      <label for="middleName">Middle Name</label>
                      <input type="text" class="form-control" name="middleName" value="<?= $user['middleName'] ?>">
                    </div>
                    <div class="form-group col-md-4">
                      <label for="lastName">Last Name</label>
                      <input type="text" class="form-control" name="lastName" value="<?= $user['lastName'] ?>" required>
                    </div>
                  </div>

                  <div class="form-group col-md-6">
                    <label for="contact">Contact</label>
                    <input type="text" class="form-control" name="contact" value="<?= $user['contact'] ?>">
                  </div>

                  <div class="form-group col-md-6">
                    <label for="username">Username</label>
                    <input type="text" class="form-control" name="username" value="<?= $user['username'] ?>" required>
                  </div>

                  <div class="form-group col-md-6">
                    <label for="role_id">Role</label>
                    <select name="role_id" class="form-control" required>
                      <option value="">Select Role</option>
                      <option value="1" <?= $user['role_id'] == 1 ? 'selected' : '' ?>>Guest</option>
                      <option value="2" <?= $user['role_id'] == 2 ? 'selected' : '' ?>>Employee</option>
                      <option value="3" <?= $user['role_id'] == 3 ? 'selected' : '' ?>>Admin</option>
                    </select>
                  </div>

                  <div class="form-group col-md-6">
                    <label for="email">Email address</label>
                    <input type="email" class="form-control" name="email" value="<?= $user['email'] ?>" required>
                  </div>

                  <div class="form-group col-md-6">
                    <label for="password">New Password (leave blank if unchanged)</label>
                    <input type="password" class="form-control" name="password" placeholder="New Password">
                  </div>
                </div>

                <div class="card-footer">
                  <button type="submit" name="update_user" class="btn btn-primary">Update User</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>

  <?php require '../../includes/footer.php'; ?>
</div>
<?php require '../../includes/script.php'; ?>
</body>
</html>
