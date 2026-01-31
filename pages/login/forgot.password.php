
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Franciscan Hoteliers | Forgot Password</title>

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="../../plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../../dist/css/adminlte.min.css">
  <!-- Toastr -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

  <style>
    .login-page {
      background-image: url('../../dist/img/bg.jpg'); /* change image if needed */
      background-size: cover;
      background-position: center;
    }
    .card {
      background: rgba(255, 255, 255, 0.95);
      border-radius: 15px;
      box-shadow: 0 0 10px rgba(0,0,0,0.2);
    }
    .property-logo {
      text-align: center;
      margin-bottom: 10px;
    }
    .property-logo img {
      width: 80px;
      height: 80px;
    }
  </style>
</head>

<body class="hold-transition login-page">
  <!--Nav bar-->
  <?php include '../../includes/header.php'; ?>

<div class="login-box">
  <div class="card card-outline card-primary">
    <div class="card-header text-center">
      <div class="property-logo">
        <img src="../../dist/img/FHLoginIcon.png" alt="Franciscan Hoteliers Logo" style="max-width: 200px; height: auto;">
      </div>
      <h3><b>Franciscan Hoteliers</b></h3>
      <p>Forgot Your Password?</p>
    </div>

    <div class="card-body">
      <p class="login-box-msg">Enter your registered email address to reset your password.</p>

      <form action="usersData/ctrl.forgot.password.php" method="post">
        <div class="input-group mb-3">
          <input type="email" name="email" class="form-control" placeholder="Email Address" required>
          <div class="input-group-append">
            <div class="input-group-text"><span class="fas fa-envelope"></span></div>
          </div>
        </div>

        <div class="row">
          <div class="col-12">
            <button type="submit" name="forgotpassword" class="btn btn-primary btn-block">
              Request Password Reset
            </button>
          </div>
        </div>
        <?php
           if (isset($_SESSION['success'])) {
                echo "<script>alert('{$_SESSION['success']}');</script>";
                unset($_SESSION['success']);
            }

          if (isset($_SESSION['error'])) {
              echo "<script>alert('{$_SESSION['error']}');</script>";
              unset($_SESSION['error']);
          }
        ?>

      </form>

      <p class="mt-3 mb-1 text-center">
        <a href="../login/login.php">Back to Login</a>
      </p>
    </div>
  </div>
</div>

<!-- Scripts -->
<script src="../../plugins/jquery/jquery.min.js"></script>
<script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../../dist/js/adminlte.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<?php
if (isset($_GET['email_not_found']) && $_GET['email_not_found'] == 'true') {
    echo "<script>
      document.addEventListener('DOMContentLoaded', function() {
        toastr.options = {
          closeButton: true,
          progressBar: true,
          positionClass: 'toast-top-right'
        };
        toastr.error('Email does not exist in our system.');
      });
    </script>";
}
if (isset($_GET['reset_success']) && $_GET['reset_success'] == 'true') {
    echo "<script>
      document.addEventListener('DOMContentLoaded', function() {
        toastr.options = {
          closeButton: true,
          progressBar: true,
          positionClass: 'toast-top-right'
        };
        toastr.success('Password reset link has been sent to your email.');
      });
    </script>";
}
?>
</body>
</html>
