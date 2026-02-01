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
    .content-wrapper { background-color: #f8f9fa !important; }
    .card-modern {
      border: none;
      border-top: 4px solid #dc143c;
      box-shadow: 0 4px 12px rgba(0,0,0,0.08);
      border-radius: 8px;
    }
    .card-modern .card-header {
      background-color: #fff;
      color: #333;
      border-bottom: 1px solid #eee;
    }
    .card-modern .card-title { font-weight: 700; color: #dc143c; }
    .form-group label { color: #555; font-weight: 600; font-size: 0.9rem; }
    .form-group label i { margin-right: 8px; color: #dc143c; width: 20px; text-align: center; }
    .btn-crimson { background-color: #dc143c; border-color: #dc143c; color: #fff; font-weight: 600; padding: 10px 30px; }
    .btn-crimson:hover { background-color: #b2112f; border-color: #b2112f; color: #fff; }
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
              <h1 class="m-0" style="font-weight: 700; color: #333;">Add User</h1>
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
          <div class="col-md-9">
            <div class="card card-modern">
              <div class="card-header">
                <h3 class="card-title">
                  <i class="fas fa-user-plus mr-2"></i> New User Registration
                </h3>
              </div>
              
              <form class="form" enctype="multipart/form-data" method="POST" action="usersData/ctrl.add.users.php">
                <div class="card-body p-4">
                  <div class="row">
                    <div class="form-group col-md-4">
                      <label for="firstName"><i class="fas fa-id-card"></i> First Name</label>
                      <input type="text" class="form-control" id="firstName" name="firstName" placeholder="Enter first name" required>
                    </div>
                    <div class="form-group col-md-4">
                      <label for="middleName"><i class="fas fa-id-card"></i> Middle Name</label>
                      <input type="text" class="form-control" id="middleName" name="middleName" placeholder="Enter middle name">
                    </div>
                    <div class="form-group col-md-4">
                      <label for="lastName"><i class="fas fa-id-card"></i> Last Name</label>
                      <input type="text" class="form-control" id="lastName" name="lastName" placeholder="Enter last name" required>
                    </div>
                  </div>

                  <hr class="my-4">

                  <div class="row">
                    <div class="form-group col-md-6">
                      <label for="role"><i class="fas fa-user-tag"></i> Role Access</label>
                      <select required class="form-control select2" id="role" name="role">
                        <option value="" disabled selected>Select Access Level</option>
                        <?php
                        $select_role = mysqli_query($conn, "SELECT * FROM tbl_roles");
                        while ($row = mysqli_fetch_array($select_role)) {
                        ?>
                          <option value="<?php echo $row['role_id'] ?>"><?php echo $row['role'] ?></option>
                        <?php } ?>
                      </select>
                    </div>
                    <div class="form-group col-md-6">
                      <label for="email"><i class="fas fa-envelope"></i> Email Address</label>
                      <input type="email" class="form-control" id="email" name="email" placeholder="email@example.com" required>
                    </div>
                  </div>

                  <div class="row">
                    <div class="form-group col-md-6">
                      <label for="contact"><i class="fas fa-phone"></i> Contact Number</label>
                      <input type="text" class="form-control" id="contact" name="contact" placeholder="09XX-XXX-XXXX" required>
                    </div>
                    <div class="form-group col-md-6">
                      <label for="username"><i class="fas fa-user-circle"></i> Username</label>
                      <input type="text" class="form-control" id="username" name="username" placeholder="Choose a username" required autocomplete="off">
                    </div>
                  </div>

                  <div class="row">
                    <div class="form-group col-md-6">
                      <label for="password"><i class="fas fa-lock"></i> Password</label>
                      <input type="password" class="form-control" id="password" name="password" placeholder="••••••••" required autocomplete="off">
                      <small class="text-muted">Minimum 8 characters with numbers and letters.</small>
                    </div>
                  </div>
                </div>

                <div class="card-footer bg-white border-top-0 p-4">
                  <button type="submit" name="submit" class="btn btn-crimson">
                    <i class="fas fa-save mr-2"></i> Register User Account
                  </button>
                  <a href="../dashboard/index.php" class="btn btn-link text-muted ml-2">Cancel</a>
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
