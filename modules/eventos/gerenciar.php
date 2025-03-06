<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <title>Musicalize - Eventos</title>
        <link rel="icon" type="image/x-icon" href="../../assets/css/img/favicon.ico">
        <meta name="description" content="Musicalize é um sistema de gerenciamento de escalas de músicos.">
        <meta name="author" content="Web FS">
        <meta name="keywords" content="escalas, músicos, música, gerenciamento">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
        <link rel="stylesheet" href="../../assets/css/style.css">
        <style>
            .table th {
                background-color: var(--bs-gray-100);
            }
            .btn-action {
                padding: 0.25rem 0.5rem;
                font-size: 0.875rem;
            }
        </style>
    </head>
    <body>
        <div id="webcrumbs" class="min-h-screen">
            <div class="w-full bg-gray-50 font-sans">
                <?php include '../../includes/header.php'; ?>

                <div class="flex min-h-screen">
                    <?php include '../../includes/navbar.php'; ?>

                    <main class="flex-1 p-4 md:p-6 overflow-auto">
                        <div class="container mx-auto">
                        <div class="mb-4">
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <a href="index.php" class="btn btn-link text-muted p-0">
                                        <span class="material-symbols-outlined">arrow_back</span>
                                    </a>
                                    <h2 class="text-xl md:text-2xl font-bold">Gerenciar Eventos</h2>
                                </div>
                                <p class="text-gray-600 mb-0">Visualizar e editar tipos de eventos cadastrados</p>
                            </div>

                            <?php if (isset($_SESSION['success'])): ?>
                                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                                    <?php 
                                        echo $_SESSION['success'];
                                        unset($_SESSION['success']);
                                    ?>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            <?php endif; ?>

                            <?php if (isset($_SESSION['error'])): ?>
                                <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                                    <?php 
                                        echo $_SESSION['error'];
                                        unset($_SESSION['error']);
                                    ?>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            <?php endif; ?>

                            <div class="bg-white rounded-lg shadow-md p-6">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Título</th>
                                                <th>Tipo</th>
                                                <th class="text-center">Ações</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            require_once '../../classes/Database.php';
                                            require_once '../../classes/Evento.php';

                                            $database = new Database();
                                            $db = $database->getConnection();
                                            $evento = new Evento($db);
                                            $stmt = $evento->listar();

                                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                                echo "<tr>";
                                                echo "<td>" . htmlspecialchars($row['titulo']) . "</td>";
                                                echo "<td>" . htmlspecialchars($row['tipo']) . "</td>";
                                                echo "<td class='text-center'>
                                                    <button type='button' 
                                                        class='btn btn-warning btn-sm btn-action me-2'
                                                        data-bs-toggle='modal' 
                                                        data-bs-target='#editarModal' 
                                                        onclick='preencherModalEditar(" . json_encode($row) . ")'>
                                                        <i class='bi bi-pencil-square me-1'></i>Editar
                                                    </button>
                                                    <button type='button' 
                                                        class='btn btn-danger btn-sm btn-action'
                                                        onclick='confirmarExclusao(" . $row['id'] . ")'>
                                                        <i class='bi bi-trash me-1'></i>Excluir
                                                    </button>
                                                </td>";
                                                echo "</tr>";
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </main>
                </div>
            </div>
        </div>

        <!-- Modal de Edição -->
        <div class="modal fade" id="editarModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Editar Evento</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <form id="formEditar" action="../../api/eventos.php" method="POST">
                            <input type="hidden" name="editarEvento" value="1">
                            <input type="hidden" name="id" id="editId">
                            
                            <div class="mb-3">
                                <label for="editTitulo" class="form-label">Título</label>
                                <input type="text" class="form-control" id="editTitulo" name="titulo" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="editTipo" class="form-label">Tipo</label>
                                <textarea class="form-control" id="editTipo" name="tipo" rows="3"></textarea>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-primary" onclick="document.getElementById('formEditar').submit()">Salvar</button>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script>
            function preencherModalEditar(evento) {
                document.getElementById('editId').value = evento.id;
                document.getElementById('editTitulo').value = evento.titulo;
                document.getElementById('editTipo').value = evento.tipo || '';
            }

            function confirmarExclusao(id) {
                if (confirm('Tem certeza que deseja excluir este evento?')) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '../../api/eventos.php';
                    
                    const input1 = document.createElement('input');
                    input1.type = 'hidden';
                    input1.name = 'excluirEvento';
                    input1.value = '1';
                    form.appendChild(input1);
                    
                    const input2 = document.createElement('input');
                    input2.type = 'hidden';
                    input2.name = 'id';
                    input2.value = id;
                    form.appendChild(input2);
                    
                    document.body.appendChild(form);
                    form.submit();
                }
            }
        </script>
    </body>
</html>