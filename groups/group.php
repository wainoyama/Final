<?php
require_once 'group_db.php';

// Get all groups or search results if search query exists
$search = isset($_GET['search']) ? $_GET['search'] : '';
$groups = $search ? searchGroups($search) : getAllGroups();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Groups - Harvest Hub</title>
    <link rel="stylesheet" href="./css/styles.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <!-- Header Section -->
        <header>
            <div class="header-content">
                <div class="logo">
                    <span>Harvest Hub</span>
                </div>
                <nav>
                    <a href="index.php"><i class="fas fa-home"></i> Home</a>
                    <a href="community.php"><i class="fas fa-users"></i> Community</a>
                    <a href="./profile_page/profile.php"><i class="fas fa-user"></i> Profile</a>
                </nav>
                <div class="auth-buttons">
                    <?php if (isLoggedIn()): ?>
                        <a href="logout.php" class="btn-logout">Log Out</a>
                    <?php else: ?>
                        <a href="login.php" class="btn-login">Log In</a>
                        <a href="register.php" class="btn-signup">Sign Up</a>
                    <?php endif; ?>
                </div>
            </div>
        </header>

        <div class="content">
            <!-- Search Bar -->
            <div class="search-bar">
                <form action="group.php" method="GET">
                    <input type="text" name="search" placeholder="Search Groups..." value="<?php echo htmlspecialchars($search); ?>">
                    <button type="submit"><i class="fas fa-search"></i></button>
                </form>
            </div>

            <!-- Create Group Button -->
            <?php if (isLoggedIn()): ?>
            <div style="text-align: right; margin-bottom: 20px;">
                <a href="create_group.php" class="btn">
                    <i class="fas fa-plus"></i> Create New Group
                </a>
            </div>
            <?php endif; ?>

            <!-- Groups List -->
            <div class="feature-boxes">
                <?php foreach ($groups as $group): ?>
                <div class="feature-box">
                    <?php if ($group['group_image']): ?>
                        <img src="<?php echo htmlspecialchars($group['group_image']); ?>" 
                             alt="Group Image" 
                             style="width: 100px; height: 100px; border-radius: 50%; margin-bottom: 15px;">
                    <?php else: ?>
                        <i class="fas fa-users"></i>
                    <?php endif; ?>
                    <h2><?php echo htmlspecialchars($group['group_name']); ?></h2>
                    <p><?php echo htmlspecialchars($group['description']); ?></p>
                    <p class="group-meta">
                        <span><i class="fas fa-user-friends"></i> <?php echo $group['member_count']; ?> members</span>
                    </p>
                    <a href="view_group.php?id=<?php echo $group['group_id']; ?>" class="btn">View Group</a>
                </div>
                <?php endforeach; ?>
            </div>

            <?php if (empty($groups)): ?>
            <div style="text-align: center; padding: 20px;">
                <p>No groups found. <?php echo $search ? 'Try a different search term.' : 'Be the first to create a group!'; ?></p>
            </div>
            <?php endif; ?>
        </div>

        <!-- Footer -->
        <footer>
            <div class="footer-content">
                <div class="footer-section">
                    <h3>About Harvest Hub</h3>
                    <p>Connecting farmers and consumers for a sustainable future.</p>
                </div>
                <div class="footer-section">
                    <h3>Quick Links</h3>
                    <ul>
                        <li><a href="about.php">About Us</a></li>
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
</body>
</html>