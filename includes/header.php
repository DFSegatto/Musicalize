<?php require_once __DIR__ . '/../config/init.php'; ?>
<header class="navbar navbar-expand-md bg-primary navbar-dark py-2 sticky-top">
    <div class="container-fluid px-3">
        <button class="btn btn-link text-white d-md-none me-3 p-0" onclick="toggleNav()">
            <span class="material-symbols-outlined">menu</span>
        </button>
        <h1 class="text-2xl font-bold">
            <a href="<?php echo url('/index.php'); ?>">Manancial</a>
        </h1>
        
        <div class="d-flex align-items-center gap-3">
            <div class="position-relative">
                <button class="btn btn-link text-white p-0">
                    <span class="material-symbols-outlined">notifications</span>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                        3
                    </span>
                </button>
            </div>
            
            <div class="dropdown">
                <button class="btn btn-link text-white p-0 dropdown-toggle d-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <div class="rounded-circle bg-white bg-opacity-25 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                        <span class="material-symbols-outlined">person</span>
                    </div>
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2">
                    <li><a class="dropdown-item d-flex align-items-center gap-2" href="#"><span class="material-symbols-outlined">person</span>Meu Perfil</a></li>
                    <li><a class="dropdown-item d-flex align-items-center gap-2" href="#"><span class="material-symbols-outlined">settings</span>Configurações</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item d-flex align-items-center gap-2 text-danger" href="#"><span class="material-symbols-outlined">logout</span>Sair</a></li>
                </ul>
            </div>
        </div>
    </div>
</header>