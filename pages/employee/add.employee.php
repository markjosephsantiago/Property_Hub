<?php
session_start();
require '../../includes/conn.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>PMS | Add Employee</title>
  <?php require '../../includes/link.php'; ?>
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

  <?php require '../../includes/navbar.php'; ?>
  <?php require '../../includes/sidebar.php'; ?>

  <div class="content-wrapper">
    <section class="content-header">
      <div class="container-fluid">
        <h1>Add Employee</h1>
      </div>
    </section>

    <section class="content">
      <div class="row">
        <div class="col-md-12">
          <div class="card card-primary">
            <div class="card-header"><h3 class="card-title">User & Employee Information</h3></div>
            <form class="form" enctype="multipart/form-data" method="POST" action="usersData/ctrl.add.employee.php">
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
                    <label for="email">Email address</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Enter email" required>
                  </div>
                  <div class="form-group col-md-6">
                    <label for="contact">Contact Number</label>
                    <input type="text" class="form-control" id="contact" name="contact" placeholder="Contact Number" required>
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

                <!-- Additional Employee Information -->
                <hr>
                <h5>Employee Information</h5>
                <div class="row">
                  <div class="form-group col-md-4">
                    <label for="position">Position</label>
                    <input type="text" class="form-control" id="position" name="position" placeholder="Position" required>
                  </div>
                  <div class="form-group col-md-4">
                    <label for="department">Department</label>
                    <input type="text" class="form-control" id="department" name="department" placeholder="Department" required>
                  </div>
                  <div class="form-group col-md-4">
                    <label for="employmentStatus">Employment Status</label>
                    <select class="form-control" id="employmentStatus" name="employmentStatus" required>
                      <option value="" disabled selected>Select Status</option>
                      <option value="Regular">Regular</option>
                      <option value="Probationary">Probationary</option>
                      <option value="Contractual">Contractual</option>
                      <option value="Part-time">Part-time</option>
                    </select>
                  </div>
                </div>

                <div class="row">
                  <div class="form-group col-md-6">
                    <label for="dateHired">Date Hired</label>
                    <input type="date" class="form-control" id="dateHired" name="dateHired" required>
                  </div>
                  <div class="form-group col-md-6">
                    <label for="salary">Salary</label>
                    <input type="number" step="0.01" class="form-control" id="salary" name="salary" placeholder="Salary" required>
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
</div>
<?php require '../../includes/script.php'; ?>
</body>
</html>
