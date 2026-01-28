<?php
session_start();
include("../../includes/conn.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login/login.php");
    exit;
}

$user_id = intval($_SESSION['user_id']);
$role = strtolower($_SESSION['role']);

if ($role === 'admin') {
    $sql = "SELECT * FROM tbl_messages ORDER BY created_at DESC";
} else {
    $sql = "SELECT * FROM tbl_messages WHERE user_id = $user_id ORDER BY created_at DESC";
}

$res = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Messages</title>
    <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="../../dist/css/adminlte.min.css">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
    <div class="content-wrapper">
        <section class="content p-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <?php echo ucfirst($role); ?> Messages
                    </h3>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>From</th>
                                <th>Email</th>
                                <th>Subject</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if ($res && mysqli_num_rows($res) > 0): ?>
                            <?php while ($msg = mysqli_fetch_assoc($res)): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($msg['name'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($msg['email'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($msg['subject'] ?? 'No Subject'); ?></td>
                                    <td><?php echo date("M d, Y H:i", strtotime($msg['created_at'])); ?></td>
                                    <td>
                                        <?php if ($msg['is_read'] == 0): ?>
                                            <span class="badge badge-danger">Unread</span>
                                        <?php else: ?>
                                            <span class="badge badge-success">Read</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="view.message.php?id=<?php echo $msg['message_id']; ?>" class="btn btn-sm btn-primary">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="6" class="text-center">No messages found</td></tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    <?php require '../../includes/back.button.php'; ?>
                </div>
            </div>
        </section>
    </div>
</div>
</body>
</html>
