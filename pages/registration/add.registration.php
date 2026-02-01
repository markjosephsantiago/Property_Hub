<?php
session_start();
require '../../includes/conn.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Property Hub | Registration</title>

  <?php require '../../includes/link.php'; ?>
</head>
<body class="hold-transition register-page">
<div class="register-box">
  <div class="card card-outline card-primary">
    <div class="card-header text-center">
      <a href="../../index.php" class="h1"><b>Property</b>Hub</a>
    </div>
    <div class="card-body">
      <p class="login-box-msg">Register a new membership</p>

      <form method="POST" action="usersData/ctrl.add.registration.php">
        <div class="row">
            <div class="col-md-4">
                <div class="input-group mb-3">
                  <input type="text" class="form-control" name="firstName" placeholder="First Name" required>
                  <div class="input-group-append">
                    <div class="input-group-text"><span class="fas fa-user"></span></div>
                  </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="input-group mb-3">
                  <input type="text" class="form-control" name="middleName" placeholder="Middle Name">
                   <div class="input-group-append">
                    <div class="input-group-text"><span class="fas fa-user"></span></div>
                  </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="input-group mb-3">
                  <input type="text" class="form-control" name="lastName" placeholder="Last Name" required>
                   <div class="input-group-append">
                    <div class="input-group-text"><span class="fas fa-user"></span></div>
                  </div>
                </div>
            </div>
        </div>

        <div class="input-group mb-3">
          <input type="text" class="form-control" name="contact" placeholder="Contact Number" required>
          <div class="input-group-append">
            <div class="input-group-text"><span class="fas fa-phone"></span></div>
          </div>
        </div>

        <div class="input-group mb-3">
          <input type="email" class="form-control" name="email" placeholder="Email" required>
          <div class="input-group-append">
            <div class="input-group-text"><span class="fas fa-envelope"></span></div>
          </div>
        </div>

        <div class="input-group mb-3">
          <input type="text" class="form-control" name="username" placeholder="Username" required autocomplete="off">
          <div class="input-group-append">
            <div class="input-group-text"><span class="fas fa-user-tag"></span></div>
          </div>
        </div>

        <div class="input-group mb-3">
          <input type="password" class="form-control" name="password" placeholder="Password" required autocomplete="off">
          <div class="input-group-append">
            <div class="input-group-text"><span class="fas fa-lock"></span></div>
          </div>
        </div>

        <input type="hidden" name="role" value="3">

        <div class="row">
          <div class="col-8">
            <!-- Checkbox for Terms could go here -->
          </div>
          <div class="col-4">
            <button type="submit" class="btn btn-primary btn-block">Register</button>
          </div>
        </div>
      </form>

      <a href="../login/login.php" class="text-center">I already have a membership</a>
    </div>
    <!-- /.form-box -->
  </div><!-- /.card -->
</div>
<!-- /.register-box -->

<?php require '../../includes/script.php'; ?>
</body>
</html>
