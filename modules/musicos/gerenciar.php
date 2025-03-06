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

<!DOCTYPE html>
<html>
    <head>
        <title>Musicalize - Gerenciar Músicos</title>
        <link rel="icon" type="image/x-icon" href="assets/css/img/favicon.ico">
        <meta name="description" content="Musicalize é um sistema de gerenciamento de escalas de músicos.">
        <meta name="author" content="Web FS">
        <meta name="keywords" content="escalas, músicos, música, gerenciamento">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
        <link rel="stylesheet" href="../../assets/css/style.css">
    </head>
    <body>
        <div id="webcrumbs" class="min-vh-100">
            <div class="bg-light">
                <?php include '../../includes/header.php'; ?>

                <div class="d-flex min-vh-100">
                    <?php include '../../includes/navbar.php'; ?>

                    <main class="flex-grow-1 overflow-auto bg-light">
                        <div class="container-fluid p-4">
                            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
                                <div class="mb-3 mb-md-0">
                                    <div class="d-flex align-items-center gap-2 mb-1">
                                        <a href="index.php" class="btn btn-link text-muted p-0">
                                            <span class="material-symbols-outlined">arrow_back</span>
                                        </a>
                                        <h1 class="h3 fw-bold mb-0">Gerenciar Músicos</h1>
                                    </div>
                                    <p class="text-muted mb-0">Gerencie os músicos cadastrados no sistema</p>
                                </div>
                                
                                <div class="form-check form-switch">
                                    <input type="checkbox" 
                                           class="form-check-input" 
                                           id="mostrarInativos" 
                                           <?php echo isset($_GET['mostrarInativos']) ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="mostrarInativos">Mostrar músicos inativos</label>
                                </div>
                            </div>

                            <div class="card border-0 shadow-sm">
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-hover align-middle mb-0">
                                            <thead class="bg-light">
                                                <tr>
                                                    <th class="ps-4">Nome</th>
                                                    <th>Instrumento</th>
                                                    <th>Status</th>
                                                    <th class="text-end pe-4">Ações</th>
                                                </tr>
                                            </thead>
                                            <tbody class="border-top-0">
                                                <?php while ($row = $musicos->fetch(PDO::FETCH_ASSOC)): ?>
                                                    <tr>
                                                        <td class="ps-4"><?php echo $row['nome']; ?></td>
                                                        <td><?php echo $row['instrumento']; ?></td>
                                                        <td>
                                                            <span class="badge <?php echo $row['status'] ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary'; ?>">
                                                                <?php echo $row['status'] ? 'Ativo' : 'Inativo'; ?>
                                                            </span>
                                                        </td>
                                                        <td class="text-end pe-4">
                                                            <?php if ($row['status']): ?>
                                                                <button onclick="alterarStatus(<?php echo $row['id']; ?>, 'inativar')" 
                                                                        class="btn btn-light btn-sm me-1" 
                                                                        title="Inativar">
                                                                    <span class="material-symbols-outlined">person_off</span>
                                                                </button>
                                                            <?php else: ?>
                                                                <button onclick="alterarStatus(<?php echo $row['id']; ?>, 'ativar')" 
                                                                        class="btn btn-light btn-sm me-1"
                                                                        title="Ativar">
                                                                    <span class="material-symbols-outlined">person_add</span>
                                                                </button>
                                                            <?php endif; ?>
                                                            
                                                            <button onclick="editarMusico(<?php echo $row['id']; ?>, '<?php echo $row['nome']; ?>', '<?php echo $row['instrumento']; ?>', '<?php echo $row['telefone']; ?>')" 
                                                                    class="btn btn-light btn-sm"
                                                                    title="Editar">
                                                                <span class="material-symbols-outlined">edit</span>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                <?php endwhile; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </main>
                </div>
            </div>
        </div>

        <!-- Modal de Edição -->
        <div class="modal fade" id="editarMusicoModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content border-0">
                    <div class="modal-header bg-primary text-white border-0">
                        <h5 class="modal-title">Editar Músico</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <form id="editarMusicoForm">
                            <input type="hidden" id="editMusicoId">
                            <div class="mb-3">
                                <label for="editNome" class="form-label small fw-medium">Nome</label>
                                <input type="text" class="form-control" id="editNome" required>
                            </div>
                            <div class="mb-3">
                                <label for="editInstrumento" class="form-label small fw-medium">Instrumento</label>
                                <input type="text" class="form-control" id="editInstrumento" required>
                            </div>
                            <div class="mb-3">
                                <label for="editTelefone" class="form-label small fw-medium">WhatsApp</label>
                                <input type="tel" 
                                       class="form-control" 
                                       id="editTelefone" 
                                       placeholder="Ex: 5549999999999"
                                       pattern="[0-9]{13,}"
                                       title="Digite o número com código do país e DDD (ex: 5549999999999)">
                                <div class="form-text">Digite o número com código do país (55) e DDD</div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-primary" onclick="salvarEdicao()">Salvar</button>
                    </div>
                </div>
            </div>
        </div>

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

            function editarMusico(id, nome, instrumento, telefone) {
                document.getElementById('editMusicoId').value = id;
                document.getElementById('editNome').value = nome;
                document.getElementById('editInstrumento').value = instrumento;
                document.getElementById('editTelefone').value = telefone;
                editModal.show();
            }

            function salvarEdicao() {
                const id = document.getElementById('editMusicoId').value;
                const nome = document.getElementById('editNome').value;
                const instrumento = document.getElementById('editInstrumento').value;
                const telefone = document.getElementById('editTelefone').value;

                if (!nome || !instrumento || !telefone) {
                    alert('Por favor, preencha todos os campos');
                    return;
                }

                const formData = new FormData();
                formData.append('id', id);
                formData.append('nome', nome);
                formData.append('instrumento', instrumento);
                formData.append('telefone', telefone);
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