<?php
require_once 'database_connection.php';

if (isset($_GET['id'])) {
    $postId = $_GET['id'];

    $stmt = $db->prepare("SELECT * FROM posts WHERE id = ?");
    $stmt->execute([$postId]);
    $post = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $message = $_POST['message'];
        $forSale = isset($_POST['for_sale']) ? 1 : 0;
        $photo = '';

        if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = './uploads/';
            $uploadFile = $uploadDir . basename($_FILES['photo']['name']);

            if (move_uploaded_file($_FILES['photo']['tmp_name'], $uploadFile)) {
                $photo = $uploadFile;
            }
        }

        $stmt = $db->prepare("UPDATE posts SET message = ?, photo = ?, for_sale = ? WHERE id = ?");
        $stmt->execute([$message, $photo, $forSale, $postId]);

        header('Location: /index.php');
        exit();
    }
}
?>
