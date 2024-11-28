<?php
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'uploads';

try {
    $conn = new mysqli($host, $username, $password, $dbname);
    
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }
    
    $conn->set_charset("utf8mb4");
    
} catch (Exception $e) {
    error_log("Database connection error: " . $e->getMessage());
    die("Sorry, there was a problem connecting to the database. Please try again later.");
}
?>