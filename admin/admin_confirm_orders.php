<?php
session_start();
require_once '../db.php';
require_once '../auth_check.php';

// Check if the user is an admin
function isAdmin() {
    // Implement your admin check logic here
    // For example, you might check for a specific user ID or role
    return isset($_SESSION['user_id']) && $_SESSION['user_id'] == 1; // Assuming user with ID 1 is admin
}

if (!isAdmin()) {
    header('Location: ../login.php');
    exit();
}

// Handle order status updates
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id']) && isset($_POST['status'])) {
    $orderId = intval($_POST['order_id']);
    $status = $_POST['status'];
    
    $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $orderId);
    $stmt->execute();
}

// Fetch all pending orders
$stmt = $conn->prepare("SELECT o.*, p.message AS item_name, b.name AS buyer_name, s.name AS seller_name 
                        FROM orders o 
                        JOIN posts p ON o.post_id = p.id 
                        JOIN users b ON o.buyer_id = b.id 
                        JOIN users s ON o.seller_id = s.id 
                        WHERE o.status = 'pending'");
$stmt->execute();
$result = $stmt->get_result();
$pendingOrders = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Confirm Orders</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="container">
        <h1>Admin - Confirm Orders</h1>
        <table>
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Item</th>
                    <th>Buyer</th>
                    <th>Seller</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pendingOrders as $order): ?>
                    <tr>
                        <td><?= htmlspecialchars($order['id']) ?></td>
                        <td><?= htmlspecialchars($order['item_name']) ?></td>
                        <td><?= htmlspecialchars($order['buyer_name']) ?></td>
                        <td><?= htmlspecialchars($order['seller_name']) ?></td>
                        <td><?= htmlspecialchars($order['status']) ?></td>
                        <td>
                            <form method="POST">
                                <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                                <select name="status">
                                    <option value="pending" <?= $order['status'] == 'pending' ? 'selected' : '' ?>>Pending</option>
                                    <option value="completed" <?= $order['status'] == 'completed' ? 'selected' : '' ?>>Completed</option>
                                    <option value="cancelled" <?= $order['status'] == 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                                </select>
                                <button type="submit">Update</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>