<<<<<<< HEAD
<?php

include 'db.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Archived Users</title>
    <link href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
</head>
<body>

    <h1>Archived Users</h1>
    <table id="usersTable" class="display">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Location</th>s
                <th>Created At</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php

            $stmt = $pdo->query("SELECT * FROM users");
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                echo "<td>" . htmlspecialchars($row['phone']) . "</td>";
                echo "<td>" . htmlspecialchars($row['location']) . "</td>";
                echo "<td>" . htmlspecialchars($row['created_at']) . "</td>";
                echo "<td><button onclick='archiveUser(" . $row['id'] . ")'>Archive</button></td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>

    <script>
        $(document).ready(function() {
            $('#usersTable').DataTable(); 
        });

        function archiveUser(userId) {
            if (confirm("Are you sure you want to archive this user?")) {
                $.ajax({
                    url: 'archive_action.php',
                    type: 'POST',
                    data: { id: userId },
                    success: function(response) {
                        alert(response);
                        location.reload(); 
                    },
                    error: function() {
                        alert("Error archiving user.");
                    }
                });
            }
        }
    </script>

</body>
</html>
=======
<?php

include 'db.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Archived Users</title>
    <link href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
</head>
<body>

    <h1>Archived Users</h1>
    <table id="usersTable" class="display">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Location</th>s
                <th>Created At</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php

            $stmt = $pdo->query("SELECT * FROM users");
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                echo "<td>" . htmlspecialchars($row['phone']) . "</td>";
                echo "<td>" . htmlspecialchars($row['location']) . "</td>";
                echo "<td>" . htmlspecialchars($row['created_at']) . "</td>";
                echo "<td><button onclick='archiveUser(" . $row['id'] . ")'>Archive</button></td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>

    <script>
        $(document).ready(function() {
            $('#usersTable').DataTable(); 
        });

        function archiveUser(userId) {
            if (confirm("Are you sure you want to archive this user?")) {
                $.ajax({
                    url: 'archive_action.php',
                    type: 'POST',
                    data: { id: userId },
                    success: function(response) {
                        alert(response);
                        location.reload(); 
                    },
                    error: function() {
                        alert("Error archiving user.");
                    }
                });
            }
        }
    </script>

</body>
</html>
>>>>>>> 12d00f67aaf2cf9cd4516c3d533ee1ec6566f411
