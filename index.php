<?php

include('db_connection.php');

// Fetch data to display on the dashboard 
$totalUsersQuery = "SELECT COUNT(*) as total_users FROM users";
$totalProductsQuery = "SELECT COUNT(*) as total_products FROM products WHERE status = 'pending'";
$totalOrdersQuery = "SELECT COUNT(*) as total_orders FROM orders WHERE status = 'pending'";

// Execute the queries
$totalUsersResult = $conn->query($totalUsersQuery);
$totalProductsResult = $conn->query($totalProductsQuery);
$totalOrdersResult = $conn->query($totalOrdersQuery);

// Fetch the counts
$totalUsers = $totalUsersResult->fetch(PDO::FETCH_ASSOC)['total_users'];
$totalProducts = $totalProductsResult->fetch(PDO::FETCH_ASSOC)['total_products'];
$totalOrders = $totalOrdersResult->fetch(PDO::FETCH_ASSOC)['total_orders'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="styles.css"> 
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
