<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

include('../db.php');

$user_id = $_SESSION['user_id'];

$sql = "SELECT * FROM users WHERE id = '$user_id'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    echo "User not found.";
    exit;
}

?>

<!DOCTYPE html>
<head>
    <title>Profile</title>
</head>
<body>

    <div class="profile-container">
        <h2>Profile</h2>

        <?php if ($user['photo']): ?>
            <img src="../<?php echo $user['photo']; ?>" alt="Profile Picture" class="profile-img">
        <?php else: ?>
            <img src="../uploads/default-profile.jpg" alt="Default Profile Picture" class="profile-img">
        <?php endif; ?>

        <p><strong>Name:</strong> <?php echo htmlspecialchars($user['name']); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
        <p><strong>Phone:</strong> <?php echo htmlspecialchars($user['phone']); ?></p>
        <p><strong>Location:</strong> <?php echo htmlspecialchars($user['location']); ?></p>

        <a href="../logout.php">Logout</a>
    </div>

</body>
</html>
