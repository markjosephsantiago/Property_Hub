<?php
session_start();
require_once __DIR__ . '/../../includes/conn.php';
require_once __DIR__ . '/../../classes/guest.php';

$guest = new Guest($conn);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save_guest'])) {
    $guest->first_name = $_POST['first_name'];
    $guest->middle_name = $_POST['middle_name'];
    $guest->last_name = $_POST['last_name'];
    $guest->email = $_POST['email'];
    $guest->contact = $_POST['contact'];
    $guest->address = $_POST['address'];
    $guest->id_type = $_POST['id_type'];
    $guest->id_number = $_POST['id_number'];

    if ($guest->create()) {
        header("Location: guest.list.php?msg=Guest added successfully&status=success");
        exit();
    } else {
        $error = "Error adding guest. Please try again.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>PMS | Add Guest</title>
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
            <h1>Add New Guest</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="../dashboard/index.php">Home</a></li>
              <li class="breadcrumb-item"><a href="guest.list.php">Guest List</a></li>
              <li class="breadcrumb-item active">Add Guest</li>
            </ol>
          </div>
        </div>
      </div>
    </section>

    <section class="content">
      <div class="container-fluid">
        <div class="card card-danger card-outline">
          <div class="card-header">
            <h3 class="card-title">Guest Information</h3>
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
                    <input type="text" name="first_name" class="form-control" placeholder="Enter First Name" required>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Middle Name (Optional)</label>
                    <input type="text" name="middle_name" class="form-control" placeholder="Enter Middle Name">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Last Name</label>
                    <input type="text" name="last_name" class="form-control" placeholder="Enter Last Name" required>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" name="email" class="form-control" placeholder="Enter Email" required>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Contact Number</label>
                    <input type="text" name="contact" class="form-control" placeholder="Enter Contact Number" required>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label>Address</label>
                <textarea name="address" class="form-control" rows="2" placeholder="Enter Address"></textarea>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label>ID Type</label>
                    <select name="id_type" class="form-control">
                      <option value="">Select ID Type</option>
                      <option value="Passport">Passport</option>
                      <option value="Drivers License">Driver's License</option>
                      <option value="National ID">National ID</option>
                      <option value="Other">Other</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label>ID Number</label>
                    <input type="text" name="id_number" class="form-control" placeholder="Enter ID Number">
                  </div>
                </div>
              </div>
            </div>
            <div class="card-footer">
              <button type="submit" name="save_guest" class="btn btn-danger">Save Guest</button>
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
