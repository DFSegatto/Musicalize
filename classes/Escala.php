<?php
class Escala {
    private $conn;
    private $table_principal = "escalas";
    private $table_detalhes = "escala_detalhes";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function criar($dados) {
        try {
            $this->conn->beginTransaction();
            
            // Insere a escala principal
            $query = "INSERT INTO " . $this->table_principal . " (evento_id, dataEscala) VALUES (:evento_id, :dataEscala)";
            $stmt = $this->conn->prepare($query);
            
            $stmt->bindParam(":dataEscala", $dados['dataEscala']);
            $stmt->bindParam(":evento_id", $dados['evento_id']);
            
            if (!$stmt->execute()) {
                throw new Exception("Erro ao criar escala principal");
            }
            
            $escala_id = $this->conn->lastInsertId();

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
                        throw new Exception("Erro ao inserir detalhe da escala");
                    }
                }
            }

            $this->conn->commit();
            return true;

        } catch (Exception $e) {
            $this->conn->rollBack();
            throw $e;
        }
    }

    // Função para listar todas as escalas
    public function listar() {
        $query = "SELECT * FROM " . $this->table_principal . " ORDER BY dataEscala DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt;
    }

    // Função para listar escalas com filtro
    public function listarPorFiltro($dataInicial, $dataFinal, $eventoFiltro = null) {
        $sql = "SELECT e.*, ev.titulo as evento_titulo 
                FROM escalas e 
                INNER JOIN eventos ev ON e.evento_id = ev.id 
                WHERE e.dataEscala BETWEEN :data_inicial AND :data_final";
        
        $params = [
            ':data_inicial' => $dataInicial,
            ':data_final' => $dataFinal
        ];

        if (!empty($eventoFiltro)) {
            $sql .= " AND ev.titulo LIKE :evento";
            $params[':evento'] = "%$eventoFiltro%";
        }

        $sql .= " ORDER BY e.dataEscala ASC";

        try {
            $stmt = $this->conn->prepare($sql);
            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }
            $stmt->execute();
            return $stmt;
        } catch (PDOException $e) {
            throw $e;
        }
    }


    public function buscarPorId($id) {
        try {
            // Buscar detalhes do evento e escala
            $query = "SELECT e.*, e.dataEscala, ev.titulo as evento_titulo
                     FROM " . $this->table_principal . " e
                     LEFT JOIN eventos ev ON e.evento_id = ev.id
                     WHERE e.id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $escala = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$escala) {
                return null;
            }

            // Buscar músicos únicos desta escala
            $query = "SELECT DISTINCT m.id, m.nome, m.instrumento
                     FROM " . $this->table_detalhes . " ed
                     LEFT JOIN musicos m ON ed.musico_id = m.id 
                     WHERE ed.escala_id = :escala_id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':escala_id', $id);
            $stmt->execute();
            $escala['musicos'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Buscar músicas com seus respectivos tons
            $query = "SELECT DISTINCT 
                        m.id as musica_id,
                        m.titulo as musica_titulo,
                        ed.tom_id
                     FROM " . $this->table_detalhes . " ed
                     LEFT JOIN musicas m ON ed.musica_id = m.id
                     WHERE ed.escala_id = :escala_id
                     ORDER BY m.titulo";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':escala_id', $id);
            $stmt->execute();
            $escala['musicas'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Buscar detalhes completos (relacionamento músico-música-tom)
            $query = "SELECT 
                        ed.musico_id,
                        ed.musica_id,
                        ed.tom_id
                     FROM " . $this->table_detalhes . " ed
                     WHERE ed.escala_id = :escala_id";
            $stmt = $this->conn->prepare($query);
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