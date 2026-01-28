<?php
session_start();
include('../../../includes/conn.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit();
}

$user_id = intval($_SESSION['user_id']);
$current_password = trim($_POST['current_password'] ?? '');
$new_password = trim($_POST['new_password'] ?? '');
$confirm_password = trim($_POST['confirm_password'] ?? '');

// Basic validation
if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
    $_SESSION['error'] = "All fields are required.";
    header("Location: ../settings.php");
    exit();
}

if ($new_password !== $confirm_password) {
    $_SESSION['error'] = "New passwords do not match.";
    header("Location: ../settings.php");
    exit();
}

// Kunin ang kasalukuyang password hash ng user
$sql = "SELECT password FROM tbl_user WHERE user_id = '$user_id' LIMIT 1";
$result = mysqli_query($conn, $sql);

if ($result && $row = mysqli_fetch_assoc($result)) {
    $hashed_password = $row['password'];

    // Check kung tama ang current password
    if (password_verify($current_password, $hashed_password)) {
        
        // Hash new password
        $new_hashed = password_hash($new_password, PASSWORD_DEFAULT);

        // Update sa database
        $update_sql = "UPDATE tbl_user SET password = '$new_hashed' WHERE user_id = '$user_id'";
        if (mysqli_query($conn, $update_sql)) {
            $_SESSION['success'] = "Password updated successfully.";
        } else {
            $_SESSION['error'] = "Something went wrong. Please try again.";
        }

    } else {
        $_SESSION['error'] = "Current password is incorrect.";
    }
} else {
    $_SESSION['error'] = "User not found.";
}

header("Location: ../settings.php");
exit();
?>
