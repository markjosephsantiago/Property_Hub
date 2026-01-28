<?php
require_once __DIR__ . '/../../../includes/conn.php';
require_once __DIR__ . '/../../../classes/users.php';
require_once __DIR__ . '/../../../classes/employee.php';

if (isset($_POST['submit'])) {
    $conn->begin_transaction();

    try {
        $user = new User($conn);

        $userData = [
            'firstName'   => $_POST['firstName'],
            'middleName'  => $_POST['middleName'],
            'lastName'    => $_POST['lastName'],
            'contact'     => $_POST['contact'],
            'email'       => $_POST['email'],
            'username'    => $_POST['username'],
            'password'    => $_POST['password'],
            'role'        => 2,
            'is_verified' => 1
        ];

        if (!$user->addUser($userData)) {
            throw new Exception("User insert failed");
        }

        $newUserId = $conn->insert_id;

        $employee = new Employee($conn);
        $employee->user_id            = $newUserId;
        $employee->position           = $_POST['position'];
        $employee->department         = $_POST['department'];
        $employee->employment_status  = $_POST['employmentStatus'];
        $employee->date_hired         = $_POST['dateHired'];
        $employee->date_resigned      = null;
        $employee->salary             = $_POST['salary'];
        $employee->is_active          = 1;

        if (!$employee->create()) {
            throw new Exception("Employee insert failed");
        }

        $conn->commit();
        header("Location: ../list.employee.php?status=success&msg=Employee added successfully!");
        exit();

    } catch (Exception $e) {
        $conn->rollback();
        header("Location: ../list.employee.php?status=error&msg=Error adding employee! ");
        exit();
    }
}
?>

