<?php
function uploadPhoto($file) {
    $uploadDir = __DIR__ . 'uploads/';
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }
    $uploadFile = $uploadDir . basename($file['name']);
    
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return "Error uploading file.";
    }
    $allowedTypes = ['image/jpg','image/jpeg', 'image/png', 'image/gif'];
    if (!in_array($file['type'], $allowedTypes)) {
        return "Invalid file type. Please upload a JPEG, PNG, or GIF.";
    }
    $maxFileSize = 40 * 1024 * 1024;
    if ($file['size'] > $maxFileSize) {
        return "File size exceeds the 40MB limit.";
    }

    if (move_uploaded_file($file['tmp_name'], $uploadFile)) {
        return 'uploads/' . basename($file['name']);
    } else {
        return "Error uploading file.";
    }
}
?>
