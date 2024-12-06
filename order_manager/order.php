<?php
// Order.php
class Order {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getProduct($post_id) {
        $stmt = $this->pdo->prepare("SELECT * FROM posts WHERE id = ?");
        $stmt->execute([$post_id]);
        return $stmt->fetch();
    }

    public function createOrder($post_id, $buyer_id, $seller_id, $item_description) {
        $status = 'pending';
        $stmt = $this->pdo->prepare("INSERT INTO orders (post_id, buyer_id, seller_id, item_description, status) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([$post_id, $buyer_id, $seller_id, $item_description, $status]);
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
