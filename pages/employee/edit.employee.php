<?php
require '../../includes/conn.php';
require '../../classes/employee.php';

if (!isset($_GET['employee_id']) || empty($_GET['employee_id'])) {
    echo "<script>alert('No ID provided.'); window.location.href='list.employee.php';</script>";
    exit;
}

$employee = new Employee($conn);
$employeeData = $employee->readOne($_GET['employee_id']);

if (!$employeeData) {
    echo "<script>alert('Employee not found.'); window.location.href='list.employee.php';</script>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>PMS | Edit Employee</title>
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
            <h1>Edit Employee</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="../dashboard/index.php">Home</a></li>
              <li class="breadcrumb-item"><a href="list.employee.php">Employee List</a></li>
              <li class="breadcrumb-item active">Edit Employee</li>
            </ol>
          </div>
        </div>
      </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Update Employee Information</h3>
                </div>
                <form class="form" enctype="multipart/form-data" method="POST" action="usersData/ctrl.update.employee.php">
                    <input type="hidden" name="user_id" value="<?= htmlspecialchars($employeeData['user_id'] ?? '') ?>">
                    <input type="hidden" name="employee_id" value="<?= htmlspecialchars($employeeData['employee_id'] ?? '') ?>">
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label for="firstName">First Name</label>
                                <input type="text" class="form-control" name="firstName" value="<?= htmlspecialchars($employeeData['firstName']?? '') ?>" required>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="middleName">Middle Name</label>
                                <input type="text" class="form-control" name="middleName" value="<?= htmlspecialchars($employeeData['middleName'] ?? '') ?>">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="lastName">Last Name</label>
                                <input type="text" class="form-control" name="lastName" value="<?= htmlspecialchars($employeeData['lastName']?? '') ?>" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="email">Email address</label>
                                <input type="email" class="form-control" name="email" value="<?= htmlspecialchars($employeeData['email']?? '') ?>" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="contact">Contact Number</label>
                                <input type="text" class="form-control" name="contact" value="<?= htmlspecialchars($employeeData['contact'] ?? '') ?>" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="username">Username</label>
                                <input type="text" class="form-control" name="username" value="<?= htmlspecialchars($employeeData['username']?? '') ?>" required autocomplete="off">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="password">New Password (leave blank if unchanged)</label>
                                <input type="password" class="form-control" name="password" placeholder="Enter new password" autocomplete="off">
                            </div>
                        </div>

                        <!-- Employee Information -->
                        <hr>
                        <h5>Employee Information</h5>
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label for="position">Position</label>
                                <input type="text" class="form-control" name="position" value="<?= htmlspecialchars($employeeData['position']?? '') ?>" required>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="department">Department</label>
                                <input type="text" class="form-control" name="department" value="<?= htmlspecialchars($employeeData['department']?? '') ?>" required>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="employment_status">Employment Status</label>
                                <select class="form-control" name="employment_status" required>
                                    <?php
                                    $statuses = ['Regular', 'Probationary', 'Contractual', 'Part-time', 'Resigned'];
                                    foreach ($statuses as $status) {
                                        $selected = ($employeeData['employment_status'] == $status) ? 'selected' : '';
                                        echo "<option value='$status' $selected>$status</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="date_hired">Date Hired</label>
                                <input type="date" class="form-control" name="date_hired" value="<?= htmlspecialchars($employeeData['date_hired']?? '') ?>" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="salary">Salary</label>
                                <input type="number" step="0.01" class="form-control" name="salary" value="<?= htmlspecialchars($employeeData['salary']?? '') ?>" required>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" name="update" class="btn btn-primary">Update</button>
                        <a href="list.employee.php" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
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
