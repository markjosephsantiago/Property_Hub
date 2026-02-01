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
    <div class="card selection-card">
        <div class="card-header bg-primary text-white text-center">
            <h3><i class="fas fa-wallet"></i> Select Payment Method</h3>
        </div>
        <div class="card-body">
            
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success"><?= $_SESSION['success'] ?></div>
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

                <button type="submit" class="btn btn-primary btn-block mt-4">
                    Proceed <i class="fas fa-chevron-right"></i>
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
