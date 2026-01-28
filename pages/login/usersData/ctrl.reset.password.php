<?php
session_start();
require '../../../includes/conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = mysqli_real_escape_string($conn, $_POST['token']);
    $new_password = mysqli_real_escape_string($conn, $_POST['new_password']);
    $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm_password']);

    if ($new_password !== $confirm_password) {
        $_SESSION['error'] = "Passwords do not match.";
        header("location: ../reset.password.php?token=$token");
        exit();
    }

    $query = "SELECT * FROM tbl_user WHERE reset_token = '$token' AND reset_expiry > NOW()";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) === 0) {
        $_SESSION['error'] = "Invalid or expired token.";
        header("location: ../forgot.password.php");
        exit();
    }

    $user = mysqli_fetch_assoc($result);
    $user_id = $user['user_id'];

    $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);

    $update = "UPDATE tbl_user 
               SET password = '$hashed_password', reset_token = NULL, reset_expiry = NULL 
               WHERE user_id = '$user_id'";
    if (mysqli_query($conn, $update)) {
        $_SESSION['success'] = "Your password has been successfully reset. Please log in.";
        header("location: ../login.php");
        exit();
    } else {
        $_SESSION['error'] = "Error updating password. Please try again.";
        header("location: ../reset.password.php?token=$token");
        exit();
    }
} else {
    header("location: ../forgot.password.php");
    exit();
}
?>
