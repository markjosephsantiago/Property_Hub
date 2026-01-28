<?php
require '../../../includes/conn.php';
require '../../../classes/users.php';
require '../../../classes/employee.php';

if (isset($_POST['update'])) {
    $user = new User($conn);
    $employee = new Employee($conn);

    $user_id = $_POST['user_id'] ?? null;
    $employee_id = $_POST['employee_id'] ?? null;

    if (!$user_id || !$employee_id) {
        echo "<script>alert('Invalid request. Missing IDs.'); window.location.href='../list.employee.php';</script>";
        exit;
    }

    $userData = [
        'firstName'    => $_POST['firstName'],
        'middleName'   => $_POST['middleName'],
        'lastName'     => $_POST['lastName'],
        'email'        => $_POST['email'],
        'contact'      => $_POST['contact'],
        'username'     => $_POST['username'],
        'role'         => $_POST['role'] ?? 2,
        'is_verified'  => $_POST['is_verified'] ?? 1,
    ];

    if (!empty($_POST['password'])) {
        $userData['password'] = $_POST['password'];
    }

    $employee->employee_id       = $employee_id;
    $employee->user_id           = $user_id;
    $employee->position          = $_POST['position'];
    $employee->department        = $_POST['department'];
    $employee->employment_status = $_POST['employment_status'];
    $employee->date_hired        = $_POST['date_hired'];
    $employee->date_resigned     = !empty($_POST['date_resigned']) ? $_POST['date_resigned'] : null;
    $employee->salary            = $_POST['salary'];
    $employee->is_active         = $_POST['is_active'] ?? 1;

    $updateUser = $user->updateUser($user_id, $userData);
    $updateEmployee = $employee->update();
    if ($updateUser && $updateEmployee) {
        header("Location: ../list.employee.php?status=success&msg=Employee updated successfully!");
    exit();
    } else {
        header("Location: ../list.employee.php?status=error&msg=Failed to update employee!");
    exit();
    }
} else {
    header("Location: ../list.employee.php");
    exit();
}

?>