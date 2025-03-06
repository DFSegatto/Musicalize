<?php
session_start();
header('Content-Type: application/json'); // Garante que a resposta será JSON
require_once '../classes/Database.php';
require_once '../classes/Musico.php';

$db = new Database();
$db = $db->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $musico = new Musico($db);

    // Verifica se é uma ação de ativar/inativar
    if (isset($_POST['acao'])) {
        try {
            if (!isset($_POST['id'])) {
                throw new Exception('ID do músico não informado');
            }
            
            $id = intval($_POST['id']);
            $acao = $_POST['acao'];

            if ($acao === 'inativar') {
                $musico->inativar($id);
                $_SESSION['success'] = 'Músico inativado com sucesso!';
                echo json_encode(['success' => 'Músico inativado com sucesso!']);
            } else if ($acao === 'ativar') {
                $musico->ativar($id);
                $_SESSION['success'] = 'Músico ativado com sucesso!';
                echo json_encode(['success' => 'Músico ativado com sucesso!']);
            } else if ($acao === 'editarMusico') {
                $telefone = isset($_POST['telefone']) ? trim($_POST['telefone']) : null;
                
                // Validação básica do formato do telefone
                if ($telefone && !preg_match('/^[0-9]{13,}$/', $telefone)) {
                    throw new Exception('Formato de telefone inválido. Use apenas números, incluindo código do país e DDD.');
                }
                
                $musico->editar($id, $_POST['nome'], $_POST['instrumento'], $telefone);
                $_SESSION['success'] = 'Músico editado com sucesso!';
                echo json_encode(['success' => 'Músico editado com sucesso!']);
            } else {
                throw new Exception('Ação inválida');
            }
        } catch(Exception $e) {
            http_response_code(400);
            $_SESSION['error'] = 'Erro ao realizar ação: ' . $e->getMessage();
            echo json_encode(['error' => $e->getMessage()]);
            exit;
        }
    }
    else {
        try {
            if (!isset($_POST['nome']) || !isset($_POST['instrumento']) || !isset($_POST['telefone'])) {
                throw new Exception('Dados incompletos para cadastro');
            }

            $telefone = isset($_POST['telefone']) ? trim($_POST['telefone']) : null;
            
            // Validação básica do formato do telefone
            if ($telefone && !preg_match('/^[0-9]{13,}$/', $telefone)) {
                throw new Exception('Formato de telefone inválido. Use apenas números, incluindo código do país e DDD.');
            }

            $musico->cadastrar($_POST['nome'], $_POST['instrumento'], $telefone);
            $_SESSION['success'] = 'Músico cadastrado com sucesso!';
            header('Location: ../modules/musicos/cadastrar.php');
            exit;
        } catch(Exception $e) {
            http_response_code(400);
            $_SESSION['error'] = 'Erro ao cadastrar músico: ' . $e->getMessage();
            header('Location: ../modules/musicos/cadastrar.php');
            exit;
        }
    }
} 
else if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        $musico = new Musico($db);
        $apenasAtivos = !isset($_GET['mostrarInativos']);
        $musicos = $musico->listar($apenasAtivos);
        echo json_encode($musicos->fetchAll(PDO::FETCH_ASSOC));
    } catch(Exception $e) {
        http_response_code(400);
        echo json_encode(['error' => $e->getMessage()]);
        exit;
    }
} 
else {
    http_response_code(405);
    echo json_encode(['error' => 'Método não permitido']);
    exit;
}

?>