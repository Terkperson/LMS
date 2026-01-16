<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if the session variable WE set in login-backend.php exists
if (!isset($_SESSION['uid'])) {
    // Use an absolute path to avoid "Page not found" or loops
    header("Location: /NEW_CLONE/school-management-system/index.php");
    exit();
}
?>