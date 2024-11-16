<?php

class ProductTransport {
    private $db; // Database connection

    public function __construct($dbConnection) {
        $this->db = $dbConnection;
    }

    public function displayStatus($orderId) {
        // Fetch the status from the database for the given orderId
        $query = "SELECT status FROM orders WHERE order_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$orderId]);
        $status = $stmt->fetchColumn();

        if ($status) {
            echo "Status: " . htmlspecialchars($status);
        } else {
            echo "Order not found.";
        }
    }

    public function updateStatus($orderId, $status, $isAdmin) {
        // Check if the user is an admin
        if (!$isAdmin) {
            echo "Error: Only admin can update the status.";
            return;
        }

        // Update the delivery status in the database
        $query = "UPDATE orders SET status = ? WHERE order_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$status, $orderId]);

        echo "Status updated to: " . htmlspecialchars($status);
    }

    public function estimateDeliveryTime($orderId) {
        // For simplicity, let's assume a static estimate
        // In a real application, this would be more dynamic
        $query = "SELECT distance FROM orders WHERE order_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$orderId]);
        $distance = $stmt->fetchColumn();

        if ($distance) {
            // Simple estimation: 50 km per day
            $estimatedDays = ceil($distance / 50);
            echo "Estimated delivery in " . $estimatedDays . " days.";
        } else {
            echo "Order not found.";
        }
    }
}

// Example usage
try {
    // Database connection (replace with your own connection details)
    $pdo = new PDO('mysql:host=localhost;dbname=your_database', 'username', 'password');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $productTransport = new ProductTransport($pdo);

    // Display order status
    $orderId = 1; // Example order ID
    $productTransport->displayStatus($orderId);

    // Update order status (only if user is admin)
    $isAdmin = true; // Simulating admin user
    $newStatus = "Delivered";
    $productTransport->updateStatus($orderId, $newStatus, $isAdmin);

    // Estimate delivery time
    $productTransport->estimateDeliveryTime($orderId);

} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage();
}
?>