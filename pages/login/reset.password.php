<?php
session_start();
require '../../includes/conn.php';

if (!isset($_GET['token'])) {
    $_SESSION['error'] = "Invalid password reset link.";
    header("location: ../forgot.password.php");
    exit();
}

$token = mysqli_real_escape_string($conn, $_GET['token']);

$query = "SELECT * FROM tbl_user WHERE reset_token = '$token' AND reset_expiry > NOW()";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) == 0) {
    $_SESSION['error'] = "Invalid or expired password reset link.";
    header("location: ../forgot.password.php");
    exit();
}

$user = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Reset Password | Franciscan Hoteliers</title>
  <link rel="stylesheet" href="../../dist/css/adminlte.min.css">
</head>
<body class="hold-transition login-page">

<div class="login-box">
  <div class="login-logo">
    <b>Franciscan Hoteliers</b> Password Reset
  </div>
  <div class="card">
    <div class="card-body login-card-body">
      <p class="login-box-msg">Enter your new password</p>

      <form action="usersData/ctrl.reset.password.php" method="POST">
        <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">

        <div class="input-group mb-3">
          <input type="password" class="form-control" name="new_password" placeholder="New Password" required>
          <div class="input-group-append">
            <div class="input-group-text"><span class="fas fa-lock"></span></div>
          </div>
        </div>

        <div class="input-group mb-3">
          <input type="password" class="form-control" name="confirm_password" placeholder="Confirm Password" required>
          <div class="input-group-append">
            <div class="input-group-text"><span class="fas fa-lock"></span></div>
          </div>
        </div>

        <div class="row">
          <div class="col-12">
            <button type="submit" class="btn btn-primary btn-block">Reset Password</button>
          </div>
        </div>
      </form>

      <p class="mt-3 mb-1">
        <a href="../login.php">Back to login</a>
      </p>
    </div>
  </div>
</div>

</body>
</html>
