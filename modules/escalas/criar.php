<?php
// Inicialização e configuração
define('REQUIRED_ROLES', ['admin', 'musician']);

// Inclusões necessárias
require_once '../../config/init.php';
require_once '../../classes/Database.php';
require_once '../../classes/Musico.php';
require_once '../../classes/Musica.php';
require_once '../../classes/Evento.php';
require_once '../../classes/Tom.php';

// Geração do token CSRF
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

try {
    $database = new Database();
    $db = $database->getConnection();
    
    $musico = new Musico($db);
    $musica = new Musica($db);
    $evento = new Evento($db);
    $tom = new Tom($db);
    
    $musicos = $musico->listar();
    $musicas = $musica->listar();
    $eventos = $evento->listar();
    $tons = $tom->listar();
} catch (Exception $e) {
    error_log("Erro ao carregar dados: " . $e->getMessage());
    $_SESSION['error'] = "Ocorreu um erro ao carregar os dados. Por favor, tente novamente.";
}

// Tratamento de mensagens
$alertMessages = [];
if (isset($_SESSION['success'])) {
    $alertMessages['success'] = $_SESSION['success'];
    unset($_SESSION['success']);
}
if (isset($_SESSION['error'])) {
    $alertMessages['error'] = $_SESSION['error'];
    unset($_SESSION['error']);
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Sistema de criação de escalas musicais">
    <meta name="author" content="Manancial">
    <title>Manancial - Escalas</title>

    <!-- CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
    <style>
        .datepicker {
            border: 2px solid black;
            border-radius: 5px;
            background: none;
            width: 120px;
            text-align: center;
        }
        .table-condensed{
            background-color: white !important;
            border: 1px solid black !important;
        }
        .card{
            width: 500px;
            margin-bottom: 300px;
        }
        .row{
            margin-left: 0px !important;
            margin-right: 0px !important;
        }
        .card-body-tons{
            padding: 10px;
        }
        .tons-card {
            border: 1px solid rgba(0,0,0,.125);
            border-radius: 0.25rem;
            padding: 15px;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <?php include '../../includes/header.php'; ?>
    
    <div class="container mt-4">
        <!-- Exibição de alertas -->
        <?php foreach ($alertMessages as $type => $message): ?>
            <div class="alert alert-<?php echo $type === 'error' ? 'danger' : $type; ?> alert-dismissible fade show">
                <?php echo htmlspecialchars($message); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endforeach; ?>

        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="mx-auto" style="width: 500px;">
                    <h1>Criar Escala</h1>
                    <div class="card">
                        <div class="card-body">
                            <form id="escalaForm" action="../../api/escalas.php" method="POST" onsubmit="return validateForm()">
                                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                <input type="hidden" name="criarEscala" value="1">
                                
                                <div class="form-group">
                                    <h5 class="card-title">
                                        Escala dia <input type="text" class="datepicker" id="dataEscala" name="dataEscala" 
                                               value="<?php echo date('d/m/Y'); ?>" required>
                                    </h5>
                                </div>

                                <select class="form-select mb-3" name="evento_id" required>
                                    <option value="">Selecione o tipo do evento</option>
                                    <?php while($row = $eventos->fetch(PDO::FETCH_ASSOC)): ?>
                                        <option value="<?php echo htmlspecialchars($row['id']); ?>">
                                            <?php echo htmlspecialchars($row['titulo']); ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>

                                <button type="button" class="btn btn-primary" onclick="abrirModalMusicos()">
                                    Selecionar Músicos
                                </button>
                                <div id="musicosSelecionados" class="mb-3">
                                    <div class="alert alert-info" role="alert">
                                        Nenhum músico selecionado
                                    </div>
                                </div>
                                <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#musicasModal">
                                    Selecionar Músicas
                                </button>
                                <div id="musicasSelecionadas" class="mt-3"></div>
                                <div id="tonsMusicasSelecionadas" class="mb-3">
                                    <!-- Os tons serão inseridos aqui dinamicamente -->
                                </div>
                                
                                <!-- Adiciona inputs ocultos para armazenar valores selecionados -->
                                <div id="hiddenInputs"></div>
                                
                                <button type="submit" class="btn btn-primary mb-3" name="criarEscala">Criar escala</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modais com melhorias de acessibilidade -->
        <div class="modal fade" id="musicosModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Selecionar Músicos</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Debug info -->
                        <div id="modalDebug" style="display:none;">
                            <pre></pre>
                        </div>
                        
                        <!-- Lista de músicos -->
                        <?php if ($musicos && $musicos->rowCount() > 0): ?>
                            <div class="list-group">
                                <?php while ($musico = $musicos->fetch(PDO::FETCH_ASSOC)): ?>
                                    <div class="list-group-item">
                                        <div class="form-check">
                                            <input type="checkbox" 
                                                   class="form-check-input musico-checkbox" 
                                                   id="musico<?php echo $musico['id']; ?>" 
                                                   value="<?php echo $musico['id']; ?>">
                                            <label class="form-check-label" 
                                                   for="musico<?php echo $musico['id']; ?>">
                                                <?php echo htmlspecialchars($musico['nome']); ?>
                                            </label>
                                        </div>
                                    </div>
                                <?php endwhile; ?>
                            </div>
                        <?php else: ?>
                            <p class="alert alert-warning">Nenhum músico cadastrado.</p>
                        <?php endif; ?>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                        <button type="button" class="btn btn-primary" onclick="confirmarSelecaoMusicos()">Confirmar</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="musicasModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Selecionar Músicas</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <?php if ($musicas && $musicas->rowCount() > 0): ?>
                            <div class="list-group">
                                <?php while ($musica = $musicas->fetch(PDO::FETCH_ASSOC)): ?>
                                    <div class="list-group-item">
                                        <div class="form-check">
                                            <input type="checkbox" 
                                                   class="form-check-input musica-checkbox" 
                                                   id="musica<?php echo $musica['id']; ?>" 
                                                   value="<?php echo $musica['id']; ?>">
                                            <label class="form-check-label" 
                                                   for="musica<?php echo $musica['id']; ?>">
                                                <?php echo htmlspecialchars($musica['titulo']); ?>
                                            </label>
                                        </div>
                                    </div>
                                <?php endwhile; ?>
                            </div>
                        <?php else: ?>
                            <p class="alert alert-warning">Nenhuma música cadastrada.</p>
                        <?php endif; ?>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                        <button type="button" class="btn btn-primary" onclick="confirmarSelecaoMusicas()">Confirmar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include '../../includes/footer.php'; ?>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/locales/bootstrap-datepicker.pt-BR.min.js"></script>
    
    <script>
    // Namespace principal
    const EscalaManager = {
        init() {
            this.initializeDatepicker();
            this.initializeEventListeners();
            this.setupFormValidation();
        },

        initializeDatepicker() {
            $('.datepicker').datepicker({
                format: 'dd/mm/yyyy',
                language: 'pt-BR',
                autoclose: true,
                todayHighlight: true,
                container: 'body'
            });
        },

        initializeEventListeners() {
            // Delegação de eventos para melhor performance
            $(document).on('change', '.musica-checkbox', this.handleMusicaCheckboxChange);
            $(document).on('change', '.tom-select', this.handleTomSelectChange);
            
            // Handlers para os modais
            $('#musicosModal').on('show.bs.modal', this.handleModalShow);
            $('#musicasModal').on('show.bs.modal', this.handleModalShow);
        },

        setupFormValidation() {
            const form = document.getElementById('escalaForm');
            form.addEventListener('submit', (e) => {
                if (!form.checkValidity()) {
                    e.preventDefault();
                    e.stopPropagation();
                }
                form.classList.add('was-validated');
            });
        },

        handleMusicaCheckboxChange(e) {
            const tomSelect = $(`#tom${this.value}`);
            const isChecked = this.checked;

            tomSelect.prop('disabled', !isChecked)
                    .toggleClass('required', isChecked);

            if (isChecked && (!tomSelect.val() || tomSelect.val() === 'Tom')) {
                tomSelect.find('option:eq(1)').prop('selected', true);
            }

            EscalaManager.updateMusicasList();
        },

        handleTomSelectChange(e) {
            const select = $(this);
            if (!select.val() || select.val() === 'Tom') {
                select.find('option:eq(1)').prop('selected', true);
            }
            EscalaManager.updateHiddenInputs();
        },

        updateMusicasList() {
            const selectedMusicas = $('.musica-checkbox:checked').map(function() {
                return {
                    id: this.value,
                    titulo: $(this).data('titulo')
                };
            }).get();

            this.renderMusicasList(selectedMusicas);
            this.updateHiddenInputs();
        },

        updateTonsSection(musicas) {
            const container = $('#tonsMusicasSelecionadas');
            if (musicas.length === 0) {
                container.empty();
                return;
            }

            let html = '<div class="tons-card">';
            html += '<h6 class="card-title">Selecione os tons:</h6>';
            
            musicas.forEach(musica => {
                html += `
                    <div class="mb-3">
                        <label class="form-label" for="tom${musica.id}">${musica.titulo}</label>
                        <select class="form-select tom-select" id="tom${musica.id}" name="tons[${musica.id}]" required>
                            <option value="">Selecione o tom</option>
                            <?php foreach($tons as $tom): ?>
                                <option value="<?php echo $tom['id']; ?>"><?php echo htmlspecialchars($tom['descricao']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                `;
            });
            
            html += '</div>';
            container.html(html);
        },

        renderMusicasList(musicas) {
            const container = $('#musicasSelecionadas');
            if (musicas.length === 0) {
                container.html(`
                    <div class="alert alert-info" role="alert">
                        Nenhuma música selecionada
                    </div>
                `);
                return;
            }

            const html = `
                <div class="alert alert-success">
                    <p><strong>Músicas selecionadas:</strong></p>
                    <ul class="mb-0">
                        ${musicas.map(m => `<li>${m.titulo}</li>`).join('')}
                    </ul>
                </div>
            `;
            container.html(html);

            // Adiciona inputs ocultos para as músicas selecionadas
            const hiddenInputs = $('#hiddenInputs');
            // Limpa inputs anteriores de músicas
            hiddenInputs.find('input[name="musicas[]"]').remove();
            musicas.forEach(musica => {
                hiddenInputs.append(`
                    <input type="hidden" name="musicas[]" value="${musica.id}">
                `);
            });
        },

        updateHiddenInputs() {
            const hiddenInputs = $('#hiddenInputs');
            hiddenInputs.empty();

            // Adiciona inputs para músicas selecionadas
            $('.musica-checkbox:checked').each(function() {
                const musicaId = $(this).val();
                hiddenInputs.append(`
                    <input type="hidden" name="musicas[]" value="${musicaId}">
                `);
            });

            // Adiciona inputs para tons selecionados
            $('.tom-select:not(:disabled)').each(function() {
                const musicaId = this.id.replace('tom', '');
                const tomId = $(this).val();
                if (tomId) {
                    hiddenInputs.append(`
                        <input type="hidden" name="tons[${musicaId}]" value="${tomId}">
                    `);
                }
            });
        },

        handleModalShow(e) {
            // Resetar seleções anteriores se necessário
            const modal = $(this);
            modal.find('input[type="checkbox"]').prop('checked', false);
        },

        renderMusicosList(musicos) {
            const container = $('#musicosSelecionados');
            if (musicos.length === 0) {
                container.html(`
                    <div class="alert alert-info" role="alert">
                        Nenhum músico selecionado
                    </div>
                `);
                return;
            }

            const html = `
                <div class="alert alert-success">
                    <p><strong>Músicos selecionados:</strong></p>
                    <ul class="mb-0">
                        ${musicos.map(m => `<li>${m.nome}</li>`).join('')}
                    </ul>
                </div>
            `;
            container.html(html);

            // Adiciona inputs ocultos para os músicos selecionados
            const hiddenInputs = $('#hiddenInputs');
            musicos.forEach(musico => {
                hiddenInputs.append(`
                    <input type="hidden" name="musicos[]" value="${musico.id}">
                `);
            });
        }
    };

    // Inicialização quando o documento estiver pronto
    $(document).ready(() => {
        EscalaManager.init();
    });

    // Variáveis globais para manter o estado
    let musicosSelecionados = [];
    let musicasSelecionadas = [];

    // Função para abrir o modal de músicos
    function abrirModalMusicos() {
        // Marca os checkboxes dos músicos já selecionados
        musicosSelecionados.forEach(musico => {
            const checkbox = document.querySelector(`#musico${musico.id}`);
            if (checkbox) checkbox.checked = true;
        });
        
        // Abre o modal
        const modal = new bootstrap.Modal(document.getElementById('musicosModal'));
        modal.show();
    }

    // Função para confirmar seleção de músicos
    function confirmarSelecaoMusicos() {
        try {
            // Pega todos os checkboxes marcados
            const checkboxes = document.querySelectorAll('.musico-checkbox:checked');
            console.log('Checkboxes de músicos marcados:', checkboxes.length);

            // Limpa a array de músicos selecionados
            musicosSelecionados = [];

            // Para cada checkbox marcado
            checkboxes.forEach(checkbox => {
                const id = checkbox.value;
                const label = document.querySelector(`label[for="musico${id}"]`);
                const nome = label ? label.textContent.trim() : `Músico ${id}`;
                
                musicosSelecionados.push({ id, nome });
            });

            // Atualiza o formulário
            atualizarFormularioMusicos();

            // Fecha o modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('musicosModal'));
            if (modal) modal.hide();

        } catch (error) {
            console.error('Erro ao confirmar seleção de músicos:', error);
        }
    }

    // Função para atualizar o formulário com os músicos selecionados
    function atualizarFormularioMusicos() {
        const hiddenContainer = document.getElementById('hiddenInputs');
        
        // Remove inputs antigos de músicos
        document.querySelectorAll('input[name="musicos[]"]').forEach(el => el.remove());
        
        // Cria novos inputs hidden para músicos
        musicosSelecionados.forEach(musico => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'musicos[]';
            input.value = musico.id;
            hiddenContainer.appendChild(input);
        });

        // Atualiza visualização
        const musicosList = document.getElementById('musicosSelecionados');
        if (musicosSelecionados.length > 0) {
            musicosList.innerHTML = `
                <div class="alert alert-success">
                    <h6>Músicos selecionados (${musicosSelecionados.length}):</h6>
                    <ul>${musicosSelecionados.map(m => `<li>${m.nome}</li>`).join('')}</ul>
                </div>`;
        } else {
            musicosList.innerHTML = `
                <div class="alert alert-info">Nenhum músico selecionado</div>`;
        }
    }

    // Função para confirmar seleção de músicas
    function confirmarSelecaoMusicas() {
        try {
            // Pega todos os checkboxes marcados
            const checkboxes = document.querySelectorAll('.musica-checkbox:checked');
            console.log('Checkboxes de músicas marcados:', checkboxes.length);

            // Limpa a array de músicas selecionadas
            musicasSelecionadas = [];

            // Para cada checkbox marcado
            checkboxes.forEach(checkbox => {
                const id = checkbox.value;
                const label = document.querySelector(`label[for="musica${id}"]`);
                const titulo = label ? label.textContent.trim() : `Música ${id}`;
                
                musicasSelecionadas.push({ 
                    id, 
                    titulo,
                    tom: '' // Tom inicial vazio
                });
            });

            // Atualiza o formulário
            atualizarFormularioMusicas();

            // Fecha o modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('musicasModal'));
            if (modal) modal.hide();

        } catch (error) {
            console.error('Erro ao confirmar seleção de músicas:', error);
        }
    }

    // Função para atualizar o formulário com as músicas selecionadas
    function atualizarFormularioMusicas() {
        const hiddenContainer = document.getElementById('hiddenInputs');
        
        // Remove inputs antigos de músicas e tons
        document.querySelectorAll('input[name="musicas[]"]').forEach(el => el.remove());
        document.querySelectorAll('input[name^="tons["]').forEach(el => el.remove());
        
        // Cria novos inputs hidden para músicas e tons
        musicasSelecionadas.forEach(musica => {
            // Input para música
            const inputMusica = document.createElement('input');
            inputMusica.type = 'hidden';
            inputMusica.name = 'musicas[]';
            inputMusica.value = musica.id;
            hiddenContainer.appendChild(inputMusica);

            // Input para tom
            if (musica.tom) {
                const inputTom = document.createElement('input');
                inputTom.type = 'hidden';
                inputTom.name = `tons[${musica.id}]`;
                inputTom.value = musica.tom;
                hiddenContainer.appendChild(inputTom);
            }
        });

        // Atualiza visualização
        const musicasList = document.getElementById('musicasSelecionadas');
        if (musicasSelecionadas.length > 0) {
            let html = `
                <div class="alert alert-success">
                    <h6>Músicas selecionadas (${musicasSelecionadas.length}):</h6>
                    <ul>`;
            
            musicasSelecionadas.forEach(musica => {
                html += `
                    <li>
                        ${musica.titulo}
                        <select class="form-select form-select-sm d-inline-block w-auto ms-2" 
                                onchange="atualizarTom('${musica.id}', this.value)">
                            <option value="">Selecione o tom</option>`;
                
                // Adiciona as opções de tom
                const tons = getTons(); // Função que retorna os tons do PHP
                tons.forEach(tom => {
                    html += `
                        <option value="${tom.id}" ${musica.tom === tom.id ? 'selected' : ''}>
                            ${tom.descricao}
                        </option>`;
                });
                
                html += `</select></li>`;
            });
            
            html += `</ul></div>`;
            musicasList.innerHTML = html;
        } else {
            musicasList.innerHTML = `
                <div class="alert alert-info">Nenhuma música selecionada</div>`;
        }
    }

    // Função para atualizar o tom de uma música
    function atualizarTom(musicaId, tomId) {
        const musica = musicasSelecionadas.find(m => m.id === musicaId);
        if (musica) {
            musica.tom = tomId;
            atualizarFormularioMusicas();
        }
    }

    // Função para obter os tons do PHP
    function getTons() {
        return <?php 
            $tons->execute();
            $tonsArray = [];
            while($tom = $tons->fetch(PDO::FETCH_ASSOC)) {
                $tonsArray[] = ['id' => $tom['id'], 'descricao' => $tom['descricao']];
            }
            echo json_encode($tonsArray);
        ?>;
    }

    // Função de validação do formulário
    function validateForm() {
        try {
            // Verifica músicos
            if (musicosSelecionados.length === 0) {
                alert('Selecione pelo menos um músico para a escala');
                return false;
            }

            // Verifica músicas
            if (musicasSelecionadas.length === 0) {
                alert('Selecione pelo menos uma música para a escala');
                return false;
            }

            // Verifica tons
            const musicasSemTom = musicasSelecionadas.filter(m => !m.tom);
            if (musicasSemTom.length > 0) {
                alert('Selecione o tom para todas as músicas');
                return false;
            }

            // Atualiza os formulários uma última vez antes do envio
            atualizarFormularioMusicos();
            atualizarFormularioMusicas();

            return true;
        } catch (error) {
            console.error('Erro na validação:', error);
            return false;
        }
    }

    // Inicialização
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('escalaForm');
        if (form) {
            form.onsubmit = function(e) {
                if (!validateForm()) {
                    e.preventDefault();
                    return false;
                }
                return true;
            };
        }
    });
    </script>
</body>
</html>