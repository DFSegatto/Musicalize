<html>
    <head>
        <title>Manancial - Escalas</title>
    </head>
    <body>
        <?php include '../../includes/header.php'; ?>
        <div class="container mt-4">
            <h1>Gerenciar Escalas</h1>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Escalas</h5>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Data</th>
                                        <th>Evento</th>
                                        <th>Músico</th>
                                        <th>Música</th>
                                        <th>Tom</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $escala = new Escala($db);
                                    $stmt = $escala->listar();
                                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                        echo "<tr>";
                                        echo "<td>" . $row['dataEscala'] . "</td>";
                                        echo "<td>" . $row['evento'] . "</td>";
                                        echo "<td>" . $row['musico'] . "</td>";
                                        echo "<td>" . $row['musica'] . "</td>";
                                        echo "<td>" . $row['tom'] . "</td>";
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
        <?php include '../../includes/footer.php'; ?>
    </body>
</html>
