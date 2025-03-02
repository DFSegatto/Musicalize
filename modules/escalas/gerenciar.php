<?php
include_once '../../classes/Database.php';
include_once '../../classes/Escala.php';

$db = new Database();
$db = $db->getConnection();

// Inicialização do objeto Escala
$escalasObj = new Escala($db);

// Pegar parâmetros do filtro
$dataInicial = isset($_GET['data_inicial']) ? $_GET['data_inicial'] : date('Y-m-01');
$dataFinal = isset($_GET['data_final']) ? $_GET['data_final'] : date('Y-m-t');
$eventoFiltro = isset($_GET['evento']) ? $_GET['evento'] : null;

// Buscar escalas com filtro
$stmt = $escalasObj->listarPorFiltro($dataInicial, $dataFinal, $eventoFiltro);

?>
<!DOCTYPE html>
<html>
    <head>
        <title>Manancial - Escalas</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
        <link rel="stylesheet" href="../../assets/css/style.css">
        <style>
            .btn-whatsapp {
                background-color: #25D366;
                color: #fff;
            }
            .btn-whatsapp:hover {
                background-color: #128C7E;
                color: #fff;
            }
            .table th {
                background-color: var(--bs-gray-100);
            }
            .text-decoration-line-through {
                opacity: 0.6;
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
                            <div class="flex justify-between items-center mb-8">
                                <h2 class="text-xl md:text-2xl font-bold">Gerenciar Escalas</h2>
                            </div>

                            <div class="bg-white rounded-lg shadow-md p-6">
                                <!-- Filtros -->
                                <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                                    <div>
                                        <label class="form-label font-medium text-gray-700">Data inicial</label>
                                        <input type="date" name="data_inicial" class="form-control" value="<?php echo $dataInicial; ?>">
                                        <label class="form-label font-medium text-gray-700 mt-2">Data final</label>
                                        <input type="date" name="data_final" class="form-control" value="<?php echo $dataFinal; ?>">
                                    </div>
                                    <div>
                                        <label class="form-label font-medium text-gray-700">Evento</label>
                                        <input type="text" name="evento" class="form-control" placeholder="Buscar evento" 
                                               value="<?php echo htmlspecialchars($eventoFiltro); ?>">
                                    </div>
                                    <div class="flex items-end gap-2">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="bi bi-search me-2"></i>Filtrar
                                        </button>
                                        <a href="gerenciar.php" class="btn btn-secondary">
                                            <i class="bi bi-x-circle me-2"></i>Limpar
                                        </a>
                                    </div>
                                </form>

                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Data</th>
                                                <th>Evento</th>
                                                <th class="text-center">Ações</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                                $result = $escalasObj->buscarPorId($row['id']);
                                                $dataFormatada = date('d/m/Y', strtotime($row['dataEscala']));
                                                $isOldDate = $row['dataEscala'] < date('Y-m-d');
                                                echo "<tr>";
                                                echo "<td" . ($isOldDate ? " class='text-decoration-line-through text-danger'" : "") . ">" . $dataFormatada . "</td>";
                                                echo "<td" . ($isOldDate ? " class='text-decoration-line-through text-danger'" : "") . ">" . $result['evento_titulo'] . "</td>";
                                                echo "<td class='text-center'>
                                                    <button type='button' 
                                                        class='btn btn-info btn-sm'
                                                        data-bs-toggle='modal' 
                                                        data-bs-target='#detalhesModal' 
                                                        onclick='abrirModalDetalhes(" . 
                                                            json_encode($dataFormatada) . ", " . 
                                                            json_encode($result['evento_titulo']) . ", " . 
                                                            json_encode($result['musicos']) . ", " . 
                                                            json_encode($result['musicas']) . ", " . 
                                                            json_encode($result['detalhes']) . 
                                                        ")'><i class='bi bi-info-circle me-2'></i>Detalhes</button>
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

        <!-- Modal -->
        <div class="modal fade" id="detalhesModal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">Detalhes da Escala</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Informações do Evento -->
                        <div class="card mb-4">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6 class="text-muted mb-2">Data</h6>
                                        <p class="card-text" id="modalData"></p>
                                    </div>
                                    <div class="col-md-6">
                                        <h6 class="text-muted mb-2">Evento</h6>
                                        <p class="card-text" id="modalEvento"></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Músicos -->
                        <div class="card mb-4">
                            <div class="card-body">
                                <h6 class="text-muted mb-3">Músicos</h6>
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0">
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
                        <div class="card">
                            <div class="card-body">
                                <h6 class="text-muted mb-3">Repertório</h6>
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0">
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
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-lg me-2"></i>Fechar
                        </button>
                        <button type="button" class="btn btn-whatsapp" onclick="compartilharWhatsapp()">
                            <i class="bi bi-whatsapp me-2"></i>Compartilhar via WhatsApp
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
        
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
