<?php
require_once 'classes/Post.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['post_id'])) {
    $post = new Post();
    $postId = $_POST['post_id'];
    $message = $_POST['message'];
    $photo = $_FILES['photo'];
    $forSale = isset($_POST['for_sale']) ? 1 : 0;
    $post->editPost($postId, $message, $photo, $forSale);
}

header('Location: upload_post.php');
exit;
