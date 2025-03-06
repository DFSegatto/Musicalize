<?php
// Inicialização e configuração
define('REQUIRED_ROLES', ['admin']);

// Inclusões necessárias
require_once '../../config/init.php';
require_once '../../classes/Jejum.php';
require_once '../../classes/Database.php';
require_once '../../classes/Musico.php';

$database = new Database();
$db = $database->getConnection();

$jejum = new Jejum($db);
$musico = new Musico($db);

// Buscar lista de músicos
$musicos = $musico->listar();

?>
<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <title>Musicalize - Cadastrar Jejum</title>
        <link rel="icon" type="image/x-icon" href="../../assets/css/img/favicon.ico">
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
                                    <h1 class="h3 fw-bold mb-0">Cadastrar Jejum</h1>
                                </div>
                                <p class="text-muted mb-0">Preencha os dados do novo jejum</p>
                            </div>

                            <div class="bg-white p-6 rounded-lg shadow-md">
                                <form action="../../api/jejum.php" method="POST">
                                    <div class="overflow-x-auto">
                                        <table class="min-w-full divide-y divide-gray-200">
                                            <thead class="bg-gray-50">
                                                <tr>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Músico</th>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dia da Semana</th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white divide-y divide-gray-200">
                                                <?php 
                                                foreach ($musicos->fetchAll(PDO::FETCH_ASSOC) as $m) { ?>
                                                <tr class="hover:bg-gray-50">
                                                    <td class="px-6 py-4 whitespace-nowrap"><?php echo $m['nome']; ?></td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <select class="form-select dia-semana-select rounded-lg border-gray-300" name="dia_semana[<?php echo $m['id']; ?>]" required>
                                                            <option value="">Selecione o dia</option>
                                                            <option value="0">Domingo</option>
                                                            <option value="1">Segunda-feira</option>
                                                            <option value="2">Terça-feira</option>
                                                            <option value="3">Quarta-feira</option>
                                                            <option value="4">Quinta-feira</option>
                                                            <option value="5">Sexta-feira</option>
                                                            <option value="6">Sábado</option>
                                                        </select>
                                                    </td>
                                                </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>

                                    <div id="resumo" class="mt-6 space-y-4"></div>

                                    <div class="mt-6">
                                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg shadow-md transform hover:scale-105 transition-all">
                                            Cadastrar/Atualizar
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </main>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const selects = document.querySelectorAll('.dia-semana-select');
                const resumoDiv = document.getElementById('resumo');

                function atualizarResumo() {
                    const dias = {
                        '0': { dia: 'Domingo', musicos: [] },
                        '1': { dia: 'Segunda-feira', musicos: [] },
                        '2': { dia: 'Terça-feira', musicos: [] },
                        '3': { dia: 'Quarta-feira', musicos: [] },
                        '4': { dia: 'Quinta-feira', musicos: [] },
                        '5': { dia: 'Sexta-feira', musicos: [] },
                        '6': { dia: 'Sábado', musicos: [] }
                    };

                    selects.forEach(select => {
                        if (select.value) {
                            const musicoNome = select.closest('tr').querySelector('td').textContent;
                            dias[select.value].musicos.push(musicoNome);
                        }
                    });

                    let html = '<h4 class="text-lg font-semibold mb-4">Resumo por dia:</h4>';
                    for (const [key, value] of Object.entries(dias)) {
                        if (value.musicos.length > 0) {
                            html += `
                                <div class="bg-gray-50 rounded-lg p-4 mb-4">
                                    <h5 class="font-semibold text-indigo-600">${value.dia}</h5>
                                    <p class="mt-2">${value.musicos.join(', ')}</p>
                                </div>`;
                        }
                    }

                    resumoDiv.innerHTML = html;
                }

                selects.forEach(select => {
                    select.addEventListener('change', atualizarResumo);
                });
            });
        </script>
    </body>
</html>



