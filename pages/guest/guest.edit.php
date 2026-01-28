<?php
include '../includes/conn.php';
include 'guest.php';

$guest = new Guest($conn);
$result = $guest->readAll();

if (isset($_GET['msg'])) {
    echo "<p style='color: green;'>" . $_GET['msg'] . "</p>";
}
?>

<a href="guest.add.php">Add Guest</a>
<table border="1" cellpadding="5">
    <tr>
        <th>ID</th><th>Name</th><th>Email</th><th>Contact</th><th>Actions</th>
    </tr>
    <?php while ($row = $result->fetch(PDO::FETCH_ASSOC)): ?>
    <tr>
        <td><?= $row['guest_id'] ?></td>
        <td><?= $row['first_name'] . " " . $row['last_name'] ?></td>
        <td><?= $row['email'] ?></td>
        <td><?= $row['contact'] ?></td>
        <td>
            <a href="guest.edit.php?id=<?= $row['guest_id'] ?>">Edit</a> | 
            <a href="guest.delete.php?id=<?= $row['guest_id'] ?>" 
               onclick="return confirm('Delete this guest?');">Delete</a>
        </td>
    </tr>
    <?php endwhile; ?>
</table>
