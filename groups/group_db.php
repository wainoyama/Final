<?php
require_once 'config.php';

function getAllGroups() {
    global $conn;
    
    $query = "SELECT g.*, 
              (SELECT COUNT(*) FROM group_members WHERE group_id = g.group_id) as member_count 
              FROM groups g 
              ORDER BY g.created_at DESC";
              
    $result = mysqli_query($conn, $query);
    
    $groups = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $groups[] = $row;
    }
    
    return $groups;
}

function searchGroups($search) {
    global $conn;
    
    $search = mysqli_real_escape_string($conn, $search);
    $query = "SELECT g.*, 
              (SELECT COUNT(*) FROM group_members WHERE group_id = g.group_id) as member_count 
              FROM groups g 
              WHERE g.group_name LIKE '%$search%' 
              OR g.description LIKE '%$search%' 
              ORDER BY g.created_at DESC";
              
    $result = mysqli_query($conn, $query);
    
    $groups = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $groups[] = $row;
    }
    
    return $groups;
}

function createGroup($name, $description, $image_path, $creator_id) {
    global $conn;
    
    $name = mysqli_real_escape_string($conn, $name);
    $description = mysqli_real_escape_string($conn, $description);
    $image_path = mysqli_real_escape_string($conn, $image_path);
    $creator_id = (int)$creator_id;
    
    $query = "INSERT INTO groups (group_name, description, group_image, creator_id, created_at) 
              VALUES ('$name', '$description', '$image_path', $creator_id, NOW())";
              
    if (mysqli_query($conn, $query)) {
        $group_id = mysqli_insert_id($conn);
        // Add creator as member
        addGroupMember($group_id, $creator_id, 'admin');
        return $group_id;
    }
    
    return false;
}

function getGroupById($group_id) {
    global $conn;
    
    $group_id = (int)$group_id;
    $query = "SELECT g.*, 
              (SELECT COUNT(*) FROM group_members WHERE group_id = g.group_id) as member_count 
              FROM groups g 
              WHERE g.group_id = $group_id";
              
    $result = mysqli_query($conn, $query);
    
    return mysqli_fetch_assoc($result);
}

function addGroupMember($group_id, $user_id, $role = 'member') {
    global $conn;
    
    $group_id = (int)$group_id;
    $user_id = (int)$user_id;
    $role = mysqli_real_escape_string($conn, $role);
    
    $query = "INSERT INTO group_members (group_id, user_id, role, joined_at) 
              VALUES ($group_id, $user_id, '$role', NOW())";
              
    return mysqli_query($conn, $query);
}

function isGroupMember($group_id, $user_id) {
    global $conn;
    
    $group_id = (int)$group_id;
    $user_id = (int)$user_id;
    
    $query = "SELECT 1 FROM group_members 
              WHERE group_id = $group_id AND user_id = $user_id";
              
    $result = mysqli_query($conn, $query);
    
    return mysqli_num_rows($result) > 0;
}

function getGroupRole($group_id, $user_id) {
    global $conn;
    
    $group_id = (int)$group_id;
    $user_id = (int)$user_id;
    
    $query = "SELECT role FROM group_members 
              WHERE group_id = $group_id AND user_id = $user_id";
              
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    
    return $row ? $row['role'] : null;
}