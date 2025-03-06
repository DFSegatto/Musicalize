<?php require_once __DIR__ . '/../config/init.php'; ?>
<header class="navbar navbar-expand-md bg-primary navbar-dark py-2 sticky-top">
    <div class="container-fluid px-3">
        <button class="btn btn-link text-white d-md-none me-3 p-0" onclick="toggleNav()">
            <span class="material-symbols-outlined">menu</span>
        </button>
        <h1 class="text-2xl font-bold">
            <a href="<?php echo url('/index.php'); ?>">Musicalize</a>
        </h1>
    </div>
</header>