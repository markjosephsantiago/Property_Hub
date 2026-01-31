<?php
session_start();
require '../../includes/conn.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Franciscan Reservation | Users List</title>
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

        <section class="content">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Users List and Info</h3>
                </div>

                <div class="card-body">
                    <form method="GET">
                        <div class="row justify-content-center">
                            <div class="form-group col-4">
                                <input type="text" class="form-control" name="search" placeholder="Search name or role...">
                            </div>
                            <div class="form-group-append">
                                <button class="btn btn-primary">Search</button>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="card-body">
                    <table class="table table-bordered" id="example1">
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
                        $search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';

                        $query = "SELECT tbl_user.*, tbl_roles.role, 
                                  CONCAT(tbl_user.lastname, ', ', tbl_user.firstname, ' ', tbl_user.middlename) AS fullname 
                                  FROM tbl_user
                                  LEFT JOIN tbl_roles ON tbl_roles.role_id = tbl_user.role_id";

                        if (!empty($search)) {
                            $query .= " WHERE (
                                tbl_user.lastname LIKE '%$search%' OR 
                                tbl_user.firstname LIKE '%$search%' OR 
                                tbl_user.middlename LIKE '%$search%' OR 
                                tbl_roles.role LIKE '%$search%')";
                        }

                        $query .= " ORDER BY tbl_user.lastname";
                        $result = mysqli_query($conn, $query);

                        while ($row = mysqli_fetch_array($result)) {
                            ?>
                            <tr>
                                <td><?php echo $row['fullname']; ?></td>
                                <td><?php echo $row['role']; ?></td>
                                <td><?php echo $row['email']; ?></td>
                                <td><?php echo $row['contact']; ?></td>
                                <td>
                                    <a href="../users/edit.users.php?user_id=<?php echo $row['user_id']; ?>" class="btn btn-info my-1">Update</a>

                                    <?php if ($row['role'] == 'Guest') { ?>
                                        <a href="../guest/add.guest.info.php?user_id=<?php echo $row['user_id']; ?>" class="btn btn-info my-1">Guest Info</a>
                                        <button type="button" class="btn btn-primary my-1" data-toggle="modal"
                                            data-target="#confirmModal<?php echo $row['user_id']; ?>">Send Email</button>
                                    <?php } ?>

                                    <?php if ($row['role'] == 'Employee') { ?>
                                        <a href="../employee/add.employee.info.php?user_id=<?php echo $row['user_id']; ?>" class="btn btn-info my-1">Employee Info</a>
                                    <?php } ?>

                                    <button type="button" class="btn btn-danger my-1" data-toggle="modal"
                                        data-target="#deleteModal<?php echo $row['user_id']; ?>">Delete</button>
                                </td>
                            </tr>

                            <!-- Delete Modal -->
                            <div class="modal fade" id="deleteModal<?php echo $row['user_id']; ?>" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Confirm Delete</h5>
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        </div>
                                        <div class="modal-body">
                                            Are you sure you want to delete <strong><?php echo $row['fullname']; ?></strong>'s account?
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                            <a href="usersData/ctrl.delete.users.php?user_id=<?php echo $row['user_id']; ?>" class="btn btn-danger">Delete</a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Email Modal -->
                            <div class="modal fade" id="confirmModal<?php echo $row['user_id']; ?>" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Send Email</h5>
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        </div>
                                        <div class="modal-body">
                                            Send email to <strong><?php echo $row['email']; ?></strong>?
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                            <a href="../email/send.email.php?user_id=<?php echo $row['user_id']; ?>" class="btn btn-info">Send</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        <?php require '../../includes/footer.php'; ?>
    </div>

    <aside class="control-sidebar control-sidebar-dark"></aside>
</div>

<?php require '../../includes/script.php'; ?>
</body>
</html>
