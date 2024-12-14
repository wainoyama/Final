<?php
require_once 'auth_check.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calabarzon Harvest Hub Landing Page</title>
    <link rel="stylesheet" href="./css/styles.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>

<body>
    <div class="container">
        <header>
            <div class="header-content">
                <div class="logo">
                    <span>Calabarzon Harvest Hub</span>
                </div>
                <nav>
                    <a href="index.php"><i class="fas fa-home"></i> Home</a>
                    <a href="./harvest_hub_landing_page/community.php"><i class="fas fa-users"></i> Community</a>
                    <a href="./profile_page/profile.php"><i class="fas fa-user"></i> Profile</a>
                </nav>
                <div class="auth-buttons">
                    <?php if (isLoggedIn()): ?>
                        <a href="./login_register/logout.php" class="btn-logout">Log Out</a>
                    <?php else: ?>
                        <a href="./login_register/login.php" class="btn-login">Log In</a>
                        <a href="./login_register/register.php" class="btn-signup">Sign Up</a>
                    <?php endif; ?>
                </div>
            </div>
        </header>

        <div class="content">
            <div class="search-bar">
                <form action="./Search/searching.php" method="GET">
                    <input type="text" name="query" placeholder="Search Calabarzon Harvest Hub...">
                    <button type="submit"><i class="fas fa-search"></i></button>
                </form>
            </div>

            <div class="welcome-section">
                <h1>Welcome to Calabarzon Harvest Hub</h1>

                <p>Connect with farmers, share your harvest, and explore sustainable agriculture.</p>
            </div>

            <div class="feature-boxes">
                <div class="feature-box">
                    <i class="fas fa-users"></i>
                    <h2>Community</h2>
                    <p>Join our thriving community of farmers and enthusiasts.</p>
                    <a href="./harvest_hub_landing_page/community.php" class="btn">Explore Community</a>
                </div>
                <div class="feature-box">
                    <i class="fas fa-shopping-basket"></i>
                    <h2>About Us</h2>
                    <p>Learn more about us!
                    </p>
                    <a href="./info/aboutus.php" class="btn">About Us</a>
                </div>
                <div class="feature-box">
                    <i class="fas fa-seedling"></i>
                    <h2>Resources</h2>
                    <p>Access farming tips, guides, and sustainable practices.</p>
                    <a href="./market/agriculture.php" class="btn">View Resources</a>
                </div>
            </div>
        </div>

        <div class="embedded">
            <div class = "embcont">    <h1>Tips for You</h1>
    <p>Preserving food is an excellent way to enjoy your harvest throughout the year. Here are some effective tips to help you!</p>

    <div class="tips">
        <iframe src="https://www.youtube.com/embed/B8hEXgDRbx4?si=ejrtYF-yfzzwObT7" title="YouTube video player" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
        <p>Discover the best methods for storing fruits and vegetables to keep them fresh for a longer time.</p>
    </div>

    <div class="tips">
        <iframe src="https://www.youtube.com/embed/zFdxU-73yAc?si=u-gtZliPx0EtDM4g" title="YouTube video player" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
        <p>Explore the role of technology in modern farming and how innovative machines have made farming more efficient.</p>
    </div>

    <div class="tips">
        <iframe src="https://www.youtube.com/embed/GGY-27uFapc?si=FnwO65Sk9BJb0Fxd" title="YouTube video player" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
        <p>This video introduces various agricultural tools and machines designed to help farmers improve productivity.</p>
    </div>

    <div class="tips">
        <iframe src="https://www.youtube.com/embed/wsNLMs6nVPA?si=ZGkymTDLTcjI9Iua" title="YouTube video player" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
        <p>Learn about the logistics of transporting fresh produce and ensuring high-quality goods reach consumers.</p>
    </div>

    <div class="tips">
        <iframe src="https://www.youtube.com/embed/KmrfXDSO5gw?si=XdYFgzTbYfjGVWFg" title="YouTube video player" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
        <p>Discover how to develop an effective marketing strategy for your farm, including key elements like product quality.</p>
    </div>

    <div class="pagination">
        <button id="prevPage" disabled>Previous</button>
        <button id="nextPage">Next</button>
    </div></div>

</div>

        <div>
        </div>
        <footer>
            <div class="footer-content">
                <div class="footer-section">
                    <h3>About Calabarzon Harvest Hub</h3>
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
                <p>&copy; 2024 Calabarzon Harvest Hub. All rights reserved.</p>
            </div>
        </footer>
    </div>
    <script>
const itemsPerPage = 1;
let currentPage = 1;
const tips = document.querySelectorAll('.tips');
const prevButton = document.getElementById('prevPage');
const nextButton = document.getElementById('nextPage');

function showPage(page) {
    const startIndex = (page - 1) * itemsPerPage;
    const endIndex = startIndex + itemsPerPage;

    tips.forEach((tip, index) => {
        if (index >= startIndex && index < endIndex) {
            tip.style.display = 'flex';
        } else {
            tip.style.display = 'none';
        }
    });

    prevButton.disabled = page === 1;
    nextButton.disabled = endIndex >= tips.length;
}

prevButton.addEventListener('click', () => {
    if (currentPage > 1) {
        currentPage--;
        showPage(currentPage);
    }
});

nextButton.addEventListener('click', () => {
    if (currentPage < Math.ceil(tips.length / itemsPerPage)) {
        currentPage++;
        showPage(currentPage);
    }
});

showPage(currentPage);
</script>
</body>
</html>