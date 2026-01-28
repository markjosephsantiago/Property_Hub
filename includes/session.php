<?php
ob_start();
session_start();
if (!isset($_SESSION['role'])) {
    header("location: ../login/login.php");
}

error_reporting(E_ALL ^ E_DEPRECATED);
?>