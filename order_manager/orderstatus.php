<?php
// order_status.php
session_start();
include 'config.php';
include 'Order.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$order = new Order($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = $_POST['order_id'];
    $status = $_POST['status'];
    if ($order->updateOrderStatus($order_id, $status)) {
        echo "<script>Swal.fire('Success!', 'Order status updated.', 'success');</script>";
    } else {
        echo "<script>Swal.fire('Error!', 'Failed to update order status.', 'error');</script>";
    }
}

$orders = $order->getUserOrders($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Status</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
</head>
<body>
    <h2>Your Orders</h2>
    <table id="orders_table">
        <thead>
            <tr>
                <th>Product</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($orders as $order) : ?>
                <tr>
                    <td><?= $order['item_description'] ?></td>
                    <td><?= $order['status'] ?></td>
                    <td>
                        <?php if ($order['status'] == 'pending' && $order['seller_id'] == $_SESSION['user_id']) : ?>
                            <form method="POST">
                                <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                                <button type="submit" name="status" value="completed">Approve</button>
                                <button type="submit" name="status" value="cancelled">Reject</button>
                            </form>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <script>
        $(document).ready(function() {
            $('#orders_table').DataTable();
        });
    </script>
</body>
</html>
