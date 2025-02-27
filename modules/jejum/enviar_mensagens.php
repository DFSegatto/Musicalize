<?php
require_once '../../classes/Database.php';
require_once '../../api/whatsapp_notificacao.php';

$notificador = new WhatsAppNotificacao();
$links = $notificador->gerarLinksJejum();
?>

<html>
    <head>
        <title>Manancial - Enviar Mensagens de Jejum</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
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
        <?php include '../../includes/header.php'; ?>
        <div class="container mt-4">
            <h1>Enviar Mensagens de Jejum</h1>
            
            <?php if (empty($links)): ?>
                <div class="alert alert-info">
                    Não há músicos com jejum programado para hoje.
                </div>
            <?php else: ?>
                <div class="alert alert-info">
                    Clique nos botões abaixo para enviar as mensagens via WhatsApp.
                </div>
                
                <div class="list-group">
                    <?php foreach ($links as $item): ?>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="mb-1"><?php echo htmlspecialchars($item['nome']); ?></h5>
                            </div>
                            <a href="<?php echo $item['link']; ?>" target="_blank" 
                               class="btn whatsapp-button">
                                <i class="bi bi-whatsapp me-2"></i>Enviar Mensagem
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <div class="mt-4">
                    <a href="gerenciar.php" class="btn btn-secondary">
                        <i class="bi bi-arrow-left me-2"></i>Voltar
                    </a>
                </div>
            <?php endif; ?>
        </div>
        <?php include '../../includes/footer.php'; ?>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html> 