<?php
require '../../includes/conn.php';
session_start();
$title = "Select Payment Type";

// 1️⃣ Validate Confirmation Code
if (!isset($_GET['code'])) {
    $_SESSION['error'] = "Booking reference missing.";
    header("Location: booking.form.php");
    exit();
}
$code = $_GET['code'];

// 2️⃣ Fetch Payment Types
$query = "SELECT * FROM tbl_paymenttype";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title><?= $title ?> | Property Hub</title>
  <link rel="stylesheet" href="../../dist/css/adminlte.min.css">
  <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
  <style>
    body { background: #f4f6f9; }
    .selection-card {
        max-width: 600px;
        margin: 50px auto;
        border-radius: 12px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }
    .payment-option:hover {
        background-color: #f8f9fa;
        cursor: pointer;
    }
  </style>
</head>
<body>

<div class="container">
    <div class="card selection-card border-0">
        <div class="card-header text-white text-center rounded-top" style="background: linear-gradient(135deg, #dc143c 0%, #8b0000 100%);">
            <h3 class="mb-0 py-2"><i class="fas fa-wallet me-2"></i> Payment Method</h3>
        </div>
        <div class="card-body p-4">
            
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success border-0 shadow-sm mb-4" style="background-color: #f8d7da; color: #842029; border-left: 5px solid #dc3545; border-radius: 10px;">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-check-circle fa-2x me-3" style="color: #dc3545;"></i>
                        <div><?= $_SESSION['success'] ?></div>
                    </div>
                </div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>



            <form action="bookingProcess/ctrl.payment.type.php" method="POST">
                <input type="hidden" name="code" value="<?= htmlspecialchars($code) ?>">
                
                <div class="form-group">
                    <label for="payment_type_id">Payment Type:</label>
                    <select name="payment_type_id" id="payment_type_id" class="form-control custom-select" required>
                        <option value="" disabled selected>-- Select Payment Method --</option>
                        <?php if ($result && $result->num_rows > 0): ?>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <option value="<?= $row['Payment_Type_ID'] ?>">
                                    <?= htmlspecialchars($row['Payment_Type']) ?> 
                                    <?= !empty($row['description']) ? ' - ' . htmlspecialchars($row['description']) : '' ?>
                                </option>
                            <?php endwhile; ?>
                        <?php endif; ?>
                    </select>
                </div>

                <button type="submit" class="btn btn-danger btn-block mt-4 shadow-sm" style="background-color: #dc143c; border: none; font-weight: 600;">
                    Proceed <i class="fas fa-chevron-right ms-1"></i>
                </button>
            </form>

        </div>
        <div class="card-footer text-center">
             <a href="booking.confirmation.php?code=<?= urlencode($code) ?>" class="btn btn-link text-muted">Skip Payment Selection (Pay at Hotel)</a>
        </div>
    </div>
</div>

<script src="../../plugins/jquery/jquery.min.js"></script>
<script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
