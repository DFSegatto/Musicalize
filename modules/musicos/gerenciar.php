<?php
require_once '../../classes/Database.php';
require_once '../../classes/Musico.php';

$db = new Database();
$db = $db->getConnection();

$musico = new Musico($db);
// Verifica se o parâmetro mostrarInativos está presente na URL
$apenasAtivos = !isset($_GET['mostrarInativos']);
$musicos = $musico->listar($apenasAtivos);

?>

<html>
    <head>
        <title>Manancial - Músicos</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    </head>
    <body>
        <?php include '../../includes/header.php'; ?>
        <div class="container mt-4">
            <h1>Gerenciar Músicos</h1>
            <div class="mb-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="mostrarInativos" 
                           <?php echo isset($_GET['mostrarInativos']) ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="mostrarInativos">
                        Mostrar músicos inativos
                    </label>
                </div>
            </div>
            <div id="musicos-list">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Instrumento</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($musico = $musicos->fetch(PDO::FETCH_ASSOC)): ?>
                            <tr class="<?php echo $musico['status'] ? '' : 'table-secondary'; ?>">
                                <td><?php echo $musico['nome']; ?></td>
                                <td><?php echo $musico['instrumento']; ?></td>
                                <td><?php echo $musico['status'] ? 'Ativo' : 'Inativo'; ?></td>
                                <td>
                                    <?php if ($musico['status']): ?>
                                        <button class="btn btn-warning" onclick="alterarStatus(<?php echo $musico['id']; ?>, 'inativar')">
                                            <i class="bi bi-person-dash"></i> Inativar
                                        </button>
                                    <?php else: ?>
                                        <button class="btn btn-success" onclick="alterarStatus(<?php echo $musico['id']; ?>, 'ativar')">
                                            <i class="bi bi-person-check"></i> Ativar
                                        </button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php include '../../includes/footer.php'; ?>
    </body>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('mostrarInativos').addEventListener('change', function() {
            const url = new URL(window.location.href);
            if (this.checked) {
                url.searchParams.set('mostrarInativos', '1');
            } else {
                url.searchParams.delete('mostrarInativos');
            }
            window.location.href = url.toString();
        });

        function alterarStatus(id, acao) {
            const mensagem = acao === 'inativar' ? 
                'Tem certeza que deseja inativar este músico?' : 
                'Deseja reativar este músico?';

            if (confirm(mensagem)) {
                const formData = new FormData();
                formData.append('id', id.toString());
                formData.append('acao', acao);

                fetch('../../api/musicos.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Erro na requisição');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        alert(data.success);
                        location.reload();
                    } else if (data.error) {
                        alert('Erro: ' + data.error);
                    }
                })
                .catch(error => {
                    console.error('Erro:', error);
                    alert('Erro ao processar requisição: ' + error.message);
                });
            }
        }
    </script>
</html>