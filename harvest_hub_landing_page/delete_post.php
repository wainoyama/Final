<?php
require_once 'database_connection.php';

if (isset($_GET['id'])) {
    $postId = (int) $_GET['id'];

    if ($postId > 0) {
        $stmt = $db->prepare("DELETE FROM posts WHERE id = ?");
        if ($stmt->execute([$postId])) {
            header('Location: /index.php');
            exit();
        } else {
            echo "Error deleting the post.";
        }
    } else {
        echo "Invalid post ID.";
    }
}
?>
