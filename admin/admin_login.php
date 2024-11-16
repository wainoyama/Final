<?php
include_once 'Database.php';
include_once 'Admin.php';

$database = new Database();
$db = $database->getConnection();

$admin = new Admin($db);
$error_message = "";

if (isset($_POST['submit'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    if ($admin->login($username, $password)) {
        header("Location: dashboard.php");
        exit();
    } else {
        $error_message = "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
</head>
<body>
    <h2>Admin Login</h2>
    <?php if ($error_message != "") { echo "<p style='color:red;'>$error_message</p>"; } ?>
    <form action="dashboard.php" method="POST">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>

        <button type="submit" name="submit">Login</button>
    </form>
</body>
</html>
