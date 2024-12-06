<?php
require_once 'auth_check.php';
require_once 'group_db.php';

if (!isLoggedIn()) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $comment_id = isset($_POST['comment_id']) ? (int)$_POST['comment_id'] : 0;
    $group_id = isset($_POST['group_id']) ? (int)$_POST['group_id'] : 0;
    $post_id = isset($_POST['post_id']) ? (int)$_POST['post_id'] : 0;

    if ($comment_id && $group_id && $post_id) {
        $user_id = $_SESSION['user_id'];
        
        // Check if user is a member of the group and has permission to delete the comment
        if (isGroupMember($group_id, $user_id) && (isCommentOwner($comment_id, $user_id) || isGroupAdmin($group_id, $user_id))) {
            if (deleteComment($comment_id)) {
                // Comment deleted successfully
                header("Location: view_post.php?id=$post_id&group_id=$group_id&success=1");
                exit();
            } else {
                $error = "Failed to delete comment. Please try again.";
            }
        } else {
            $error = "You don't have permission to delete this comment.";
        }
    } else {
        $error = "Invalid input. Please try again.";
    }
}

// If we reach here, there was an error. Redirect back to the post with an error message.
$redirect_url = "view_post.php?id=$post_id&group_id=$group_id";
if (isset($error)) {
    $redirect_url .= "&error=" . urlencode($error);
}
header("Location: $redirect_url");
exit();

function deleteComment($comment_id) {
    global $conn;
    
    $comment_id = (int)$comment_id;
    
    $query = "DELETE FROM comments WHERE comment_id = $comment_id";
    
    return mysqli_query($conn, $query);
}

function isCommentOwner($comment_id, $user_id) {
    global $conn;
    
    $comment_id = (int)$comment_id;
    $user_id = (int)$user_id;
    
    $query = "SELECT 1 FROM comments WHERE comment_id = $comment_id AND user_id = $user_id";
    $result = mysqli_query($conn, $query);
    
    return mysqli_num_rows($result) > 0;
}

function isGroupAdmin($group_id, $user_id) {
    return getGroupRole($group_id, $user_id) === 'admin';
}