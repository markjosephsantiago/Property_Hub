<?php
require_once __DIR__ . '/../../../includes/conn.php';
require_once __DIR__ . '/../../../classes/employee.php';

$employee = new Employee($conn);

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
    $id = (int) $_GET['id'];

    if ($employee->delete($id)) {
        header("Location: ../list.employee.php?status=success&msg=Employee deleted successfully");
        exit;
    } else {
        header("Location: ../list.employee.php?status=error&msg=Error deleting employee");
        exit;
    }
} else {
    header("Location: ../list.employee.php?status=error&msg=Invalid request");
    exit;
}
