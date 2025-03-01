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
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
        <link rel="stylesheet" href="../../assets/css/style.css">
    </head>
    <body>
        <div id="webcrumbs" class="min-h-screen">
            <div class="w-full bg-gray-50 font-sans">
                <header class="bg-indigo-600 text-white py-4 px-6 shadow-lg">
                    <div class="container mx-auto flex justify-between items-center">
                        <h1 class="text-2xl font-bold">Manancial</h1>
                        <div class="flex items-center space-x-4">
                            <span class="relative">
                                <span class="material-symbols-outlined text-2xl cursor-pointer hover:text-indigo-200 transition-colors">notifications</span>
                                <span class="absolute -top-1 -right-1 bg-red-500 text-xs rounded-full w-5 h-5 flex items-center justify-center">3</span>
                            </span>
                            <details class="relative">
                                <summary class="flex items-center space-x-2 cursor-pointer list-none">
                                    <div class="w-10 h-10 rounded-full bg-indigo-300 flex items-center justify-center">
                                        <span class="material-symbols-outlined">person</span>
                                    </div>
                                    <span class="hidden md:inline">Usuário</span>
                                    <span class="material-symbols-outlined">expand_more</span>
                                </summary>
                                <div class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-xl z-10 py-2">
                                    <a href="#" class="block px-4 py-2 hover:bg-indigo-100 transition-colors">Meu Perfil</a>
                                    <a href="#" class="block px-4 py-2 hover:bg-indigo-100 transition-colors">Configurações</a>
                                    <a href="#" class="block px-4 py-2 hover:bg-indigo-100 transition-colors">Sair</a>
                                </div>
                            </details>
                        </div>
                    </div>
                </header>

                <div class="flex min-h-screen">
                    <aside class="w-16 md:w-64 bg-white shadow-md">
                        <nav class="p-4">
                            <ul class="space-y-2">
                                <li>
                                    <a href="../../index.php" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-indigo-50 transition-colors">
                                        <span class="material-symbols-outlined">dashboard</span>
                                        <span class="hidden md:inline">Dashboard</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="../eventos/index.php" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-indigo-50 transition-colors">
                                        <span class="material-symbols-outlined">event</span>
                                        <span class="hidden md:inline">Eventos</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="index.php" class="flex items-center space-x-3 p-3 rounded-lg bg-indigo-50 text-indigo-700">
                                        <span class="material-symbols-outlined">music_note</span>
                                        <span class="hidden md:inline">Músicos</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="../escalas/index.php" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-indigo-50 transition-colors">
                                        <span class="material-symbols-outlined">playlist_add_check</span>
                                        <span class="hidden md:inline">Escalas</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="../musicas/index.php" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-indigo-50 transition-colors">
                                        <span class="material-symbols-outlined">queue_music</span>
                                        <span class="hidden md:inline">Playlist</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="../jejum/index.php" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-indigo-50 transition-colors">
                                        <span class="material-symbols-outlined">timer</span>
                                        <span class="hidden md:inline">Jejum</span>
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    </aside>

                    <main class="flex-1 p-4 md:p-6 overflow-auto">
                        <div class="container mx-auto">
                            <div class="flex justify-between items-center mb-8">
                                <h2 class="text-xl md:text-2xl font-bold">Gerenciar Músicos</h2>
                                <div class="flex items-center space-x-4">
                                    <label class="inline-flex items-center cursor-pointer">
                                        <input type="checkbox" 
                                               class="form-checkbox h-5 w-5 text-indigo-600 rounded border-gray-300 focus:ring-indigo-500" 
                                               id="mostrarInativos" 
                                               <?php echo isset($_GET['mostrarInativos']) ? 'checked' : ''; ?>>
                                        <span class="ml-2 text-gray-700">Mostrar músicos inativos</span>
                                    </label>
                                </div>
                            </div>

                            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nome</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Instrumento</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            <?php while ($row = $musicos->fetch(PDO::FETCH_ASSOC)): ?>
                                                <tr class="hover:bg-gray-50">
                                                    <td class="px-6 py-4 whitespace-nowrap"><?php echo $row['nome']; ?></td>
                                                    <td class="px-6 py-4 whitespace-nowrap"><?php echo $row['instrumento']; ?></td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo $row['status'] ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'; ?>">
                                                            <?php echo $row['status'] ? 'Ativo' : 'Inativo'; ?>
                                                        </span>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                                        <?php if ($row['status']): ?>
                                                            <button onclick="alterarStatus(<?php echo $row['id']; ?>, 'inativar')" 
                                                                    class="text-yellow-600 hover:text-yellow-900">
                                                                <span class="material-symbols-outlined">person_off</span>
                                                            </button>
                                                        <?php else: ?>
                                                            <button onclick="alterarStatus(<?php echo $row['id']; ?>, 'ativar')" 
                                                                    class="text-green-600 hover:text-green-900">
                                                                <span class="material-symbols-outlined">person_add</span>
                                                            </button>
                                                        <?php endif; ?>
                                                        <button onclick="editarMusico(<?php echo $row['id']; ?>, '<?php echo $row['nome']; ?>', '<?php echo $row['instrumento']; ?>')" 
                                                                class="text-indigo-600 hover:text-indigo-900">
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
                    </main>
                </div>
            </div>
        </div>

        <!-- Modal de Edição -->
        <div class="modal fade" id="editarMusicoModal" tabindex="-1" aria-labelledby="editarMusicoModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-indigo-600 text-white">
                        <h5 class="modal-title" id="editarMusicoModalLabel">Editar Músico</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="editarMusicoForm" class="space-y-4">
                            <input type="hidden" id="editMusicoId">
                            <div>
                                <label for="editNome" class="block text-sm font-medium text-gray-700">Nome</label>
                                <input type="text" 
                                       class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" 
                                       id="editNome" 
                                       required>
                            </div>
                            <div>
                                <label for="editInstrumento" class="block text-sm font-medium text-gray-700">Instrumento</label>
                                <input type="text" 
                                       class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" 
                                       id="editInstrumento" 
                                       required>
                            </div>
                            <div>
                                <label for="editTelefone" class="block text-sm font-medium text-gray-700">WhatsApp</label>
                                <input type="tel" 
                                       class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" 
                                       id="editTelefone" 
                                       name="telefone" 
                                       placeholder="Ex: 5549999999999"
                                       pattern="[0-9]{13,}"
                                       title="Digite o número com código do país e DDD (ex: 5549999999999)">
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700" onclick="salvarEdicao()">Salvar</button>
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