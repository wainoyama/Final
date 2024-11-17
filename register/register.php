<?php
include_once 'Database.php';
include_once 'User.php';

$database = new Database();
$db = $database->getConnection();
$user = new User($db);

$error_message = "";

if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    if ($user->register($name, $email, $password)) {
        echo "Registration successful!";
    } else {
        echo "Error: Could not register user.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Registration</title>
</head>
<body>
    <h2>User Registration</h2>
    <form action="register.php" method="POST">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>

        <button type="submit" name="submit">Register</button>
    </form>
</body>
</html>
