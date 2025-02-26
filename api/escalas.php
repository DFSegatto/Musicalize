<?php
session_start();
require_once '../classes/Database.php';
require_once '../classes/Escala.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['criarEscala'])) {
    
    try {
        $database = new Database();
        $db = $database->getConnection();
        $escala = new Escala($db);

        // Validação da data
        if (empty($_POST['dataEscala'])) {
            throw new Exception("A data da escala é obrigatória");
        }

        // Validação do evento
        if (empty($_POST['evento_id'])) {
            throw new Exception("Selecione o tipo do evento");
        }

        // Validação dos músicos
        if (!isset($_POST['musicos']) || !is_array($_POST['musicos']) || empty($_POST['musicos'])) {
            throw new Exception("Selecione pelo menos um músico para a escala");
        }

        // Validação das músicas e tons
        if (!isset($_POST['musicas']) || !is_array($_POST['musicas']) || empty($_POST['musicas'])) {
            throw new Exception("Selecione pelo menos uma música para a escala");
        }

        if (!isset($_POST['tons']) || !is_array($_POST['tons'])) {
            throw new Exception("Tons não foram definidos corretamente");
        }
        
        // Formata a data
        $data = DateTime::createFromFormat('d/m/Y', $_POST['dataEscala']);
        if (!$data) {
            throw new Exception("Data inválida");
        }
        $dataFormatada = $data->format('Y-m-d');

        // Prepara os dados
        $dadosEscala = [
            'dataEscala' => $dataFormatada,
            'evento_id' => $_POST['evento_id'],
            'musicos' => $_POST['musicos'],
            'musicas' => $_POST['musicas'],
            'tons' => $_POST['tons']
        ];

        // Tenta criar a escala
        if ($escala->criar($dadosEscala)) {
            $_SESSION['success'] = "Escala criada com sucesso!";
            error_log("Escala criada com sucesso!");
        } else {
            throw new Exception("Erro ao criar escala");
        }

    } catch (Exception $e) {
        error_log("Erro ao criar escala: " . $e->getMessage());
        $_SESSION['error'] = "Erro ao criar escala: " . $e->getMessage();
    }

    // Redireciona de volta
    header('Location: ../modules/escalas/criar.php');
    exit;
}

// Se chegou aqui, método não é POST ou não é criação de escala
error_log("Requisição inválida para escalas.php");
$_SESSION['error'] = "Requisição inválida";
header('Location: ../modules/escalas/criar.php');
exit;
?>