<?php
require_once '../db.php';
require_once '../auth_check.php';
require_once '../harvest_hub_landing_page/classes/Post.php';
require_once '../notifications/notif.php';

if (!isLoggedIn()) {
    header('Location: login.php');
    exit();
}

if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    die('CSRF token validation failed');
}

if (!isset($_POST['post_id'])) {
    die('Post ID not provided.');
}

$postId = intval($_POST['post_id']);
$buyerId = $_SESSION['user_id'];

$postManager = new Post($conn);
$post = $postManager->getPostById($postId);

if (!$post || $post['for_sale'] != 1 || $post['order_status'] != 'pending') {
    die('Invalid post or product not available for purchase.');
}

$sellerId = $post['user_id'];

if ($buyerId == $sellerId) {
    die('You cannot buy your own product.');
}

$stmt = $conn->prepare("INSERT INTO orders (post_id, buyer_id, seller_id, item_description, status, created_at) VALUES (?, ?, ?, ?, 'pending', NOW())");
$stmt->bind_param("iiis", $postId, $buyerId, $sellerId, $post['item_description']);

if ($stmt->execute()) {
    $orderId = $stmt->insert_id;

    // Update post status
    $updateStmt = $conn->prepare("UPDATE posts SET order_status = 'sold' WHERE id = ?");
    $updateStmt->bind_param("i", $postId);
    $updateStmt->execute();

    // Get the user's name
    $userNameStmt = $conn->prepare("SELECT name FROM users WHERE id = ?");
    $userNameStmt->bind_param("i", $userId);
    $userNameStmt->execute();
    $userNameResult = $userNameStmt->get_result();
    $userName = $userNameResult->fetch_assoc()['name'];
    
    $_SESSION['success_message'] = "Order placed successfully!";
    header('Location: ../harvest_hub_landing_page/community.php');
    exit();
} else {
    $_SESSION['error_message'] = "Failed to place order. Please try again.";
    header('Location: ../harvest_hub_landing_page/community.php');
    exit();
}