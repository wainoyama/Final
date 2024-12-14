<?php
session_start();
require_once '../db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login_register/login.php");
    exit();
}

// Fetch user's name from the database
$stmt = $conn->prepare("SELECT name FROM users WHERE id = ?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$user_name = $result->fetch_assoc()['name'];

// Handle post submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['content'])) {
    $content = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_STRING);
    
    if (!empty($content)) {
        $stmt = $conn->prepare("INSERT INTO group_posts (user_id, content) VALUES (?, ?)");
        $stmt->bind_param("is", $_SESSION['user_id'], $content);
        $stmt->execute();
    }
}

// Fetch group posts
$stmt = $conn->prepare("
    SELECT gp.*, u.name as user_name, 
    (SELECT COUNT(*) FROM group_post_likes WHERE post_id = gp.id) as like_count,
    (SELECT COUNT(*) FROM group_post_comments WHERE post_id = gp.id) as comment_count
    FROM group_posts gp
    JOIN users u ON gp.user_id = u.id
    ORDER BY gp.created_at DESC
");
$stmt->execute();
$result = $stmt->get_result();
$posts = $result->fetch_all(MYSQLI_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calabarzon Harvest Hub Group Page</title>
    <link rel="stylesheet" href="../css/groups.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <header>
        <div class="header-content">
            <div class="logo">
                <span>Calabarzon Harvest Hub</span>
            </div>
            <nav>
                <a href="../index.php"><i class="fas fa-home"></i> Home</a>
                <a href="community.php"><i class="fas fa-users"></i> Community</a>
                <a href="../profile_page/profile.php"><i class="fas fa-user"></i> Profile</a>
            </nav>
            <div class="auth-buttons">
                <a href="../login_register/logout.php" class="btn-logout">Log Out</a>
            </div>
        </div>
    </header>

    <div class="container">
        <h1>Welcome to the Calabarzon Harvest Hub Group Page</h1>

        <div class="content">
            <h2>Create a Post</h2>
            <form method="POST" id="post-form">
                <textarea name="content" required maxlength="500" placeholder="What's on your mind?"></textarea>
                <button type="submit">Post</button>
            </form>

            <h2>Group Posts</h2>
            <div id="posts-container">
                <?php foreach ($posts as $post): ?>
                    <div class="post" data-post-id="<?php echo $post['id']; ?>">
                        <p><strong><?php echo htmlspecialchars($post['user_name']); ?></strong> at <?php echo $post['created_at']; ?></p>
                        <p><?php echo htmlspecialchars($post['content']); ?></p>
                        <div class="post-actions">
                            <button class="like-btn" data-post-id="<?php echo $post['id']; ?>">
                                Like (<span class="like-count"><?php echo $post['like_count']; ?></span>)
                            </button>
                            <button class="comment-btn" data-post-id="<?php echo $post['id']; ?>">
                                Comment (<span class="comment-count"><?php echo $post['comment_count']; ?></span>)
                            </button>
                            <?php if ($post['user_id'] == $_SESSION['user_id']): ?>
                                <button class="delete-btn" data-post-id="<?php echo $post['id']; ?>">Delete</button>
                            <?php endif; ?>
                        </div>
                        <div class="comments-container" style="display: none;"></div>
                        <form class="comment-form" style="display: none;">
                            <input type="text" name="comment" placeholder="Write a comment..." required>
                            <button type="submit">Submit</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <footer>
        <p>&copy; 2023 Calabarzon Harvest Hub. All rights reserved.</p>
    </footer>

    <script>
    $(document).ready(function() {
        // Like button functionality
        $('.like-btn').click(function() {
            var postId = $(this).data('post-id');
            var button = $(this);
            
            $.post('group_post_likes.php', {post_id: postId}, function(response) {
                if (response.success) {
                    button.find('.like-count').text(response.likeCount);
                    if (response.action === 'liked') {
                        button.addClass('liked');
                        Swal.fire({
                            icon: 'success',
                            title: 'Liked!',
                            text: 'You liked this post.',
                            timer: 1500,
                            showConfirmButton: false
                        });
                    } else {
                        button.removeClass('liked');
                        Swal.fire({
                            icon: 'info',
                            title: 'Unliked',
                            text: 'You unliked this post.',
                            timer: 1500,
                            showConfirmButton: false
                        });
                    }
                }
            }, 'json');
        });

        // Comment button functionality
        $('.comment-btn').click(function() {
            var postId = $(this).data('post-id');
            var commentsContainer = $(this).closest('.post').find('.comments-container');
            var commentForm = $(this).closest('.post').find('.comment-form');
            
            commentsContainer.toggle();
            commentForm.toggle();

            if (commentsContainer.is(':visible') && commentsContainer.is(':empty')) {
                $.get('group_post_comments.php', {post_id: postId}, function(response) {
                    if (response.success) {
                        commentsContainer.html(response.comments);
                    }
                }, 'json');
            }
        });

        // Submit comment functionality
        $('.comment-form').submit(function(e) {
            e.preventDefault();
            var form = $(this);
            var postId = form.closest('.post').data('post-id');
            var content = form.find('input[name="comment"]').val();
            
            $.post('group_post_comments.php', {post_id: postId, content: content}, function(response) {
                if (response.success) {
                    var newComment = '<div class="comment"><strong>' + response.userName + ':</strong> ' + response.content + '</div>';
                    form.closest('.post').find('.comments-container').append(newComment);
                    form.find('input[name="comment"]').val('');
                    form.closest('.post').find('.comment-count').text(response.commentCount);
                    Swal.fire({
                        icon: 'success',
                        title: 'Comment Added',
                        text: 'Your comment has been posted successfully.',
                        timer: 1500,
                        showConfirmButton: false
                    });
                }
            }, 'json');
        });

        // Delete post functionality
        $('.delete-btn').click(function() {
            var postId = $(this).data('post-id');
            var postElement = $('[data-post-id="' + postId + '"]');
            
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post('delete_group_post.php', {post_id: postId}, function(response) {
                        if (response.success) {
                            postElement.remove();
                            Swal.fire(
                                'Deleted!',
                                'Your post has been deleted.',
                                'success'
                            );
                        } else {
                            Swal.fire(
                                'Error!',
                                'There was a problem deleting your post.',
                                'error'
                            );
                        }
                    }, 'json');
                }
            });
        });
    });
    </script>
</body>
</html>

