<?php
    session_start();
?>

<html>
    <head>
        <title>Manancial - Cadastrar Evento</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    </head>
    <body>
        <?php include '../../includes/header.php'; ?>
        <div class="container mt-4">
            <?php if (isset($_SESSION['mensagem'])) { ?>
                <div id="mensagem" class="alert alert-success" role="alert">
                    <?php echo $_SESSION['mensagem']; ?>
                    <?php unset($_SESSION['mensagem']); ?>
                </div>
            <?php } ?>
            <h1>Cadastrar Evento</h1>
            <form action="../../api/eventos.php" method="post">
                <div class="mb-3">
                    <label for="titulo" class="form-label">Título</label>
                    <input type="text" class="form-control" id="titulo" name="titulo">
                </div>
                <div class="mb-3">
                    <label for="tipo" class="form-label">Tipo de Evento</label>
                    <select class="form-select" id="tipo" name="tipo">
                        <option value="Culto">Culto</option>
                        <option value="Ensaio">Ensaio</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Cadastrar</button>
            </form>
        </div>
        <?php include '../../includes/footer.php'; ?>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    </body>
</html>