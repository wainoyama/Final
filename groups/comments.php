<?php
require_once 'auth_check.php';
require_once 'group_db.php';

if (!isLoggedIn()) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $post_id = isset($_POST['post_id']) ? (int)$_POST['post_id'] : 0;
    $comment_content = isset($_POST['comment_content']) ? trim($_POST['comment_content']) : '';
    $group_id = isset($_POST['group_id']) ? (int)$_POST['group_id'] : 0;

    if ($post_id && $comment_content && $group_id) {
        $user_id = $_SESSION['user_id'];
        
        // Check if user is a member of the group
        if (isGroupMember($group_id, $user_id)) {
            $comment_id = addComment($post_id, $user_id, $comment_content);
            if ($comment_id) {
                // Comment added successfully
                header("Location: view_post.php?id=$post_id&group_id=$group_id");
                exit();
            } else {
                $error = "Failed to add comment. Please try again.";
            }
        } else {
            $error = "You must be a member of the group to comment.";
        }
    } else {
        $error = "Invalid input. Please fill in all required fields.";
    }
}

// If we reach here, there was an error. Redirect back to the post with an error message.
$redirect_url = "view_post.php?id=$post_id&group_id=$group_id";
if (isset($error)) {
    $redirect_url .= "&error=" . urlencode($error);
}
header("Location: $redirect_url");
exit();

function addComment($post_id, $user_id, $content) {
    global $conn;
    
    $post_id = (int)$post_id;
    $user_id = (int)$user_id;
    $content = mysqli_real_escape_string($conn, $content);
    
    $query = "INSERT INTO comments (post_id, user_id, content, created_at) 
              VALUES ($post_id, $user_id, '$content', NOW())";
              
    if (mysqli_query($conn, $query)) {
        return mysqli_insert_id($conn);
    }
    
    return false;
}