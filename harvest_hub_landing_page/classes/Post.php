<?php

class Post
{
    private $filePath = 'posts.json';

    public function __construct()
    {
        // Initialize posts file if it doesn't exist
        if (!file_exists($this->filePath)) {
            file_put_contents($this->filePath, json_encode([]));
        }
    }

    // Create a new post
    public function createPost($message, $photo, $forSale)
    {
        $posts = $this->getPosts();
        $newPost = [
            'id' => time(),
            'message' => $message,
            'photo' => $photo,
            'for_sale' => $forSale,
            'timestamp' => time(),
            'comments' => []
        ];
        $posts[] = $newPost;
        file_put_contents($this->filePath, json_encode($posts));
    }

    // Get all posts
    public function getPosts()
    {
        return json_decode(file_get_contents($this->filePath), true);
    }

    // Get post by ID
    public function getPostById($id)
    {
        $posts = $this->getPosts();
        foreach ($posts as $post) {
            if ($post['id'] == $id) {
                return $post;
            }
        }
        return null;
    }

    // Delete post by ID
    public function deletePost($id)
    {
        $posts = $this->getPosts();
        foreach ($posts as $key => $post) {
            if ($post['id'] == $id) {
                unset($posts[$key]);
                file_put_contents($this->filePath, json_encode(array_values($posts)));
                return true;
            }
        }
        return false;
    }

    // Edit a post
    public function editPost($id, $message, $photo, $forSale)
    {
        $posts = $this->getPosts();
        foreach ($posts as &$post) {
            if ($post['id'] == $id) {
                $post['message'] = $message;
                $post['photo'] = $photo;
                $post['for_sale'] = $forSale;
                file_put_contents($this->filePath, json_encode($posts));
                return true;
            }
        }
        return false;
    }

    // Add a comment to a post
    public function addComment($postId, $message, $author = 'Anonymous')
    {
        $posts = $this->getPosts();
        foreach ($posts as &$post) {
            if ($post['id'] == $postId) {
                $post['comments'][] = [
                    'message' => $message,
                    'author' => $author,
                    'timestamp' => time()
                ];
                file_put_contents($this->filePath, json_encode($posts));
                return true;
            }
        }
        return false;
    }
}
?>
