<?php
include 'agriculture.php';

class MarketStocks {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getMarketStocks() {
        $query = "SELECT ms.id, p.name AS product_name, ms.price, r.name AS region_name
                  FROM market_stocks ms
                  JOIN products p ON ms.product_id = p.id
                  JOIN regions r ON ms.region_id = r.id";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function displayMarketStocks() {
        $marketStocks = $this->getMarketStocks();
        foreach ($marketStocks as $stock) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($stock['product_name']) . "</td>";
            echo "<td>" . number_format($stock['price'], 2) . "</td>";
            echo "<td>" . htmlspecialchars($stock['region_name']) . "</td>";
            echo "</tr>";
        }
    }
}
?>
