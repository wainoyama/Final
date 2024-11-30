<?php
require_once 'db.php';
require_once 'auth_check.php';

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

require_once './harvest_hub_landing_page/classes/Post.php';

$postManager = new Post($conn);

$posts = $postManager->getPosts();
usort($posts, function($a, $b) {
    return strtotime($b['timestamp']) - strtotime($a['timestamp']);
});

if (!isset($_SESSION['user_groups'])) {
    $_SESSION['user_groups'] = [];
}

// Modify the $available_groups array to separate cities
$available_groups = [
    'cities' => ['Cavite', 'Batangas', 'Laguna', 'Quezon', 'Rizal'],
    'farming' => ['Farming Tips'],
    'specialty' => ['Organic Farmers Network', 'Crop Exchange Hub']
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die('CSRF token validation failed');
    }

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
                    }
                }
            }
        }

        $newPostId = $postManager->createPost($message, $photo, $for_sale);
        if ($newPostId) {
            $posts = $postManager->getPosts();
            usort($posts, function($a, $b) {
                return strtotime($b['timestamp']) - strtotime($a['timestamp']);
            });
        }
    } elseif (isset($_POST['like'])) {
        $postId = $_POST['post_id'];
        $userId = $_SESSION['user_id'];
        if ($postManager->isLiked($postId, $userId)) {
            $postManager->removeLike($postId, $userId);
        } else {
            $postManager->addLike($postId, $userId);
        }
    } elseif (isset($_POST['comment'])) {
        $postId = $_POST['post_id'];
        $comment = $_POST['comment_text'];
        $userId = $_SESSION['user_id'];
        $postManager->addComment($postId, $comment, $userId);
    }
}

$posts = $postManager->getPosts();
usort($posts, function($a, $b) {
    return strtotime($b['timestamp']) - strtotime($a['timestamp']);
});
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Harvest Hub Community</title>
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="./css/community_additional.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .post {
            margin-bottom: 20px;
            border-bottom: 1px solid #eee;
            padding-bottom: 20px;
        }
        .post:last-child {
            border-bottom: none;
        }
        .posts {
            display: flex;
            flex-direction: column;
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <div class="header-content">
                <div class="logo">
                    <span>Harvest Hub</span>
                </div>
                <nav>
                    <a href="index.php"><i class="fas fa-home"></i> Home</a>
                    <a href="community.php"><i class="fas fa-users"></i> Community</a>
                    <a href="./profile_page/profile.php"><i class="fas fa-user"></i> Profile</a>
                </nav>
                <div class="auth-buttons">
                    <?php if (isLoggedIn()): ?>
                        <a href="logout.php" class="btn-logout">Log Out</a>
                    <?php else: ?>
                        <a href="login.php" class="btn-login">Log In</a>
                        <a href="register.php" class="btn-signup">Sign Up</a>
                    <?php endif; ?>
                </div>
            </div>
        </header>
        
        <div class="community-layout">
            <div class="group-section">
                <h2>Community Groups</h2>

                <div class="group-list">
                    <h3>CALABARZON Cities</h3>
                    <?php foreach ($available_groups['cities'] as $city): ?>
                        <?php echo createGroupButton($city); ?>
                    <?php endforeach; ?>

                    <h3>Farming</h3>
                    <?php foreach ($available_groups['farming'] as $group): ?>
                        <?php echo createGroupButton($group); ?>
                    <?php endforeach; ?>

                    <h3>Specialty Groups</h3>
                    <?php foreach ($available_groups['specialty'] as $group): ?>
                        <?php echo createGroupButton($group); ?>
                    <?php endforeach; ?>
                </div>

                <?php if (!empty($_SESSION['user_groups'])): ?>
                    <div class="group-post-form">
                        <h3>Post to Group</h3>
                        <form method="POST">
                            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                            <input type="text" name="username" placeholder="Your Name" required>
                            <textarea name="content" rows="3" placeholder="Share something with the group..." required></textarea>
                            <select name="group" required>
                                <option value="">Select a group</option>
                                <optgroup label="Cities">
                                    <?php foreach ($available_groups['cities'] as $city): ?>
                                        <?php if (in_array($city, $_SESSION['user_groups'])): ?>
                                            <option value="<?= htmlspecialchars($city) ?>"><?= htmlspecialchars($city) ?></option>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </optgroup>
                                <optgroup label="Farming">
                                    <?php foreach ($available_groups['farming'] as $group): ?>
                                        <?php if (in_array($group, $_SESSION['user_groups'])): ?>
                                            <option value="<?= htmlspecialchars($group) ?>"><?= htmlspecialchars($group) ?></option>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </optgroup>
                                <optgroup label="Specialty">
                                    <?php foreach ($available_groups['specialty'] as $group): ?>
                                        <?php if (in_array($group, $_SESSION['user_groups'])): ?>
                                            <option value="<?= htmlspecialchars($group) ?>"><?= htmlspecialchars($group) ?></option>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </optgroup>
                            </select>
                            <button type="submit" name="post_content">Post to Group</button>
                        </form>
                    </div>

                    <div class="group-posts">
                        <h3>Recent Group Posts</h3>
                        <?php foreach ($_SESSION['user_groups'] as $group): ?>
                            <h4><?= htmlspecialchars($group) ?></h4>
                            <?php if (!empty($_SESSION['group_posts'][$group])): ?>
                                <?php foreach (array_reverse($_SESSION['group_posts'][$group]) as $groupPost): ?>
                                    <div class="post">
                                        <strong><?php echo htmlspecialchars($groupPost['username']); ?></strong>
                                        <small><?php echo date('F j, g:i a', strtotime($groupPost['timestamp'])); ?></small>
                                        <p><?php echo htmlspecialchars($groupPost['content']); ?></p>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p>No posts yet in this group. Be the first to post!</p>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="main-content">
                <div class="upload-post">
                    <form method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                        <textarea name="message" placeholder="What's on your mind?" required></textarea>
                        <div class="upload-actions">
                            <input type="file" name="photo" accept="image/*">
                            <label for="for_sale" class="for-sale-toggle">
                                <input type="checkbox" name="for_sale" id="for_sale">
                                For Sale
                            </label>
                            <button type="submit">Post</button>
                        </div>
                    </form>
                </div>

                <div class="posts">
                    <?php foreach ($posts as $postItem): ?>
                        <div class="post">
                            <div class="post-header">
                                <div class="user-info">
                                    <img src="<?= !empty($postItem['user_photo']) ? htmlspecialchars($postItem['user_photo']) : './harvest_hub_landing_page/assets/default-avatar.png' ?>" 
                                        alt="User avatar" 
                                        class="avatar">
                                    <div>
                                        <h3><?= htmlspecialchars($postItem['user_name'] ?? 'Anonymous') ?></h3>
                                        <small><?= date('F j \a\t g:i a', strtotime($postItem['timestamp'])) ?> · <i class="fas fa-globe"></i></small>
                                    </div>
                                </div>
                                <div class="post-actions">
                                    <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $postItem['user_id']): ?>
                                        <a href="./harvest_hub_landing_page/edit_post.php?id=<?= $postItem['id'] ?>">Edit</a>
                                        <a href="./harvest_hub_landing_page/delete_post.php?id=<?= $postItem['id'] ?>">Delete</a>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="post-content">
                                <p><?= htmlspecialchars($postItem['message']) ?></p>
                                <?php if ($postItem['photo']): ?>
                                    <img src="<?= htmlspecialchars($postItem['photo']) ?>" alt="Post image" class="post-image">
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

                            <?php if ($postItem['for_sale']): ?>
                                <div class="buy-section">
                                    <button class="buy-btn">Buy Now</button>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
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

<?php
function createGroupButton($group) {
    $isJoined = in_array($group, $_SESSION['user_groups']);
    $buttonText = $isJoined ? 'Leave' : 'Join';
    $buttonClass = $isJoined ? 'leave-group' : 'join-group';
    $formAction = $isJoined ? 'leave_group' : 'join_group';

    return "
    <form method='POST' class='group-button-form'>
        <input type='hidden' name='csrf_token' value='{$_SESSION['csrf_token']}'>
        <input type='hidden' name='{$formAction}'>
        <input type='hidden' name='group' value='" . htmlspecialchars($group) . "'>
        <button type='submit' class='{$buttonClass}'>{$buttonText} {$group}</button>
    </form>
    ";
}
?>

