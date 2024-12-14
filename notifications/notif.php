<?php

function notif($user_id, $message, $conn, $user_name = null) {
    if ($user_name) {
        $message = "$user_name $message"; 
    }
    $stmt = $conn->prepare("INSERT INTO notifications (user_id, message, created_at) VALUES (?, ?, NOW())");
    $stmt->bind_param("is", $user_id, $message);
    $stmt->execute();
}

function unreadNotif($userId, $conn) {
    $stmt = $conn->prepare("SELECT * FROM notifications WHERE user_id = ? AND is_read = 0 ORDER BY created_at DESC");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

function markNotifAsRead($notificationId, $conn) {
    $stmt = $conn->prepare("UPDATE notifications SET is_read = 1 WHERE id = ?");
    $stmt->bind_param("i", $notificationId);
    $stmt->execute();
}

?>

