<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <title>Musicalize - Gerenciamento de Jejum</title>
        <link rel="icon" type="image/x-icon" href="../../assets/css/img/favicon.ico">
        <meta name="description" content="Musicalize é um sistema de gerenciamento de escalas de músicos.">
        <meta name="author" content="Web FS">
        <meta name="keywords" content="escalas, músicos, música, gerenciamento">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
        <link rel="stylesheet" href="../../assets/css/style.css">
    </head>
    <body>
        <div id="webcrumbs" class="min-h-screen">
            <div class="w-full bg-gray-50 font-sans">
                <?php include '../../includes/header.php'; ?>

                <div class="flex min-h-screen">
                    <?php include '../../includes/navbar.php'; ?>

                    <main class="flex-1 p-4 md:p-6 overflow-auto">
                        <div class="container mx-auto">
                            <div class="flex justify-between items-center mb-8">
                                <h2 class="text-xl md:text-2xl font-bold">Gerenciamento de Jejum</h2>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                
                                <!-- Card 1: Cadastrar Jejum -->
                                <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow">
                                    <a href="cadastrar.php" class="block text-center">
                                        <div class="flex flex-col items-center">
                                            <span class="material-symbols-outlined text-6xl text-indigo-600 mb-4">edit_calendar</span>
                                            <h3 class="text-lg font-semibold text-gray-800">Cadastrar Jejum</h3>
                                            <p class="text-gray-600 mt-2">Adicionar novo jejum ao sistema</p>
                                        </div>
                                    </a>
                                </div>

                                <!-- Card 2: Visualizar Jejum -->
                                <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow">
                                    <a href="gerenciar.php" class="block text-center">
                                        <div class="flex flex-col items-center">
                                            <span class="material-symbols-outlined text-6xl text-indigo-600 mb-4">calendar_month</span>
                                            <h3 class="text-lg font-semibold text-gray-800">Visualizar Jejum</h3>
                                            <p class="text-gray-600 mt-2">Consultar escala de jejum dos músicos</p>
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