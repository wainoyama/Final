<?php
require_once 'classes/Post.php';
$postHandler = new Post();
$posts = $postHandler->getPosts();
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
    <?php include 'templates/header.php'; ?>
    <?php include 'templates/nav.php'; ?>

    <main class="container">
        <aside></aside>
        <div class="content">
            <form class="post-form" method="POST" action="upload_post.php" enctype="multipart/form-data">
                <h3>Create a new post</h3>
                <textarea name="message" placeholder="What's on your mind?"></textarea>
                <div class="post-form-footer">
                    <div>
                        <input type="file" name="photo" id="photo" accept="image/*" style="display: none;">
                        <label for="photo" class="upload-btn">Upload a photo</label>
                        <label>
                            <input type="checkbox" name="for_sale"> For sale
                        </label>
                    </div>
                    <button type="submit" class="submit-btn">UPLOAD POST</button>
                </div>
            </form>

            <div class="posts">
                <?php foreach ($posts as $post): ?>
                    <div class="post">
                        <?php if ($post['for_sale']): ?>
                            <div class="for-sale-badge">For Sale</div>
                        <?php endif; ?>
                        <p><?php echo htmlspecialchars($post['message']); ?></p>
                        <?php if (!empty($post['photo'])): ?>
                            <img src="<?php echo htmlspecialchars($post['photo']); ?>" alt="Post image">
                        <?php endif; ?>
                        <small>Posted: <?php echo date('M j, Y g:i A', $post['timestamp']); ?></small>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <aside></aside>
    </main>

    <?php include 'templates/footer.php'; ?>
</body>
</html>
