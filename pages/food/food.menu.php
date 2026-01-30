<?php
include '../../includes/conn.php';
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// REQUIRED: reservation_id & room_id
$reservation_id = $_GET['reservation_id'] ?? null;
$room_id = $_GET['room_id'] ?? null;

// if (!$reservation_id || !$room_id) {
//     die("Invalid access.");
// }

// Fetch available food
$foods = mysqli_query($conn, "SELECT * FROM tbl_food_menu WHERE status = 'available'");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Room Service | Food Menu</title>
    <link rel="stylesheet" href="../../dist/css/adminlte.min.css">
    <style>
        .food-card {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 10px;
        }
        .total-box {
            font-size: 18px;
            font-weight: bold;
        }
    </style>
</head>
<body class="bg-light">

<div class="container mt-4">
    <div class="card">
        <div class="card-header bg-warning">
            <h5 class="mb-0">🍽️ Room Service – Food Menu</h5>
        </div>

        <form action="ctrl.food.order.php" method="POST">
            <div class="card-body">

                <input type="hidden" name="reservation_id" value="<?= $reservation_id ?>">
                <input type="hidden" name="room_id" value="<?= $room_id ?>">

                <?php while ($food = mysqli_fetch_assoc($foods)) { ?>
                    <div class="food-card">
                        <div class="row align-items-center">
                            <div class="col-md-5">
                                <strong><?= htmlspecialchars($food['food_name']) ?></strong><br>
                                <small><?= htmlspecialchars($food['category']) ?></small>
                            </div>

                            <div class="col-md-3">
                                ₱<?= number_format($food['price'], 2) ?>
                                <input type="hidden" class="price" value="<?= $food['price'] ?>">
                            </div>

                            <div class="col-md-2">
                                <input type="number"
                                       name="quantity[<?= $food['food_id'] ?>]"
                                       class="form-control qty"
                                       min="0"
                                       value="0">
                            </div>

                            <div class="col-md-2">
                                ₱<span class="subtotal">0.00</span>
                            </div>
                        </div>
                    </div>
                <?php } ?>

                <hr>

                <div class="text-right total-box">
                    Total: ₱<span id="grandTotal">0.00</span>
                </div>

            </div>

            <div class="card-footer text-right">
                <button type="submit" class="btn btn-success">
                    ✅ Place Food Order
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.querySelectorAll('.qty').forEach((input) => {
    input.addEventListener('input', calculate);
});

function calculate() {
    let total = 0;

    document.querySelectorAll('.food-card').forEach(card => {
        const qty = card.querySelector('.qty').value;
        const price = card.querySelector('.price').value;
        const subtotal = qty * price;

        card.querySelector('.subtotal').innerText = subtotal.toFixed(2);
        total += subtotal;
    });

    document.getElementById('grandTotal').innerText = total.toFixed(2);
}
</script>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php if (isset($_SESSION['food_status'])): ?>
<script>
Swal.fire({
    icon: '<?= $_SESSION['food_status'] ?>',
    title: '<?= $_SESSION['food_status'] === "success" ? "Success!" : "Error!" ?>',
    text: '<?= $_SESSION['food_message'] ?>',
    confirmButtonColor: '#28a745'
});
</script>
<?php
unset($_SESSION['food_status']);
unset($_SESSION['food_message']);
endif;
?>


</body>
</html>
