<?php
require '../../includes/conn.php';

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    $sql = "SELECT user_id FROM tbl_user WHERE verification_token = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $update = "UPDATE tbl_user SET is_verified = 1, verification_token = NULL WHERE user_id = ?";
        $stmt2 = $conn->prepare($update);
        $stmt2->bind_param("i", $row['user_id']);
        $stmt2->execute();

        echo "<h3>Email verified!</h3><p>You may now <a href='login.php'>log in</a>.</p>";
    } else {
        echo "<h3>Invalid or expired verification link.</h3>";
    }
} else {
    echo "<h3>No token provided.</h3>";
}
?>
