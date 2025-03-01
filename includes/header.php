<?php
$current_page = basename($_SERVER['PHP_SELF']);
$current_dir = basename(dirname($_SERVER['PHP_SELF']));
?>
<header class="bg-indigo-600 text-white py-4 px-6 shadow-lg">
    <div class="container mx-auto flex justify-between items-center">
        <h1 class="text-2xl font-bold">Manancial</h1>
        <div class="flex items-center space-x-4">
            <span class="relative">
                <span class="material-symbols-outlined text-2xl cursor-pointer hover:text-indigo-200 transition-colors">notifications</span>
                <span class="absolute -top-1 -right-1 bg-red-500 text-xs rounded-full w-5 h-5 flex items-center justify-center">3</span>
            </span>
            <details class="relative">
                <summary class="flex items-center space-x-2 cursor-pointer list-none">
                    <div class="w-10 h-10 rounded-full bg-indigo-300 flex items-center justify-center">
                        <span class="material-symbols-outlined">person</span>
                    </div>
                    <span class="hidden md:inline">Usuário</span>
                    <span class="material-symbols-outlined">expand_more</span>
                </summary>
                <div class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-xl z-10 py-2">
                    <a href="#" class="block px-4 py-2 hover:bg-indigo-100 transition-colors">Meu Perfil</a>
                    <a href="#" class="block px-4 py-2 hover:bg-indigo-100 transition-colors">Configurações</a>
                    <a href="#" class="block px-4 py-2 hover:bg-indigo-100 transition-colors">Sair</a>
                </div>
            </details>
        </div>
    </div>
</header>

<aside class="w-16 md:w-64 bg-white shadow-md">
    <nav class="p-4">
        <ul class="space-y-2">
            <li>
                <a href="<?php echo $current_dir == 'modules' ? '../' : ''; ?>index.php" 
                   class="flex items-center space-x-3 p-3 rounded-lg <?php echo $current_page == 'index.php' && $current_dir != 'musicos' ? 'bg-indigo-50 text-indigo-700' : 'hover:bg-indigo-50 transition-colors'; ?>">
                    <span class="material-symbols-outlined">dashboard</span>
                    <span class="hidden md:inline">Dashboard</span>
                </a>
            </li>
            <li>
                <a href="<?php echo $current_dir == 'modules' ? '' : '../'; ?>eventos/index.php" 
                   class="flex items-center space-x-3 p-3 rounded-lg <?php echo $current_dir == 'eventos' ? 'bg-indigo-50 text-indigo-700' : 'hover:bg-indigo-50 transition-colors'; ?>">
                    <span class="material-symbols-outlined">event</span>
                    <span class="hidden md:inline">Eventos</span>
                </a>
            </li>
            <li>
                <a href="<?php echo $current_dir == 'modules' ? '' : '../'; ?>musicos/index.php" 
                   class="flex items-center space-x-3 p-3 rounded-lg <?php echo $current_dir == 'musicos' ? 'bg-indigo-50 text-indigo-700' : 'hover:bg-indigo-50 transition-colors'; ?>">
                    <span class="material-symbols-outlined">music_note</span>
                    <span class="hidden md:inline">Músicos</span>
                </a>
            </li>
            <li>
                <a href="<?php echo $current_dir == 'modules' ? '' : '../'; ?>escalas/index.php" 
                   class="flex items-center space-x-3 p-3 rounded-lg <?php echo $current_dir == 'escalas' ? 'bg-indigo-50 text-indigo-700' : 'hover:bg-indigo-50 transition-colors'; ?>">
                    <span class="material-symbols-outlined">playlist_add_check</span>
                    <span class="hidden md:inline">Escalas</span>
                </a>
            </li>
            <li>
                <a href="<?php echo $current_dir == 'modules' ? '' : '../'; ?>musicas/index.php" 
                   class="flex items-center space-x-3 p-3 rounded-lg <?php echo $current_dir == 'musicas' ? 'bg-indigo-50 text-indigo-700' : 'hover:bg-indigo-50 transition-colors'; ?>">
                    <span class="material-symbols-outlined">queue_music</span>
                    <span class="hidden md:inline">Playlist</span>
                </a>
            </li>
            <li>
                <a href="<?php echo $current_dir == 'modules' ? '' : '../'; ?>jejum/index.php" 
                   class="flex items-center space-x-3 p-3 rounded-lg <?php echo $current_dir == 'jejum' ? 'bg-indigo-50 text-indigo-700' : 'hover:bg-indigo-50 transition-colors'; ?>">
                    <span class="material-symbols-outlined">timer</span>
                    <span class="hidden md:inline">Jejum</span>
                </a>
            </li>
        </ul>
    </nav>
</aside>
