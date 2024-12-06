<?php
require_once 'config.php';

class OrderStatus {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getStatus($orderId) {
        $sql = "SELECT * FROM orders WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $orderId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateStatus($orderId, $status) {
        $sql = "UPDATE orders SET status = :status WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':status' => $status, ':id' => $orderId]);
    }
}

include 'header.php';

$orderStatus = new OrderStatus();

if (isset($_GET['orderId'])) {
    $orderId = $_GET['orderId'];
    $status = $orderStatus->getStatus($orderId);

    if ($status) {
        echo "<h1>Order Status</h1>";
        echo "<p>Order ID: " . htmlspecialchars($orderId) . "</p>";
        echo "<p>Status: " . htmlspecialchars($status['status']) . "</p>";
        echo "<p>Location: " . htmlspecialchars($status['location']) . "</p>";
        echo "<p>Contact: " . htmlspecialchars($status['contact']) . "</p>";
        echo "<p>Pickup Date: " . htmlspecialchars($status['pickup_date']) . "</p>";
        echo "<p>Pickup Time: " . htmlspecialchars($status['pickup_time']) . "</p>";
    } else {
        echo "<p>Order not found.</p>";
    }
} else {
    echo "<h2>Track Your Order</h2>";
    echo "<form method='get' action=''>";
    echo "<label for='orderId'>Enter Order ID:</label>";
    echo "<input type='number' name='orderId' id='orderId' required>";
    echo "<input type='submit' value='Check Status'>";
    echo "</form>";
}

?>

