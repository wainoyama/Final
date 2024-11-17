<?php

class ProductTransport
{
    private $orderStatuses = [];

    public function __construct()
    {
        $this->orderStatuses = [
            1 => ['status' => 'Pending', 'estimatedDelivery' => '3-5 days'],
            2 => ['status' => 'Pending', 'estimatedDelivery' => '1-2 days'],
        ];
    }

    public function displayStatus($orderId)
    {
        if (isset($this->orderStatuses[$orderId])) {
            $status = $this->orderStatuses[$orderId]['status'];
            $estimatedDelivery = $this->orderStatuses[$orderId]['estimatedDelivery'];
            echo "Order ID: $orderId<br>";
            echo "Current Status: $status<br>";
            echo "Estimated Delivery: $estimatedDelivery<br><br>";
        } else {
            echo "Order ID $orderId not found.<br><br>";
        }
    }

    public function updateStatus($orderId, $status, $admin = false)
    {
        if ($admin) {
            if (isset($this->orderStatuses[$orderId])) {
                $this->orderStatuses[$orderId]['status'] = $status;
                echo "Order ID $orderId status updated to '$status'.<br><br>";
            } else {
                echo "Order ID $orderId not found.<br><br>";
            }
        } else {
            echo "You are not authorized to update the status.<br><br>";
        }
    }

    public function estimateDeliveryTime($orderId)
    {
        if (isset($this->orderStatuses[$orderId])) {
            $estimatedDelivery = $this->orderStatuses[$orderId]['estimatedDelivery'];
            echo "Order ID $orderId is estimated to be delivered in: $estimatedDelivery.<br><br>";
        } else {
            echo "Order ID $orderId not found.<br><br>";
        }
    }

    public function sendNotification($orderId)
    {
        if (isset($this->orderStatuses[$orderId])) {
            $status = $this->orderStatuses[$orderId]['status'];
            echo "Notification: Your order ID $orderId is now '$status'.<br><br>";
        } else {
            echo "Order ID $orderId not found.<br><br>";
        }
    }

    public function showStatusHistory($orderId)
    {
        echo "Showing status history for Order ID $orderId (sample data):<br>";
        echo "1. Order Packed<br>";
        echo "2. Shipped<br>";
        echo "3. In Transit<br>";
        echo "4. Delivered<br><br>";
    }
}

if (isset($_GET['orderId'])) {
    $orderId = $_GET['orderId'];

    $productTransport = new ProductTransport();

    $productTransport->displayStatus($orderId);
    $productTransport->estimateDeliveryTime($orderId);
    $productTransport->sendNotification($orderId);
    $productTransport->showStatusHistory($orderId);
} else {
    echo "Please provide an order ID to check the status.<br><br>";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Status Tracker</title>
</head>
<body>

<h2>Track Your Order</h2>

<form method="get" action="">
    <label for="orderId">Enter Order ID:</label>
    <input type="number" name="orderId" id="orderId" required>
    <input type="submit" value="Check Status">
</form>

</body>
</html>