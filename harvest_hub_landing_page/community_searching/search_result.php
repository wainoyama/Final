<?php
require_once '../db.php'; 


$searchQuery = isset($_GET['search']) ? trim($_GET['search']) : '';

if ($searchQuery !== '') {
    $stmt = $conn->prepare("
        SELECT id, name, email, phone, location, photo 
        FROM users 
        WHERE name LIKE ? OR email LIKE ? OR location LIKE ?
    ");
    $likeQuery = "%" . $searchQuery . "%";
    $stmt->bind_param("sss", $likeQuery, $likeQuery, $likeQuery);
    $stmt->execute();
    $result = $stmt->get_result();

    $users = [];
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
} else {
    $users = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calabarzon Harvest Hub Community</title>
    <link rel="stylesheet" href="community_search.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<header>
            <div class="header-content">
                <div class="logo">
                    <span>Calabarzon Harvest Hub</span>
                </div>
                <nav>
                    <a href="../../index.php"><i class="fas fa-home"></i> Home</a>
                    <a href="../community.php"><i class="fas fa-users"></i> Community</a>
                    <a href="../../profile_page/profile.php"><i class="fas fa-user"></i> Profile</a>
                </nav>
            </div>
        </header>
<body>

    <div class="container">
        <h1>Search Results for "<?php echo htmlspecialchars($searchQuery); ?>"</h1>

        <?php if (count($users) > 0): ?>
            <div class="user-list">
                <?php foreach ($users as $user): ?>
                    <div class="user-card">
                        <div class="user-info">
                            <h2><?php echo htmlspecialchars($user['name']); ?></h2>
                            <p>Email: <?php echo htmlspecialchars($user['email']); ?></p>
                            <p>Phone: <?php echo htmlspecialchars($user['phone'] ?: 'N/A'); ?></p>
                            <p>Location: <?php echo htmlspecialchars($user['location'] ?: 'N/A'); ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>No users found matching your search criteria.</p>
        <?php endif; ?>
    </div>
</body>
</html>
