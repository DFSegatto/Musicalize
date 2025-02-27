<html>
    <head>
        <title>Manancial - Sistema de Gerenciamento de Escalas</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    </head>
    <body>
        <?php include 'includes/header.php'; ?>
        <div class="container mt-4">
        <div class="row row-cols-1 row-cols-md-3 g-4">
            <!-- Primeiro ícone Playlist -->  
            <div class="col text-center">
            <a href="./modules/musicas/index.php"><img src="./assets/css/img/playlist.png" alt="Playlist Manancial" width="100" height="100"></a>
            <p>Playlist</p>
            </div>
            <!-- Segundo ícone Escala -->  
            <div class="col text-center">
            <a href="./modules/escalas/index.php"><img src="./assets/css/img/checklist.png" alt="Playlist Manancial" width="100" height="100"></a>
            <p>Escalas</p>
            </div>
            <!-- Terceiro ícone Eventos -->  
            <div class="col text-center">
            <a href="./modules/eventos/index.php"><img src="./assets/css/img/calendario.png" alt="Eventos Manancial" width="100" height="100"></a>
            <p>Eventos</p>
            </div>
            <!-- Quarto ícone Músicos -->  
            <div class="col text-center">
            <a href="./modules/musicos/index.php"><img src="./assets/css/img/musico.png" alt="Músicos Manancial" width="100" height="100"></a>
            <p>Músicos</p>
            </div>
            <!-- Quinto ícone Jejum -->  
            <div class="col text-center">
            <a href="./modules/jejum/index.php"><img src="./assets/css/img/jejum.png" alt="Jejum Manancial" width="100" height="100"></a>
            <p>Jejum</p>
            </div>
        </div>
        </div>
        <?php include 'includes/footer.php'; ?>
    </body>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</html>