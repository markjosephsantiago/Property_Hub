<?php
session_start();
require '../../../includes/conn.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $room_number = $_POST['room_number'];
    $room_type = $_POST['room_type'];
    $capacity = $_POST['capacity'];
    $price = $_POST['price'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("INSERT INTO tbl_rooms (room_number, room_type, capacity, price, status) 
                            VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssids", $room_number, $room_type, $capacity, $price, $status);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Room added successfully!";
    } else {
        $_SESSION['error'] = "Failed to add room: " . $conn->error;
    }
    header("Location: ../list.rooms.php");
    exit();
}
?><?php
session_start();
require '../../../includes/conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $room_number = $_POST['room_number'];
    $room_type = $_POST['room_type'];
    $capacity = $_POST['capacity'];
    $price = $_POST['price'];
    $status = $_POST['status'];

    $room_image = null;

    // ✅ Handle Image Upload
    if (isset($_FILES['room_image']) && $_FILES['room_image']['error'] == 0) {
        $targetDir = "../../../uploads/rooms/";
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }
        $fileName = time() . "_" . basename($_FILES["room_image"]["name"]);
        $targetFile = $targetDir . $fileName;

        if (move_uploaded_file($_FILES["room_image"]["tmp_name"], $targetFile)) {
            $room_image = $fileName;
        }
    }

    // ✅ Insert sa DB
    $query = "INSERT INTO tbl_rooms (room_number, room_type, capacity, price, status, room_image)
              VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssids", $room_number, $room_type, $capacity, $price, $status, $room_image);

    if ($stmt->execute()) {
        $_SESSION['room_added'] = true;
    } else {
        $_SESSION['error'] = "Failed to add room.";
    }

    header("Location: ../add.rooms.php");
    exit();
}
