<?php
include('db.php');

$name = $email = $password = $phone = $location = $profilePicture = $farmName = $produceType = '';
$role = 'Buyer';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $phone = $_POST['phone'];
    $location = $_POST['location'];
    $role = $_POST['role'];

    if (isset($_FILES['profilePicture']) && $_FILES['profilePicture']['error'] == 0) {
        $profilePicture = 'uploads/' . basename($_FILES['profilePicture']['name']);
        move_uploaded_file($_FILES['profilePicture']['tmp_name'], $profilePicture);
    }

    if ($role === 'Farmer') {
        $farmName = $_POST['farmName'];
        $produceType = $_POST['produceType'];
    }

    $sql = 'SELECT * FROM users WHERE email = ?';
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $errors[] = 'An account with this email already exists.';
    } else {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $sql = 'INSERT INTO users (name, email, phone, password, role, farm_name, produce_type, photo) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)';
        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            'ssssssss',
            $name,
            $email,
            $phone,
            $hashedPassword,
            $role,
            $farmName,
            $produceType,
            $profilePicture
        );

        if ($stmt->execute()) {
            header('Location: login.php');
            exit;
        } else {
            $errors[] = 'Error registering account. Please try again.';
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
</head>
<body>
    <h2>Register</h2>

    <?php if (!empty($errors)): ?>
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?php echo $error; ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <label for="name">Name:</label>
        <input type="text" name="name" value="<?php echo $name; ?>" required><br><br>

        <label for="email">Email:</label>
        <input type="email" name="email" value="<?php echo $email; ?>" required><br><br>

        <label for="password">Password:</label>
        <input type="password" name="password" required><br><br>

        <label for="phone">Phone Number:</label>
        <input type="text" name="phone" value="<?php echo $phone; ?>" required><br><br>

        <label for="location">Location:</label>
        <input type="text" name="location" value="<?php echo $location; ?>" required><br><br>

        <label for="role">Role:</label>
        <select name="role" required onchange="this.form.submit()">
            <option value="Buyer" <?php echo ($role === 'Buyer') ? 'selected' : ''; ?>>Buyer</option>
            <option value="Farmer" <?php echo ($role === 'Farmer') ? 'selected' : ''; ?>>Farmer</option>
        </select><br><br>

        <label for="profilePicture">Profile Picture:</label>
        <input type="file" name="profilePicture" accept="image/*"><br><br>

        <?php if ($role === 'Farmer'): ?>
            <label for="farmName">Farm Name:</label>
            <input type="text" name="farmName" value="<?php echo $farmName; ?>"><br><br>

            <label for="produceType">Produce Type:</label>
            <input type="text" name="produceType" value="<?php echo $produceType; ?>"><br><br>
        <?php endif; ?>

        <button type="submit">Register</button>
    </form>

    <p>Already have an account? <a href="login.php">Login here</a></p>
</body>
</html>
