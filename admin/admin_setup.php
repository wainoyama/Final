<?php
include_once 'Database.php';

$database = new Database();
$db = $database->getConnection();

$admin_username = "admin";
$admin_password = "admin123";

$hashed_password = password_hash($admin_password, PASSWORD_DEFAULT);

$query = "INSERT INTO admins (username, password) VALUES (:username, :password)";
$stmt = $db->prepare($query);

$stmt->bindParam(":username", $admin_username);
$stmt->bindParam(":password", $hashed_password);

if ($stmt->execute()) {
    echo "Admin user has been successfully created!";
} else {
    echo "Error: Could not insert admin user.";
}
?>
