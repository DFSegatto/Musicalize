<?php
require_once '../../classes/Database.php';
require_once '../../classes/Evento.php';

$db = new Database();
$db = $db->getConnection();

$evento = new Evento($db);
$eventos = $evento->listar();
?>

<html>
    <head>
        <title>Manancial - Eventos</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    </head>
    <body>
        <?php include '../../includes/header.php'; ?>
        <div class="container mt-4">
            <h1>Gerenciar Eventos</h1>
            <div class="mb-3">
            <div id="eventos-list">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Título</th>
                            <th>Tipo</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($evento = $eventos->fetch(PDO::FETCH_ASSOC)): ?>
                            <tr>
                                <td><?php echo $evento['titulo']; ?></td>
                                <td><?php echo $evento['tipo']; ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
        </div>
        <?php include '../../includes/footer.php'; ?>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>