<?php
class Musico {
    private $conn;
    private $table_name = "musicos";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function listar() {
        try {
            $query = "SELECT id, nome, instrumento FROM " . $this->table_name . " ORDER BY nome";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt;
        } catch(PDOException $e) {
            return false;
        }
    }
}
