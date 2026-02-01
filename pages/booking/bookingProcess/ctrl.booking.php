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

    /* ==========================================================
       1. ROOM ASSIGNMENT LOGIC
       ========================================================== */

    if ($selected_room_id) {
        // 👉 USER CLICKED A RECOMMENDED ROOM
        $assigned_room_id = $selected_room_id;

    } else {
        // 👉 AUTO ASSIGN (NO RECOMMENDATION SELECTED)
        $query = "
            SELECT room_id 
            FROM tbl_rooms
            WHERE room_type = ?
              AND status = 'available'
              AND room_id NOT IN (
                    SELECT room_id FROM tbl_reservations
                    WHERE (
                        checkin < ?
                        AND checkout > ?
                    )
              )
            ORDER BY room_id ASC
            LIMIT 1
        ";

        $stmt = $conn->prepare($query);
        $stmt->bind_param("sss", $room_type, $checkout, $checkin);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            $_SESSION['error'] = "No available rooms for the selected schedule.";
            header("Location: ../booking.form.php");
            exit();
        }

        $room = $result->fetch_assoc();
        $assigned_room_id = $room['room_id'];
    }

    /* ==========================================================
       2. INSERT RESERVATION
       ========================================================== */

    $stmt = $conn->prepare("
        INSERT INTO tbl_reservations 
        (user_id, room_id, guestName, email, contact, guest_count, checkin, checkout, duration, status, confirmation_code)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending', ?)
    ");

    $stmt->bind_param(
        "iississsis",
        $user_id,
        $assigned_room_id,
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

        // 1️⃣ UPDATE ROOM STATUS
        $update = $conn->prepare("UPDATE tbl_rooms SET status = 'occupied' WHERE room_id = ?");
        $update->bind_param("i", $assigned_room_id);
        $update->execute();

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
