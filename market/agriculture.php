<?php
session_start();
// Database connection parameters
$host = 'localhost';
$dbname = 'calabarzon_harvest_hub';
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

        <section class="data-section">
            <h2>Types of Produce</h2>
            <div class="table-container">
                <table id="produceTable" class="display">
                    <thead>
                        <tr>
                            <th>Type</th>
                            <th>Name</th>
                            <th>Definition</th>
                            <th>Preservation Tips</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Fruit</td>
                            <td>Apple</td>
                            <td>A sweet, edible fruit produced by an apple tree.</td>
                            <td>Keep in a cool, dry place.</td>
                        </tr>
                        <tr>
                            <td>Fruit</td>
                            <td>Banana</td>
                            <td>A long, curved fruit with a thick skin and soft flesh.</td>
                            <td>Store at room temperature.</td>
                        </tr>
                        <tr>
                            <td>Fruit</td>
                            <td>Cherry</td>
                            <td>A small, round stone fruit that is typically bright or dark red.</td>
                            <td>Refrigerate for longer freshness.</td>
                        </tr>
                        <tr>
                            <td>Fruit</td>
                            <td>Grapes</td>
                            <td>Small, round fruits that grow in bunches on vines.</td>
                            <td>Store in the refrigerator.</td>
                        </tr>
                        <tr>
                            <td>Fruit</td>
                            <td>Orange</td>
                            <td>A round citrus fruit with a tough, bright orange skin.</td>
                            <td>Keep in a cool place, or refrigerate.</td>
                        </tr>
                        <tr>
                            <td>Fruit</td>
                            <td>Strawberry</td>
                            <td>A red, sweet fruit with tiny seeds on its surface.</td>
                            <td>Store in the refrigerator and consume quickly.</td>
                        </tr>
                        <tr>
                            <td>Vegetable</td>
                            <td>Carrot</td>
                            <td>A root vegetable, usually orange in color, rich in vitamins.</td>
                            <td>Refrigerate to keep fresh.</td>
                        </tr>
                        <tr>
                            <td>Vegetable</td>
                            <td>Lettuce</td>
                            <td>A leafy green vegetable often used in salads.</td>
                            <td>Keep in a sealed container in the fridge.</td>
                        </tr>
                        <tr>
                            <td>Vegetable</td>
                            <td>Tomato</td>
                            <td>A red or yellowish fruit with a juicy pulp, used in salads and cooking.</td>
                            <td>Store at room temperature; refrigerate only if cut.</td>
                        </tr>
                        <tr>
                            <td>Vegetable</td>
                            <td>Spinach</td>
                            <td>A leafy green vegetable that is rich in iron and vitamins.</td>
                            <td>Keep in a plastic bag in the fridge.</td>
                        </tr>
                        <tr>
                            <td>Vegetable</td>
                            <td>Cucumber</td>
                            <td>A long, green vegetable that is often eaten raw in salads.</td>
                            <td>Store in the refrigerator.</td>
                        </tr>
                        <tr>
                            <td>Vegetable</td>
                            <td>Broccoli</td>
                            <td>A green vegetable resembling a tree with a thick stalk and florets.</td>
                            <td>Keep refrigerated in a bag.</td>
                        </tr>
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
            $('#produceTable').DataTable();
        });
    </script>
</body>
</html>
