<?php
session_start();

if (!isset($_SESSION['posts'])) {
    $_SESSION['posts'] = [];
}

if (isset($_POST['join_group'])) {
    $_SESSION['joined'] = true;
}

if (isset($_POST['post_content']) && $_SESSION['joined']) {
    $username = htmlspecialchars($_POST['username']);
    $content = htmlspecialchars($_POST['content']);
    $timestamp = date("Y-m-d H:i:s");

    if (!empty($username) && !empty($content)) {
        $_SESSION['posts'][] = [
            'username' => $username,
            'content' => $content,
            'timestamp' => $timestamp
        ];
    }
}

if (isset($_POST['leave_group'])) {
    session_destroy();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Group Page</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Welcome to the Group</h1>

    <div class="rules">
        <h2>Rules and Guidelines</h2>
        <ul>
            <li>Be respectful to others.</li>
            <li>No spamming or offensive content.</li>
            <li>Follow the group purpose and stay on topic.</li>
        </ul>
    </div>

    <?php if (!isset($_SESSION['joined'])): ?>
        <form method="POST">
            <button type="submit" name="join_group">Join Group</button>
        </form>
    <?php else: ?>
        <p><strong>You have joined the group!</strong></p>
        <form method="POST">
            <input type="hidden" name="leave_group">
            <button type="submit">Leave Group</button>
        </form>

        <div class="content">
            <h2>Post Content</h2>
            <form method="POST">
                <label for="username">Your Name:</label><br>
                <input type="text" id="username" name="username" required><br><br>

                <label for="content">Your Post:</label><br>
                <textarea id="content" name="content" rows="4" cols="50" required></textarea><br><br>

                <button type="submit" name="post_content">Post</button>
            </form>
        </div>

        <div class="posts">
            <h2>Group Posts</h2>
            <?php if (!empty($_SESSION['posts'])): ?>
                <?php foreach ($_SESSION['posts'] as $post): ?>
                    <div class="post">
                        <p><strong><?php echo $post['username']; ?></strong> at <?php echo $post['timestamp']; ?></p>
                        <p><?php echo $post['content']; ?></p>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No posts yet. Be the first to post!</p>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</body>
</html>
