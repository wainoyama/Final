<?php
session_start();

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

if (!isLoggedIn()) {
    header("Location: ./login_register/login.php");
    exit();
}
?>