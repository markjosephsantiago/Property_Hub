<?php
$unread_count = 0;

if (isset($_SESSION['user_id']) && isset($_SESSION['role'])) {
    $user_id = intval($_SESSION['user_id']); 
    $role = strtolower($_SESSION['role']);

    if ($role === 'admin') {
        $sql = "SELECT COUNT(*) AS unread_count FROM tbl_messages WHERE is_read = 0";
    } else {
        $sql = "SELECT COUNT(*) AS unread_count FROM tbl_messages WHERE user_id = '$user_id' AND is_read = 0";
    }

    $result = mysqli_query($conn, $sql);
    if ($result && $row = mysqli_fetch_assoc($result)) {
        $unread_count = (int)$row['unread_count'];
    }
}
?>

<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-dark-beige">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <!-- Left navbar links -->
  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
    </li>
    <li class="nav-item d-none d-sm-inline-block">
      <a href="../../home.php" class="nav-link">Home</a>
    </li>
    <li class="nav-item d-none d-sm-inline-block">
      <a href="../dashboard/index.php" class="nav-link">Contact</a>
    </li>
  </ul>

  <!-- Right navbar links -->
  <ul class="navbar-nav ml-auto">

    <?php if (isset($_SESSION['role']) && in_array($_SESSION['role'], ['Admin','Employee'])): ?>

<?php
$notifCount = 0;
$notifList = [];

$res = $conn->prepare("
    SELECT message, link, created_at
    FROM tbl_notifications
    WHERE user_role = ?
    AND is_read = 0
    ORDER BY created_at DESC
    LIMIT 5
");
$res->bind_param("s", $_SESSION['role']);
$res->execute();
$notifList = $res->get_result();
$notifCount = $notifList->num_rows;
?>

<li class="nav-item dropdown">
  <a class="nav-link" data-toggle="dropdown" href="#">
    <i class="far fa-bell"></i>
    <?php if ($notifCount > 0): ?>
      <span class="badge badge-danger navbar-badge"><?= $notifCount ?></span>
    <?php endif; ?>
  </a>

  <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
    <span class="dropdown-header"><?= $notifCount ?> Notifications</span>

    <?php while ($n = $notifList->fetch_assoc()): ?>
      <a href="<?= $n['link'] ?>" class="dropdown-item">
        <i class="fas fa-utensils mr-2"></i>
        <?= htmlspecialchars($n['message']) ?>
        <span class="float-right text-muted text-sm">
          <?= date("H:i", strtotime($n['created_at'])) ?>
        </span>
      </a>
      <div class="dropdown-divider"></div>
    <?php endwhile; ?>

    <a href="../notifications/mark.all.read.php"
       class="dropdown-item dropdown-footer">
       Mark all as read
    </a>
  </div>
</li>

<?php endif; ?>

    <!-- Messages Dropdown -->
    <?php
    $latest_messages = [];

    if (isset($_SESSION['user_id']) && isset($_SESSION['role'])) {
        $user_id = intval($_SESSION['user_id']); 
        $role = strtolower($_SESSION['role']);

        if ($role === 'admin') {
            $sql_latest = "SELECT message_id, name, message, is_read, created_at
                          FROM tbl_messages
                          ORDER BY created_at DESC
                          LIMIT 5";
        } else {
            $sql_latest = "SELECT message_id, name, message, is_read, created_at
                          FROM tbl_messages
                          WHERE user_id = '$user_id'
                          ORDER BY created_at DESC
                          LIMIT 5";
        }

        $res_latest = mysqli_query($conn, $sql_latest);
        if ($res_latest && mysqli_num_rows($res_latest) > 0) {
            while ($row = mysqli_fetch_assoc($res_latest)) {
                $latest_messages[] = $row;
            }
        }
    }
    ?>

    <!-- Message Notification Icon -->
    <li class="nav-item dropdown">
      <a class="nav-link" data-toggle="dropdown" href="#">
        <i class="far fa-comments"></i>
        <?php if ($unread_count > 0): ?>
          <span class="badge badge-danger navbar-badge"><?php echo $unread_count; ?></span>
        <?php endif; ?>
      </a>
      <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
        <?php if (!empty($latest_messages)): ?>
          <?php foreach ($latest_messages as $msg): ?>
            <a href="../message/view.message.php?id=<?php echo $msg['message_id']; ?>" class="dropdown-item">
              <div class="media">
                <div class="media-body">
                  <h6 class="dropdown-item-title">
                    <?php echo htmlspecialchars($msg['name']); ?>
                    <span class="float-right text-sm text-muted">
                      <?php echo date("M d, H:i", strtotime($msg['created_at'])); ?>
                    </span>
                  </h6>
                  <p class="text-sm"><?php echo htmlspecialchars(substr($msg['message'], 0, 30)); ?>...</p>
                </div>
              </div>
            </a>
            <div class="dropdown-divider"></div>
          <?php endforeach; ?>
          <a href="../message/list.message.php" class="dropdown-item dropdown-footer">See All Messages</a>
        <?php else: ?>
          <span class="dropdown-item dropdown-header">No new messages</span>
        <?php endif; ?>
      </div>
    </li>

    <!-- Vertical Ellipsis Dropdown Menu -->
    <li class="nav-item dropdown">
      <a class="nav-link" data-toggle="dropdown" href="#">
        <i class="fas fa-ellipsis-v"></i>
      </a>
      <div class="dropdown-menu dropdown-menu-right">
        <a href="../users/profile.php" class="dropdown-item">
          <i class="fas fa-user mr-2"></i> Profile
        </a>
        <div class="dropdown-divider"></div>
        <a href="../users/settings.php" class="dropdown-item">
          <i class="fas fa-cog mr-2"></i> Settings
        </a>
        <div class="dropdown-divider"></div>
        <a class="nav-link" href="#" onclick="confirmLogout(event)">
          <i class="fas fa-sign-out-alt"></i> Logout
        </a>

        <script>
        function confirmLogout(e) {
          e.preventDefault();

          Swal.fire({
            title: "Logout Confirmation",
            text: "Are you sure you want to log out?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, log me out"
          }).then((result) => {
            if (result.isConfirmed) {
              window.location.href = "../login/usersData/ctrl.logout.php";
            }
          });
        }
        </script>

      </div>
    </li>
  </ul>
</nav>
<!-- /.navbar -->
