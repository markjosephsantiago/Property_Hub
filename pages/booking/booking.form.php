<?php
include "../../includes/conn.php";

// Fetch available room types
$query = "SELECT DISTINCT room_type FROM tbl_rooms WHERE status = 'available'";
$result = mysqli_query($conn, $query);
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
            background-color: #007bff;
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

                <!-- Room Type -->
                <div class="form-group mb-3">
                    <label for="room_type">Select Room Type</label>
                    <select name="room_type" id="room_type" class="form-control" required>
                        <option value="" disabled selected>-- Choose Room Type --</option>
                        <?php while ($row = mysqli_fetch_assoc($result)) {
                            echo "<option value='{$row['room_type']}'>{$row['room_type']}</option>";
                        } ?>
                    </select>
                </div>

                <!-- Check-in -->
                <div class="form-group mb-3">
                    <label for="checkin">Check-in Date & Time</label>
                    <input type="datetime-local" name="checkin" id="checkin" class="form-control" required>
                </div>

                <!-- Duration -->
                <div class="form-group mb-3">
                    <label for="duration">Duration (Hours)</label>
                    <select name="duration" id="duration" class="form-control" required>
                        <option value="6">6 Hours</option>
                        <option value="12">12 Hours</option>
                        <option value="24">24 Hours</option>
                        <option value="36">36 Hours</option>
                    </select>
                </div>

                <!-- Guest Count -->
                <div class="form-group mb-3">
                    <label for="guest_count">Number of Guests</label>
                    <input type="number" name="guest_count" id="guest_count" class="form-control" min="1" required>
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

                <!-- Recommendation Area -->
                <div id="recommendationArea" class="mt-3"></div>

            </form>
        </div>
    </div>
</div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Recommendation AJAX (only after form is complete) -->
<script>
$(document).ready(function(){
    $('#bookingForm input, #bookingForm select').on('change keyup', function(){
        var guestName = $('#guestName').val();
        var email = $('#email').val();
        var contact = $('#contact').val();
        var roomType = $('#room_type').val();
        var checkin = $('#checkin').val();
        var duration = $('#duration').val();
        var guestCount = $('#guest_count').val();

        if(guestName && email && contact && roomType && checkin && duration && guestCount){
            // All required fields filled → load recommendations
            $.ajax({
                type: 'POST',
                url: 'recommendation.php',
                data: { room_type: roomType },
                success: function(response){
                    $('#recommendationArea').html(response);
                },
                error: function(xhr, status, error){
                    console.error('Error loading recommendations:', error);
                }
            });
        } else {
            $('#recommendationArea').html('');
        }
    });
});
// Kapag na-click ang recommended room
$(document).on('click', '.recommend-room', function(){
    var selectedRoomId = $(this).data('room-id');
    var price = $(this).data('price');       // kung meron
    var capacity = $(this).data('capacity'); // kung meron

    $('#room_id').val(selectedRoomId);
    $('#room_price').val(price);
    $('#room_capacity').val(capacity);

    // Highlight selected room
    $('.recommend-room').removeClass('bg-primary text-white');
    $(this).addClass('bg-primary text-white');
});

</script>

</body>
</html>
