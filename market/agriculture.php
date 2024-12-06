<?php
session_start();
// Database connection parameters
$host = 'localhost';
$dbname = 'agriculture';
$username = 'root';
$password = '';

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/agriculture.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="./css/agriculture.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
</head>
<body>
    <header>
        <div class="header-content">
            <div class="logo">
                <span>Harvest Hub</span>
            </div>
            <nav>
                <a href="../index.php"><i class="fas fa-home"></i> Home</a>
                <a href="community.php"><i class="fas fa-users"></i> Community</a>
                <a href="../profile_page/profile.php"><i class="fas fa-user"></i> Profile</a>
            </nav>
            <div class="auth-buttons">
                <?php if (isLoggedIn()): ?>
                    <a href="../login_register/logout.php" class="btn-logout">Log Out</a>
                <?php else: ?>
                    <a href="./login.php" class="btn-login">Log In</a>
                    <a href="register.php" class="btn-signup">Sign Up</a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <div class="container">
        <section class="data-section">
            <h2>Products</h2>
            <div class="table-container">
                <table id="productsTable" class="display">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Price</th>
                            <th>Origin</th>
                            <th>Preservation Tips</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $stmt = $pdo->query("SELECT * FROM products");
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['type']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['price']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['origin']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['preservation_tips']) . "</td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </section>

        <section class="data-section">
            <h2>Regions</h2>
            <div class="table-container">
                <table id="regionsTable" class="display">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $stmt = $pdo->query("SELECT * FROM regions");
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </section>

        <section class="data-section">
            <h2>Market Stocks</h2>
            <div class="table-container">
                <table id="marketStocksTable" class="display">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Region</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $stmt = $pdo->query("SELECT ms.id, p.name AS product_name, ms.price, r.name AS region_name 
                                             FROM market_stocks ms 
                                             JOIN products p ON ms.product_id = p.id 
                                             JOIN regions r ON ms.region_id = r.id");
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['product_name']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['price']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['region_name']) . "</td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </section>
    </div>

    <script>
        $(document).ready(function() {
            $('#productsTable').DataTable();
            $('#regionsTable').DataTable();
            $('#marketStocksTable').DataTable();
        });
    </script>
</body>
</html>

