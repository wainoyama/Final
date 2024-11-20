<?php
require_once 'database_connection.php';
require_once 'classes/Post.php'; 

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['post_id'])) {
    $post = new Post($db);
    $postId = $_POST['post_id'];
    $comment = $_POST['comment_text'];
    $post->addComment($postId, $comment);
}
header('Location: ./index.php');
exit;
