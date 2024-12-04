<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $order_id = $_POST['order_id'];

    if (!empty($order_id)) {
        $query = "UPDATE orders SET status = 'Declined' WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $order_id);

        if ($stmt->execute()) {
            echo "Order successfully declined.";
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Order ID is missing.";
    }
}
?>
