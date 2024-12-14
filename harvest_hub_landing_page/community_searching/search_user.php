<?php
header('Content-Type: application/json');
require_once '../db.php'; // Adjust to your database connection file path

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['search'])) {
    $searchQuery = $data['search'];

    // Prepare the SQL query to search in name, email, and location fields
    $stmt = $conn->prepare("
        SELECT id, name, email, phone, location, photo 
        FROM users 
        WHERE name LIKE ? OR email LIKE ? OR location LIKE ?
    ");
    $likeQuery = "%" . $searchQuery . "%";
    $stmt->bind_param("sss", $likeQuery, $likeQuery, $likeQuery);
    $stmt->execute();
    $result = $stmt->get_result();

    // Fetch all matching users
    $users = [];
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }

    // Return the results as JSON
    echo json_encode(['users' => $users]);
} else {
    echo json_encode(['error' => 'Invalid request.']);
}
?>
