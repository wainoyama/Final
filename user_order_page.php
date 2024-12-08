<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $customer_name = $_POST['customer_name'];
    $product_name = $_POST['product_name'];
    $quantity = $_POST['quantity'];

    if (!empty($customer_name) && !empty($product_name) && !empty($quantity)) {
        $query = "INSERT INTO orders (customer_name, product_name, quantity, status) VALUES (?, ?, ?, 'Pending')";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssi", $customer_name, $product_name, $quantity);

        if ($stmt->execute()) {
            echo "Order placed successfully.";
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "All fields are required.";
    }
}
?>
<!DOCTYPE html>
<head>
    <title>User Order Page</title>
</head>
<body>
    <h1>Place Your Order</h1>
    <form method="POST" action="user_order_page.php">
        <label for="customer_name">Your Name:</label>
        <input type="text" name="customer_name" id="customer_name" required>
        <br>
        <label for="product_name">Product Name:</label>
        <input type="text" name="product_name" id="product_name" required>
        <br>
        <label for="quantity">Quantity:</label>
        <input type="number" name="quantity" id="quantity" required>
        <br>
        <button type="submit">Place Order</button>
    </form>
</body>
</html>
