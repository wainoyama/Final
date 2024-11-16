<?php
require_once 'classes/Post.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['post_id'])) {
    $post = new Post();
    $postId = $_POST['post_id'];
    $post->deletePost($postId);
}

header('Location: upload_post.php');
exit;
