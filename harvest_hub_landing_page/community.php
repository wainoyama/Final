<?php
require_once '../db.php';
require_once '../auth_check.php';
require_once './classes/Post.php';
require_once '../notifications/notif.php';
require_once '../notifications/db_notif.php';
require_once 'upload_handler.php';

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$postManager = new Post($conn);

$posts = $postManager->getPosts();
usort($posts, function ($a, $b) {
    return strtotime($b['timestamp']) - strtotime($a['timestamp']);
});

if (!isset($_SESSION['user_groups'])) {
    $_SESSION['user_groups'] = [];
}

$notifications = unreadNotif($_SESSION['user_id'], $conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die('CSRF token validation failed');
    }

    if (isset($_POST['message'])) {
        $message = $_POST['message'];
        $for_sale = isset($_POST['for_sale']) ? 1 : 0;
        $price = isset($_POST['price']) ? floatval($_POST['price']) : null;
        $item_description = isset($_POST['item_description']) ? $_POST['item_description'] : null;
        $photo = null;

        if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
            $photoUrl = uploadPhoto($_FILES['photo']);
            if (strpos($photoUrl, 'Error') === false) {
                $photo = $photoUrl;
            } else {
                $_SESSION['error_message'] = $photoUrl;
            }
        }

        if (isset($_SESSION['new_profile_picture'])) {
            $postManager = new Post($conn);
            $postManager->updateUserPhotoInPosts($_SESSION['user_id'], $_SESSION['new_profile_picture']);
            unset($_SESSION['new_profile_picture']);
        }

        $newPostId = $postManager->createPost($message, $photo, $for_sale, $price, $item_description);

        if ($newPostId) {
            $notificationMessage = "created a new post";
            $allUsersSql = "SELECT id FROM users WHERE id != ?";
            $allUsersStmt = $conn->prepare($allUsersSql);
            $allUsersStmt->bind_param("i", $_SESSION['user_id']);
            $allUsersStmt->execute();
            $allUsersResult = $allUsersStmt->get_result();
            while ($user = $allUsersResult->fetch_assoc()) {
                notif($user['id'], $notificationMessage, $conn);
            }

            $posts = $postManager->getPosts();
            usort($posts, function ($a, $b) {
                return strtotime($b['timestamp']) - strtotime($a['timestamp']);
            });
        }
    } elseif (isset($_POST['like'])) {
        $postId = $_POST['post_id'];
        $userId = $_SESSION['user_id'];
        if ($postManager->isLiked($postId, $userId)) {
            $result = $postManager->removeLike($postId, $userId);
        } else {
            $result = $postManager->addLike($postId, $userId);
        }
        if ($result === false) {
            $_SESSION['error_message'] = "Unable to process like. The post may have been deleted.";
        }
        header("Location: community.php");
        exit();
    } elseif (isset($_POST['comment'])) {
        $postId = $_POST['post_id'];
        $comment = $_POST['comment_text'];
        $userId = $_SESSION['user_id'];
        $postManager->addComment($postId, $comment, $userId);
    }
}

$posts = $postManager->getPosts();
usort($posts, function ($a, $b) {
    return strtotime($b['timestamp']) - strtotime($a['timestamp']);
});

$notifications = unreadNotif($_SESSION['user_id'], $conn);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Harvest Hub Community</title>
    <link rel="stylesheet" href="../css/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <?php
    if (isset($_SESSION['error_message'])) {
        echo '<div class="error-message">' . htmlspecialchars($_SESSION['error_message']) . '</div>';
        unset($_SESSION['error_message']);
    }
    ?>
    <div class="container">
        <header>
            <div class="header-content">
                <div class="logo">
                    <span>Harvest Hub</span>
                </div>
                <nav>
                    <a href="../index.php"><i class="fas fa-home"></i> Home</a>
                    <a href="community.php"><i class="fas fa-users"></i> Community</a>
                    <a href="../profile_page/profile.php"><i class="fas fa-user"></i> Profile</a>
                </nav>
                <div class="auth-buttons">
                    <?php if (isLoggedIn()): ?>
                        <a href="../login_register/logout.php" class="btn-logout">Log Out</a>
                    <?php else: ?>
                        <a href="./login.php" class="btn-login">Log In</a>
                        <a href="register.php" class="btn-signup">Sign Up</a>
                    <?php endif; ?>
                </div>
            </div>
        </header>

        <div class="community-layout">
            <div class="left-side">
                <div class="search-bar">
                    <form action="search_user.php" method="GET">
                        <input type="text" name="search" id="search-input" placeholder="Search users..." />
                        <button type="submit"><i class="fas fa-search"></i></button>
                    </form>
                    <div id="search-results"></div>
                </div>
                <div class="notifs">
                    <h2>Notifications</h2>
                    <?php if (!empty($notifications)): ?>
                        <ul>
                            <?php foreach ($notifications as $notif): ?>
                                <li class="notification-item" data-notification-id="<?= $notif['id'] ?>">
                                    <span><?= htmlspecialchars($notif['message']) ?></span>
                                    <small><?= date('F j, Y g:i a', strtotime($notif['created_at'])) ?></small>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <p>No notifications yet.</p>
                    <?php endif; ?>
                </div>
            </div>
            <div class="main-content">
                <div class="upload-post">
                    <form method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                        <textarea name="message" placeholder="What's on your mind?" required></textarea>
                        <div class="upload-actions">
                            <input type="file" name="photo" accept="image/*">
                            <label for="for_sale" class="for-sale-toggle">
                                <input type="checkbox" name="for_sale" id="for_sale" onchange="toggleForSaleFields()"> For Sale
                            </label>
                            <div id="for_sale_fields" style="display: none;">
                                <input type="number" step="0.01" name="price" placeholder="Price" id="price">
                                <textarea name="item_description" placeholder="Item description" id="item_description"></textarea>
                            </div>
                            <button type="submit">Post</button>
                        </div>
                    </form>
                </div>
                <div class="posts" id="posts">
                    <?php foreach ($posts as $postItem): ?>
                        <div class="post">
                            <div class="post-header">
                                <div class="user-info">
                                    <img src="<?= !empty($postItem['user_photo']) ? htmlspecialchars($postItem['user_photo']) : 'uploads/default-avatar.png' ?>"
                                        alt="User avatar"
                                        class="avatar">
                                    <div>
                                        <h3><?= htmlspecialchars($postItem['user_name'] ?? 'Anonymous') ?></h3>
                                        <small><?= date('F j \a\t g:i a', strtotime($postItem['timestamp'])) ?> · <i class="fas fa-globe"></i></small>
                                    </div>
                                </div>
                                <div class="post-actions">
                                    <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $postItem['user_id']): ?>
                                        <a href="edit_post.php?id=<?= $postItem['id'] ?>">Edit</a>
                                        <a href="delete_post.php?id=<?= $postItem['id'] ?>">Delete</a>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="post-content">
                                <p><?= htmlspecialchars($postItem['message']) ?></p>
                                <?php if ($postItem['photo']): ?>
                                    <img src="<?= htmlspecialchars($postItem['photo']) ?>" alt="Post image" class="post-image">
                                <?php endif; ?>
                                <?php if ($postItem['for_sale']): ?>
                                    <div class="for-sale-info">
                                        <p><strong>Price:</strong> Php<?= number_format($postItem['price'], 2) ?></p>
                                        <p><strong>Description:</strong> <?= htmlspecialchars($postItem['item_description']) ?></p>
                                        <p><strong>Status:</strong> <?= ucfirst($postItem['order_status']) ?></p>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="post-interactions">
                                <form method="POST" class="like-form">
                                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                    <input type="hidden" name="post_id" value="<?= $postItem['id'] ?>">
                                    <button type="submit" name="like" class="like-btn <?= $postManager->isLiked($postItem['id'], $_SESSION['user_id']) ? 'liked' : '' ?>">
                                        <i class="far fa-thumbs-up"></i> Like (<?= $postItem['like_count'] ?>)
                                    </button>
                                </form>
                                <button class="comment-btn" onclick="toggleCommentForm(<?= $postItem['id'] ?>)">
                                    <i class="far fa-comment"></i> Comment (<?= $postItem['comment_count'] ?>)
                                </button>
                            </div>

                            <div id="comment-form-<?= $postItem['id'] ?>" class="comment-form" style="display: none;">
                                <form method="POST">
                                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                    <input type="hidden" name="post_id" value="<?= $postItem['id'] ?>">
                                    <input type="text" name="comment_text" placeholder="Write a comment..." required>
                                    <button type="submit" name="comment">Post Comment</button>
                                </form>
                            </div>

                            <div class="comments">
                                <?php
                                $comments = $postManager->getComments($postItem['id']);
                                foreach ($comments as $comment):
                                ?>
                                    <div class="comment">
                                        <strong><?= htmlspecialchars($comment['user_name']) ?>:</strong>
                                        <?= htmlspecialchars($comment['content']) ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>

                            <?php if ($postItem['for_sale'] && $postItem['order_status'] === 'pending' && $postItem['user_id'] != $_SESSION['user_id']): ?>
                                <div class="buy-section">
                                    <form method="POST" action="../order_manager/order_product.php">
                                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                        <input type="hidden" name="post_id" value="<?= $postItem['id'] ?>">
                                        <button type="submit" class="buy-btn" onclick="return confirmPurchase(<?= $postItem['id'] ?>)">Buy Now</button>
                                    </form>
                                </div>
                            <?php elseif ($postItem['for_sale'] && $postItem['order_status'] === 'sold'): ?>
                                <div class="buy-section">
                                    <button class="buy-btn" disabled>Sold</button>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="group-section">
                <h2>Community Groups</h2>
                <div class="harvest-group-container">
                    <h2>Harvest Group</h2>
                    <p>View the groups you are connected to know updates from your group!</p>
                    <a href="groups.php" class="btn-open-groups">Open groups</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        <?php if (isset($_SESSION['success_message'])): ?>
            Swal.fire({
                title: 'Success!',
                text: '<?php echo $_SESSION['success_message']; ?>',
                icon: 'success',
                confirmButtonText: 'OK'
            });
            <?php unset($_SESSION['success_message']); ?>
        <?php endif; ?>

    $(document).ready(function() {
        $('#search-input').on('input', function() {
            var searchTerm = $(this).val();
            if (searchTerm.length >= 3) {
                $.ajax({
                    url: 'search_user.php',
                    method: 'GET',
                    data: { search: searchTerm },
                    success: function(response) {
                        $('#search-results').html(response);
                    }
                });
            } else {
                $('#search-results').empty();
            }
        });
    });

    function toggleCommentForm(postId) {
        var form = document.getElementById('comment-form-' + postId);
        form.style.display = form.style.display === 'none' ? 'block' : 'none';
    }

    function toggleForSaleFields() {
        const forSaleFields = document.getElementById('for-sale_fields');
        const forSaleCheckbox = document.getElementById('for_sale');
        forSaleFields.style.display = forSaleCheckbox.checked ? 'block' : 'none';
    }

    function refreshPosts() {
        var xhr = new XMLHttpRequest();
        xhr.open('GET', 'fetch_posts.php', true);
        xhr.onload = function() {
            if (xhr.status == 200) {
                var response = JSON.parse(xhr.responseText);
                if (response.success) {
                    document.getElementById('posts').innerHTML = response.html;
                    if (response.message) {
                        Swal.fire({
                            title: 'Success!',
                            text: response.message,
                            icon: 'success',
                            confirmButtonText: 'OK'
                        });
                    }
                } else {
                    Swal.fire({
                        title: 'Error!',
                        text: 'Error refreshing posts: ' + response.message,
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            }
        };
        xhr.send();
    }

    setInterval(refreshPosts, 5000);

    function confirmPurchase(postId) {
        return new Promise((resolve) => {
            Swal.fire({
                title: 'Confirm Purchase',
                text: 'Are you sure you want to buy this item?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes, buy it!',
                cancelButtonText: 'No, cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.querySelector(`form[action="../order_manager/order_product.php"] input[name="post_id"][value="${postId}"]`)
                        .closest('form')
                        .submit();
                }
                resolve(result.isConfirmed);
            });
        });
    }

    function markNotificationAsRead(notificationId) {
        fetch('mark_notification_read.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'notification_id=' + notificationId
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.querySelector(`[data-notification-id="${notificationId}"]`).remove();
                Swal.fire({
                    title: 'Success!',
                    text: 'Notification marked as read',
                    icon: 'success',
                    confirmButtonText: 'OK'
                });
            } else {
                Swal.fire({
                    title: 'Error!',
                    text: 'Failed to mark notification as read',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                title: 'Error!',
                text: 'An unexpected error occurred',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        });
    }

    document.querySelectorAll('.notification-item').forEach(item => {
        item.addEventListener('click', function() {
            const notificationId = this.getAttribute('data-notification-id');
            markNotificationAsRead(notificationId);
        });
    });
    </script>
</body>

</html>

