<?php
session_start();
include("../../includes/conn.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login/login.php");
    exit;
}

if (!isset($_GET['id'])) {
    die("Invalid request.");
}

$message_id = intval($_GET['id']);
$user_id = intval($_SESSION['user_id']);
$role = strtolower($_SESSION['role']);

// ✅ Kunin yung message
if ($role === 'admin') {
    // Admin can view any message
    $sql = "SELECT * FROM tbl_messages WHERE message_id = $message_id LIMIT 1";
} else {
    // Normal user can only view their own
    $sql = "SELECT * FROM tbl_messages 
            WHERE message_id = $message_id AND user_id = $user_id LIMIT 1";
}

$res = mysqli_query($conn, $sql);

if (!$res || mysqli_num_rows($res) === 0) {
    die("Message not found or access denied.");
}

$message = mysqli_fetch_assoc($res);

if ($message['is_read'] == 0) {
    $update_sql = "UPDATE tbl_messages SET is_read = 1 WHERE message_id = $message_id";
    mysqli_query($conn, $update_sql);
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>View Message</title>
    <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="../../dist/css/adminlte.min.css">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
    <!-- Main content -->
    <div class="content-wrapper">
        <section class="content p-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Message from <?php echo htmlspecialchars($message['name']); ?></h3>
                </div>
                <div class="card-body">
                    <p><strong>Date:</strong> <?php echo date("M d, Y H:i", strtotime($message['created_at'])); ?></p>
                    <hr>
                    <p><?php echo nl2br(htmlspecialchars($message['message'])); ?></p>
                </div>
                <div class="card-footer">
                    <a href="../dashboard/index.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back</a>
                </div>
            </div>
        </section>
    </div>
</div>
</body>
</html>
