<!DOCTYPE html>
<html>
    <head>
        <title>Manancial - Cadastrar Músico</title>
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
                                <h2 class="text-xl md:text-2xl font-bold">Cadastrar Músico</h2>
                            </div>

                            <div class="bg-white rounded-lg shadow-md p-6">
                                <form action="../../api/musicos.php" method="post" class="space-y-6">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <label for="nome" class="block text-sm font-medium text-gray-700 mb-2">Nome</label>
                                            <input type="text" 
                                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500" 
                                                   id="nome" 
                                                   name="nome" 
                                                   required>
                                        </div>
                                        <div>
                                            <label for="instrumento" class="block text-sm font-medium text-gray-700 mb-2">Instrumento</label>
                                            <input type="text" 
                                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500" 
                                                   id="instrumento" 
                                                   name="instrumento" 
                                                   required>
                                        </div>
                                        <div class="md:col-span-2">
                                            <label for="telefone" class="block text-sm font-medium text-gray-700 mb-2">WhatsApp</label>
                                            <input type="tel" 
                                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500" 
                                                   id="telefone" 
                                                   name="telefone" 
                                                   placeholder="Ex: 5549999999999"
                                                   pattern="[0-9]{13,}"
                                                   title="Digite o número com código do país e DDD (ex: 5549999999999)">
                                            <p class="mt-1 text-sm text-gray-500">Digite o número com código do país (55) e DDD, sem espaços ou caracteres especiais</p>
                                        </div>
                                    </div>
                                    <div class="flex justify-end space-x-4">
                                        <a href="index.php" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition-colors">Cancelar</a>
                                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition-colors">
                                            Cadastrar
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </main>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    </body>
</html>