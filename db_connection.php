<?php
// Database connection settings
$db = new mysqli('hostname', 'username', 'password', 'database');

// Check connection
if ($db->connect_error) {
    die('Connection failed: ' . $db->connect_error);
}
?>