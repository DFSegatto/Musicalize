projeto_musicos/
├── assets/
│   ├── css/
│   │   ├── style.css
│   │   └── vendor/          # CSS de terceiros
│   ├── js/
│   │   ├── main.js
│   │   └── vendor/          # JavaScript de terceiros
│   └── images/
│
├── config/
│   ├── database.php         # Configuração do MySQL
│   └── config.php          # Configurações gerais
│
├── includes/
│   ├── header.php
│   ├── footer.php
│   └── navbar.php
│
├── classes/
│   ├── Database.php
│   ├── Musico.php
│   ├── Evento.php
│   ├── Musica.php
│   └── Escala.php
│
├── modules/
│   ├── musicos/
│   │   ├── index.php
│   │   ├── cadastrar.php
│   │   ├── editar.php
│   │   └── excluir.php
│   ├── eventos/
│   │   ├── index.php
│   │   ├── cadastrar.php
│   │   └── editar.php
│   ├── escalas/
│   │   ├── index.php
│   │   ├── criar.php
│   │   └── gerenciar.php
│   └── musicas/
│       ├── index.php
│       ├── cadastrar.php
│       └── repertorio.php
│
├── api/
│   ├── musicos.php
│   ├── eventos.php
│   └── escalas.php
│
├── utils/
│   ├── functions.php
│   └── helpers.php
│
├── vendor/                  # Dependências (se usar Composer)
│
├── uploads/                 # Arquivos enviados (partituras, etc)
│   ├── partituras/
│   └── fotos/
│
├── index.php
├── .htaccess
└── README.md