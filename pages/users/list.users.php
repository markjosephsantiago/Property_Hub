<?php
session_start();
require '../../includes/conn.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PMS | Users List</title>

    <?php require '../../includes/link.php'; ?>
</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <!-- Navbar -->
        <?php require '../../includes/navbar.php'; ?>
        <?php require '../../includes/sidebar.php'; ?>

        <div class="content-wrapper">
            <!-- Content Header -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Users List</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="../dashboard/index.php">Home</a></li>
                                <li class="breadcrumb-item active">Users List</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Main content -->
            <section class="content">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Users List</h3>
                    </div>

                    <div class="card-body">
                        <form method="GET">
                            <div class="row justify-content-center">
                                <div class="form-group col-4">
                                    <input type="text" class="form-control" name="search"
                                        placeholder="Search first name, last name, ...">
                                </div>
                                <div class="form-group-append">
                                    <span class="form-group-text"><button class="btn btn-primary">Search</button></span>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="card-body">
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

                        <table id="example1" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Fullname</th>
                                    <th>Role</th>
                                    <th>Email</th>
                                    <th>Contact Number</th>
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
                                        u.user_id,
                                        u.firstname,
                                        u.middlename,
                                        u.lastname,
                                        u.email,
                                        u.contact,
                                        r.role,
                                        CONCAT(u.lastname, ', ', u.firstname, ' ', u.middlename) AS fullname
                                      FROM tbl_user u
                                      LEFT JOIN tbl_roles r ON r.role_id = u.role_id";

                            if (!empty($search)) {
                                $query .= " WHERE (
                                                u.lastname LIKE '%$search%' OR 
                                                u.firstname LIKE '%$search%' OR 
                                                u.middlename LIKE '%$search%' OR 
                                                r.role LIKE '%$search%'
                                            )";
                            }

                            $query .= " ORDER BY u.lastname ASC";

                            $info = mysqli_query($conn, $query);

                            if ($info && mysqli_num_rows($info) > 0) {
                                while ($row = mysqli_fetch_assoc($info)) {
                            ?>
                                <tr>
                                    <td><?= htmlspecialchars($row['fullname'] ?? '') ?></td>
                                    <td><?= htmlspecialchars($row['role'] ?? '') ?></td>
                                    <td><?= htmlspecialchars($row['email'] ?? '') ?></td>
                                    <td><?= htmlspecialchars($row['contact'] ?? '') ?></td>
                                    <td>
                                        <a href="edit.users.php?user_id=<?= $row['user_id'] ?>" class="btn my-1 btn-info">Update</a>
                                        <button type="button" class="btn my-1 btn-danger" data-toggle="modal"
                                            data-target="#modal-default<?= $row['user_id'] ?>">Delete</button>
                                    </td>
                                </tr>

                                <!-- Delete Confirmation Modal -->
                                <div class="modal fade" id="modal-default<?= $row['user_id'] ?>" tabindex="-1">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Confirm Delete</h5>
                                                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                                            </div>
                                            <div class="modal-body">
                                                <p>Are you sure you want to delete <b><?= htmlspecialchars($row['fullname']) ?></b>'s account?</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                                <a href="usersData/ctrl.delete.users.php?id=<?= $row['user_id'] ?>" class="btn btn-danger">Delete</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php
                                }
                            } else {
                                echo "<tr><td colspan='5' class='text-center'>No users found.</td></tr>";
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
        </div>
        </section>
        <?php require '../../includes/footer.php'; ?>
        <aside class="control-sidebar control-sidebar-dark"></aside>
    </div>

    <?php require '../../includes/script.php'; ?>
</body>
</html>
