<?php
session_start();
include('../../includes/conn.php');

if (!isset($_SESSION['user_id'])) {
  header("Location: ../../login.php");
  exit();
}

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
  <title>Edit Profile</title>
  <?php include('../../includes/link.php'); ?>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">
  <?php include('../../includes/navbar.php'); ?>

  <div class="content-wrapper p-4">
    <section class="content-header">
      <h1>Edit Profile</h1>
    </section>

    <section class="content">
      <div class="edit-profile-card">
        <?php if (isset($_SESSION['error'])): ?>
          <div class="alert alert-danger"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php elseif (isset($_SESSION['success'])): ?>
          <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
        <?php endif; ?>

        <form action="usersData/ctrl.edit.profile.php" method="POST" enctype="multipart/form-data">
          <input type="hidden" name="user_id" value="<?php echo $user['user_id']; ?>">

          <div class="text-center mb-3">
            <?php if (!empty($user['img'])): ?>
              <img src="data:image/jpeg;base64,<?php echo base64_encode($user['img']); ?>" 
                   alt="Profile Picture" class="profile-pic">
            <?php else: ?>
              <img src="../../dist/img/default-user.png" alt="Default Picture" class="profile-pic">
            <?php endif; ?>
          </div>

          <div class="form-group text-center">
            <label>Change Profile Picture</label>
            <input type="file" name="profile_image" accept="image/*" class="form-control-file">
          </div>

          <div class="form-row mt-4">
            <div class="form-group col-md-4">
              <label>First Name</label>
              <input type="text" name="firstName" class="form-control" 
                     value="<?php echo htmlspecialchars($user['firstName'] ?? ''); ?>" required>
            </div>
            <div class="form-group col-md-4">
              <label>Middle Name</label>
              <input type="text" name="middleName" class="form-control" 
                     value="<?php echo htmlspecialchars($user['middleName'] ?? ''); ?>">
            </div>
            <div class="form-group col-md-4">
              <label>Last Name</label>
              <input type="text" name="lastName" class="form-control" 
                     value="<?php echo htmlspecialchars($user['lastName'] ?? ''); ?>" required>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group col-md-6">
              <label>Email</label>
              <input type="email" name="email" class="form-control" 
                     value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" required>
            </div>
            <div class="form-group col-md-6">
              <label>Contact</label>
              <input type="text" name="contact" class="form-control" 
                     value="<?php echo htmlspecialchars($user['contact'] ?? ''); ?>">
            </div>
          </div>

          <div class="form-row">
            <div class="form-group col-md-4">
              <label>Age</label>
              <input type="number" name="age" class="form-control" 
                     value="<?php echo htmlspecialchars($user['age'] ?? ''); ?>" min="1">
            </div>
            <div class="form-group col-md-8">
              <label>Address</label>
              <input type="text" name="address" class="form-control" 
                     value="<?php echo htmlspecialchars($user['address'] ?? ''); ?>">
            </div>
          </div>

          <button type="submit" class="btn btn-success">
            <i class="fas fa-save mr-1"></i> Save Changes
          </button>
          <a href="profile.php" class="btn btn-secondary">
            <i class="fas fa-arrow-left mr-1"></i> Cancel
          </a>
        </form>
      </div>
    </section>
  </div>
</div>
</body>
</html>
