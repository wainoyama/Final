<?php
session_start();
require_once '../db.php';

if (!isset($_SESSION['user_id'])) {
    die(json_encode(['success' => false, 'message' => 'User not logged in']));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $post_id = filter_input(INPUT_POST, 'post_id', FILTER_VALIDATE_INT);
    $user_id = $_SESSION['user_id'];

    if (!$post_id) {
        die(json_encode(['success' => false, 'message' => 'Invalid post ID']));
    }

    // Check if the user owns the post
    $stmt = $conn->prepare("SELECT user_id FROM group_posts WHERE id = ?");
    $stmt->bind_param("i", $post_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $post = $result->fetch_assoc();

    if ($post && $post['user_id'] == $user_id) {
        // Delete associated likes and comments
        $stmt = $conn->prepare("DELETE FROM group_post_likes WHERE post_id = ?");
        $stmt->bind_param("i", $post_id);
        $stmt->execute();

        $stmt = $conn->prepare("DELETE FROM group_post_comments WHERE post_id = ?");
        $stmt->bind_param("i", $post_id);
        $stmt->execute();

        // Delete the post
        $stmt = $conn->prepare("DELETE FROM group_posts WHERE id = ?");
        $stmt->bind_param("i", $post_id);
        $stmt->execute();

        echo json_encode(['success' => true, 'message' => 'Post deleted successfully']);
    } else {
        die(json_encode(['success' => false, 'message' => 'You do not have permission to delete this post']));
    }
} else {
    die(json_encode(['success' => false, 'message' => 'Invalid request method']));
}

