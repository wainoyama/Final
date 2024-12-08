<?php
require_once 'database_connection.php';
require_once 'upload_handler.php'; 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $message = $_POST['message'];
    $for_sale = isset($_POST['for_sale']) ? 1 : 0;
    $photo = null;

    if (isset($_FILES['photo'])) {
        $photo = uploadPhoto($_FILES['photo']);
        
        if (strpos($photo, 'uploads/') === false) {
            echo $photo;
            exit();
        }
    }
    $stmt = $db->prepare("INSERT INTO posts (message, photo, for_sale) VALUES (?, ?, ?)");
    $stmt->execute([$message, $photo, $for_sale]);

    header("Location: ./index.php");
    exit();
}
?>
