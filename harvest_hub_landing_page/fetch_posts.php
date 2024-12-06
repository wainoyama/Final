<?php
require_once '../db.php';
require_once './classes/Post.php';

$postManager = new Post($conn);

$posts = $postManager->getPosts();
usort($posts, function ($a, $b) {
    return strtotime($b['timestamp']) - strtotime($a['timestamp']);
});


foreach ($posts as $postItem) {
    echo "<div class='post'>";
    echo "<div class='post-header'>";
    echo "<div class='user-info'>";
    echo "<img src='" . (!empty($postItem['user_photo']) ? htmlspecialchars($postItem['user_photo']) : 'uploads/default-avatar.png') . "' alt='User avatar' class='avatar'>";
    echo "<div><h3>" . htmlspecialchars($postItem['user_name'] ?? 'Anonymous') . "</h3>";
    echo "<small>" . date('F j \a\t g:i a', strtotime($postItem['timestamp'])) . " · <i class='fas fa-globe'></i></small></div>";
    echo "</div></div>";
    echo "<div class='post-content'>";
    echo "<p>" . htmlspecialchars($postItem['message']) . "</p>";
    if ($postItem['photo']) {
        echo "<img src='" . htmlspecialchars($postItem['photo']) . "' alt='Post image' class='post-image'>";
    }
    echo "</div>";
    echo "</div>";
}
?>
