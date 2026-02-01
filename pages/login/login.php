<?php
require '../../includes/conn.php';
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Franciscan Hoteliers | Log in</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet"
    href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="../../plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../../dist/css/adminlte.css">
  <!-- Bootstrap 4 -->
  <link rel="stylesheet" href="../../plugins/bootstrap/css/bootstrap.min.css">
  <!-- Toastr -->
  <link rel="stylesheet" href="../../plugins/toastr/toastr.min.css">
  <style>
    .login-page {
      background-image: url('../../dist/img/photo1.png');
      background-size: cover;
    }

    .card {
      background-image: url("../../dist/img/white.jpg");
    }
  </style>
</head>

<body class="hold-transition login-page">
  
  <div class="login-box">
    <!--Nav bar-->
  <?php include '../../includes/header.php'; ?>
    <!-- /.login-logo -->
    <div class="card card-outline card-danger">
      <div class="card-header text-center">
        <div class="sfac-logo">
          <img src="../../dist/img/FHLoginIcon.png" alt="ph1" style="width:auto;max-width:250px;height:auto;">
      <div class="card-body">
        <p class="login-box-msg">Sign in to your account.</p>
        <form action="usersData/ctrl.login.php" method="POST">
          <div class="input-group mb-3">
            <input type="text" id="username" class="form-control" name="username" placeholder="Username" required>
            <p class="error username-error"></p>
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-envelope"></span>
              </div>
            </div>
          </div>
          <div class="input-group mb-3">
            <input type="password" id="password" class="form-control" name="password" placeholder="Password" required>
            <p class="error password-error"></p>
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-lock"></span>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col"></div>
            <div class="col-md-auto">

              <button type="submit" name="signin" class="btn btn-danger">Sign In</button>
              <p class="success-message"></p>
            </div>
          </div>
          <p class="mb-1">
            <a href="forgot.password.php">I forgot my password</a>
          </p>
        </form>
      </div>
    </div>
    
  </div>
  <script src="../../plugins/jquery/jquery.min.js"></script>
  <script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../../plugins/toastr/toastr.min.js"></script>
  <script>
    <?php
    if (isset($_SESSION['password_success'])) {
    ?>
      $(function() {
        toastr.success('Password successfully changed!', 'Success')
      });
    <?php
    } elseif (isset($_SESSION['error'])) {
    ?>
      $(function() {
        toastr.error('<?php echo $_SESSION['error']?>!', 'Error')
    });
    <?php
    }

    unset($_SESSION['password_success']);
    unset($_SESSION['error']);
    ?>
  </script>
  
</body>

</html>
