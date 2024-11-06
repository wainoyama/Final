<?php
session_start();

if (!isset($_SESSION['posts'])) {
    $_SESSION['posts'] = [];
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['postContent'])) {
        $newPost = htmlspecialchars(trim($_POST['postContent']));
        $isForSale = isset($_POST['isForSale']) ? true : false; 
        $imageName = '';

        if (isset($_FILES['postImage']) && $_FILES['postImage']['error'] === UPLOAD_ERR_OK) {
            $imageName = basename($_FILES['postImage']['name']);
            $targetDir = 'uploads/';
            $targetFile = $targetDir . $imageName;
            move_uploaded_file($_FILES['postImage']['tmp_name'], $targetFile);
        }

        if (!empty($newPost)) {
            $_SESSION['posts'][] = [
                'content' => $newPost,
                'image' => $imageName,
                'timestamp' => date("Y-m-d H:i:s"),
                'likes' => 0,
                'isForSale' => $isForSale,
                'comments' => []
            ];
        }
    } elseif (isset($_POST['delete'])) {
        $postId = $_POST['delete'];
        unset($_SESSION['posts'][$postId]);
    } elseif (isset($_POST['edit'])) {
        $postId = $_POST['edit'];
        $editContent = htmlspecialchars(trim($_POST['editContent']));
        $_SESSION['posts'][$postId]['content'] = $editContent;
    } elseif (isset($_POST['comment'])) {
        $postId = $_POST['comment'];
        $comment = htmlspecialchars(trim($_POST['commentContent']));
        if (!empty($comment)) {
            $_SESSION['posts'][$postId]['comments'][] = $comment;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Community Page</title>
    <link rel="stylesheet" href="communitypage.css">
</head>
<body>

<div class="container">
    <h1>Harvest Hub</h1>
    <button class="post-btn" onclick="document.getElementById('createPostForm').style.display='block'">Create Post</button>

    <div id="createPostForm" style="display:none;">
        <h2>Create a New Post</h2>
        <form method="post" enctype="multipart/form-data">
            <textarea name="postContent" placeholder="What's on your mind?" required></textarea><br>
            <input type="file" name="postImage" accept="image/*"><br>
            <label>
                <input type="checkbox" name="isForSale"> For Sale
            </label><br>
            <button type="submit" class="post-btn">Post</button>
            <button type="button" onclick="document.getElementById('createPostForm').style.display='none'">Cancel</button>
        </form>
    </div>

    <h2>News Feed:</h2>
    <?php if (!empty($_SESSION['posts'])): ?>
        <?php foreach (array_reverse($_SESSION['posts'], true) as $id => $post): ?>
            <div class="post">
                <div class="post-content"><?php echo nl2br($post['content']); ?></div>
                <?php if ($post['image']): ?>
                    <img src="uploads/<?php echo $post['image']; ?>" alt="Post Image" style="max-width: 100%; height: auto;">
                <?php endif; ?>
                <div class="post-meta">
                    <span><?php echo date("F j, Y, g:i a", strtotime($post['timestamp'])); ?></span>
                    <form method="post" style="display:inline;">
                        <button type="submit" name="delete" value="<?php echo $id; ?>" class="delete-btn">Delete</button>
                        <button type="button" onclick="document.getElementById('editForm<?php echo $id; ?>').style.display='block'">Edit</button>
                    </form>
                </div>
                <div id="editForm<?php echo $id; ?>" style="display:none;">
                    <form method="post">
                        <textarea name="editContent" required><?php echo $post['content']; ?></textarea><br>
                        <button type="submit" name="edit" value="<?php echo $id; ?>">Update</button>
                        <button type="button" onclick="document.getElementById('editForm<?php echo $id; ?>').style.display='none'">Cancel</button>
                    </form>
                </div>
                <div class="comments-section">
                    <h4>Comments:</h4>
                    <form method="post">
                        <textarea name="commentContent" placeholder="Add a comment..." required></textarea><br>
                        <button type="submit" name="comment" value="<?php echo $id; ?>">Comment</button>
                    </form>
                    <?php if (!empty($post['comments'])): ?>
                        <ul>
                            <?php foreach ($post['comments'] as $comment): ?>
                                <li><?php echo nl2br($comment); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No posts yet! Start by creating a new post.</p>
    <?php endif; ?>
</div>

</body>
</html>
