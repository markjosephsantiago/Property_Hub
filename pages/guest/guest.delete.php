<?php
session_start();
require_once __DIR__ . '/../../includes/conn.php';
require_once __DIR__ . '/../../classes/guest.php';

if (!isset($_GET['id'])) {
    header("Location: guest.list.php");
    exit();
}

$id = intval($_GET['id']);
$guest = new Guest($conn);

if ($guest->delete($id)) {
    header("Location: guest.list.php?msg=Guest deleted successfully&status=success");
} else {
    header("Location: guest.list.php?msg=Error deleting guest&status=error");
}
exit();
