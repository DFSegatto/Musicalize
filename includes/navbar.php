<?php
$current_page = basename($_SERVER['PHP_SELF']);
$current_dir = basename(dirname($_SERVER['PHP_SELF']));
?>

<aside class="w-16 md:w-64 bg-white shadow-md">
    <nav class="p-4">
        <ul class="space-y-2">
            <li>
                <a href="
                <?php 
                echo $current_dir == 'musicos' || 
                $current_dir == 'eventos' || 
                $current_dir == 'escalas' || 
                $current_dir == 'musicas' || 
                $current_dir == 'jejum' ? '../../' : './index.php'; ?>" 
                   class="flex items-center space-x-3 p-3 rounded-lg <?php echo $current_page == 'index.php' && $current_dir != 'musicos' ? 'bg-indigo-50 text-indigo-700' : 'hover:bg-indigo-50 transition-colors'; ?>">
                    <span class="material-symbols-outlined">dashboard</span>
                    <span class="hidden md:inline">Dashboard</span>
                </a>
            </li>
            <li>
                <a href="<?php echo $current_dir == 'eventos' ? '' : './modules/eventos/index.php'; ?>" 
                   class="flex items-center space-x-3 p-3 rounded-lg <?php echo $current_dir == 'eventos' ? 'bg-indigo-50 text-indigo-700' : 'hover:bg-indigo-50 transition-colors'; ?>">
                    <span class="material-symbols-outlined">event</span>
                    <span class="hidden md:inline">Eventos</span>
                </a>
            </li>
            <li>
                <a href="<?php echo $current_dir == 'musicos' ? '' : './modules/musicos/index.php'; ?>" 
                   class="flex items-center space-x-3 p-3 rounded-lg <?php echo $current_dir == 'musicos' ? 'bg-indigo-50 text-indigo-700' : 'hover:bg-indigo-50 transition-colors'; ?>">
                    <span class="material-symbols-outlined">music_note</span>
                    <span class="hidden md:inline">Músicos</span>
                </a>
            </li>
            <li>
                <a href="<?php echo $current_dir == 'escalas' ? '' : './modules/escalas/index.php'; ?>" 
                   class="flex items-center space-x-3 p-3 rounded-lg <?php echo $current_dir == 'escalas' ? 'bg-indigo-50 text-indigo-700' : 'hover:bg-indigo-50 transition-colors'; ?>">
                    <span class="material-symbols-outlined">playlist_add_check</span>
                    <span class="hidden md:inline">Escalas</span>
                </a>
            </li>
            <li>
                <a href="<?php echo $current_dir == 'musicas' ? '' : './modules/musicas/index.php'; ?>" 
                   class="flex items-center space-x-3 p-3 rounded-lg <?php echo $current_dir == 'musicas' ? 'bg-indigo-50 text-indigo-700' : 'hover:bg-indigo-50 transition-colors'; ?>">
                    <span class="material-symbols-outlined">queue_music</span>
                    <span class="hidden md:inline">Playlist</span>
                </a>
            </li>
            <li>
                <a href="<?php echo $current_dir == 'jejum' ? '' : './modules/jejum/index.php'; ?>" 
                   class="flex items-center space-x-3 p-3 rounded-lg <?php echo $current_dir == 'jejum' ? 'bg-indigo-50 text-indigo-700' : 'hover:bg-indigo-50 transition-colors'; ?>">
                    <span class="material-symbols-outlined">timer</span>
                    <span class="hidden md:inline">Jejum</span>
                </a>
            </li>
        </ul>
    </nav>
</aside>