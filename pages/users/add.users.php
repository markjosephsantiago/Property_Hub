<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
require '../../includes/conn.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Franciscan Reservation | Add User</title>
  <?php require '../../includes/link.php'; ?>
  <style>
    .add-user {
      background-image: url('../../dist/img/photo2.png');
      background-size: cover;
    }
    .card {
      background-image: url("../../dist/img/white.jpg");
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
              <h1>Add User</h1>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="../dashboard/index.php">Home</a></li>
                <li class="breadcrumb-item active">Add User</li>
              </ol>
            </div>
          </div>
        </div>
      </section>

      <section class="content">
        <div class="row justify-content-center">
          <div class="col-md-8">
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">User Info</h3>
              </div>
              
              <form class="form" enctype="multipart/form-data" method="POST" action="usersData/ctrl.add.users.php">
                <div class="card-body">
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
                      <label for="role">Role</label>
                      <select required class="form-control select2" id="role" name="role">
                        <option value="" disabled selected>Select Role</option>
                        <?php
                        $select_role = mysqli_query($conn, "SELECT * FROM tbl_roles");
                        while ($row = mysqli_fetch_array($select_role)) {
                        ?>
                          <option value="<?php echo $row['role_id'] ?>"><?php echo $row['role'] ?></option>
                        <?php } ?>
                      </select>
                    </div>
                    <div class="form-group col-md-6">
                      <label for="email">Email address</label>
                      <input type="email" class="form-control" id="email" name="email" placeholder="Enter email" required>
                    </div>
                  </div>

                  <div class="row">
                    <div class="form-group col-md-6">
                      <label for="contact">Contact Number</label>
                      <input type="text" class="form-control" id="contact" name="contact" placeholder="Contact Number" required>
                    </div>
                    <div class="form-group col-md-6">
                      <label for="username">Username</label>
                      <input type="text" class="form-control" id="username" name="username" placeholder="Username" required autocomplete="off">
                    </div>
                  </div>

                  <div class="row">
                    <div class="form-group col-md-6">
                      <label for="password">Password</label>
                      <input type="password" class="form-control" id="password" name="password" placeholder="Password" required autocomplete="off">
                    </div>
                  </div>
                </div>

                <div class="card-footer">
                  <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                </div>
              </form>
            </div>
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
