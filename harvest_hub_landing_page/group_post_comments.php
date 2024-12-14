<?php
session_start();
require_once '../db.php';

if (!isset($_SESSION['user_id'])) {
    die(json_encode(['success' => false, 'message' => 'User not logged in']));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $post_id = filter_input(INPUT_POST, 'post_id', FILTER_VALIDATE_INT);
    $content = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_STRING);
    $user_id = $_SESSION['user_id'];

    if (!$post_id || !$content) {
        die(json_encode(['success' => false, 'message' => 'Invalid input']));
    }

    // Add the new comment
    $stmt = $conn->prepare("INSERT INTO group_post_comments (post_id, user_id, content) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $post_id, $user_id, $content);
    
    if ($stmt->execute()) {
        $comment_id = $stmt->insert_id;

        // Fetch the user's name
        $stmt = $conn->prepare("SELECT name FROM users WHERE id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user_name = $result->fetch_assoc()['name'];

        // Get the updated comment count
        $stmt = $conn->prepare("SELECT COUNT(*) as comment_count FROM group_post_comments WHERE post_id = ?");
        $stmt->bind_param("i", $post_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $comment_count = $result->fetch_assoc()['comment_count'];

        echo json_encode([
            'success' => true,
            'commentId' => $comment_id,
            'userName' => $user_name,
            'content' => $content,
            'commentCount' => $comment_count,
            'message' => 'Your comment has been posted successfully.'
        ]);
    } else {
        die(json_encode(['success' => false, 'message' => 'Failed to add comment']));
    }
} else if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $post_id = filter_input(INPUT_GET, 'post_id', FILTER_VALIDATE_INT);

    if (!$post_id) {
        die(json_encode(['success' => false, 'message' => 'Invalid post ID']));
    }

    // Fetch comments for the post
    $stmt = $conn->prepare("
        SELECT gpc.*, u.name as user_name
        FROM group_post_comments gpc
        JOIN users u ON gpc.user_id = u.id
        WHERE gpc.post_id = ?
        ORDER BY gpc.created_at ASC
    ");
    $stmt->bind_param("i", $post_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $comments = $result->fetch_all(MYSQLI_ASSOC);

    $comments_html = '';
    foreach ($comments as $comment) {
        $comments_html .= '<div class="comment">';
        $comments_html .= '<strong>' . htmlspecialchars($comment['user_name']) . ':</strong> ';
        $comments_html .= htmlspecialchars($comment['content']);
        $comments_html .= '</div>';
    }

    echo json_encode(['success' => true, 'comments' => $comments_html]);
} else {
    die(json_encode(['success' => false, 'message' => 'Invalid request method']));
}

