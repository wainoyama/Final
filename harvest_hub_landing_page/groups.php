<?php
session_start();

if (!isset($_SESSION['posts'])) {
    $_SESSION['posts'] = [];
}

if (isset($_POST['join_group'])) {
    session_regenerate_id(true); 
    $_SESSION['joined'] = true;
    $feedback = "You have joined the group!";
}

if (isset($_POST['post_content']) && $_SESSION['joined']) {
    $username = htmlspecialchars(trim($_POST['username']));
    $content = htmlspecialchars(trim($_POST['content']));
    $timestamp = date("Y-m-d H:i:s");

   
    if (!empty($username) && !empty($content) && strlen($username) <= 50 && strlen($content) <= 500) {
        $_SESSION['posts'][] = [
            'username' => $username,
            'content' => $content,
            'timestamp' => $timestamp
        ];
        $feedback = "Post submitted successfully!";
    } else {
        $feedback = "Please enter valid username and content.";
    }
}

if (isset($_POST['leave_group'])) {
    unset($_SESSION['joined']); // Just unset joined variable
    $feedback = "You have left the group.";
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
    <h1>Welcome to the Harvest Hub Group Page</h1>

    <div class="rules">
        <h2>Rules and Guidelines</h2>
        <ul>
            <li>Be respectful to others.</li>
            <li>No spamming or offensive content.</li>
            <li>Follow the group purpose and stay on topic.</li>
        </ul>
    </div>

    
    <?php if (isset($feedback) && $feedback !== "You have joined the group!"): ?>
        <p><strong><?php echo $feedback; ?></strong></p>
    <?php endif; ?>

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
                <input type="text" id="username" name="username" required maxlength="50"><br><br>

                <label for="content">Your Post:</label><br>
                <textarea id="content" name="content" rows="4" cols="50" required maxlength="500"></textarea><br><br>

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