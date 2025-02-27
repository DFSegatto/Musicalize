<html>
    <head>
        <title>Manancial - Cadastrar Músico</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    </head>
    <body>
        <?php include '../../includes/header.php'; ?>
        <div class="container mt-4">
            <h1>Cadastrar Músico</h1>
            <form action="../../api/musicos.php" method="post">
                <div class="mb-3">
                    <label for="nome" class="form-label">Nome</label>
                    <input type="text" class="form-control" id="nome" name="nome" required>
                </div>
                <div class="mb-3">
                    <label for="instrumento" class="form-label">Instrumento</label>
                    <input type="text" class="form-control" id="instrumento" name="instrumento" required>
                </div>
                <div class="mb-3">
                    <label for="telefone" class="form-label">WhatsApp</label>
                    <input type="tel" class="form-control" id="telefone" name="telefone" 
                           placeholder="Ex: 5549999999999"
                           pattern="[0-9]{13,}"
                           title="Digite o número com código do país e DDD (ex: 5549999999999)">
                    <div class="form-text">Digite o número com código do país (55) e DDD, sem espaços ou caracteres especiais</div>
                </div>
                <button type="submit" class="btn btn-primary">Cadastrar</button>
            </form>
        </div>
        <?php include '../../includes/footer.php'; ?>
    </body>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</html>