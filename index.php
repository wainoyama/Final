<?php
require_once './harvest_hub_landing_page/database_connection.php';
require_once './harvest_hub_landing_page/classes/Post.php';

$post = new Post($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['message'])) {
        $message = $_POST['message'];
        $for_sale = isset($_POST['for_sale']) ? 1 : 0;
        $photo = null;

        if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
            $fileName = basename($_FILES['photo']['name']);
            $sanitizedFileName = preg_replace("/[^a-zA-Z0-9\._-]/", "_", $fileName);
            
            $uploadDir = __DIR__ . '/harvest_hub_landing_page/uploads/';
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            
            $uploadFile = $uploadDir . $sanitizedFileName;
            $photoUrl = '/harvest_hub_landing_page/uploads/' . $sanitizedFileName;

            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            $fileType = $_FILES['photo']['type'];

            if (in_array($fileType, $allowedTypes)) {
                $maxFileSize = 40 * 1024 * 1024;
                if ($_FILES['photo']['size'] <= $maxFileSize) {
                    if (move_uploaded_file($_FILES['photo']['tmp_name'], $uploadFile)) {
                        $photo = $photoUrl;
                    } else {
                        $uploadError = error_get_last();
                        echo "File upload failed. Error: " . ($uploadError['message'] ?? 'Unknown error');
                    }
                } else {
                    echo "The file is too large. Please upload a file smaller than 40MB.";
                }
            } else {
                echo "Invalid file type. Please upload a JPEG, PNG, or GIF.";
            }
        }

        $post->createPost($message, $photo, $for_sale);
    } elseif (isset($_POST['like'])) {
        $postId = $_POST['post_id'];
        if ($post->isLiked($postId)) {
            $post->removeLike($postId);
        } else {
            $post->addLike($postId);
        }
    } elseif (isset($_POST['comment'])) {
        $postId = $_POST['post_id'];
        $comment = $_POST['comment_text'];
        $post->addComment($postId, $comment);
    }

    header("Location: ./index.php");
    exit();
}

$posts = $post->getPosts();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Harvest Hub Landing Page</title>
    <link rel="stylesheet" href="./harvest_hub_landing_page/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <header>
        <h1>Harvest Hub</h1>
    </header>

    <div class="container">
        <div class="content">
            <div class="upload-post">
                <form method="POST" enctype="multipart/form-data">
                    <textarea name="message" placeholder="What's on your mind?" required></textarea>
                    <div class="upload-actions">
                        <input type="file" name="photo" id="photo" accept="image/*">
                        <label for="for_sale" class="for-sale-toggle">
                            <input type="checkbox" name="for_sale" id="for_sale">
                            For Sale
                        </label>
                        <button type="submit">Post</button>
                    </div>
                </form>
            </div>

            <div class="posts">
                <?php foreach ($posts as $post): ?>
                    <div class="post">
                        <div class="post-header">
                            <div class="user-info">
                                <img src="./harvest_hub_landing_page/assets/default-avatar.png" alt="User avatar" class="avatar">
                                <div>
                                    <h3>User</h3>
                                    <small><?= date('F j \a\t g:i a', strtotime($post['timestamp'])) ?> · <i class="fas fa-globe"></i></small>
                                </div>
                            </div>
                            <div class="post-actions">
                                <a href="./harvest_hub_landing_page/edit_post.php?id=<?= $post['id'] ?>">Edit</a>
                                <a href="./harvest_hub_landing_page/delete_post.php?id=<?= $post['id'] ?>">Delete</a>
                            </div>
                        </div>

                        <div class="post-content">
                            <p><?= htmlspecialchars($post['message']) ?></p>
                            <?php if ($post['photo']): ?>
                                <img src="<?= htmlspecialchars($post['photo']) ?>" alt="Post image" class="post-image">
                            <?php endif; ?>
                        </div>

                        <?php if ($post['for_sale']): ?>
                            <div class="buy-section">
                                <button class="buy-btn">Buy Now</button>
                            </div>
                        <?php endif; ?>

                        <div class="post-footer">
                            <div class="interaction-info">
                                <span><?= $post['like_count'] ?> likes</span>
                                <span><?= $post['comment_count'] ?> comments</span>
                            </div>
                            <div class="interaction-buttons">
                                <form method="POST" class="like-form">
                                    <input type="hidden" name="post_id" value="<?= $post['id'] ?>">
                                    <button type="submit" name="like" class="like-btn <?= $post->isLiked($post['id']) ? 'liked' : '' ?>">
                                        <i class="far fa-thumbs-up"></i> Like
                                    </button>
                                </form>
                                <button class="comment-btn" onclick="toggleCommentForm(<?= $post['id'] ?>)">
                                    <i class="far fa-comment"></i> Comment
                                </button>
                            </div>
                            <div id="comment-form-<?= $post['id'] ?>" class="comment-form" style="display: none;">
                                <form method="POST">
                                    <input type="hidden" name="post_id" value="<?= $post['id'] ?>">
                                    <input type="text" name="comment_text" placeholder="Write a comment..." required>
                                    <button type="submit" name="comment">Comment</button>
                                </form>
                            </div>
                            <div class="comments">
                                <?php
                                $comments = $post->getComments($post['id']);
                                foreach ($comments as $comment):
                                ?>
                                    <div class="comment">
                                        <strong><?= htmlspecialchars($comment['author']) ?>:</strong>
                                        <?= htmlspecialchars($comment['message']) ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <script>
    function toggleCommentForm(postId) {
        var form = document.getElementById('comment-form-' + postId);
        form.style.display = form.style.display === 'none' ? 'block' : 'none';
    }
    </script>
</body>
</html>