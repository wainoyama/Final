<?php
// order_product.php
session_start();
include 'config.php';
include 'Order.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}


$order = new Order($pdo);

if (isset($_GET['post_id'])) {  
    $post_id = $_GET['post_id'];
    $product = $order->getProduct($post_id);
} else {
    echo "Product not found.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_GET['post_id'])) {  
        $post_id = $_GET['post_id'];
        error_log("Post ID: " . $post_id); // Log the post_id for debugging
        $product = $order->getProduct($post_id);
        
        if (!$product) {
            die("Product not found. Please ensure the post is marked for sale.");
        }
    } else {
        die("Post ID not provided.");
    }

    if (isset($_GET['post_id'])) {
        $post_id = intval($_GET['post_id']); // Ensure the ID is an integer
        $order = new Order($pdo); // Ensure Order is initialized
    
        $product = $order->getProduct($post_id);
        if (!$product) {
            die("Product not found. Please ensure the post is marked for sale.");
        }
    } else {
        die("Post ID not provided.");
    }

    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die('CSRF token validation failed');
    }

    $postId = $_POST['post_id'];
    $buyerId = $_SESSION['user_id'];

    $stmt = $conn->prepare("SELECT user_id FROM posts WHERE id = ?");
    $stmt->bind_param("i", $postId);
    $stmt->execute();
    $result = $stmt->get_result();
    $post = $result->fetch_assoc();
    $sellerId = $post['user_id'];

    $stmt = $conn->prepare("INSERT INTO orders (post_id, buyer_id, seller_id, status, order_date) VALUES (?, ?, ?, 'pending', NOW())");
    $stmt->bind_param("iii", $postId, $buyerId, $sellerId);
    $stmt->execute();

    header('Location: order_confirmation.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Product</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
</head>
<body>
    <h2>Order Product</h2>
    <p><strong>Product Description:</strong> <?= htmlspecialchars($product['item_description']) ?></p>
    <p><strong>Price:</strong> $<?= htmlspecialchars($product['price']) ?></p>
    <form method="POST">
        <textarea name="item_description" placeholder="Description" required></textarea><br>
        <button type="submit">Place Order</button>
    </form>
</body>
</html>
