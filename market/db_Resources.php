<?php
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'agriculture';

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

header('Content-Type: application/json');

$sql = "SELECT * FROM your_table_name";
$result = $conn->query($sql);

$data = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = [
            'id' => $row['id'],
            'name' => $row['name'],
            'description' => $row['description'],
            'price' => $row['price'],
        ];
    }
}

echo json_encode([
    'draw' => intval($_GET['draw']),
    'recordsTotal' => $result->num_rows,
    'recordsFiltered' => $result->num_rows,
    'data' => $data,
]);

$conn->close();
?>
