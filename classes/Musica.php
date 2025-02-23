<?php

class Musica {
    private $conn;
    private $table_name = "musicas";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function listar() {
        try {
            $query = "SELECT id, titulo, artista FROM " . $this->table_name . " ORDER BY titulo";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt;
        } catch(PDOException $e) {
            return false;
        }
    }

    public function salvarMusica($titulo, $artista, $duracao){
        try {
            $query = "INSERT INTO " . $this->table_name . " (titulo, artista, duracao) VALUES ('$titulo', '$artista', '$duracao')";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt;
        } catch(PDOException $e) {
            return false;
        }
    }

    public function atualizarMusica($id, $titulo, $artista, $duracao){
        try {
            $query = "UPDATE " . $this->table_name . " SET titulo = '$titulo', artista = '$artista', duracao = '$duracao' WHERE id = '$id'";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt;
        } catch(PDOException $e) {
            return false;
        }
    }
}

