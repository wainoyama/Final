<?php
include('../db.php');

$name = $email = $password = $confirmPassword = $phone = $location = $profilePicture = '';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];
    $phone = $_POST['phone'];
    $location = $_POST['location'];

    // Password validation
    if (!preg_match('/[A-Z]/', $password) || 
        !preg_match('/[0-9].*[0-9].*[0-9]/', $password) || 
        !preg_match('/[\W_]/', $password)) { 
        $errors[] = 'Password must include at least one uppercase letter, three numbers, and one special character.';
    }

    // Confirm password validation
    if ($password !== $confirmPassword) {
        $errors[] = 'Passwords do not match.';
    }

    if (isset($_FILES['profilePicture']) && $_FILES['profilePicture']['error'] == 0) {
        $profilePicture = '../harvest_hub_landing_page/uploads/' . basename($_FILES['profilePicture']['name']);
        move_uploaded_file($_FILES['profilePicture']['tmp_name'], $profilePicture);
    }

    $sql = 'SELECT * FROM users WHERE email = ?';
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $errors[] = 'An account with this email already exists.';
    } else if (empty($errors)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $sql = 'INSERT INTO users (name, email, phone, password, location, photo) 
                VALUES (?, ?, ?, ?, ?, ?)';
        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            'ssssss',
            $name,
            $email,
            $phone,
            $hashedPassword,
            $location,
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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Calabarzon Harvest Hub</title>
    <link rel="stylesheet" href="../css/register.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="content">
            <div class="auth-form">
                <h2>Create an Account</h2>
                
                <?php if (!empty($errors)): ?>
                    <div class="error-messages">
                        <?php foreach ($errors as $error): ?>
                            <div class="message error"><?php echo htmlspecialchars($error); ?></div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <form method="POST" enctype="multipart/form-data" class="form-container">
                    <div class="form-group">
                        <label for="name">Name:</label>
                        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="password">Password:</label>
                        <input type="password" id="password" name="password" required>
                    </div>

                    <div class="form-group">
                        <label for="confirmPassword">Confirm Password:</label>
                        <input type="password" id="confirmPassword" name="confirmPassword" required>
                    </div>

                    <div class="form-group">
                        <label for="phone">Phone Number:</label>
                        <input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($phone); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="location">Location:</label>
                        <select id="location" name="location" required>
                            <option value="">Select Location</option>
                            <option value="Cavite" <?php if($location == 'Cavite') echo 'selected'; ?>>Cavite</option>
                            <option value="Laguna" <?php if($location == 'Laguna') echo 'selected'; ?>>Laguna</option>
                            <option value="Batangas" <?php if($location == 'Batangas') echo 'selected'; ?>>Batangas</option>
                            <option value="Quezon" <?php if($location == 'Quezon') echo 'selected'; ?>>Quezon</option>
                            <option value="Rizal" <?php if($location == 'Rizal') echo 'selected'; ?>>Rizal</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="profilePicture">Profile Picture:</label>
                        <input type="file" id="profilePicture" name="profilePicture" accept="image/*">
                    </div>

                    <button type="submit" class="btn-primary">Register</button>
                </form>

                <div class="auth-links">
                    <p>Already have an account? <a href="login.php">Login here</a></p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
