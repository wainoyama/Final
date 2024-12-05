<?php
require_once 'config.php';

class Order
{
    private $location;
    private $contact;
    private $pickupDate;
    private $pickupTime;
    private $orderStatus;

    public function __construct()
    {
        $this->orderStatus = "Pending";
    }

    public function setLocation($location)
    {
        if (empty($location)) {
            throw new Exception("Location is required.");
        }
        $this->location = $location;
    }

    public function setContact($contact)
    {
        if (empty($contact)) {
            throw new Exception("Contact is required.");
        }
        $this->contact = $contact;
    }

    public function setPickupDetails($date, $time)
    {
        if (empty($date) || empty($time)) {
            throw new Exception("Pickup date and time are required.");
        }
        $this->pickupDate = $date;
        $this->pickupTime = $time;
    }

    public function getLocation()
    {
        return $this->location;
    }

    public function getContact()
    {
        return $this->contact;
    }

    public function getPickupDate()
    {
        return $this->pickupDate;
    }

    public function getPickupTime()
    {
        return $this->pickupTime;
    }

    public function getOrderStatus()
    {
        return $this->orderStatus;
    }

    public function saveToDatabase($pdo)
    {
        $sql = "INSERT INTO orders (location, contact, pickup_date, pickup_time, status) 
                VALUES (:location, :contact, :pickup_date, :pickup_time, :status)";
        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            ':location' => $this->location,
            ':contact' => $this->contact,
            ':pickup_date' => $this->pickupDate,
            ':pickup_time' => $this->pickupTime,
            ':status' => $this->orderStatus,
        ]);

        return $pdo->lastInsertId(); 
    }
}

// Start output buffering
ob_start();

// Include the header
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Harvest Hub - Process Order</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://kit.fontawesome.com/your-fontawesome-kit.js" crossorigin="anonymous"></script>
</head>
<body>
    <header>
        <div class="header-content">
            <div class="logo">
                <span>Harvest Hub</span>
            </div>
            <nav>
                <a href="index.php"><i class="fas fa-home"></i> Home</a>
                <a href="./harvest_hub_landing_page/community.php"><i class="fas fa-users"></i> Community</a>
                <a href="./profile_page/profile.php"><i class="fas fa-user"></i> Profile</a>
            </nav>
        </div>
    </header>

    <div class="container">
<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $order = new Order();

        $location = $_POST['location'] ?? '';
        $contact = $_POST['contact'] ?? '';
        $pickup_date = $_POST['pickup_date'] ?? '';
        $pickup_time = $_POST['pickup_time'] ?? '';

        $order->setLocation($location);
        $order->setContact($contact);
        $order->setPickupDetails($pickup_date, $pickup_time);

        $pdo = Database::getInstance()->getConnection();
        $orderId = $order->saveToDatabase($pdo);

        echo "<h1>Order Confirmation</h1>";
        echo "<p>Your order has been confirmed! Cash on Delivery is the only payment option.</p>";
        echo "<p>Order ID: " . htmlspecialchars($orderId) . "</p>";
        echo "<p>Location: " . htmlspecialchars($order->getLocation()) . "</p>";
        echo "<p>Contact: " . htmlspecialchars($order->getContact()) . "</p>";
        echo "<p>Pickup Date: " . htmlspecialchars($order->getPickupDate()) . "</p>";
        echo "<p>Pickup Time: " . htmlspecialchars($order->getPickupTime()) . "</p>";
    } catch (Exception $e) {
        echo "<h1>Error</h1>";
        echo "<p>" . htmlspecialchars($e->getMessage()) . "</p>";
    }
} else {
    // If it's not a POST request, display the order form
    ?>
    <h1>Place Your Order</h1>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <div class="form-group">
            <label for="location">Location:</label>
            <input type="text" id="location" name="location" required>
        </div>
        <div class="form-group">
            <label for="contact">Contact:</label>
            <input type="text" id="contact" name="contact" required>
        </div>
        <div class="form-group">
            <label for="pickup_date">Pickup Date:</label>
            <input type="date" id="pickup_date" name="pickup_date" required>
        </div>
        <div class="form-group">
            <label for="pickup_time">Pickup Time:</label>
            <input type="time" id="pickup_time" name="pickup_time" required>
        </div>
        <button type="submit" class="btn-submit">Place Order</button>
    </form>
    <?php
}

// Footer
?>
    </div>
    <footer>
        <div class="container">
            <p>&copy; <?php echo date("Y"); ?> Harvest Hub. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
<?php
// End output buffering and flush
ob_end_flush();
?>

