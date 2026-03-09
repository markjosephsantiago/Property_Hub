<?php
session_start();
include "../../includes/conn.php";

// Fetch available room types with details
$query = "SELECT room_type, capacity, price, room_image FROM tbl_rooms WHERE status = 'available' GROUP BY room_type";
$result = mysqli_query($conn, $query);

// Fetch Max Capacity for Input Constraint
$cap_query = "SELECT MAX(capacity) as max_cap FROM tbl_rooms";
$cap_result = mysqli_query($conn, $cap_query);
$max_capacity = mysqli_fetch_assoc($cap_result)['max_cap'] ?? 10; // Default to 10 if null
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Hourly Room Booking</title>
    <link rel="stylesheet" href="../../dist/css/adminlte.min.css">
    <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
    <style>
        body {
            background: #f4f6f9;
        }
        .card {
            max-width: 500px;
            margin: 40px auto;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        .card-header {
            background-color: #dc143c;
            color: #fff;
            border-radius: 12px 12px 0 0;
        }
        .form-control {
            font-size: 14px;
            height: 35px;
        }
        label {
            font-weight: 600;
            font-size: 14px;
        }
        .btn-primary {
            width: 100%;
            font-size: 15px;
            padding: 8px 0;
            background-color: #dc143c;
            border-color: #dc143c;
        }
        .btn-primary:hover {
            background-color: #c41235;
            border-color: #c41235;
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <div class="card">
        <div class="card-header text-center">
            <h5 class="mb-0"><i class="fas fa-calendar-plus"></i> Book a Room</h5>
        </div>

        <div class="card-body p-4">
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= $_SESSION['success'] ?>
                    <?php unset($_SESSION['success']); ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php elseif (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= $_SESSION['error'] ?>
                    <?php unset($_SESSION['error']); ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php endif; ?>
            
            <form id="bookingForm" action="bookingProcess/ctrl.booking.php" method="POST">

                <!-- Guest Info -->
                <div class="form-group mb-3">
                    <label for="guestName">Full Name</label>
                    <input type="text" name="guestName" id="guestName" class="form-control" required>
                </div>

                <div class="form-group mb-3">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" class="form-control" required>
                </div>

                <div class="form-group mb-3">
                    <label for="contact">Contact Number</label>
                    <input type="text" name="contact" id="contact" class="form-control" maxlength="11" pattern="\d{11}" placeholder="09XXXXXXXXX" required>
                </div>

                <!-- Check-in -->
                <div class="form-group mb-3">
                    <label for="checkin">Check-in Date</label>
                    <input type="date" name="checkin" id="checkin" class="form-control" required>
                </div>

                <!-- Duration -->
                <div class="form-group mb-3">
                    <label for="duration">Duration (Days)</label>
                    <select name="duration" id="duration" class="form-control" required>
                        <option value="" disabled selected>-- Select Duration --</option>
                        <?php for ($i = 1; $i <= 30; $i++) {
                            echo "<option value='$i'>$i day(s)</option>";
                        } ?>
                    </select>
                </div>

                <!-- Guest Count -->
                <div class="form-group mb-3">
                    <label for="guest_count">Number of Guests (Max: <?= $max_capacity ?>)</label>
                    <input type="number" name="guest_count" id="guest_count" class="form-control" min="1" max="<?= $max_capacity ?>" required>
                </div>

                <!-- Room Type -->
                <div class="form-group mb-3">
                    <label for="room_type">
                        Select Room Type 
                        <span id="recommendation-badge" class="badge badge-success d-none ml-2" style="font-size: 11px;">
                            <i class="fas fa-magic"></i> Trending for you: <span id="recommended-type"></span>
                        </span>
                        <div id="recommendation-warning" class="text-warning d-none mt-1" style="font-size: 11px; font-style: italic;">
                            <i class="fas fa-exclamation-triangle"></i> Note: All rooms of this type are currently occupied. Schedule is subject to change.
                        </div>
                    </label>
                    <select name="room_type" id="room_type" class="form-control" required>
                        <option value="" disabled selected>-- Select Room Type --</option>
                        <?php 
                        // Reset result pointer if needed (though it should be fine if group by is unique)
                        mysqli_data_seek($result, 0);
                        while ($row = mysqli_fetch_assoc($result)) {
                            $displayText = $row['room_type'] . " (Cap: " . $row['capacity'] . " | ₱" . number_format($row['price'], 2) . ")";
                            $imgSrc = !empty($row['room_image']) ? "../../uploads/rooms/" . $row['room_image'] : "../../dist/img/default-room.jpg";
                            
                            echo "<option value='{$row['room_type']}' 
                                    data-price='{$row['price']}' 
                                    data-capacity='{$row['capacity']}' 
                                    data-image='$imgSrc'
                                    data-original-text='$displayText'>
                                    $displayText
                                  </option>";
                        } ?>
                    </select>
                </div>

                <!-- Selected Room Image Preview -->
                <div class="form-group mb-3 d-none" id="room-preview-container">
                    <label>Room Preview</label>
                    <div class="text-center">
                        <img id="room-image-preview" src="" alt="Room Preview" class="img-fluid rounded shadow-sm" style="max-height: 200px; width: 100%; object-fit: cover;">
                        <p class="mt-2 text-muted" style="font-size: 13px;">
                            <i class="fas fa-info-circle"></i> 
                            Capacity: <span id="preview-capacity" class="font-weight-bold"></span> | 
                            Price: <span id="preview-price" class="font-weight-bold text-success"></span>
                        </p>
                    </div>
                </div>

                <!-- Hidden User ID -->
                <input type="hidden" name="user_id" value="<?= $_SESSION['user_id'] ?? '' ?>">
                <input type="hidden" name="room_id" id="room_id">
                <input type="hidden" name="room_price" id="room_price">
                <input type="hidden" name="room_capacity" id="room_capacity">

                <!-- Submit -->
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-arrow-right"></i> Proceed
                </button>
            </form>
        </div>
    </div>
</div>

<script src="../../plugins/jquery/jquery.min.js"></script>
<script>
$(document).ready(function() {
    // Handle Room Type Change
    $('#room_type').on('change', function() {
        const selectedOption = $(this).find('option:selected');
        const price = selectedOption.data('price');
        const capacity = selectedOption.data('capacity');
        const image = selectedOption.data('image');

        // Update hidden fields
        $('#room_price').val(price);
        $('#room_capacity').val(capacity);

        // Update Preview
        if (image) {
            $('#room-image-preview').attr('src', image);
            $('#preview-capacity').text(capacity + ' Guests');
            $('#preview-price').text('₱' + parseFloat(price).toLocaleString('en-US', {minimumFractionDigits: 2}));
            $('#room-preview-container').removeClass('d-none');
        } else {
            $('#room-preview-container').addClass('d-none');
        }
    });

    function getRecommendation() {
        const guestCount = $('#guest_count').val();
        let duration = $('#duration').val();

        // Default duration to 1 if not selected
        if (!duration) duration = 1;

        if (guestCount > 0) {
            $.ajax({
                url: 'get_recommendation.php',
                type: 'GET',
                data: { guest_count: guestCount, duration: duration },
                dataType: 'json',
                success: function(response) {
                    if (response.recommendation) {
                        $('#recommended-type').text(response.recommendation);
                        $('#recommendation-badge').removeClass('d-none');
                        
                        // Show warning if applicable
                        if (response.warning) {
                            $('#recommendation-warning').removeClass('d-none');
                        } else {
                            $('#recommendation-warning').addClass('d-none');
                        }
                        
                        // Highlight the recommended option in the select
                        $('#room_type option').each(function() {
                            if ($(this).val() === response.recommendation) {
                                $(this).text($(this).data('original-text') + ' (Trending)');
                                $(this).css('font-weight', 'bold');
                                $(this).css('color', '#dc143c');
                            } else {
                                $(this).text($(this).data('original-text'));
                                $(this).css('font-weight', 'normal');
                                $(this).css('color', 'inherit');
                            }
                        });
                    } else {
                        $('#recommendation-badge').addClass('d-none');
                        $('#recommendation-warning').addClass('d-none');
                    }
                }
            });
        }
    }

    // Trigger on guest count or duration change
    $('#guest_count, #duration').on('input change', getRecommendation);
});
</script>

</body>
</html>
