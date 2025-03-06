<?php
// Configurar timezone para Brasil
date_default_timezone_set('America/Sao_Paulo');

require_once '../../classes/Database.php';
require_once '../../api/whatsapp_notificacao.php';

$notificador = new WhatsAppNotificacao();
$links = $notificador->gerarLinksJejum();
?>
<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <title>Musicalize - Enviar Mensagens de Jejum</title>
        <link rel="icon" type="image/x-icon" href="../../assets/css/img/favicon.ico">
        <meta name="description" content="Musicalize é um sistema de gerenciamento de escalas de músicos.">
        <meta name="author" content="Web FS">
        <meta name="keywords" content="escalas, músicos, música, gerenciamento">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
        <link rel="stylesheet" href="../../assets/css/style.css">
        <style>
            .whatsapp-button {
                background-color: #25D366;
                color: white;
            }
            .whatsapp-button:hover {
                background-color: #128C7E;
                color: white;
            }
        </style>
    </head>
    <body>
        <div id="webcrumbs" class="min-vh-100">
            <div class="w-100 bg-light">
                <?php include '../../includes/header.php'; ?>

                <div class="d-flex min-vh-100">
                    <?php include '../../includes/navbar.php'; ?>

                    <main class="flex-grow-1 overflow-auto bg-light">
                        <div class="container-fluid p-4">
                            <div class="mb-4">
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <a href="gerenciar.php" class="btn btn-link text-muted p-0">
                                        <span class="material-symbols-outlined">arrow_back</span>
                                    </a>
                                    <h2 class="h3 fw-bold mb-0">Enviar Mensagens de Jejum</h2>
                                </div>
                                <p class="text-muted mb-0">Envie notificações de jejum para os músicos via WhatsApp</p>
                            </div>

                            <div class="card border-0 shadow-sm">
                                <div class="card-body">
                                    <?php if (empty($links)): ?>
                                        <div class="alert alert-info mb-0">
                                            <div class="d-flex align-items-center">
                                                <span class="material-symbols-outlined me-2">info</span>
                                                <span>Não há músicos com jejum programado para hoje.</span>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <div class="alert alert-info mb-4">
                                            <div class="d-flex align-items-center">
                                                <span class="material-symbols-outlined me-2">info</span>
                                                <span>Clique nos botões abaixo para enviar as mensagens via WhatsApp.</span>
                                            </div>
                                        </div>
                                        
                                        <div class="list-group">
                                            <?php foreach ($links as $item): ?>
                                                <div class="list-group-item d-flex justify-content-between align-items-center border-start-0 border-end-0 py-3">
                                                    <div>
                                                        <h5 class="mb-0 fw-medium"><?php echo htmlspecialchars($item['nome']); ?></h5>
                                                    </div>
                                                    <a href="<?php echo $item['link']; ?>" target="_blank" 
                                                    class="btn whatsapp-button d-inline-flex align-items-center gap-2">
                                                        <i class="bi bi-whatsapp"></i>Enviar Mensagem
                                                    </a>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>
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