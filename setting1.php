<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

echo "Welcome, " . $_SESSION['name'] . "\n" .
"Sorry wala pang nakalagay dito. Pero dito sana ang Home page or something :D";
?>
