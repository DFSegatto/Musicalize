<?php
session_start();
header('Content-Type: application/json');

include_once '../classes/Database.php';
include_once '../classes/Jejum.php';

$db = new Database();
$db = $db->getConnection();

$jejum = new Jejum($db);

if($_SERVER['REQUEST_METHOD'] == "POST"){
    try {
        // Processa cada músico e seu dia de jejum
        foreach ($_POST['dia_semana'] as $musico_id => $dia) {
            $jejum->cadastrar($musico_id, $dia);
        }
        
        $_SESSION['mensagem'] = 'Jejum cadastrados com sucesso!';
        header('Location: ../modules/jejum/gerenciar.php');
        exit;
    } catch (PDOException $e) {
        $_SESSION['mensagem'] = 'Erro ao cadastrar: ' . $e->getMessage();
        header('Location: ../modules/jejum/cadastrar.php');
        exit;
    }
}

if($_SERVER['REQUEST_METHOD'] == "GET"){
    $jejums = $jejum->listar();
    echo json_encode($jejums);
}


