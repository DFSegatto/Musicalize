<?php
// Pega o caminho atual da URL
$current_path = $_SERVER['PHP_SELF'];

// Define o prefixo do caminho baseado na localização atual
$prefix = "";
if (strpos($current_path, '/modules/') !== false) {
    $prefix = "../../";
} else if (strpos($current_path, '/musicas/') !== false) {
    $prefix = "../";
} else {
    $prefix = "./";
}
?>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="<?php echo $prefix; ?>index.php">Manancial</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo $prefix; ?>index.php">Início</a>
                </li>
                <?php if(!strpos($current_path, '/musicos/index.php')){ ?>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo $prefix; ?>modules/musicos/index.php">Músicos</a>
                </li>
                <?php } ?>
                <?php if(!strpos($current_path, '/escalas/index.php')){ ?>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo $prefix; ?>modules/escalas/index.php">Escalas</a>
                </li>
                <?php } ?>
                <?php if(!strpos($current_path, '/eventos/index.php')){ ?>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo $prefix; ?>modules/eventos/index.php">Eventos</a>
                </li>
                <?php } ?>
                <?php if(!strpos($current_path, '/musicas/index.php')){ ?>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo $prefix; ?>musicas/index.php">Músicas</a>
                </li>
                <?php } ?>
            </ul>
        </div>
    </div>
</nav>
