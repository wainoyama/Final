<?php
$admin_username = "admin";
$admin_password = "admin123";

$error_message = "";

if (isset($_POST['submit'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($username === $admin_username && $password === $admin_password) {
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
    <link rel="stylesheet" href="./css/admin_page.css">
</head>
<body>
    <h2>Admin Login</h2>
    <div class="form-container">
        <?php if ($error_message != "") { echo "<p class='error'>$error_message</p>"; } ?>
        <form action="admin_login.php" method="POST">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <button type="submit" name="submit">Login</button>
        </form>
    </div>
</body>
</html>