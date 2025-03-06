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
    <div id="webcrumbs" class="min-h-screen">
            <div class="w-full bg-gray-50 font-sans">
                <?php include '../../includes/header.php'; ?>

                <div class="flex min-h-screen">
                    <?php include '../../includes/navbar.php'; ?>

                    <main class="flex-1 p-4 md:p-6 overflow-auto">
                        <div class="container mx-auto">
                            <div class="mb-4">
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <a href="index.php" class="btn btn-link text-muted p-0">
                                        <span class="material-symbols-outlined">arrow_back</span>
                                    </a>
                                    <h2 class="text-xl md:text-2xl font-bold">Cadastrar Músico</h2>
                                </div>
                                <p class="text-gray-600 mb-0">Preencha os dados do novo músico</p>
                            </div>

                        <!-- Alertas -->
                        <?php foreach ($alertMessages as $type => $message): ?>
                            <div class="alert alert-<?php echo $type === 'error' ? 'danger' : $type; ?> alert-dismissible fade show mb-4">
                                <?php echo htmlspecialchars($message); ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endforeach; ?>

                            <div class="bg-white rounded-lg shadow-md p-6">
                                <form action="../../api/musicos.php" method="POST" class="needs-validation" novalidate>
                                    <input type="hidden" name="cadastrarMusico" value="1">
                                    
                                    <div class="mb-4">
                                        <label for="nome" class="form-label">Nome do Músico</label>
                                        <input type="text" class="form-control" id="nome" name="nome" required>
                                        <div class="invalid-feedback">
                                            Por favor, insira o nome do músico.
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <label for="instrumento" class="form-label">Instrumento</label>
                                        <input type="text" class="form-control" id="instrumento" name="instrumento" required>
                                        <div class="invalid-feedback">
                                            Por favor, insira o instrumento.
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <label for="telefone" class="form-label">WhatsApp</label>
                                        <input type="tel" 
                                               class="form-control" 
                                               id="telefone" 
                                               name="telefone" 
                                               placeholder="Ex: 5549999999999"
                                               pattern="[0-9]{13,}"
                                               required>
                                        <div class="form-text text-muted">Digite o número com código do país (55) e DDD, sem espaços ou caracteres especiais</div>
                                        <div class="invalid-feedback">
                                            Por favor, insira um número válido com código do país e DDD.
                                        </div>
                                    </div>

                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="bi bi-check-lg me-2"></i>Cadastrar Músico
                                    </button>
                                </form>
                            </div>
                        </div>
                    </main>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script>
            // Form validation
            (function () {
                'use strict'
                var forms = document.querySelectorAll('.needs-validation')
                Array.prototype.slice.call(forms)
                    .forEach(function (form) {
                        form.addEventListener('submit', function (event) {
                            if (!form.checkValidity()) {
                                event.preventDefault()
                                event.stopPropagation()
                            }
                            form.classList.add('was-validated')
                        }, false)
                    })
            })()
        </script>
    </body>
</html>