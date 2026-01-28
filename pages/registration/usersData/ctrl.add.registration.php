<?php
session_start();
require '../../../includes/conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $firstName   = trim($_POST['firstName']);
    $middleName  = trim($_POST['middleName']);
    $lastName    = trim($_POST['lastName']);
    $contact     = trim($_POST['contact']);
    $email       = trim($_POST['email']);
    $username    = trim($_POST['username']);
    $password    = $_POST['password'];
    $role_id     = $_POST['role'] ?? 3;

    if (empty($firstName) || empty($lastName) || empty($contact) || empty($email) || empty($username) || empty($password)) {
        $_SESSION['error'] = "All required fields must be filled out.";
        header("Location: ../add.registration.php");
        exit();
    }

    $checkQuery = $conn->prepare("SELECT * FROM tbl_user WHERE email = ? OR username = ?");
    $checkQuery->bind_param("ss", $email, $username);
    $checkQuery->execute();
    $checkResult = $checkQuery->get_result();

    if ($checkResult->num_rows > 0) {
        $_SESSION['error'] = "Email or Username already exists.";
        header("Location: ../add.registration.php");
        exit();
    }

    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    $insertQuery = $conn->prepare("INSERT INTO tbl_user (firstName, middleName, lastName, contact, email, username, password, role_id, created_at)
                                   VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())");
    $insertQuery->bind_param("sssssssi", $firstName, $middleName, $lastName, $contact, $email, $username, $hashedPassword, $role_id);

    if ($insertQuery->execute()) {
        $_SESSION['success'] = "Registration successful! You can now log in.";
        header("Location: ../../../home.php");
        exit();
    } else {
        $_SESSION['error'] = "Something went wrong while saving your registration. Please try again.";
        header("Location: ../add.registration.php");
        exit();
    }

} else {

    $_SESSION['error'] = "Invalid request.";
    header("Location: ../add.registration.php");
    exit();
}
?>
