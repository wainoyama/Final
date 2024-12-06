<?php
include 'agriculture.php';

class FarmProducts {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getFarmProducts() {
        $query = "SELECT id, name, type, price, origin, preservation_tips FROM products";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function displayFarmProducts() {
        $products = $this->getFarmProducts();
        foreach ($products as $product) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($product['name']) . "</td>";
            echo "<td>" . number_format($product['price'], 2) . "</td>";
            echo "<td>" . htmlspecialchars($product['origin']) . "</td>";
            echo "</tr>";
        }
    }

    public function getPreservationTips() {
        $query = "SELECT name, preservation_tips FROM products";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
    }
}
?>
