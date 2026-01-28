<?php
session_start();
require '../../../includes/conn.php';

if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "Please log in to book.";
    header("Location: ../add.booking.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id     = $_SESSION['user_id'];
    $guestName   = $_POST['guestName'] ?? '';
    $email       = $_POST['email'] ?? '';
    $contact     = $_POST['contact'] ?? '';
    $guest_count = $_POST['guest_count'] ?? '';
    $room_id     = $_POST['room_id'] ?? '';
    $checkin     = $_POST['checkin'] ?? '';
    $checkout    = $_POST['checkout'] ?? '';
    $status      = 'pending';

    // ✅ Generate confirmation code
    $confirmation_code = strtoupper(substr(md5(time()), 0, 8));

    // ✅ Check for booking conflict
    $checkQuery = "SELECT * FROM tbl_reservations 
                   WHERE room_id = ? 
                   AND checkin <= ? AND checkout >= ?";
    $checkStmt = $conn->prepare($checkQuery);
    $checkStmt->bind_param("iss", $room_id, $checkout, $checkin);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();

    if ($checkResult->num_rows > 0) {
        $_SESSION['error'] = "This room is already booked for your selected dates.";
        header("Location: ../add.booking.php");
        exit();
    }

    // ✅ Compute duration in days
    $checkinDate  = new DateTime($checkin);
    $checkoutDate = new DateTime($checkout);
    $duration_days = $checkoutDate->diff($checkinDate)->days;

    // ✅ Get room details
    $roomQuery = "SELECT room_number, room_type, price FROM tbl_rooms WHERE room_id = ?";
    $roomStmt  = $conn->prepare($roomQuery);
    $roomStmt->bind_param("i", $room_id);
    $roomStmt->execute();
    $room = $roomStmt->get_result()->fetch_assoc();

    // ✅ Insert new pending booking
    $stmt = $conn->prepare("INSERT INTO tbl_reservations 
        (user_id, guestName, email, contact, room_id, checkin, checkout, confirmation_code, status, guest_count, duration_days)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param(
        "isssissssii",
        $user_id, $guestName, $email, $contact, $room_id,
        $checkin, $checkout, $confirmation_code,
        $status, $guest_count, $duration_days
    );

    if ($stmt->execute()) {
        // ✅ Get the new reservation ID
        $reservation_id = $stmt->insert_id;

        // ✅ Compute total price
        $total_price = $room['price'] * $duration_days;

        // ✅ Update room status to "Occupied"
        $updateRoom = $conn->prepare("UPDATE tbl_rooms SET status = 'booked' WHERE room_id = ?");
        $updateRoom->bind_param("i", $room_id);
        $updateRoom->execute();

        // ✅ Store booking info in session
        $_SESSION['booking_success'] = [
            'guestName'         => $guestName,
            'checkin'           => $checkin,
            'checkout'          => $checkout,
            'confirmation_code' => $confirmation_code,
            'room_number'       => $room['room_number'],
            'room_type'         => $room['room_type'],
            'price'             => $room['price'],
            'duration_days'     => $duration_days,
            'total_price'       => $total_price,
            'status'            => $status
        ];

        // ✅ Redirect to payment form
        header("Location: ../payment.form.php?reservation_id=" . $reservation_id);
        exit();
    } else {
        $_SESSION['error'] = "Booking failed: " . $conn->error;
        header("Location: ../add.booking.php");
        exit();
    }

    $stmt->close();
    $conn->close();
}
?>
