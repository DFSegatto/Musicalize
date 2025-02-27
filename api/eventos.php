<?php
session_start();
header('Content-Type: application/json');

require_once '../classes/Database.php';
require_once '../classes/Evento.php';

$db = new Database();
$db = $db->getConnection();
$evento = new Evento($db);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $titulo = $_POST['titulo'];
        $tipo = $_POST['tipo'];

        $evento->cadastrar($titulo, $tipo);

        echo json_encode(['success' => 'Evento cadastrado com sucesso!']);
        $_SESSION['mensagem'] = 'Evento cadastrado com sucesso!';
        header('Location: ../modules/eventos/cadastrar.php');
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
        $_SESSION['mensagem'] = 'Erro ao cadastrar evento!';
        header('Location: ../modules/eventos/cadastrar.php');
    }
}
?>