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

    // Check if the user has already liked the post
    $stmt = $conn->prepare("SELECT id FROM group_post_likes WHERE post_id = ? AND user_id = ?");
    $stmt->bind_param("ii", $post_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // User has already liked the post, so remove the like
        $stmt = $conn->prepare("DELETE FROM group_post_likes WHERE post_id = ? AND user_id = ?");
        $stmt->bind_param("ii", $post_id, $user_id);
        $stmt->execute();
        $action = 'unliked';
    } else {
        // User hasn't liked the post, so add a new like
        $stmt = $conn->prepare("INSERT INTO group_post_likes (post_id, user_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $post_id, $user_id);
        $stmt->execute();
        $action = 'liked';
    }

    // Get the updated like count
    $stmt = $conn->prepare("SELECT COUNT(*) as like_count FROM group_post_likes WHERE post_id = ?");
    $stmt->bind_param("i", $post_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $like_count = $result->fetch_assoc()['like_count'];

    echo json_encode([
        'success' => true,
        'action' => $action,
        'likeCount' => $like_count,
        'message' => $action === 'liked' ? 'You liked this post.' : 'You unliked this post.'
    ]);
} else {
    die(json_encode(['success' => false, 'message' => 'Invalid request method']));
}

