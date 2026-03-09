<?php
session_start();
require '../../../includes/conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // FORM DATA
    $guestName   = trim($_POST['guestName']);
    $email       = trim($_POST['email']);
    $contact     = trim($_POST['contact']);
    $room_type   = trim($_POST['room_type']);
    $guest_count = (int)$_POST['guest_count'];
    $duration    = (int)$_POST['duration'];

    // USER or GUEST - Auto Registration Logic
    $user_id = !empty($_SESSION['user_id']) ? $_SESSION['user_id'] : NULL;
    $new_account_msg = "";

    if (empty($user_id)) {
        // Check if email already has an account
        $checkUser = $conn->prepare("SELECT user_id FROM tbl_user WHERE email = ?");
        $checkUser->bind_param("s", $email);
        $checkUser->execute();
        $resUser = $checkUser->get_result();
        
        if ($resUser->num_rows > 0) {
            // User exists, link to this account
            $user_id = $resUser->fetch_assoc()['user_id'];
        } else {
            // Create new account automatically
            $temp_password = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
            $hashed_password = password_hash($temp_password, PASSWORD_DEFAULT);
            $new_username = explode('@', $email)[0];
            
            // Uniqueness check for username
            $checkUsername = $conn->prepare("SELECT user_id FROM tbl_user WHERE username = ?");
            $checkUsername->bind_param("s", $new_username);
            $checkUsername->execute();
            if ($checkUsername->get_result()->num_rows > 0) {
                $new_username .= rand(10, 99);
            }
            
            // Split name into First, Middle, and Last
            $name_parts = explode(' ', $guestName);
            $fName = "";
            $mName = "";
            $lName = "";

            if (count($name_parts) == 1) {
                $fName = $name_parts[0];
                $lName = "Guest";
            } elseif (count($name_parts) == 2) {
                $fName = $name_parts[0];
                $lName = $name_parts[1];
            } else {
                $fName = $name_parts[0];
                $lName = $name_parts[count($name_parts) - 1];
                // Middle is everything in between
                array_shift($name_parts);
                array_pop($name_parts);
                $mName = implode(' ', $name_parts);
            }
            
            $regStmt = $conn->prepare("INSERT INTO tbl_user (firstName, middleName, lastName, contact, email, username, password, role_id, is_verified) VALUES (?, ?, ?, ?, ?, ?, ?, 3, 1)");
            $regStmt->bind_param("sssssss", $fName, $mName, $lName, $contact, $email, $new_username, $hashed_password);
            if ($regStmt->execute()) {
                $user_id = $regStmt->insert_id;

                // Also Create Guest Profile for the Guest List
                require_once __DIR__ . '/../../../classes/guest.php';
                $guestObj = new Guest($conn);
                $guestObj->user_id = $user_id;
                $guestObj->first_name = $fName;
                $guestObj->middle_name = $mName;
                $guestObj->last_name = $lName;
                $guestObj->email = $email;
                $guestObj->contact = $contact;
                $guestObj->create();

                $new_account_msg = "<div class='alert alert-danger mt-3' style='border-left: 5px solid #dc143c; color: #842029; background-color: #f8d7da; border-radius: 8px;'>
                    <h5 class='alert-heading' style='font-size: 1.1rem; font-weight: 700;'><i class='fas fa-user-plus me-2'></i> Welcome, new user!</h5>
                    <p class='mb-2' style='font-size: 0.9rem;'>An account has been created for you. Use these credentials to manage your bookings later:</p>
                    <div style='background: rgba(255,255,255,0.7); padding: 10px; border-radius: 4px; border: 1px dashed #dc143c;'>
                        <p class='mb-0' style='font-family: monospace; font-weight: bold;'>Username: $new_username</p>
                        <p class='mb-0' style='font-family: monospace; font-weight: bold;'>Temp Password: $temp_password</p>
                    </div>
                </div>";
            }
        }
    }

    // ROOM ID FROM RECOMMENDATION (OPTIONAL)
    $selected_room_id = !empty($_POST['room_id']) ? (int)$_POST['room_id'] : null;

    // DATE FIX
    $checkin_raw = $_POST['checkin'];
    $checkin = date('Y-m-d', strtotime(str_replace('T', ' ', $checkin_raw)));
    $checkout = date('Y-m-d', strtotime("+{$duration} days  ", strtotime($checkin)));

    // CONFIRMATION CODE
    $confirmation_code = strtoupper(substr(md5(uniqid(rand(), true)), 0, 8));

    // AUTO ROOM ASSIGNMENT for Staff
    $assigned_room_id = null;
    $role = $_SESSION['role'] ?? '';
    if (in_array($role, ['Admin', 'Employee'])) {
        $findRoom = $conn->prepare("SELECT room_id FROM tbl_rooms WHERE room_type = ? AND status = 'available' LIMIT 1");
        $findRoom->bind_param("s", $room_type);
        $findRoom->execute();
        $roomRes = $findRoom->get_result();
        if ($roomRes->num_rows > 0) {
            $assigned_room_id = $roomRes->fetch_assoc()['room_id'];
        }
    }

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

        // 3️⃣ TRIGGER AI TRAINING
        $trainScriptPath = __DIR__ . '/../../analytics/train_model.php';
        if (file_exists($trainScriptPath)) {
            include $trainScriptPath;
        }

        $_SESSION['success'] = "Booking successful! Please select a payment type. Code: <b>$confirmation_code</b>" . $new_account_msg;
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
