<?php
class Post {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function createPost($message, $photo = null, $forSale = 0, $price = null, $itemDescription = null)
    {
        $sql = "INSERT INTO posts (message, photo, for_sale, price, item_description, user_id, timestamp) 
                VALUES (?, ?, ?, ?, ?, ?, NOW())";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param(
            "ssidsi",
            $message,
            $photo,
            $forSale,
            $price,
            $itemDescription,
            $_SESSION['user_id']
        );
        if ($stmt->execute()) {
            return $stmt->insert_id;
        } else {
            return false;
        }
    }

    public function getPosts() {
        $stmt = $this->conn->prepare("SELECT posts.*, 
                                      users.name as user_name, 
                                      users.photo as user_photo,
                                      (SELECT COUNT(*) FROM likes WHERE likes.post_id = posts.id) as like_count, 
                                      (SELECT COUNT(*) FROM comments WHERE comments.post_id = posts.id) as comment_count 
                                      FROM posts 
                                      LEFT JOIN users ON posts.user_id = users.id 
                                      ORDER BY posts.timestamp DESC");
        $stmt->execute();
        $result = $stmt->get_result();
        $posts = [];
        while ($row = $result->fetch_assoc()) {
            $posts[] = $row;
        }
        return $posts;
    }

    public function getPostById($id) {
        $stmt = $this->conn->prepare("SELECT posts.*, users.name as user_name, users.photo as user_photo, 
                                      (SELECT COUNT(*) FROM likes WHERE likes.post_id = posts.id) as like_count, 
                                      (SELECT COUNT(*) FROM comments WHERE comments.post_id = posts.id) as comment_count 
                                      FROM posts 
                                      LEFT JOIN users ON posts.user_id = users.id 
                                      WHERE posts.id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function deletePost($id) {
        $stmt = $this->conn->prepare("DELETE FROM posts WHERE id = ?");
        $stmt->bind_param("i", $id);
        $result = $stmt->execute();
        if ($result) {
            return $this->getPosts();
        }
        return false;
    }

    public function editPost($id, $message, $photo, $forSale) {
        $stmt = $this->conn->prepare("UPDATE posts SET message = ?, photo = ?, for_sale = ? WHERE id = ?");
        $stmt->bind_param("ssii", $message, $photo, $forSale, $id);
        return $stmt->execute();
    }

    public function addComment($postId, $content, $userId) {
        $stmt = $this->conn->prepare("SELECT user_id FROM posts WHERE id = ?");
        $stmt->bind_param("i", $postId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $postOwnerId = $row['user_id'];
        $stmt->close();

        if (!$postOwnerId) {
            return false;
        }

        $stmt = $this->conn->prepare("INSERT INTO comments (post_id, user_id, content) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $postId, $userId, $content);
        $result = $stmt->execute();
        $stmt->close();

        if ($result) {
            $notificationMessage = (isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'A user') . " commented on your post!";
            notif($postOwnerId, $notificationMessage, $this->conn);
            return true;
        }

        return false;
    }

    public function addLike($postId, $userId) {
        $stmt = $this->conn->prepare("SELECT user_id FROM posts WHERE id = ?");
        $stmt->bind_param("i", $postId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $postOwnerId = $row['user_id'];
        $stmt->close();

        if (!$postOwnerId) {
            return false;
        }

        $stmt = $this->conn->prepare("INSERT INTO likes (post_id, user_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $postId, $userId);
        $result = $stmt->execute();
        $stmt->close();

        if ($result) {
            $notificationMessage = (isset($_SESSION['user_name']) ? $_SESSION['user_name'] : " liked your post!") ;
            notif($postOwnerId, $notificationMessage, $this->conn);
            return true;
        }

        return false;
    }

    public function removeLike($postId, $userId) {
        $stmt = $this->conn->prepare("DELETE FROM likes WHERE post_id = ? AND user_id = ?");
        $stmt->bind_param("ii", $postId, $userId);
        return $stmt->execute();
    }

    public function isLiked($postId, $userId) {
        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM likes WHERE post_id = ? AND user_id = ?");
        $stmt->bind_param("ii", $postId, $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_row()[0] > 0;
    }

    public function getComments($postId) {
        $stmt = $this->conn->prepare("SELECT comments.*, users.name as user_name 
                                      FROM comments 
                                      LEFT JOIN users ON comments.user_id = users.id 
                                      WHERE comments.post_id = ? 
                                      ORDER BY comments.created_at ASC");
        $stmt->bind_param("i", $postId);
        $stmt->execute();
        $result = $stmt->get_result();
        $comments = [];
        while ($row = $result->fetch_assoc()) {
            $comments[] = $row;
        }
        return $comments;
    }
}
?>


