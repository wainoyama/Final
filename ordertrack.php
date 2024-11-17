<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Harvest Hub: Order Tracking</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Harvest Hub: Order Tracking</h1>
        <form action="track_order.php" method="post">
            <input type="text" name="customer_id" placeholder="Enter Customer ID" required>
            <input type="text" name="order_id" placeholder="Enter Order ID" required>
            <input type="submit" value="Track Order">
        </form>
        <form action="update_status.php" method="post">
            <h2>Admin: Update Order Status</h2>
            <input type="text" name="order_id" placeholder="Enter Order ID" required>
            <input type="text" name="status" placeholder="Enter New Status" required>
            <input type="submit" value="Update Status">
        </form>
    </div>
</body>
</html>