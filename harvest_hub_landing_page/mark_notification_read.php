<?php
session_start();
require_once '../db.php';
require_once '../auth_check.php';
require_once './notifications/notif.php';

if (!isLoggedIn()) {
    die('Unauthorized');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['notification_id'])) {
        $notificationId = intval($_POST['notification_id']);
        markNotifAsRead($notificationId, $conn);
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Notification ID not provided']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
}