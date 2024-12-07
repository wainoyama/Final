<?php
// Order.php
class Order {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getProduct($post_id) {
        $stmt = $this->pdo->prepare("SELECT * FROM posts WHERE id = ? AND for_sale = 1");
        $stmt->execute([$post_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    

    public function createOrder($post_id, $buyer_id, $seller_id, $item_description) {
        $this->pdo->beginTransaction();
        try {
            $stmt = $this->pdo->prepare("INSERT INTO orders (post_id, buyer_id, seller_id, item_description, status, created_at) 
                                         VALUES (?, ?, ?, ?, 'pending', NOW())");
            $stmt->execute([$post_id, $buyer_id, $seller_id, $item_description]);
    
            $stmt = $this->pdo->prepare("UPDATE posts SET order_status = 'sold' WHERE id = ?");
            $stmt->execute([$post_id]);
    
            $this->pdo->commit();
            return true;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            error_log("Order creation failed: " . $e->getMessage());
            return false;
        }
    }

    public function getUserOrders($user_id) {
        $stmt = $this->pdo->prepare("SELECT * FROM orders WHERE buyer_id = ? OR seller_id = ?");
        $stmt->execute([$user_id, $user_id]);
        return $stmt->fetchAll();
    }

    public function updateOrderStatus($order_id, $status) {
        $stmt = $this->pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
        return $stmt->execute([$status, $order_id]);
    }
}
