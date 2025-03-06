<?php
    include_once 'classes/Database.php';
    include_once 'classes/Escala.php';

    $database = new Database();
    $db = $database->getConnection();
    
    $escalasObj = new Escala($db);
    $escalas = $escalasObj->listar();
?>
<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <title>Musicalize</title>
        <link rel="icon" type="image/x-icon" href="assets/css/img/favicon.ico">
        <meta name="description" content="Musicalize é um sistema de gerenciamento de escalas de músicos.">
        <meta name="author" content="Web FS">
        <meta name="keywords" content="escalas, músicos, música, gerenciamento">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
        <link rel="stylesheet" href="assets/css/style.css">
    </head>
    <body>
        <div id="webcrumbs" class="min-vh-100">
            <div class="w-100 bg-light">
                <?php include 'includes/header.php'; ?>

                <div class="d-flex min-vh-100">
                    <?php include 'includes/navbar.php'; ?>

                    <main class="flex-grow-1 overflow-auto bg-light">
                        <div class="container-fluid p-4">
                            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
                                <div class="mb-3 mb-md-0">
                                    <h1 class="h3 fw-bold mb-1">Gerenciamento de Escalas</h1>
                                    <p class="text-muted mb-0">Organize e gerencie as escalas de músicos</p>
                                </div>
                                <button class="btn btn-primary d-inline-flex align-items-center gap-2" onclick="window.location.href='modules/escalas/criar.php'">
                                    <span class="material-symbols-outlined">add</span>
                                    Nova Escala
                                </button>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <div class="card border-0 shadow-sm">
                                        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                                            <h5 class="card-title mb-0 fw-semibold">Próximos Eventos</h5>
                                        </div>
                                        <div class="card-body p-0">
                                            <div class="table-responsive">
                                                <table class="table table-hover align-middle mb-0">
                                                    <thead class="bg-light">
                                                        <tr>
                                                            <th class="ps-4">Evento</th>
                                                            <th>Data</th>
                                                            <th>Status</th>
                                                            <th class="text-end pe-4">Ações</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="border-top-0">
                                                        <?php 
                                                        if (!empty($escalas)):
                                                            while ($row = $escalas->fetch(PDO::FETCH_ASSOC)){
                                                                $isOldDate = $row['dataEscala'] < date('Y-m-d');
                                                                $escala = $escalasObj->buscarPorId($row['id']);
                                                                $dataFormatada = date('d/m/Y', strtotime($row['dataEscala']));
                                                                if (!$isOldDate):
                                                        ?>
                                                        <tr>
                                                            <td class="text-truncate ps-4" style="max-width: 300px;">
                                                                <div class="fw-medium"><?php echo $escala['evento_titulo']; ?></div>
                                                            </td>
                                                            <td><?php echo $dataFormatada; ?></td>
                                                            <td>
                                                                <span class="badge bg-success-subtle text-success">Ativo</span>
                                                            </td>
                                                            <td class="text-end pe-4">
                                                                <button 
                                                                    class="btn btn-light btn-sm"
                                                                    data-bs-toggle="modal" 
                                                                    data-bs-target="#detalhesModal"
                                                                    onclick='abrirModalDetalhes(
                                                                        <?php echo json_encode($dataFormatada); ?>,
                                                                        <?php echo json_encode($escala["evento_titulo"]); ?>,
                                                                        <?php echo !empty($escala["musicos"]) ? json_encode($escala["musicos"]) : "[]"; ?>,
                                                                        <?php echo !empty($escala["musicas"]) ? json_encode($escala["musicas"]) : "[]"; ?>,
                                                                        <?php echo !empty($escala["detalhes"]) ? json_encode($escala["detalhes"]) : "[]"; ?>
                                                                    )'
                                                                    title="Visualizar">
                                                                    <span class="material-symbols-outlined">visibility</span>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                        <?php 
                                                                endif;
                                                            }
                                                        endif;
                                                        ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </main>
                </div>
            </div>
        </div>

        <!-- Modal de Visualização -->
        <div class="modal fade" id="detalhesModal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content border-0">
                    <div class="modal-header bg-primary text-white border-0">
                        <h5 class="modal-title">Detalhes da Escala</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Informações do Evento -->
                        <div class="card border-0 shadow-sm mb-3">
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="text-muted small text-uppercase">Data</label>
                                        <p class="mb-0 fw-medium" id="modalData"></p>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="text-muted small text-uppercase">Evento</label>
                                        <p class="mb-0 fw-medium" id="modalEvento"></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Músicos -->
                        <div class="card border-0 shadow-sm mb-3">
                            <div class="card-body">
                                <h6 class="text-muted small text-uppercase mb-3">Músicos</h6>
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Nome</th>
                                                <th>Instrumento</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tabelaMusicos"></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Repertório -->
                        <div class="card border-0 shadow-sm mb-3">
                            <div class="card-body">
                                <h6 class="text-muted small text-uppercase mb-3">Repertório</h6>
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0">
                                        <thead class="table-light">
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
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Fechar</button>
                        <button type="button" class="btn btn-primary" onclick="compartilharWhatsapp()">
                            <span class="material-symbols-outlined me-2">share</span>
                            Compartilhar
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script>
            function abrirModalDetalhes(data, evento, musicos, musicas, detalhes) {
                try {
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
