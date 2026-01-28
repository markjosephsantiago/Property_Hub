<?php
session_start();
require '../../../includes/conn.php';

if (isset($_SESSION['success'])) {
    echo "<script>
        alert('{$_SESSION['success']}');
    </script>";
    unset($_SESSION['success']);
}

if (isset($_SESSION['error'])) {
    echo "<script>
        alert('{$_SESSION['error']}');
    </script>";
    unset($_SESSION['error']);
}

if (isset($_POST['email'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);

    $query = "SELECT * FROM tbl_user WHERE email = '$email'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);

        $token = bin2hex(random_bytes(32));
        $expiry = date("Y-m-d H:i:s", strtotime("+30 minutes"));

        $update = "UPDATE tbl_user SET reset_token = '$token', reset_expiry = '$expiry' WHERE email = '$email'";
        mysqli_query($conn, $update);

        $_SESSION['success'] = "Password reset link generated. Token: $token (Valid for 30 mins)";
        header("location: ../login.php");
        exit();
    } else {
        $_SESSION['error'] = "Email address not found.";
        header("location: ../forgot.password.php");
        exit();
    }
} else {
    $_SESSION['error'] = "Please enter your email address.";
    header("location: ../forgot.password.php");
    exit();
}
?>
