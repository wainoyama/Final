<?php
// PHP logic for handling post creation and rendering posts
include 'classes/Post.php';

$post = new Post();

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $message = $_POST['message'] ?? '';
    $forSale = isset($_POST['for_sale']) ? true : false;

    // File upload handling
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
        $fileName = time() . '_' . basename($_FILES['photo']['name']);
        $targetPath = 'uploads/' . $fileName;

        if (move_uploaded_file($_FILES['photo']['tmp_name'], $targetPath)) {
            $photoPath = $targetPath;
        }
    }

    // Save the post
    $post->createPost($message, $photoPath ?? '', $forSale);
}

$posts = $post->getPosts();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Harvest Hub Landing Page</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Harvest Hub</h1>
    </header>

    <div class="upload-post">
        <form method="POST" enctype="multipart/form-data">
            <label for="message">What's on your mind?</label>
            <textarea name="message" id="message" placeholder="Share your thoughts..." required></textarea>

            <label for="photo">Upload a photo</label>
            <input type="file" name="photo" id="photo" accept="image/*" aria-describedby="file-info">

            <label for="for_sale">
                <input type="checkbox" name="for_sale" id="for_sale"> For Sale
            </label>

            <button type="submit">Post</button>
        </form>
    </div>

    <div class="posts">
        <?php foreach ($posts as $post): ?>
            <div class="post">
                <h3><?= htmlspecialchars($post['message']) ?></h3>
                <?php if ($post['photo']): ?>
                    <img src="<?= htmlspecialchars($post['photo']) ?>" alt="Post image">
                <?php endif; ?>

                <?php if ($post['for_sale']): ?>
                    <div class="for-sale-badge">For Sale</div>
                    <button class="buy-btn">Buy</button>
                <?php endif; ?>

                <small>Posted: <?= date('M j, Y g:i A', $post['timestamp']) ?></small>
                <div class="post-actions">
                    <a href="edit_post.php?id=<?= $post['id'] ?>">Edit</a> |
                    <a href="delete_post.php?id=<?= $post['id'] ?>">Delete</a>
                </div>

                <div class="comments">
                    <h4>Comments</h4>
                    <?php foreach ($post['comments'] as $comment): ?>
                        <p><?= htmlspecialchars($comment['message']) ?> - <small><?= $comment['author'] ?></small></p>
                    <?php endforeach; ?>
                    <form action="add_comment.php" method="POST">
                        <input type="hidden" name="post_id" value="<?= $post['id'] ?>">
                        <input type="text" name="comment" placeholder="Add a comment..." required>
                        <button type="submit">Add Comment</button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>