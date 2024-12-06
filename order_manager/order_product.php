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
    $buyer_id = $_SESSION['user_id'];
    $seller_id = $product['seller_id'];
    $item_description = $_POST['item_description'];

    if ($order->createOrder($post_id, $buyer_id, $seller_id, $item_description)) {
        echo "<script>Swal.fire('Order placed!', 'Your order has been placed successfully.', 'success');</script>";
    } else {
        echo "<script>Swal.fire('Error!', 'There was an issue placing your order.', 'error');</script>";
    }
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
    <p><strong>Product Name:</strong> <?= $product['name'] ?></p>
    <p><strong>Price:</strong> $<?= $product['price'] ?></p>
    <form method="POST">
        <textarea name="item_description" placeholder="Description" required></textarea><br>
        <button type="submit">Place Order</button>
    </form>
</body>
</html>
