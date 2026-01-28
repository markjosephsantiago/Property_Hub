<?php
include '../config/db.php';
include 'guest.php';

$guest = new Guest($conn);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $guest->first_name = $_POST['first_name'];
    $guest->last_name  = $_POST['last_name'];
    $guest->email      = $_POST['email'];
    $guest->contact    = $_POST['contact'];

    if ($guest->create()) {
        header("Location: guest.list.php?msg=Guest added successfully");
        exit();
    } else {
        echo "Error adding guest.";
    }
}
?>

<form method="POST">
    <input type="text" name="first_name" placeholder="First Name" required>
    <input type="text" name="last_name" placeholder="Last Name" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="text" name="contact" placeholder="Contact" required>
    <button type="submit">Add Guest</button>
</form>
