<?php
    include_once 'classes/Database.php';
    include_once 'classes/Escala.php';

    $database = new Database();
    $db = $database->getConnection();
    
    $escalasObj = new Escala($db);
    $escalas = $escalasObj->listar();
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Manancial - Sistema de Gerenciamento de Escalas</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
        <link rel="stylesheet" href="assets/css/style.css">
    </head>
    <body>
        <div id="webcrumbs" class="min-h-screen">
            <div class="w-full bg-gray-50 font-sans">
                <?php include 'includes/header.php'; ?>

                <div class="flex min-h-screen">
                  <?php include 'includes/navbar.php'; ?>

                    <main class="flex-1 p-4 md:p-6 overflow-auto">
                        <div class="container mx-auto">
                            <div class="flex justify-between items-center mb-8">
                                <h2 class="text-xl md:text-2xl font-bold">Gerenciamento de Escalas</h2>
                                <button class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg shadow-md flex items-center space-x-2 transform hover:scale-105 transition-all">
                                    <span class="material-symbols-outlined">add</span>
                                    <span>Nova Escala</span>
                                </button>
                            </div>

                            <div class="grid grid-cols-1 lg:grid-cols-1 gap-4 md:gap-6">
                                <!-- Próximos Eventos -->
                                <div class="bg-white p-6 rounded-lg shadow-md">
                                    <h3 class="text-lg font-semibold mb-4">Próximos Eventos</h3>
                                    <div class="overflow-x-auto">
                                        <table class="min-w-full divide-y divide-gray-200">
                                            <thead class="bg-gray-50">
                                                <tr>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Evento</th>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data</th>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white divide-y divide-gray-200">
                                                <?php 
                                                if (!empty($escalas)):
                                                    while ($row = $escalas->fetch(PDO::FETCH_ASSOC)){
                                                        $isOldDate = $row['dataEscala'] < date('Y-m-d');
                                                        $escala = $escalasObj->buscarPorId($row['id']);
                                                        $dataFormatada = date('d/m/Y', strtotime($row['dataEscala']));
                                                        if (!$isOldDate):
                                                ?>
                                                <tr class="hover:bg-gray-50 transition-colors">
                                                    <td class="px-6 py-4 whitespace-nowrap"><?php echo $escala['evento_titulo']; ?></td>
                                                    <td class="px-6 py-4 whitespace-nowrap"><?php echo $dataFormatada; ?></td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                            Ativo
                                                        </span>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                        <button 
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#detalhesModal"
                                                            onclick='abrirModalDetalhes(
                                                                <?php echo json_encode($dataFormatada); ?>,
                                                                <?php echo json_encode($escala["evento_titulo"]); ?>,
                                                                <?php echo !empty($escala["musicos"]) ? json_encode($escala["musicos"]) : "[]"; ?>,
                                                                <?php echo !empty($escala["musicas"]) ? json_encode($escala["musicas"]) : "[]"; ?>,
                                                                <?php echo !empty($escala["detalhes"]) ? json_encode($escala["detalhes"]) : "[]"; ?>
                                                            )' 
                                                            class="text-indigo-600 hover:text-indigo-900 mr-3 transform hover:scale-110 transition-transform">
                                                            <span class="material-symbols-outlined">visibility</span>
                                                        </button>
                                                    </td>
                                                </tr>
                                                <?php 
                                                        endif;
                                                    }
                                                else:
                                                ?>
                                                <tr>
                                                    <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                                                        Nenhum evento disponível
                                                    </td>
                                                </tr>
                                                <?php endif; ?>
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

        <!-- Substitua o Modal de Visualização atual por este -->
        <div class="modal fade" id="detalhesModal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">Detalhes da Escala</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Informações do Evento -->
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6 class="card-subtitle mb-2 text-muted">Data</h6>
                                        <p class="card-text" id="modalData"></p>
                                    </div>
                                    <div class="col-md-6">
                                        <h6 class="card-subtitle mb-2 text-muted">Evento</h6>
                                        <p class="card-text" id="modalEvento"></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Músicos -->
                        <div class="card mb-3">
                            <div class="card-body">
                                <h6 class="card-subtitle mb-2 text-muted">Músicos</h6>
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Música</th>
                                                <th>Instrumento</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tabelaMusicos"></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Repertório -->
                        <div class="card mb-3">
                            <div class="card-body">
                                <h6 class="card-subtitle mb-2 text-muted">Repertório</h6>
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Música</th>
                                                <th>Tom</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tabelaMusicas"></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script>
            function abrirModalDetalhes(data, evento, musicos, musicas, detalhes) {
                try {
                    // Debug
                    console.log('Dados recebidos:', {
                        musicos: musicos,
                        musicas: musicas,
                        detalhes: detalhes
                    });

                    // Informações básicas
                    document.getElementById('modalData').textContent = data;
                    document.getElementById('modalEvento').textContent = evento;

                    const tabelaMusicos = document.getElementById('tabelaMusicos');
                    tabelaMusicos.innerHTML = '';

                    // Processar músicos
                    if(musicos){
                        if(Array.isArray(musicos)){
                            musicos.forEach(musico => {
                                const row = tabelaMusicos.insertRow();
                                const cellNome = row.insertCell(0);
                                const cellInstrumento = row.insertCell(1);
                                cellNome.textContent = musico.nome;
                                cellInstrumento.textContent = musico.instrumento;
                            });
                        } else {
                            const row = tabelaMusicos.insertRow();
                            row.innerHTML = '<td colspan="2">Nenhum músico definido</td>';
                        }
                    }

                    // Processar músicas
                    const tabelaMusicas = document.getElementById('tabelaMusicas');
                    tabelaMusicas.innerHTML = ''; // Limpar tabela

                    if (musicas) {
                        const musicasArray = Array.isArray(musicas) ? musicas : Object.values(musicas);
                        
                        if (musicasArray.length > 0) {
                            musicasArray.forEach(musica => {
                                const row = tabelaMusicas.insertRow();
                                const cellTitulo = row.insertCell(0);
                                const cellTom = row.insertCell(1);

                                if (typeof musica == 'object' && musica !== null) {
                                    cellTitulo.textContent = musica.musica_titulo;
                                    cellTom.textContent = musica.tom_id;
                                } else {
                                    cellTitulo.textContent = String(musica);
                                    cellTom.textContent = '';
                                }
                            });
                        } else {
                            const row = tabelaMusicas.insertRow();
                            const cell = row.insertCell(0);
                            cell.colSpan = 2;
                            cell.textContent = 'Nenhuma música cadastrada';
                            cell.className = 'text-center';
                        }
                    }
                } catch (error) {
                    alert('Erro ao processar dados: ' + error.message);
                }
            }

            function compartilharWhatsapp() {
                const data = document.getElementById('modalData').textContent;
                const evento = document.getElementById('modalEvento').textContent;
                
                // Obtém todas as linhas da tabela de músicos
                const musicosRows = document.getElementById('tabelaMusicos').getElementsByTagName('tr');
                let musicosText = '';
                for (let row of musicosRows) {
                    const cells = row.getElementsByTagName('td');
                    if (cells.length === 2) {
                        musicosText += `${cells[0].textContent} - ${cells[1].textContent}\n`;
                    }
                }

                // Obtém todas as linhas da tabela de músicas
                const musicasRows = document.getElementById('tabelaMusicas').getElementsByTagName('tr');
                let musicasText = '';
                for (let row of musicasRows) {
                    const cells = row.getElementsByTagName('td');
                    if (cells.length === 2) {
                        musicasText += `${cells[0].textContent} - ${cells[1].textContent}\n`;
                    }
                }

                const mensagem = 
`*Escala dia ${data} - ${evento}:*

*Músicos:*
${musicosText}
*Repertório:*
${musicasText}`;
                
                const url = `https://wa.me/?text=${encodeURIComponent(mensagem)}`;
                window.open(url, '_blank');
            }
        </script>
    </body>
</html>