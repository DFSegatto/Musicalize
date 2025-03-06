<?php
// Configurações de erro e reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Configuração de timezone
date_default_timezone_set('America/Sao_Paulo');

// Configurações de sessão
session_start();

// Define o caminho base do projeto
$base_path = '';

// Detecta se está em ambiente de desenvolvimento
if ($_SERVER['SERVER_NAME'] === 'localhost' || $_SERVER['SERVER_NAME'] === '127.0.0.1') {
    $base_path = '/aplicativomanancial';
}

define('BASE_PATH', $base_path);

// Função auxiliar para gerar URLs
function url($path = '') {
    return BASE_PATH . $path;
}

// Constantes da aplicação
define('APP_URL', 'http://localhost/aplicativoManancial'); // Ajuste conforme seu ambiente

// Configurações do banco de dados
define('DB_HOST', 'localhost');
define('DB_NAME', 'manancial');
define('DB_USER', 'root');
define('DB_PASS', 'root');

// Funções de utilidade global
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function checkPermission($requiredRoles) {
    if (!isset($_SESSION['user_role'])) {
        return false;
    }
    return in_array($_SESSION['user_role'], $requiredRoles);
}

// Função para tratamento de erros
function handleError($errno, $errstr, $errfile, $errline) {
    error_log("Erro [$errno]: $errstr em $errfile na linha $errline");
    return true;
}
set_error_handler('handleError');

spl_autoload_register(function ($class) {
    $file = BASE_PATH . '/classes/' . $class . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});

// Funções de segurança
function sanitizeInput($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

// Verificação de CSRF
function validateCSRF() {
    if (!isset($_SESSION['csrf_token']) || !isset($_POST['csrf_token']) ||
        $_SESSION['csrf_token'] !== $_POST['csrf_token']) {
        die('Erro de validação CSRF');
    }
} 