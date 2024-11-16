<?php
class Admin {
    private $conn;
    private $table_name = "admins";

    public function __construct($db) {
        $this->conn = $db;
    }


    public function login($username, $password) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE username = :username LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":username", $username);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $stored_password = $row['password']; 

            if (password_verify($password, $stored_password)) {
                return true; 
            } else {
                return false;
            }
        } else {
            return false; 
        }
    }
}
?>
