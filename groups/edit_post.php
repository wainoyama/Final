<?php
require_once 'auth_check.php';
require_once 'group_db.php';

if (!isLoggedIn()) {
    header('Location: login.php');
    exit();
}

$post_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$group_id = isset($_GET['group_id']) ? (int)$_GET['group_id'] : 0;

if (!$post_id || !$group_id) {
    header('Location: group.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Check if user is a member of the group and has permission to edit the post
if (!isGroupMember($group_id, $user_id) || (!isPostOwner($post_id, $user_id) && !isGroupAdmin($group_id, $user_id))) {
    header("Location: view_post.php?id=$post_id&group_id=$group_id&error=" . urlencode("You don't have permission to edit this post."));
    exit();
}

$post = getPostById($post_id);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = isset($_POST['title']) ? trim($_POST['title']) : '';
    $content = isset($_POST['content']) ? trim($_POST['content']) : '';

    if ($title && $content) {
        if (updatePost($post_id, $title, $content)) {
            header("Location: view_post.php?id=$post_id&group_id=$group_id&success=1");
            exit();
        } else {
            $error = "Failed to update post. Please try again.";
        }
    } else {
        $error = "Please fill in all required fields.";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Post - Harvest Hub</title>
    <link rel="stylesheet" href="./css/styles.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <!-- Header Section -->
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
                    <a href="logout.php" class="btn-logout">Log Out</a>
                </div>
            </div>
        </header>

        <div class="content">
            <h1>Edit Post</h1>
            <?php if (isset($error)): ?>
                <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            <form action="edit_post.php?id=<?php echo $post_id; ?>&group_id=<?php echo $group_id; ?>" method="POST" class="post-form">
                <div class="form-group">
                    <label for="title">Title:</label>
                    <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($post['title']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="content">Content:</label>
                    <textarea id="content" name="content" rows="6" required><?php echo htmlspecialchars($post['content']); ?></textarea>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn">Update Post</button>
                    <a href="view_post.php?id=<?php echo $post_id; ?>&group_id=<?php echo $group_id; ?>" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>

        <!-- Footer -->
        <footer>
            <div class="footer-content">
                <div class="footer-section">
                    <h3>About Harvest Hub</h3>
                    <p>Connecting farmers and consumers for a sustainable future.</p>
                </div>
                <div class="footer-section">
                    <h3>Quick Links</h3>
                    <ul>
                        <li><a href="about.php">About Us</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h3>Connect With Us</h3>
                    <div class="social-icons">
                        <a href="#"><i class="fab fa-facebook"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-linkedin"></i></a>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2024 Harvest Hub. All rights reserved.</p>
            </div>
        </footer>
    </div>
</body>
</html>

<?php
function getPostById($post_id) {
    global $conn;
    
    $post_id = (int)$post_id;
    
    $query = "SELECT * FROM posts WHERE post_id = $post_id";
    $result = mysqli_query($conn, $query);
    
    return mysqli_fetch_assoc($result);
}

function updatePost($post_id, $title, $content) {
    global $conn;
    
    $post_id = (int)$post_id;
    $title = mysqli_real_escape_string($conn, $title);
    $content = mysqli_real_escape_string($conn, $content);
    
    $query = "UPDATE posts SET title = '$title', content = '$content', updated_at = NOW() WHERE post_id = $post_id";
    
    return mysqli_query($conn, $query);
}

function isPostOwner($post_id, $user_id) {
    global $conn;
    
    $post_id = (int)$post_id;
    $user_id = (int)$user_id;
    
    $query = "SELECT 1 FROM posts WHERE post_id = $post_id AND user_id = $user_id";
    $result = mysqli_query($conn, $query);
    
    return mysqli_num_rows($result) > 0;
}