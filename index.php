<?php
// Include your database connection
include('db_connection.php');

// Fetch data to display on the dashboard (you can adjust these queries to match your database)
$totalUsersQuery = "SELECT COUNT(*) as total_users FROM users";
$totalProductsQuery = "SELECT COUNT(*) as total_products FROM products WHERE status = 'pending'";
$totalOrdersQuery = "SELECT COUNT(*) as total_orders FROM orders WHERE status = 'pending'";

// Execute the queries
$totalUsersResult = $db->query($totalUsersQuery);
$totalProductsResult = $db->query($totalProductsQuery);
$totalOrdersResult = $db->query($totalOrdersQuery);

// Fetch the counts
$totalUsers = $totalUsersResult->fetch_assoc()['total_users'];
$totalProducts = $totalProductsResult->fetch_assoc()['total_products'];
$totalOrders = $totalOrdersResult->fetch_assoc()['total_orders'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link your CSS file -->
</head>
<body>
    <div class="container">
        <h1>Welcome to the Admin Dashboard</h1>
        
        <div class="dashboard-cards">
            <!-- User Management Summary -->
            <div class="card">
                <h2>Total Users</h2>
                <p><?php echo $totalUsers; ?></p>
                <a href="manage_users.php">Manage Users</a>
            </div>

            <!-- Product Management Summary -->
            <div class="card">
                <h2>Pending Products</h2>
                <p><?php echo $totalProducts; ?></p>
                <a href="manage_products.php">Manage Products</a>
            </div>

            <!-- Order Management Summary -->
            <div class="card">
                <h2>Pending Orders</h2>
                <p><?php echo $totalOrders; ?></p>
                <a href="manage_orders.php">Manage Orders</a>
            </div>
        </div>

        <h3>Quick Links</h3>
        <ul>
            <li><a href="manage_users.php">Manage Users</a></li>
            <li><a href="manage_products.php">Manage Products</a></li>
            <li><a href="manage_orders.php">Manage Orders</a></li>
        </ul>
    </div>
</body>
</html>