<?php
session_start();
require '../../includes/conn.php';

$role = $_SESSION['role'];

$conn->query("
    UPDATE tbl_notifications
    SET is_read = 1
    WHERE user_role = '$role'
");

header("Location: ../dashboard/index.php");
exit;
?>