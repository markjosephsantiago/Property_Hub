<?php
session_start();
require '../../../includes/conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // USER or GUEST
    $user_id = !empty($_SESSION['user_id']) ? $_SESSION['user_id'] : NULL;

    // FORM DATA
    $guestName   = trim($_POST['guestName']);
    $email       = trim($_POST['email']);
    $contact     = trim($_POST['contact']);
    $room_type   = trim($_POST['room_type']);
    $guest_count = (int)$_POST['guest_count'];
    $duration    = (int)$_POST['duration'];

    // ROOM ID FROM RECOMMENDATION (OPTIONAL)
    $selected_room_id = !empty($_POST['room_id']) ? (int)$_POST['room_id'] : null;

    // DATE FIX
    $checkin_raw = $_POST['checkin'];
    $checkin = date('Y-m-d', strtotime(str_replace('T', ' ', $checkin_raw)));
    $checkout = date('Y-m-d', strtotime("+{$duration} days  ", strtotime($checkin)));

    // CONFIRMATION CODE
    $confirmation_code = strtoupper(substr(md5(uniqid(rand(), true)), 0, 8));

    // NO EARLY ROOM ASSIGNMENT
    // Specific room will be assigned by staff during check-in.
    $assigned_room_id = null; // Use NULL instead of 0 for better database compatibility

    /* ==========================================================
       2. INSERT RESERVATION
       ========================================================== */

    $stmt = $conn->prepare("
        INSERT INTO tbl_reservations 
        (user_id, room_id, room_type, guestName, email, contact, guest_count, checkin, checkout, duration, status, confirmation_code)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending', ?)
    ");

    $stmt->bind_param(
        "iissssissis", 
        $user_id,
        $assigned_room_id,
        $room_type,
        $guestName,
        $email,
        $contact,
        $guest_count,
        $checkin,
        $checkout,
        $duration,
        $confirmation_code
    );

    if ($stmt->execute()) {
        $reservation_id = $stmt->insert_id;

        // 1️⃣ ROOM STATUS - NOT UPDATED HERE
        // Room status will be updated to 'occupied' at check-in.

        // 2️⃣ INITIALIZE PAYMENT STATUS (tbl_payment)
        $payStmt = $conn->prepare("INSERT INTO tbl_payment (reservation_id, payment_status) VALUES (?, 'pending')");
        $payStmt->bind_param("i", $reservation_id);
        $payStmt->execute();

        $_SESSION['success'] = "Booking successful! Please select a payment type. Code: <b>$confirmation_code</b>";
        header("Location: ../payment.type.php?code=" . urlencode($confirmation_code));
        exit();

    } else {
        $_SESSION['error'] = "Booking failed: " . $stmt->error;
        header("Location: ../booking.form.php");
        exit();
    }

} else {
    $_SESSION['error'] = "Invalid request.";
    header("Location: ../booking.form.php");
    exit();
}
?>
