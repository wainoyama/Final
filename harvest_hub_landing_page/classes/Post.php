<?php
class Post
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function createPost($message, $photo, $forSale)
    {
        $stmt = $this->db->prepare("INSERT INTO posts (message, photo, for_sale) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", $message, $photo, $forSale);
        return $stmt->execute();
    }

    public function getPosts()
    {
        $result = $this->db->query("SELECT posts.*, 
                                  (SELECT COUNT(*) FROM likes WHERE likes.post_id = posts.id) as like_count,
                                  (SELECT COUNT(*) FROM comments WHERE comments.post_id = posts.id) as comment_count
                                  FROM posts ORDER BY timestamp DESC");

        $posts = [];
        while ($row = $result->fetch_assoc()) {
            $posts[] = $row;
        }
        return $posts;
    }

    public function getPostById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM posts WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function deletePost($id)
    {
        $stmt = $this->db->prepare("DELETE FROM posts WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    public function editPost($id, $message, $photo, $forSale)
    {
        $stmt = $this->db->prepare("UPDATE posts SET message = ?, photo = ?, for_sale = ? WHERE id = ?");
        $stmt->bind_param("ssii", $message, $photo, $forSale, $id);
        return $stmt->execute();
    }

    public function addComment($postId, $content)
    {
        $stmt = $this->db->prepare("INSERT INTO comments (post_id, user_id, content) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $postId, $content);
        return $stmt->execute();
    }

    public function getComments($postId)
    {
        $stmt = $this->db->prepare("SELECT * FROM comments WHERE post_id = ? ORDER BY created_at DESC");
        $stmt->bind_param("i", $postId);
        $stmt->execute();
        $result = $stmt->get_result();

        $comments = [];
        while ($row = $result->fetch_assoc()) {
            $comments[] = $row;
        }
        return $comments;
    }

    public function addLike($postId)
    {
        $stmt = $this->db->prepare("INSERT INTO likes (post_id) VALUES (?)");
        $stmt->bind_param("i", $postId);
        return $stmt->execute();
    }

    public function removeLike($postId)
    {
        $stmt = $this->db->prepare("DELETE FROM likes WHERE post_id = ?");
        $stmt->bind_param("i", $postId);
        return $stmt->execute();
    }

    public function isLiked($postId)
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM likes WHERE post_id = ?");
        $stmt->bind_param("i", $postId);
        $stmt->execute();
        return $stmt->get_result()->fetch_row()[0] > 0;
    }
}
?>