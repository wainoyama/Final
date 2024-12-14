<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "calabarzon_harvest_hub";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$searchQuery = isset($_GET['query']) ? trim($_GET['query']) : "";

$resultData = [];

if (!empty($searchQuery)) {
    $sql = "SELECT * FROM calabarzon_topography";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $combinedData = $row['province'] . " " . $row['location'] . " " . $row['topography'] . " " . $row['soil_types'] . " " . $row['agricultural_relevance'];

            if (stripos($combinedData, $searchQuery) !== false) {
                $resultData[] = $row;
            }
        }
    }
}

function highlightSearchTerm($text, $searchQuery) {
    return preg_replace("/(" . preg_quote($searchQuery, '/') . ")/i", "<span class='highlight'>$1</span>", $text);
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/results.css">
</head>
<body>
    <header>
        <div class="header-content">
            <div class="logo">
                <span>Harvest Hub</span>
            </div>
            <nav>
                <a href="../index.php"><i class="fas fa-home"></i> Home</a>
                <a href="../harvest_hub_landing_page/community.php"><i class="fas fa-users"></i> Community</a>
                <a href="../profile_page/profile.php"><i class="fas fa-user"></i> Profile</a>
            </nav>
        </div>
    </header>

    <div class="container">
        <div class="results-container">
            <?php if (!empty($searchQuery)): ?>
                <h2>Results for "<?php echo htmlspecialchars($searchQuery); ?>"</h2>
                <?php if (count($resultData) > 0): ?>
                    <?php foreach ($resultData as $data): ?>
                        <div class="result">
                            <h3><?php echo highlightSearchTerm(htmlspecialchars($data['province']), $searchQuery); ?></h3>
                            <p><strong>Location:</strong> <?php echo highlightSearchTerm(htmlspecialchars($data['location']), $searchQuery); ?></p>
                            <p><strong>Topography:</strong> <?php echo highlightSearchTerm(htmlspecialchars($data['topography']), $searchQuery); ?></p>
                            <p><strong>Soil Types:</strong> <?php echo highlightSearchTerm(htmlspecialchars($data['soil_types']), $searchQuery); ?></p>
                            <p><strong>Agricultural Relevance:</strong> <?php echo highlightSearchTerm(htmlspecialchars($data['agricultural_relevance']), $searchQuery); ?></p>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="error-message">No results found for "<?php echo htmlspecialchars($searchQuery); ?>".</p>
                <?php endif; ?>
            <?php else: ?>
                <p class="error-message">Please enter a search term.</p>
            <?php endif; ?>
        </div>
    </div>

    <footer>
        <div class="container">
            <p>&copy; <?php echo date("Y"); ?> Harvest Hub. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>

