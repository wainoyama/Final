<?php
class Post {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }


    public function createPost($message, $photo, $forSale) {
        $userId = $_SESSION['user_id'];
        $stmt = $this->db->prepare("INSERT INTO posts (message, photo, for_sale, user_id) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssii", $message, $photo, $forSale, $userId);
        
        if ($stmt->execute()) {
            return $this->db->insert_id;
        }
        return false;
    }

    public function getPosts() {
        $result = $this->db->query("SELECT posts.*, 
                                users.name as user_name, 
                                users.photo as user_photo, /* Get the latest photo from users table */
                                (SELECT COUNT(*) FROM likes WHERE likes.post_id = posts.id) as like_count, 
                                (SELECT COUNT(*) FROM comments WHERE comments.post_id = posts.id) as comment_count 
                                FROM posts 
                                LEFT JOIN users ON posts.user_id = users.id 
                                ORDER BY posts.timestamp DESC");
        $posts = [];
        while ($row = $result->fetch_assoc()) {
            $posts[] = $row;
        }
        return $posts;
    }

    public function getPostById($id) {
        $stmt = $this->db->prepare("SELECT posts.*, users.name as user_name, users.photo as user_photo, (SELECT COUNT(*) FROM likes WHERE likes.post_id = posts.id) as like_count, (SELECT COUNT(*) FROM comments WHERE comments.post_id = posts.id) as comment_count FROM posts LEFT JOIN users ON posts.user_id = users.id WHERE posts.id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function deletePost($id) {
        $stmt = $this->db->prepare("DELETE FROM posts WHERE id = ?");
        $stmt->bind_param("i", $id);
        $result = $stmt->execute();
        if ($result) {
            return $this->getPosts();
        }
        return false;
    }

    public function editPost($id, $message, $photo, $forSale) {
        $stmt = $this->db->prepare("UPDATE posts SET message = ?, photo = ?, for_sale = ? WHERE id = ?");
        $stmt->bind_param("ssii", $message, $photo, $forSale, $id);
        return $stmt->execute();
    }

    public function addComment($postId, $content, $userId) {
        $stmt = $this->db->prepare("INSERT INTO comments (post_id, user_id, content) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $postId, $userId, $content);
        return $stmt->execute();
    }

    public function getComments($postId) {
        $stmt = $this->db->prepare("SELECT comments.*, users.name as user_name FROM comments LEFT JOIN users ON comments.user_id = users.id WHERE post_id = ? ORDER BY comments.created_at DESC");
        $stmt->bind_param("i", $postId);
        $stmt->execute();
        $result = $stmt->get_result();
        $comments = [];
        while ($row = $result->fetch_assoc()) {
            $comments[] = $row;
        }
        return $comments;
    }

    public function addLike($postId, $userId) {
        $stmt = $this->db->prepare("INSERT INTO likes (post_id, user_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $postId, $userId);
        return $stmt->execute();
    }

    public function removeLike($postId, $userId) {
        $stmt = $this->db->prepare("DELETE FROM likes WHERE post_id = ? AND user_id = ?");
        $stmt->bind_param("ii", $postId, $userId);
        return $stmt->execute();
    }

    public function isLiked($postId, $userId) {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM likes WHERE post_id = ? AND user_id = ?");
        $stmt->bind_param("ii", $postId, $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_row()[0] > 0;
    }
}
?>