<html>
    <head>
        <title>Manancial - Escalas</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    </head>
    <body>
        <?php include '../../includes/header.php'; ?>
        <div class="container mt-4">
        <div class="row row-cols-1 row-cols-md-2 g-4">
            <!-- Primeiro ícone Inserir Escala -->  
            <div class="col text-center">
            <a href="criar.php"><img src="../../assets/css/img/inserir.png" alt="Inserir Escala" width="100" height="100"></a>
            <p>Inserir Escala</p>
            </div>
            <!-- Segundo ícone Gerenciar Escalas -->  
            <div class="col text-center">
            <a href="gerenciar.php"><img src="../../assets/css/img/gerenciar.png" alt="Gerenciar Escalas" width="100" height="100"></a>
            <p>Gerenciar Escalas</p>
            </div>
        </div>
        </div>
        <?php include '../../includes/footer.php'; ?>
    </body>
</html>