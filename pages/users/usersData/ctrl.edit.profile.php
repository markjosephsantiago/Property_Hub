<?php
session_start();
include('../../../includes/conn.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = intval($_POST['user_id']);
    $firstName = mysqli_real_escape_string($conn, $_POST['firstName']);
    $middleName = mysqli_real_escape_string($conn, $_POST['middleName']);
    $lastName = mysqli_real_escape_string($conn, $_POST['lastName']);
    $contact = mysqli_real_escape_string($conn, $_POST['contact']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $age = intval($_POST['age']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);

    // Base SQL
    $sql = "
        UPDATE tbl_user
        SET 
            firstName = '$firstName',
            middleName = '$middleName',
            lastName = '$lastName',
            contact = '$contact',
            email = '$email',
            age = '$age',
            address = '$address'
    ";

    // Optional image upload
    if (!empty($_FILES['profile_image']['tmp_name']) && is_uploaded_file($_FILES['profile_image']['tmp_name'])) {
        $imageTmpPath = $_FILES['profile_image']['tmp_name'];
        $imageData = file_get_contents($imageTmpPath);
        $imageData = mysqli_real_escape_string($conn, $imageData);

        $sql .= ", img = '$imageData'";
    }

    $sql .= " WHERE user_id = '$user_id'";

    if (mysqli_query($conn, $sql)) {
        $_SESSION['success'] = "Profile updated successfully!";
        header("Location: ../profile.php");
        exit();
    } else {
        $_SESSION['error'] = "Error updating profile: " . mysqli_error($conn);
        header("Location: ../edit.profile.php");
        exit();
    }
} else {
    header("Location: ../edit.profile.php");
    exit();
}
