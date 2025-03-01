<!DOCTYPE html>
<html>
    <head>
        <title>Manancial - Músicos</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
        <link rel="stylesheet" href="../../assets/css/style.css">
    </head>
    <body>
        <div id="webcrumbs" class="min-h-screen">
            <div class="w-full bg-gray-50 font-sans">
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

                <div class="flex min-h-screen">
                    <aside class="w-16 md:w-64 bg-white shadow-md">
                        <nav class="p-4">
                            <ul class="space-y-2">
                                <li>
                                    <a href="../../index.php" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-indigo-50 transition-colors">
                                        <span class="material-symbols-outlined">dashboard</span>
                                        <span class="hidden md:inline">Dashboard</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="../eventos/index.php" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-indigo-50 transition-colors">
                                        <span class="material-symbols-outlined">event</span>
                                        <span class="hidden md:inline">Eventos</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="index.php" class="flex items-center space-x-3 p-3 rounded-lg bg-indigo-50 text-indigo-700">
                                        <span class="material-symbols-outlined">music_note</span>
                                        <span class="hidden md:inline">Músicos</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="../escalas/index.php" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-indigo-50 transition-colors">
                                        <span class="material-symbols-outlined">playlist_add_check</span>
                                        <span class="hidden md:inline">Escalas</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="../musicas/index.php" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-indigo-50 transition-colors">
                                        <span class="material-symbols-outlined">queue_music</span>
                                        <span class="hidden md:inline">Playlist</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="../jejum/index.php" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-indigo-50 transition-colors">
                                        <span class="material-symbols-outlined">timer</span>
                                        <span class="hidden md:inline">Jejum</span>
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    </aside>

                    <main class="flex-1 p-4 md:p-6 overflow-auto">
                        <div class="container mx-auto">
                            <div class="flex justify-between items-center mb-8">
                                <h2 class="text-xl md:text-2xl font-bold">Gerenciamento de Músicos</h2>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Cadastrar Músico -->
                                <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow">
                                    <a href="cadastrar.php" class="block text-center">
                                        <div class="flex flex-col items-center">
                                            <span class="material-symbols-outlined text-6xl text-indigo-600 mb-4">person_add</span>
                                            <h3 class="text-lg font-semibold text-gray-800">Cadastrar Músico</h3>
                                            <p class="text-gray-600 mt-2">Adicionar novo músico ao sistema</p>
                                        </div>
                                    </a>
                                </div>

                                <!-- Gerenciar Músicos -->
                                <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow">
                                    <a href="gerenciar.php" class="block text-center">
                                        <div class="flex flex-col items-center">
                                            <span class="material-symbols-outlined text-6xl text-indigo-600 mb-4">manage_accounts</span>
                                            <h3 class="text-lg font-semibold text-gray-800">Gerenciar Músicos</h3>
                                            <p class="text-gray-600 mt-2">Visualizar e editar músicos cadastrados</p>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </main>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>