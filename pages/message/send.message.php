<?php
session_start();
require '../../includes/conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name'] ?? '');
    $email = mysqli_real_escape_string($conn, $_POST['email'] ?? '');
    $message = mysqli_real_escape_string($conn, $_POST['message'] ?? '');

    if (!empty($name) && !empty($email) && !empty($message)) {
        $sql = "INSERT INTO tbl_messages (name, email, message, created_at) 
                VALUES ('$name', '$email', '$message', NOW())";

        if (mysqli_query($conn, $sql)) {
            $_SESSION['success'] = "Message sent successfully!";
        } else {
            $_SESSION['error'] = "Something went wrong: " . mysqli_error($conn);
        }
    } else {
        $_SESSION['error'] = "All fields are required.";
    }

    header("Location: ../home/contact.php");
    exit();
} else {
    header("Location: ../home/contact.php");
    exit();
}
?>
