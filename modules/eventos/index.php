<?php

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
            <div class="row row-cols-1 row-cols-md-2 g-4">
                <div class="col text-center">
                    <a href="cadastrar.php"><img src="../../assets/css/img/inserir.png" alt="Inserir Evento" width="100" height="100"></a>
                    <p>Cadastrar Evento</p>
                </div>
                <div class="col text-center">
                    <a href="gerenciar.php"><img src="../../assets/css/img/gerenciar.png" alt="Gerenciar Eventos" width="100" height="100"></a>
                    <p>Gerenciar Eventos</p>
                </div>
            </div>
        </div>
        <?php include '../../includes/footer.php'; ?>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>