<?php
// Inicialização e configuração
define('REQUIRED_ROLES', ['admin']);

// Inclusões necessárias
require_once '../../config/init.php';
require_once '../../classes/Musico.php';
require_once '../../classes/Database.php';

$database = new Database();
$db = $database->getConnection();

$musico = new Musico($db);

// Tratamento de mensagens
$alertMessages = [];
if (isset($_SESSION['success'])) {
    $alertMessages['success'] = $_SESSION['success'];
    unset($_SESSION['success']);
}
if (isset($_SESSION['error'])) {
    $alertMessages['error'] = $_SESSION['error'];
    unset($_SESSION['error']);
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Musicalize - Cadastrar Músico</title>
        <link rel="icon" type="image/x-icon" href="assets/css/img/favicon.ico">
        <meta name="description" content="Musicalize é um sistema de gerenciamento de escalas de músicos.">
        <meta name="author" content="Web FS">
        <meta name="keywords" content="escalas, músicos, música, gerenciamento">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
        <link rel="stylesheet" href="../../assets/css/style.css">
    </head>
    <body>
        <div id="webcrumbs" class="min-vh-100">
            <div class="bg-light">
                <?php include '../../includes/header.php'; ?>

                <div class="d-flex min-vh-100">
                    <?php include '../../includes/navbar.php'; ?>

                    <main class="flex-grow-1 overflow-auto bg-light">
                        <div class="container-fluid p-4">
                            <div class="mb-4">
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <a href="index.php" class="btn btn-link text-muted p-0">
                                        <span class="material-symbols-outlined">arrow_back</span>
                                    </a>
                                    <h1 class="h3 fw-bold mb-0">Cadastrar Músico</h1>
                                </div>
                                <p class="text-muted mb-0">Preencha os dados do novo músico</p>
                            </div>

                            <div class="row justify-content-center">
                                <div class="col-12 col-md-8 col-lg-6">
                                    <div class="card border-0 shadow-sm">
                                        <div class="card-body p-4">
                                            <form action="../../api/musicos.php" method="post">
                                                <div class="mb-4">
                                                    <label for="nome" class="form-label small fw-medium">Nome</label>
                                                    <input type="text" 
                                                           class="form-control" 
                                                           id="nome" 
                                                           name="nome" 
                                                           required>
                                                </div>
                                                
                                                <div class="mb-4">
                                                    <label for="instrumento" class="form-label small fw-medium">Instrumento</label>
                                                    <input type="text" 
                                                           class="form-control" 
                                                           id="instrumento" 
                                                           name="instrumento" 
                                                           required>
                                                </div>
                                                
                                                <div class="mb-4">
                                                    <label for="telefone" class="form-label small fw-medium">WhatsApp</label>
                                                    <input type="tel" 
                                                           class="form-control" 
                                                           id="telefone" 
                                                           name="telefone" 
                                                           placeholder="Ex: 5549999999999"
                                                           pattern="[0-9]{13,}"
                                                           title="Digite o número com código do país e DDD (ex: 5549999999999)">
                                                    <div class="form-text">Digite o número com código do país (55) e DDD, sem espaços ou caracteres especiais</div>
                                                </div>

                                                <div class="d-flex gap-2 justify-content-end">
                                                    <a href="index.php" class="btn btn-light">Cancelar</a>
                                                    <button type="submit" class="btn btn-primary">
                                                        Cadastrar
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </main>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>