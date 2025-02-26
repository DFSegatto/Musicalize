<?php
include_once '../../classes/Database.php';
include_once '../../classes/Escala.php';

$db = new Database();
$db = $db->getConnection();

$escalas = new Escala($db);
?>

<html>
    <head>
        <title>Manancial - Escalas</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
        <style>
            .btn-whatsapp {
                background-color: #25D366;
                color: #fff;
            }
            .card{
                margin-bottom: 200px;
            }
        </style>
    </head>
    <body>
        <?php include '../../includes/header.php'; ?>
        <div class="container mt-4">
            <h1>Gerenciar Escalas</h1>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Selecione uma escala</h5>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Data</th>
                                        <th>Evento</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $stmt = $escalas->listar();
                                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                        $result = $escalas->buscarPorId($row['id']);
                                        $dataFormatada = date('d/m/Y', strtotime($row['dataEscala']));
                                        $isOldDate = $row['dataEscala'] < date('Y-m-d');
                                        echo "<tr>";
                                        echo "<td" . ($isOldDate ? " class='text-decoration-line-through text-danger'" : "") . ">" . $dataFormatada . "</td>";
                                        echo "<td" . ($isOldDate ? " class='text-decoration-line-through text-danger'" : "") . ">" . $result['evento_titulo'] . "</td>";
                                        echo "<td>
                                        <button type='button' 
                                            data-bs-toggle='modal' 
                                            data-bs-target='#detalhesModal' 
                                            onclick='abrirModalDetalhes(" . 
                                                json_encode($dataFormatada) . ", " . 
                                                json_encode($result['evento_titulo']) . ", " . 
                                                json_encode($result['musicos']) . ", " . 
                                                json_encode($result['musicas']) . ", " . 
                                                json_encode($result['detalhes']) . 
                                            ")' 
                                            class='btn btn-info'>Detalhes</button>
                                        <a href='editar.php?id=" . $row['id'] . "' " . ($isOldDate ? "class='btn btn-primary disabled'" : "class='btn btn-primary'") . ">Editar</a>
                                        <a href='deletar.php?id=" . $row['id'] . "' " . ($isOldDate ? "class='btn btn-danger disabled'" : "class='btn btn-danger'") . ">Deletar</a>
                                        </td>";
                                        echo "</tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>  
                    </div>
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
                        <button type="button" class="btn btn-success btn-whatsapp" onclick="compartilharWhatsapp()" >
                            <i class="bi bi-whatsapp"></i> Compartilhar via WhatsApp
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <?php include '../../includes/footer.php'; ?>
    </body>
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
</html>
