<?php
session_start();
header('Content-Type: application/json'); // Garante que a resposta será JSON
require_once '../classes/Database.php';
require_once '../classes/Musico.php';

$db = new Database();
$db = $db->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['acao'])) {
        try {
            $musico = new Musico($db);
            
            // Validação dos dados recebidos
            if (!isset($_POST['id']) || !isset($_POST['acao'])) {
                throw new Exception('Parâmetros inválidos');
            }
            
            $id = intval($_POST['id']); // Garante que o ID é um número
            $acao = $_POST['acao'];

            if ($acao === 'inativar') {
                $musico->inativar($id);
                echo json_encode(['success' => 'Músico inativado com sucesso!']);
            } else if ($acao === 'ativar') {
                $musico->ativar($id);
                echo json_encode(['success' => 'Músico ativado com sucesso!']);
            } else {
                throw new Exception('Ação inválida');
            }
            
        } catch(Exception $e) {
            http_response_code(400);
            echo json_encode(['error' => $e->getMessage()]);
            exit;
        }
    } else {
        try {
            $nome = $_POST['nome'];
            $instrumento = $_POST['instrumento'];

            $musico = new Musico($db);
            $musico->cadastrar($nome, $instrumento);
            header('Location: ../modules/musicos/cadastrar.php');
        } catch(PDOException $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
} else if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        $musico = new Musico($db);
        // Verifica se deve mostrar inativos
        $apenasAtivos = !isset($_GET['mostrarInativos']);
        $musicos = $musico->listar($apenasAtivos);
        echo json_encode($musicos->fetchAll(PDO::FETCH_ASSOC));
    } catch(PDOException $e) {
        http_response_code(400);
        echo json_encode(['error' => $e->getMessage()]);
        exit;
    }
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Método não permitido']);
    exit;
}



