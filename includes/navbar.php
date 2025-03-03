<?php require_once __DIR__ . '/../config/init.php'; ?>
<?php
$current_page = basename($_SERVER['PHP_SELF']);
$current_dir = basename(dirname($_SERVER['PHP_SELF']));
?>

<aside class="bg-white border-end" id="sideNav">
    <nav class="p-3 h-100">
        <ul class="nav nav-pills flex-column gap-1">
            <li class="nav-item">
                <a href="<?php echo url('/index.php'); ?>" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-indigo-50 transition-colors">
                    <span class="material-symbols-outlined">dashboard</span>
                    <span class="hidden md:inline menu-text">Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?php echo url('/modules/eventos/index.php'); ?>" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-indigo-50 transition-colors">
                    <span class="material-symbols-outlined">event</span>
                    <span class="hidden md:inline">Eventos</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?php echo url('/modules/musicos/index.php'); ?>" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-indigo-50 transition-colors">
                    <span class="material-symbols-outlined">music_note</span>
                    <span class="hidden md:inline">Músicos</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?php echo url('/modules/escalas/index.php'); ?>" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-indigo-50 transition-colors">
                    <span class="material-symbols-outlined">playlist_add_check</span>
                    <span class="hidden md:inline">Escalas</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?php echo url('/modules/musicas/index.php'); ?>" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-indigo-50 transition-colors">
                    <span class="material-symbols-outlined">queue_music</span>
                    <span class="hidden md:inline">Playlist</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?php echo url('/modules/jejum/index.php'); ?>" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-indigo-50 transition-colors">
                    <span class="material-symbols-outlined">timer</span>
                    <span class="hidden md:inline">Jejum</span>
                </a>
            </li>
        </ul>
    </nav>
</aside>

<script>
function toggleNav() {
    const nav = document.getElementById('sideNav');
    nav.classList.toggle('expanded');
    
    // Ajusta a visibilidade dos textos do menu
    const menuTexts = nav.querySelectorAll('.hidden.md\\:inline');
    menuTexts.forEach(text => {
        if (nav.classList.contains('expanded')) {
            text.style.display = 'inline';
            text.style.opacity = '1';
        } else {
            text.style.display = '';
            text.style.opacity = '';
        }
    });
}
</script>