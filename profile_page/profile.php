<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

include('../db.php');

$user_id = $_SESSION['user_id'];

$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    echo "User not found.";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $location = $_POST['location'];

    $check_duplicate_sql = "SELECT id FROM users WHERE (email = ? OR name = ?) AND id != ?";
    $check_duplicate_stmt = $conn->prepare($check_duplicate_sql);
    $check_duplicate_stmt->bind_param("ssi", $email, $name, $user_id);
    $check_duplicate_stmt->execute();
    $duplicate_result = $check_duplicate_stmt->get_result();

    if ($duplicate_result->num_rows > 0) {
        $error_message = "The name or email is already in use.";
    } else {
        $update_sql = "UPDATE users SET name = ?, email = ?, phone = ?, location = ? WHERE id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("ssssi", $name, $email, $phone, $location, $user_id);

        if ($update_stmt->execute()) {
            $success_message = "Profile updated successfully!";
            $user['name'] = $name;
            $user['email'] = $email;
            $user['phone'] = $phone;
            $user['location'] = $location;
        } else {
            $error_message = "Error updating profile.";
        }
    }
}

if (isset($_FILES['profile_picture'])) {
    $target_dir = "../uploads/";
    $target_file = $target_dir . basename($_FILES["profile_picture"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file)) {
        $photo_path = "uploads/" . basename($_FILES["profile_picture"]["name"]);
        $update_photo_sql = "UPDATE users SET photo = ? WHERE id = ?";
        $update_photo_stmt = $conn->prepare($update_photo_sql);
        $update_photo_stmt->bind_param("si", $photo_path, $user_id);

        if ($update_photo_stmt->execute()) {
            $success_message = "Profile picture updated successfully!";
            $user['photo'] = $photo_path;
        } else {
            $error_message = "Error updating profile picture in database.";
        }
    } else {
        $error_message = "Error uploading your file.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - Harvest Hub</title>
    <link rel="stylesheet" href="../css/profile.css">
    <link rel="stylesheet" href="../css/styles.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="container">
        <header>
            <div class="header-content">
                <div class="logo"><span>Harvest Hub</span></div>
                <nav>
                    <a href="../index.php"><i class="fas fa-home"></i> Home</a>
                    <a href="../community.php"><i class="fas fa-users"></i> Community</a>
                    <a href="./profile.php" class="active"><i class="fas fa-user"></i> Profile</a>
                </nav>
                <div class="auth-buttons">
                    <a href="../logout.php" class="btn-logout">Log Out</a>
                </div>
            </div>
        </header>
        
        <div class="content">
            <h1>Your Profile</h1>
            <div class="profile-container">
                <div class="profile-picture">
                    <?php if ($user['photo']): ?>
                        <img src="../<?php echo htmlspecialchars($user['photo']); ?>" alt="Profile Picture" class="profile-img">
                    <?php else: ?>
                        <img src="../uploads/default-profile.jpg" alt="Default Profile Picture" class="profile-img">
                    <?php endif; ?>
                </div>
            
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data" class="profile-form">
                    <div class="form-group">
                        <label for="profile_picture">Update Profile Picture:</label>
                        <input type="file" name="profile_picture" id="profile_picture">
                    </div>
                    <div class="form-group">
                        <label for="name">Name:</label>
                        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone:</label>
                        <input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>">
                    </div>
                    <div class="form-group">
                        <label for="location">Location:</label>
                        <input type="text" id="location" name="location" value="<?php echo htmlspecialchars($user['location']); ?>">
                    </div>
                    <button type="submit" class="btn-primary">Update Profile</button>
                </form>
            </div>
        </div>
        
        <footer>
            <div class="footer-content">
                <div class="footer-section">
                    <h3>About Harvest Hub</h3>
                    <p>Connecting farmers and consumers for a sustainable future.</p>
                </div>
                <div class="footer-section">
                    <h3>Quick Links</h3>
                    <ul>
                        <li><a href="../about.php">About Us</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h3>Connect With Us</h3>
                    <div class="social-icons">
                        <a href="#"><i class="fab fa-facebook"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-linkedin"></i></a>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2024 Harvest Hub. All rights reserved.</p>
            </div>
        </footer>
    </div>

    <?php if (isset($success_message)): ?>
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: '<?php echo $success_message; ?>'
            });
        </script>
    <?php endif; ?>

    <?php if (isset($error_message)): ?>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: '<?php echo $error_message; ?>'
            });
        </script>
    <?php endif; ?>
</body>
</html>
