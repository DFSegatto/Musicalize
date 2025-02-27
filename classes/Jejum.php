<?php
class Jejum {
    private $conn;
    private $table_name = "jejum_semanal";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function cadastrar($musico_id, $dia_semana) {
        try {
            // Verifica se já existe um registro para este músico
            $check_query = "SELECT id FROM " . $this->table_name . " WHERE musico_id = :musico_id";
            $check_stmt = $this->conn->prepare($check_query);
            $check_stmt->bindParam(":musico_id", $musico_id);
            $check_stmt->execute();

            if ($check_stmt->rowCount() > 0) {
                // Atualiza o registro existente
                $query = "UPDATE " . $this->table_name . " 
                         SET dia_semana = :dia_semana 
                         WHERE musico_id = :musico_id";
            } else {
                // Insere novo registro
                $query = "INSERT INTO " . $this->table_name . " 
                         (musico_id, dia_semana) 
                         VALUES (:musico_id, :dia_semana)";
            }

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":musico_id", $musico_id);
            $stmt->bindParam(":dia_semana", $dia_semana);

            return $stmt->execute();
        } catch (PDOException $e) {
            throw $e;
        }
    }

    public function listar() {
        try {
            $query = "SELECT j.*, m.nome, 
                     CASE j.dia_semana 
                        WHEN 0 THEN 'Domingo'
                        WHEN 1 THEN 'Segunda-feira'
                        WHEN 2 THEN 'Terça-feira'
                        WHEN 3 THEN 'Quarta-feira'
                        WHEN 4 THEN 'Quinta-feira'
                        WHEN 5 THEN 'Sexta-feira'
                        WHEN 6 THEN 'Sábado'
                     END as dia_semana
                     FROM " . $this->table_name . " j 
                     INNER JOIN musicos m ON j.musico_id = m.id 
                     ORDER BY j.dia_semana ASC";
            
            $stmt = $this->conn->prepare($query);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw $e;
        }
    }
}

