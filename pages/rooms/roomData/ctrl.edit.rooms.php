<?php
session_start();
require '../../../includes/conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $room_id     = $_POST['room_id'];
    $room_number = $_POST['room_number'];
    $room_type   = $_POST['room_type'];
    $capacity    = $_POST['capacity'];
    $price       = $_POST['price'];
    $status      = $_POST['status'];

    // Handle image upload
    $room_image = null;
    if (isset($_FILES['room_image']) && $_FILES['room_image']['error'] === UPLOAD_ERR_OK) {
        $targetDir = "../../../uploads/rooms/";
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        $fileName = time() . "_" . basename($_FILES['room_image']['name']);
        $targetFile = $targetDir . $fileName;

        $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
        if (in_array($_FILES['room_image']['type'], $allowedTypes)) {
            if (move_uploaded_file($_FILES['room_image']['tmp_name'], $targetFile)) {
                $room_image = $fileName;
            } else {
                $_SESSION['error'] = "Failed to upload image.";
                header("Location: ../edit.rooms.php?room_id=$room_id");
                exit();
            }
        } else {
            $_SESSION['error'] = "Invalid file type. Only JPG, PNG allowed.";
            header("Location: ../edit.rooms.php?room_id=$room_id");
            exit();
        }
    }

    // If new image uploaded, update with it; else retain old image
    if ($room_image) {
        $stmt = $conn->prepare("UPDATE tbl_rooms 
                                SET room_number=?, room_type=?, capacity=?, price=?, status=?, room_image=? 
                                WHERE room_id=?");
        $stmt->bind_param("ssidssi", $room_number, $room_type, $capacity, $price, $status, $room_image, $room_id);
    } else {
        $stmt = $conn->prepare("UPDATE tbl_rooms 
                                SET room_number=?, room_type=?, capacity=?, price=?, status=? 
                                WHERE room_id=?");
        $stmt->bind_param("ssidsi", $room_number, $room_type, $capacity, $price, $status, $room_id);
    }

    if ($stmt->execute()) {
        $_SESSION['success'] = "Room updated successfully!";
    } else {
        $_SESSION['error'] = "Failed to update room.";
    }

    header("Location: ../list.rooms.php?room_id=$room_id");
    exit();
}
?>
