<?php
require_once '../../classes/Database.php';
require_once '../../classes/Jejum.php';

$db = new Database();
$db = $db->getConnection();

$jejuns = new Jejum($db);
$jejuns = $jejuns->listar();
?>

<html>
    <head>
        <title>Manancial - Jejum</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    </head>
    <body>
        <?php include '../../includes/header.php'; ?>
        <div class="container mt-4">
            <h1>Visualizar Jejum</h1>
            <div class="mb-3">
            <div id="jejum-list">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Músico</th>
                            <th>Dia da Semana</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($jejuns as $jejum) { ?>
                        <tr>
                            <td><?php echo $jejum['nome']; ?></td>
                            <td><?php echo $jejum['dia_semana']; ?></td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
                <button class="btn btn-primary" onclick="window.location.href='enviar_mensagens.php'">
                    <i class="bi bi-whatsapp me-2"></i>Enviar Mensagens de Jejum
                </button>
            </div>
        </div>
        </div>
        <?php include '../../includes/footer.php'; ?>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>