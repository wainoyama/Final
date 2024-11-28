<?php
include 'market_stock.php';
include 'farm_products.php';
include 'agriculture.php'; 


$marketStocks = new MarketStocks($pdo);
$farmProducts = new FarmProducts($pdo);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CALABARZON Agricultural Resources</title>
    <link rel="stylesheet" href="./css/resources.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <header>
        <div class="header-content">
            <div class="logo">
                <h1>CALABARZON Harvest Hub</h1>
            </div>
            <nav>
                <a href="index.php"><i class="fas fa-home"></i> Home</a>
                <a href="profile.php"><i class="fas fa-user"></i> Profile</a>
                <a href="resources.php"><i class="fas fa-seedling"></i> Resources</a>
            </nav>
            <div class="auth-buttons">
                <a href="login.php" class="btn-login">Log In</a>
                <a href="register.php" class="btn-signup">Sign Up</a>
            </div>
        </div>
    </header>

    <div class="content">
        <div class="section">
            <h2>Recent Market Stocks</h2>
            <table>
                <tr>
                    <th>Product</th>
                    <th>Price (PHP)</th>
                    <th>Location</th>
                </tr>
                <?php
                $marketStocks->displayMarketStocks();
                ?>
            </table>
        </div>

        <div class="section">
            <h2>Farm Products</h2>
            <table>
                <tr>
                    <th>Product</th>
                    <th>Price (PHP)</th>
                    <th>Origin</th>
                </tr>
                <?php
                $farmProducts->displayFarmProducts();
                ?>
            </table>
        </div>

        <div class="section">
            <h2>Preservation Tips</h2>
            <ul>
                <?php
                $preservationTips = $farmProducts->getPreservationTips();
                foreach ($preservationTips as $product => $tip) {
                    echo "<li><strong>" . htmlspecialchars($product) . ":</strong> " . htmlspecialchars($tip) . "</li>";
                }
                ?>
            </ul>
        </div>
    </div>

    <footer>
        <div class="footer-content">
            <div class="footer-section">
                <h3>About CALABARZON Harvest Hub</h3>
                <p>Connecting farmers and buyers in the CALABARZON region.</p>
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
            <p>&copy; 2024 CALABARZON Harvest Hub. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
