<?php
session_start();
header('Content-Type: application/json');

require_once '../classes/Database.php';
require_once '../classes/Evento.php';

$db = new Database();
$db = $db->getConnection();
$evento = new Evento($db);

if (isset($_POST['cadastrarEvento'])) {
    try {
        $titulo = $_POST['titulo'];
        $tipo = $_POST['tipo'];

        $evento->cadastrar($titulo, $tipo);

        $_SESSION['success'] = "Evento cadastrado com sucesso!";
        header('Location: ../modules/eventos/cadastrar.php');
        exit;
    } catch (Exception $e) {
        $_SESSION['error'] = "Erro ao cadastrar evento: " . $e->getMessage();
        header('Location: ../modules/eventos/cadastrar.php');
        exit;
    }
}
?>