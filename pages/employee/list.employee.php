<?php
session_start();
require '../../includes/conn.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>PMS | Employee List</title>
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
              <h1>Employee List</h1>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="../dashboard/index.php">Home</a></li>
                <li class="breadcrumb-item active">Employee List</li>
              </ol>
            </div>
          </div>
        </div>
      </section>

      <section class="content">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Employees</h3>
          </div>

          <div class="card-body">
            <form method="GET">
              <div class="row justify-content-center">
                <div class="form-group col-4">
                  <input type="text" class="form-control" name="search" placeholder="Search employee code, name, department...">
                </div>
                <div class="form-group-append">
                  <button class="btn btn-primary">Search</button>
                </div>
              </div>
            </form>
          </div>

          <div class="card-body">
            <!-- Alerts -->
            <?php if (isset($_GET['msg'])): ?>
              <?php 
                $alertClass = 'info'; 
                if (isset($_GET['status']) && $_GET['status'] === 'success') {
                    $alertClass = 'success';
                } elseif (isset($_GET['status']) && $_GET['status'] === 'error') {
                    $alertClass = 'danger';
                }
              ?>
              <div class="alert alert-<?= $alertClass ?> alert-dismissible fade show" role="alert">
                <?= htmlspecialchars($_GET['msg']); ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
            <?php endif; ?>

            <table id="example1" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>Employee Code</th>
                  <th>Fullname</th>
                  <th>Email</th>
                  <th>Contact</th>
                  <th>Position</th>
                  <th>Department</th>
                  <th>Status</th>
                  <th>Date Hired</th>
                  <th>Active</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
              <?php
              $search = "";
              if (isset($_GET['search'])) {
                $search = mysqli_real_escape_string($conn, $_GET['search']);
              }

              $query = "SELECT 
                          e.employee_id,
                          e.employee_code,
                          e.position,
                          e.department,
                          e.employment_status,
                          e.date_hired,
                          e.is_active,
                          u.firstname,
                          u.middlename,
                          u.lastname,
                          u.email,
                          u.contact,
                          CONCAT(u.lastname, ', ', u.firstname, ' ', u.middlename) AS fullname
                        FROM tbl_employee e
                        LEFT JOIN tbl_user u ON e.user_id = u.user_id";

              if (!empty($search)) {
                $query .= " WHERE (
                              e.employee_code LIKE '%$search%' OR 
                              u.firstname LIKE '%$search%' OR 
                              u.lastname LIKE '%$search%' OR 
                              u.middlename LIKE '%$search%' OR 
                              e.department LIKE '%$search%' OR 
                              e.position LIKE '%$search%'
                            )";
              }

              $query .= " ORDER BY e.employee_id ASC";

              $info = mysqli_query($conn, $query);

              if ($info && mysqli_num_rows($info) > 0) {
                while ($row = mysqli_fetch_assoc($info)) {
              ?>
                <tr>
                  <td><?= !empty($row['employee_code']) ? htmlspecialchars($row['employee_code']) : '' ?></td>
                  <td><?= !empty($row['fullname']) ? htmlspecialchars($row['fullname']) : '' ?></td>
                  <td><?= !empty($row['email']) ? htmlspecialchars($row['email']) : '' ?></td>
                  <td><?= !empty($row['contact']) ? htmlspecialchars($row['contact']) : '' ?></td>
                  <td><?= !empty($row['position']) ? htmlspecialchars($row['position']) : '' ?></td>
                  <td><?= !empty($row['department']) ? htmlspecialchars($row['department']) : '' ?></td>
                  <td><?= !empty($row['employment_status']) ? htmlspecialchars($row['employment_status']) : '' ?></td>
                  <td><?= !empty($row['date_hired']) ? htmlspecialchars($row['date_hired']) : '' ?></td>
                  <td><?= isset($row['is_active']) && $row['is_active'] ? 'Active' : 'Inactive' ?></td>
                  <td>
                    <a href="edit.employee.php?employee_id=<?= urlencode($row['employee_id'] ?? '') ?>" 
                      class="btn btn-info btn-sm">Update</a>
                    <button type="button" 
                            class="btn btn-danger btn-sm" 
                            data-toggle="modal"
                            data-target="#modal-delete<?= htmlspecialchars($row['employee_id'] ?? '') ?>">
                      Delete
                    </button>
                  </td>
                </tr>
                <!-- Delete Modal -->
                <div class="modal fade" id="modal-delete<?= $row['employee_id'] ?>" tabindex="-1">
                  <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title">Confirm Delete</h5>
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                      </div>
                      <div class="modal-body">
                        <p>Are you sure you want to delete <b><?= !empty($row['fullname']) ? htmlspecialchars($row['fullname']) : '' ?></b>?</p>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        <a href="usersData/ctrl.delete.employee.php?id=<?= $row['employee_id'] ?>" class="btn btn-danger">Delete</a>
                      </div>
                    </div>
                  </div>
                </div>
              <?php
                }
              } else {
                echo "<tr><td colspan='10' class='text-center'>No employees found.</td></tr>";
              }
              ?>
              </tbody>
            </table>
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
