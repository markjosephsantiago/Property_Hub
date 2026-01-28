<?php
require_once __DIR__ . '/../../../includes/conn.php';
require_once __DIR__ . '/../../../classes/users.php';

$user = new User($conn);

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
    $id = (int) $_GET['id'];

    if ($user->deleteUser($id)) {
        header("Location: ../list.users.php?status=success&msg=User deleted successfully");
        exit;
    } else {
        header("Location: ../list.users.php?status=error&msg=Error deleting user");
        exit;
    }
} else {
    header("Location: ../list.users.php?status=error&msg=Invalid request");
    exit;
}
