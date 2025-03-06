<?php
class Evento {
    private $conn;
    private $table_name = "eventos";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function listar() {
        try {
            $query = "SELECT id, titulo, tipo FROM " . $this->table_name . " ORDER BY titulo";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt;
        } catch(PDOException $e) {
            return false;
        }
    }

    public function cadastrar($titulo, $tipo) {
        try {
            $query = "INSERT INTO " . $this->table_name . " (titulo, tipo) VALUES (:titulo, :tipo)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':titulo', $titulo);
            $stmt->bindParam(':tipo', $tipo);
            $stmt->execute();
            return $stmt;
        } catch(PDOException $e) {
            return $e->getMessage();
        }
    }

    public function editar($id, $titulo, $tipo) {
        try {
            $query = "UPDATE " . $this->table_name . " SET titulo = :titulo, tipo = :tipo WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':titulo', $titulo);
            $stmt->bindParam(':tipo', $tipo);
            $stmt->execute();
            return $stmt;
        } catch(PDOException $e) {
            return $e->getMessage();
        }
    }

    public function excluir($id) {
        try {
            $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return $stmt;
        } catch(PDOException $e) {
            return $e->getMessage();
        }
    }
}
?>