<?php
require_once __DIR__ . '/../../../includes/conn.php';
require_once __DIR__ . '/../../../classes/users.php';

$user = new User($conn);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Optional: sanitize input
    $data = [
        'firstName' => trim($_POST['firstName']),
        'middleName' => trim($_POST['middleName']),
        'lastName'  => trim($_POST['lastName']),
        'img'       => $_FILES['img']['tmp_name'] ?? null, // kung may upload
        'contact'   => trim($_POST['contact']),
        'email'     => trim($_POST['email']),
        'username'  => trim($_POST['username']),
        'password'  => $_POST['password'],
        'role'      => $_POST['role'] ?? 3,
        'is_verified' => $_POST['is_verified'] ?? 1
    ];

    if ($user->addUser($data)) {
        header("Location: ../list.users.php?msg=User added successfully");
        exit;
    } else {
        header("Location: ../list.users.php?msg=Error adding user");
        exit;
    }
}
