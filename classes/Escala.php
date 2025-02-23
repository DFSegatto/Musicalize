<?php
class Escala {
    private $conn;
    private $table_name = "escalas";
    private $table_detalhes = "escala_detalhes";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function criar($dados) {
        try {
            $this->conn->beginTransaction();
            
            // Log dos dados recebidos
            error_log("Iniciando criação de escala com dados: " . print_r($dados, true));

            // Insere a escala principal
            $query = "INSERT INTO " . $this->table_name . " (evento_id, dataEscala) VALUES (:evento_id, :dataEscala)";
            $stmt = $this->conn->prepare($query);
            
            $stmt->bindParam(":dataEscala", $dados['dataEscala']);
            $stmt->bindParam(":evento_id", $dados['evento_id']);
            
            if (!$stmt->execute()) {
                error_log("Erro ao inserir escala principal: " . print_r($stmt->errorInfo(), true));
                throw new Exception("Erro ao criar escala principal");
            }
            
            $escala_id = $this->conn->lastInsertId();
            error_log("Escala principal criada com ID: " . $escala_id);

            // Insere os detalhes
            foreach ($dados['musicos'] as $musico_id) {
                foreach ($dados['musicas'] as $index => $musica_id) {
                    $tom_id = $dados['tons'][$musica_id];
                    
                    $query = "INSERT INTO " . $this->table_detalhes . " 
                             (escala_id, musico_id, musica_id, tom_id) 
                             VALUES (:escala_id, :musico_id, :musica_id, :tom_id)";
                    
                    $stmt = $this->conn->prepare($query);
                    
                    $stmt->bindParam(":escala_id", $escala_id);
                    $stmt->bindParam(":musico_id", $musico_id);
                    $stmt->bindParam(":musica_id", $musica_id);
                    $stmt->bindParam(":tom_id", $tom_id);
                    
                    if (!$stmt->execute()) {
                        error_log("Erro ao inserir detalhe da escala: " . print_r($stmt->errorInfo(), true));
                        throw new Exception("Erro ao inserir detalhe da escala");
                    }
                }
            }

            $this->conn->commit();
            error_log("Escala criada com sucesso!");
            return true;

        } catch (Exception $e) {
            $this->conn->rollBack();
            error_log("Erro na criação da escala: " . $e->getMessage());
            throw $e;
        }
    }

    public function listar() {
        $query = "SELECT e.*, ev.titulo as evento_titulo 
                 FROM " . $this->table_name . " e
                 LEFT JOIN eventos ev ON e.evento_id = ev.id
                 ORDER BY e.dataEscala DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt;
    }

    public function buscarPorId($id) {
        try {
            // Busca informações básicas da escala
            $sql = "SELECT e.*, ev.titulo as evento_titulo 
                    FROM " . $this->table_name . " e
                    LEFT JOIN eventos ev ON e.evento_id = ev.id
                    WHERE e.id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            
            $escala = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$escala) {
                return null;
            }

            // Busca detalhes da escala (músicos, músicas e tons)
            $sql = "SELECT ed.*, m.nome as musico_nome, mu.titulo as musica_titulo, t.nome as tom_nome
                    FROM " . $this->table_detalhes . " ed
                    INNER JOIN musicos m ON ed.musico_id = m.id
                    INNER JOIN musicas mu ON ed.musica_id = mu.id
                    INNER JOIN tons t ON ed.tom_id = t.id
                    WHERE ed.escala_id = :escala_id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':escala_id', $id);
            $stmt->execute();
            $escala['detalhes'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $escala;
        } catch(PDOException $e) {
            throw $e;
        }
    }

    public function atualizarTom($detalhe_id, $novo_tom) {
        try {
            $query = "UPDATE " . $this->table_detalhes . " 
                     SET tom_id = :tom_id 
                     WHERE id = :detalhe_id";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':tom_id', $novo_tom);
            $stmt->bindParam(':detalhe_id', $detalhe_id);
            
            return $stmt->execute();
        } catch(PDOException $e) {
            throw $e;
        }
    }
}