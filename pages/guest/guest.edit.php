<?php
session_start();
require_once __DIR__ . '/../../includes/conn.php';
require_once __DIR__ . '/../../classes/guest.php';

$guest = new Guest($conn);

if (!isset($_GET['id'])) {
    header("Location: guest.list.php");
    exit();
}

$id = intval($_GET['id']);
$guestData = $guest->readOne($id);

if (!$guestData) {
    header("Location: guest.list.php?msg=Guest not found&status=error");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_guest'])) {
    $guest->guest_id = $id;
    $guest->first_name = $_POST['first_name'];
    $guest->middle_name = $_POST['middle_name'];
    $guest->last_name = $_POST['last_name'];
    $guest->email = $_POST['email'];
    $guest->contact = $_POST['contact'];
    $guest->address = $_POST['address'];
    $guest->id_type = $_POST['id_type'];
    $guest->id_number = $_POST['id_number'];

    if ($guest->update()) {
        header("Location: guest.list.php?msg=Guest updated successfully&status=success");
        exit();
    } else {
        $error = "Error updating guest. Please try again.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>PMS | Edit Guest</title>
  <?php require '../../includes/link.php'; ?>
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
  <?php require '../../includes/navbar.php'; ?>
  <?php require '../../includes/sidebar.php'; ?>

  <div class="content-wrapper">
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Edit Guest</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="../dashboard/index.php">Home</a></li>
              <li class="breadcrumb-item"><a href="guest.list.php">Guest List</a></li>
              <li class="breadcrumb-item active">Edit Guest</li>
            </ol>
          </div>
        </div>
      </div>
    </section>

    <section class="content">
      <div class="container-fluid">
        <div class="card card-info card-outline">
          <div class="card-header">
            <h3 class="card-title">Update Guest Details</h3>
          </div>
          <form method="POST">
            <div class="card-body">
              <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?= $error ?></div>
              <?php endif; ?>
              <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                    <label>First Name</label>
                    <input type="text" name="first_name" class="form-control" value="<?= htmlspecialchars($guestData['first_name']) ?>" required>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Middle Name</label>
                    <input type="text" name="middle_name" class="form-control" value="<?= htmlspecialchars($guestData['middle_name'] ?? '') ?>">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Last Name</label>
                    <input type="text" name="last_name" class="form-control" value="<?= htmlspecialchars($guestData['last_name']) ?>" required>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($guestData['email']) ?>" required>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Contact Number</label>
                    <input type="text" name="contact" class="form-control" value="<?= htmlspecialchars($guestData['contact']) ?>" required>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label>Address</label>
                <textarea name="address" class="form-control" rows="2"><?= htmlspecialchars($guestData['address'] ?? '') ?></textarea>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label>ID Type</label>
                    <select name="id_type" class="form-control">
                      <option value="Passport" <?= ($guestData['id_type'] == 'Passport') ? 'selected' : '' ?>>Passport</option>
                      <option value="Drivers License" <?= ($guestData['id_type'] == 'Drivers License') ? 'selected' : '' ?>>Driver's License</option>
                      <option value="National ID" <?= ($guestData['id_type'] == 'National ID') ? 'selected' : '' ?>>National ID</option>
                      <option value="Other" <?= ($guestData['id_type'] == 'Other') ? 'selected' : '' ?>>Other</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label>ID Number</label>
                    <input type="text" name="id_number" class="form-control" value="<?= htmlspecialchars($guestData['id_number'] ?? '') ?>">
                  </div>
                </div>
              </div>
            </div>
            <div class="card-footer">
              <button type="submit" name="update_guest" class="btn btn-info">Update Guest</button>
              <a href="guest.list.php" class="btn btn-default">Cancel</a>
            </div>
          </form>
        </div>
      </div>
    </section>
  </div>
  <?php require '../../includes/footer.php'; ?>
</div>
<?php require '../../includes/script.php'; ?>
</body>
</html>
