<?php
// Start session and manage user session
session_start();

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function requireLogin() {
    if (!isLoggedIn()) {
        header("Location: /student-dropout-analysis/auth/login.php");
        exit();
    }
}
?>
