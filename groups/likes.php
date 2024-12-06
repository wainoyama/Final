<?php
require_once 'auth_check.php';
require_once 'group_db.php';

if (!isLoggedIn()) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $post_id = isset($_POST['post_id']) ? (int)$_POST['post_id'] : 0;
    $group_id = isset($_POST['group_id']) ? (int)$_POST['group_id'] : 0;
    $action = isset($_POST['action']) ? $_POST['action'] : '';

    if ($post_id && $group_id && ($action === 'like' || $action === 'unlike')) {
        $user_id = $_SESSION['user_id'];
        
        // Check if user is a member of the group
        if (isGroupMember($group_id, $user_id)) {
            if ($action === 'like') {
                $result = addLike($post_id, $user_id);
            } else {
                $result = removeLike($post_id, $user_id);
            }
            
            if ($result) {
                // Like added or removed successfully
                header("Location: view_post.php?id=$post_id&group_id=$group_id");
                exit();
            } else {
                $error = "Failed to $action post. Please try again.";
            }
        } else {
            $error = "You must be a member of the group to like posts.";
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

function addLike($post_id, $user_id) {
    global $conn;
    
    $post_id = (int)$post_id;
    $user_id = (int)$user_id;
    
    // Check if the user has already liked the post
    $check_query = "SELECT 1 FROM likes WHERE post_id = $post_id AND user_id = $user_id";
    $check_result = mysqli_query($conn, $check_query);
    
    if (mysqli_num_rows($check_result) > 0) {
        // User has already liked the post
        return true;
    }
    
    $query = "INSERT INTO likes (post_id, user_id, created_at) 
              VALUES ($post_id, $user_id, NOW())";
              
    return mysqli_query($conn, $query);
}

function removeLike($post_id, $user_id) {
    global $conn;
    
    $post_id = (int)$post_id;
    $user_id = (int)$user_id;
    
    $query = "DELETE FROM likes WHERE post_id = $post_id AND user_id = $user_id";
    
    return mysqli_query($conn, $query);
}