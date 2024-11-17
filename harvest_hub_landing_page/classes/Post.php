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
        $stmt->execute([$message, $photo, $forSale]);
    }

    public function getPosts()
    {
        $stmt = $this->db->query("SELECT posts.*, 
                                  (SELECT COUNT(*) FROM likes WHERE likes.post_id = posts.id) as like_count,
                                  (SELECT COUNT(*) FROM comments WHERE comments.post_id = posts.id) as comment_count
                                  FROM posts ORDER BY timestamp DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPostById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM posts WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function deletePost($id)
    {
        $stmt = $this->db->prepare("DELETE FROM posts WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function editPost($id, $message, $photo, $forSale)
    {
        $stmt = $this->db->prepare("UPDATE posts SET message = ?, photo = ?, for_sale = ? WHERE id = ?");
        return $stmt->execute([$message, $photo, $forSale, $id]);
    }

    public function addComment($postId, $message, $author = 'Anonymous')
    {
        $stmt = $this->db->prepare("INSERT INTO comments (post_id, message, author, timestamp) VALUES (?, ?, ?, NOW())");
        return $stmt->execute([$postId, $message, $author]);
    }

    public function getComments($postId)
    {
        $stmt = $this->db->prepare("SELECT * FROM comments WHERE post_id = ? ORDER BY timestamp ASC");
        $stmt->execute([$postId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addLike($postId)
    {
        $stmt = $this->db->prepare("INSERT INTO likes (post_id, timestamp) VALUES (?, NOW())");
        return $stmt->execute([$postId]);
    }

    public function removeLike($postId)
    {
        $stmt = $this->db->prepare("DELETE FROM likes WHERE post_id = ?");
        return $stmt->execute([$postId]);
    }

    public function isLiked($postId)
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM likes WHERE post_id = ?");
        $stmt->execute([$postId]);
        return $stmt->fetchColumn() > 0;
    }
}
?>