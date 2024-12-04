<?php

$conn = new mysqli("localhost", "username", "password", "database");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['archive_post'])) {
    $postId = intval($_POST['post_id']);
    $query = "UPDATE posts SET archived = 1 WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $postId);

    if ($stmt->execute()) {
        echo "Post archived successfully.";
    } else {
        echo "Error archiving post.";
    }
    $stmt->close();
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Archive User Posts</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
</head>
<body>
    <h1>Archive User Posts</h1>
    <table id="postsTable" class="display">
        <thead>
            <tr>
                <th>ID</th>
                <th>Message</th>
                <th>Photo</th>
                <th>For Sale</th>
                <th>Timestamp</th>
                <th>User Name</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $query = "SELECT * FROM posts WHERE archived = 0";
            $result = $conn->query($query);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                        <td>{$row['id']}</td>
                        <td>{$row['message']}</td>
                        <td>";
                    if ($row['photo']) {
                        echo "<img src='{$row['photo']}' alt='Photo' width='50'>";
                    } else {
                        echo "No Photo";
                    }
                    echo "</td>
                        <td>" . ($row['for_sale'] ? "Yes" : "No") . "</td>
                        <td>{$row['timestamp']}</td>
                        <td>{$row['user_name']}</td>
                        <td>
                            <form method='POST' onsubmit='return confirm(\"Are you sure you want to archive this post?\");'>
                                <input type='hidden' name='post_id' value='{$row['id']}'>
                                <button type='submit' name='archive_post'>Archive</button>
                            </form>
                        </td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='7'>No posts found.</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <script>
        $(document).ready(function () {
            $('#postsTable').DataTable();
        });
    </script>
</body>
</html>