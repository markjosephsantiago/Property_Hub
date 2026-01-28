<?php
session_start();
include('../../includes/conn.php');

// Redirect kung walang session (not logged in)
if (!isset($_SESSION['user_id'])) {
  header("Location: ../login.php");
  exit();
}

// Kunin ang user data
$user_id = intval($_SESSION['user_id']);
$sql = "
  SELECT u.*, r.role
  FROM tbl_user AS u
  LEFT JOIN tbl_roles AS r ON u.role_id = r.role_id
  WHERE u.user_id = '$user_id'
  LIMIT 1
";

$result = mysqli_query($conn, $sql);
$user = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>My Profile</title>
  <?php include('../../includes/link.php'); ?>
</head>
<body class="hold-transition sidebar-mini layout-fixed">

<div class="wrapper">
  <?php include('../../includes/navbar.php'); ?>

  <div class="content-wrapper p-4">
    <section class="content-header">
      <h1>My Profile</h1>
    </section>

    <section class="content">
        <div class="card profile-card">
        <div class="profile-header text-center">
        <?php if (!empty($user['img'])): ?>
            <img src="data:image/jpeg;base64,<?php echo base64_encode($user['img']); ?>" 
                alt="Profile Picture" 
                class="profile-pic mb-2">
        <?php else: ?>
            <img src="../../dist/img/default-user.png" 
                alt="Default Picture" 
                class="profile-pic mb-2">
        <?php endif; ?>

        <h3>
            <?php echo htmlspecialchars(trim(($user['firstName'] ?? '') . ' ' . ($user['lastName'] ?? ''))); ?>
        </h3>
        <p><?php echo htmlspecialchars($user['email'] ?? ''); ?></p>
        </div>
        <div class="profile-body">
            <p><strong>Age:</strong> <?php echo htmlspecialchars($user['age'] ?? 'Not specified'); ?></p>
            <p><strong>Address:</strong> <?php echo htmlspecialchars($user['address'] ?? 'Not specified'); ?></p>
            <p><strong>Contact:</strong> <?php echo htmlspecialchars($user['contact'] ?? 'N/A'); ?></p>
            <p><strong>Username:</strong> <?php echo htmlspecialchars($user['username'] ?? 'N/A'); ?></p>
            <p><strong>Role:</strong> <?php echo htmlspecialchars($user['role'] ?? 'N/A'); ?></p>
            <p><strong>Date Joined:</strong> <?php echo isset($user['created_at']) ? date("F d, Y", strtotime($user['created_at'])) : 'N/A'; ?></p>
            <a href="edit.profile.php" class="btn btn-primary btn-edit-profile">
            <i class="fas fa-edit mr-1"></i> Edit Profile
            </a>
        </div>
        </div>

    </section>
  </div>
</div>

</body>
</html>
