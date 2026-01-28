<?php
include_once __DIR__ . '/../../includes/conn.php';
include_once __DIR__ . '/employee.php';

$employee = new Employee($conn);

$employee->user_id = 1;
$employee->position = "Technician";
$employee->department = "Maintenance";
$employee->employment_status = "Regular";
$employee->date_hired = "2025-09-17";
$employee->date_resigned = null;
$employee->salary = 20000.00;
$employee->is_active = 1;

if ($employee->create()) {
    echo "Employee added successfully!<br>";
} else {
    echo "Failed to add employee.<br>";
}

$result = $employee->readAll();
while ($row = $result->fetch_assoc()) {
    echo $row['employee_code'] . " - " . $row['position'] . " (" . $row['department'] . ")<br>";
}

?>
