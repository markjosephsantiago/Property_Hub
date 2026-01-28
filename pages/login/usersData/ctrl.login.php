<?php
ob_start();
session_start(); 
require '../../../includes/conn.php'; 

if (isset($_POST['signin'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    $select_data = mysqli_query($conn, "SELECT *, CONCAT(tbl_user.lastName, ', ', tbl_user.firstName) AS fullname 
        FROM tbl_user
        LEFT JOIN tbl_roles ON tbl_user.role_id = tbl_roles.role_id 
        WHERE username = '$username'");
    $check = mysqli_num_rows($select_data);

    if ($check == 1) {
        $row = mysqli_fetch_assoc($select_data);

        if ($row['is_verified'] != 1) {
            $_SESSION['error'] = 'Please verify your email before logging in.';
            header("location: ../login.php");
            exit();
        } elseif (password_verify($password, $row['password'])) {

            $_SESSION['user_id'] = $row['user_id'];
            $_SESSION['username'] = $username;
            $_SESSION['fullname'] = $row['fullname'];
            $_SESSION['role'] = $row['role']; 

            if ($row['role'] === 'Guest') {
                header("location: ../../../home.php");
            } else {
                header("location: ../../dashboard/index.php");
            }
            exit();

        } else {
            $_SESSION['error'] = 'Wrong Password';
            header("location: ../login.php");
            exit();
        }
    } else {
        $_SESSION['error'] = 'Wrong Username';
        header("location: ../login.php");
        exit();
    }
}
?>
