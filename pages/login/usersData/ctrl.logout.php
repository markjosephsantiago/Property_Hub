<?php
session_start();

// Destroy all session data
session_unset();
session_destroy();

// Redirect to login page (update the path as needed)
header("Location: ../login.php");
exit();
