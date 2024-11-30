<?php
require_once 'auth_check.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Harvest Hub Landing Page</title>
    <link rel="stylesheet" href="./css/styles.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
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
            <div class="search-bar">
                <form action="search.php" method="GET">
                    <input type="text" name="query" placeholder="Search Harvest Hub...">
                    <button type="submit"><i class="fas fa-search"></i></button>
                </form>
            </div>

            <div class="welcome-section">
                <h1>Welcome to Harvest Hub</h1>
                <p>Connect with farmers, share your harvest, and explore sustainable agriculture.</p>
            </div>

            <div class="feature-boxes">
                <div class="feature-box">
                    <i class="fas fa-users"></i>
                    <h2>Community</h2>
                    <p>Join our thriving community of farmers and enthusiasts.</p>
                    <a href="community.php" class="btn">Explore Community</a>
                </div>
                <div class="feature-box">
                    <i class="fas fa-shopping-basket"></i>
                    <h2>About Us</h2>
                    <p>Learn more about us!
</p>
                    <a href="aboutus.php" class="btn">About Us</a>
                </div>
                <div class="feature-box">
                    <i class="fas fa-seedling"></i>
                    <h2>Resources</h2>
                    <p>Access farming tips, guides, and sustainable practices.</p>
                    <a href="resources.php" class="btn">View Resources</a>
                </div>
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