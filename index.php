<?php
session_start();
if (isset($_SESSION['role'])) {
    header("location: pages/dashboard/index.php");
} else {
    header("location: home.php");
}

?>

