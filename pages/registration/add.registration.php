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

<body class="register-wrapper">
  <div class="register-card">
    <div class="register-header">
      Property Hub — Registration
    </div>

    <div class="register-body">
      <form method="POST" action="usersData/ctrl.add.registration.php">
        <div class="row">
          <div class="form-group col-md-4">
            <label for="firstName">First Name</label>
            <input type="text" class="form-control" id="firstName" name="firstName" placeholder="First Name" required>
          </div>

          <div class="form-group col-md-4">
            <label for="middleName">Middle Name</label>
            <input type="text" class="form-control" id="middleName" name="middleName" placeholder="Middle Name">
          </div>

          <div class="form-group col-md-4">
            <label for="lastName">Last Name</label>
            <input type="text" class="form-control" id="lastName" name="lastName" placeholder="Last Name" required>
          </div>
        </div>

        <div class="row">
          <div class="form-group col-md-6">
            <label for="contact">Contact Number</label>
            <input type="text" class="form-control" id="contact" name="contact" placeholder="Contact Number" required>
          </div>

          <div class="form-group col-md-6">
            <label for="email">Email Address</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="Enter email" required>
          </div>
        </div>

        <div class="row">
          <div class="form-group col-md-6">
            <label for="username">Username</label>
            <input type="text" class="form-control" id="username" name="username" placeholder="Username" required autocomplete="off">
          </div>

          <div class="form-group col-md-6">
            <label for="password">Password</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="Password" required autocomplete="off">
          </div>
        </div>

        <!-- Automatic Guest Role -->
        <input type="hidden" name="role" value="3"> <!-- assuming role_id = 3 is Guest -->

        <div class="text-center mt-3">
          <button type="submit" class="btn btn-primary px-5 py-2">Register</button>
        </div>
      </form>

      <a href="../login.php" class="login-link">I already have a membership</a>
    </div>
  </div>

  <?php require '../../includes/script.php'; ?>
</body>
</html>
