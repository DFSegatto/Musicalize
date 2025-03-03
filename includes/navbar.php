<?php
$current_page = basename($_SERVER['PHP_SELF']);
$current_dir = basename(dirname($_SERVER['PHP_SELF']));
?>

<aside class="bg-white border-end" id="sideNav">
    <nav class="p-3 h-100">
        <ul class="nav nav-pills flex-column gap-1">
            <li class="nav-item">
                <a href="
                <?php 
                echo $current_dir == 'musicos' || 
                $current_dir == 'eventos' || 
                $current_dir == 'escalas' || 
                $current_dir == 'musicas' || 
                $current_dir == 'jejum' ? '../../' : './index.php'; ?>" 
                   class="nav-link d-flex align-items-center gap-3 <?php echo $current_page == 'index.php' && $current_dir != 'musicos' ? 'active' : ''; ?>">
                    <span class="material-symbols-outlined">dashboard</span>
                    <span class="menu-text">Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?php echo $current_dir == 'eventos' ? '' : './modules/eventos/index.php'; ?>" 
                   class="nav-link d-flex align-items-center gap-3 <?php echo $current_dir == 'eventos' ? 'active' : ''; ?>">
                    <span class="material-symbols-outlined">event</span>
                    <span class="menu-text">Eventos</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?php echo $current_dir == 'musicos' ? '' : './modules/musicos/index.php'; ?>" 
                   class="nav-link d-flex align-items-center gap-3 <?php echo $current_dir == 'musicos' ? 'active' : ''; ?>">
                    <span class="material-symbols-outlined">music_note</span>
                    <span class="menu-text">Músicos</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?php echo $current_dir == 'escalas' ? '' : './modules/escalas/index.php'; ?>" 
                   class="nav-link d-flex align-items-center gap-3 <?php echo $current_dir == 'escalas' ? 'active' : ''; ?>">
                    <span class="material-symbols-outlined">playlist_add_check</span>
                    <span class="menu-text">Escalas</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?php echo $current_dir == 'musicas' ? '' : './modules/musicas/index.php'; ?>" 
                   class="nav-link d-flex align-items-center gap-3 <?php echo $current_dir == 'musicas' ? 'active' : ''; ?>">
                    <span class="material-symbols-outlined">queue_music</span>
                    <span class="menu-text">Playlist</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?php echo $current_dir == 'jejum' ? '' : './modules/jejum/index.php'; ?>" 
                   class="nav-link d-flex align-items-center gap-3 <?php echo $current_dir == 'jejum' ? 'active' : ''; ?>">
                    <span class="material-symbols-outlined">timer</span>
                    <span class="menu-text">Jejum</span>
                </a>
            </li>
        </ul>
    </nav>
</aside>

<script>
function toggleNav() {
    const nav = document.getElementById('sideNav');
    nav.classList.toggle('expanded');
    document.body.classList.toggle('sidebar-expanded');
}
</script>