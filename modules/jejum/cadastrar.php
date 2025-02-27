<?php
session_start();

include_once '../../classes/Database.php';
include_once '../../classes/Jejum.php';
include_once '../../classes/Musico.php';


$db = new Database();
$db = $db->getConnection();

$jejum = new Jejum($db);
$musico = new Musico($db);
?>
<html>
    <head>
        <title>Manancial - Jejum</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    </head>
    <body>
        <?php include '../../includes/header.php'; ?>
        <div class="container mt-4">
        <?php if (isset($_SESSION['mensagem'])) { ?>
                <div id="mensagem" class="alert alert-success" role="alert">
                    <?php echo $_SESSION['mensagem']; ?>
                    <?php unset($_SESSION['mensagem']); ?>
                </div>
            <?php } ?>
            <h1>Cadastrar Jejum</h1>
            <form action="../../api/jejum.php" method="POST">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Músico</th>
                            <th>Dia da Semana</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($musico->listar() as $m) { ?>
                        <tr>
                            <td><?php echo $m['nome']; ?></td>
                            <td>
                                <select class="form-select dia-semana-select" name="dia_semana[<?php echo $m['id']; ?>]" required>
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
                <div id="resumo" class="mt-4 mb-4"></div>
                <button type="submit" class="btn btn-primary">Cadastrar/Atualizar</button>
            </form>
        </div>
    </body>
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

            let html = '<h4>Resumo por dia:</h4>';
            for (const [key, value] of Object.entries(dias)) {
                if (value.musicos.length > 0) {
                    html += `<div class="card mb-2">
                        <div class="card-header">${value.dia}</div>
                        <div class="card-body">
                            <p class="card-text">${value.musicos.join(', ')}</p>
                        </div>
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
</html>



