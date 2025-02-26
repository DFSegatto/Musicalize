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
                                    <button class="btn btn-danger" onclick="editarMusico(<?php echo $musico['id']; ?>, '<?php echo $musico['nome']; ?>', '<?php echo $musico['instrumento']; ?>')">
                                        <i class="bi bi-pencil"></i> Editar
                                    </button>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Add Modal -->
        <div class="modal fade" id="editarMusicoModal" tabindex="-1" aria-labelledby="editarMusicoModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editarMusicoModalLabel">Editar Músico</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="editarMusicoForm">
                            <input type="hidden" id="editMusicoId">
                            <div class="mb-3">
                                <label for="editNome" class="form-label">Nome</label>
                                <input type="text" class="form-control" id="editNome" required>
                            </div>
                            <div class="mb-3">
                                <label for="editInstrumento" class="form-label">Instrumento</label>
                                <input type="text" class="form-control" id="editInstrumento" required>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-primary" onclick="salvarEdicao()">Salvar</button>
                    </div>
                </div>
            </div>
        </div>

        <?php include '../../includes/footer.php'; ?>
        
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

        let editModal;
        document.addEventListener('DOMContentLoaded', function() {
            editModal = new bootstrap.Modal(document.getElementById('editarMusicoModal'));
        });

        function editarMusico(id, nome, instrumento) {
            document.getElementById('editMusicoId').value = id;
            document.getElementById('editNome').value = nome;
            document.getElementById('editInstrumento').value = instrumento;
            editModal.show();
        }

        function salvarEdicao() {
            const id = document.getElementById('editMusicoId').value;
            const nome = document.getElementById('editNome').value;
            const instrumento = document.getElementById('editInstrumento').value;

            if (!nome || !instrumento) {
                alert('Por favor, preencha todos os campos');
                return;
            }

            const formData = new FormData();
            formData.append('id', id);
            formData.append('nome', nome);
            formData.append('instrumento', instrumento);
            formData.append('acao', 'editarMusico');

            fetch('../../api/musicos.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.success);
                    editModal.hide();
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
        </script>
    </body>
</html>