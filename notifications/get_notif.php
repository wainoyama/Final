<?php
require 'db_conn.php'; 

$userId = $_SESSION['user_id']; 
echo json_encode(unreadNotif($userId, $pdo));
?>