<?php
require_once '../../api/musicas.php';
require_once '../../classes/Musica.php';
require_once '../../classes/Database.php';
require_once '../../classes/Playlist.php';

$db = new Database();
$db = $db->getConnection();

$spotify = new SpotifyAPI();

// Verifica se já temos um token de acesso
if (!$spotify->getAccessToken()) {
    // Se não tiver token, redireciona para autorização
    header('Location: ' . $spotify->getAuthUrl());
    exit;
}

$playlistId = $_GET['playlistId'] ?? null;
$playlist = $spotify->getPlaylist($playlistId);
$tracks = $spotify->getPlaylistTracks($playlistId);

$playlistObj = new Playlist($db);

if(isset($playlistId) && $playlist) {
    // Salva a playlist no histórico
    $playlistObj->salvar([
        'playlist_id' => $playlistId,
        'nome' => $playlist['name'],
        'descricao' => $playlist['description'],
        'imagem_url' => $playlist['images'][0]['url'],
        'total_musicas' => count($playlist['tracks']['items'])
    ]);
}

// Busca as playlists já acessadas
$playlistsAcessadas = $playlistObj->listar();

if(isset($tracks['items'][0]['track']['id'])){
    $musica = new Musica($db);
    $musicasExistentes = $musica->listar();
    $musicasAtuais = array();
    
    // Cria array com títulos das músicas já cadastradas
    while($row = $musicasExistentes->fetch(PDO::FETCH_ASSOC)) {
        $musicasAtuais[] = strtolower($row['titulo']);
    }

    foreach($tracks['items'] as $item){
        // Verifica se a música já existe no banco
        if(!in_array(strtolower($item['track']['name']), $musicasAtuais)){
            $spotify->cadastrarMusica(
                $item['track']['name'],
                $item['track']['artists'][0]['name'],
                $item['track']['duration_ms']
            );
        }
    }
}


?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Musicalize - Playlist Spotify</title>
    <link rel="icon" type="image/x-icon" href="assets/css/img/favicon.ico">
    <meta name="description" content="Musicalize é um sistema de gerenciamento de escalas de músicos.">
    <meta name="author" content="Web FS">
    <meta name="keywords" content="escalas, músicos, música, gerenciamento">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="stylesheet" href="../../assets/css/style.css">
    
    <style>
        body {
            background-color: #02416d;
            color: white;
        }

        .table>:not(caption)>*>* {
            background-color:rgb(15, 15, 15) !important;
        }

        .tracks-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .track {
            transition: background-color 0.2s;
            border-radius: 4px;
        }

        .track:hover {
            background-color: #282828;
        }

        .track-number {
            color:rgba(255, 255, 255, 0.7);
            width: 50px;
            text-align: center;
        }

        .track-name {
            color: white;
            margin: 0;
            font-size: 0.95rem;
            font-weight: 500;
        }

        .track-artists {
            color:rgba(255, 255, 255, 0.7);
            margin: 0;
            font-size: 0.85rem;
        }

        .track-duration {
            color:rgba(255, 255, 255, 0.7);
            font-size: 0.85rem;
            text-align: right;
        }

        .header {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            border-bottom: 1px solid #282828;
            padding: 8px 0;
            margin-bottom: 16px;
        }

        .play-button {
            display: none;
            color: #aedd2b;
        }

        .track:hover .track-number .number {
            display: none;
        }

        .track:hover .play-button {
            display: inline;
        }

        .play-button:hover {
            color: #f8f8ec;
        }

        /* Estilo para o card da playlist */
        .playlist-header {
            background: linear-gradient(to bottom, #066699, #0a5483, #02416d);
            padding: 40px 0 20px;
            margin-bottom: 20px;
        }

        .playlist-image {
            width: 232px;
            height: 232px;
            box-shadow: 0 4px 60px rgba(0,0,0,.5);
        }

        .playlist-info {
            color: white;
        }

        .playlist-info p.mb-0 {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.9rem;
            letter-spacing: 0.1em;
        }

        .playlist-info h1 {
            color: white;
            font-size: 3rem;
            font-weight: bold;
            margin: 20px 0 10px;
        }
        
        /* Botões de ação */
        .action-buttons {
            margin: 20px 0;
        }

        .btn-success {
            background-color: #aedd2b;
            padding: 12px 35px;
            border-radius: 25px;
            font-weight: bold;
        }

        .btn-success:hover {
            background-color: #aedd2b;
            transform: scale(1.05);
        }

        /* Estilos adicionais para o player */
        #player-controls {
            background: rgba(0, 0, 0, 0.98) !important;
            border-top: 1px solid #282828;
            z-index: 1000;
            transition: all 0.3s ease;
        }

        .progress {
            background-color:rgba(255, 255, 255, 0.7);
            height: 4px;
            transition: height 0.2s ease;
        }

        .progress:hover {
            height: 6px;
        }

        .progress-bar {
            background-color: #aedd2b;
            transition: width 0.1s linear;
        }

        .progress:hover .progress-bar {
            background-color: #aedd2b;
        }

        .form-range::-webkit-slider-thumb {
            background: #fff;
        }

        .form-range::-webkit-slider-runnable-track {
            background: #535353;
        }

        #current-track-name {
            color: white;
            font-size: 0.9rem;
            font-weight: 500;
        }

        #current-track-artist {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.8rem;
        }

        #current-time, #total-time {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.75rem;
        }

        /* Ajuste para o conteúdo principal não ficar escondido atrás do player */
        body {
            padding-bottom: 100px;
        }

        /* Ajustes de responsividade */
        @media (max-width: 991px) {
            .playlist-image {
                width: 192px;
                height: 192px;
            }

            .playlist-info h1 {
                font-size: 2rem;
                margin: 10px 0 5px;
            }

            .playlist-header .row {
                display: flex;
                align-items: end;
            }

            .playlist-header .col-md-3 {
                width: auto;
                flex: 0 0 auto;
                padding-right: 20px;
            }

            .playlist-header .col-md-9 {
                width: auto;
                flex: 1 1 auto;
            }
        }

        @media (max-width: 767px) {
            .playlist-header .row {
                text-align: center;
                display: block;
            }

            .playlist-image {
                margin-bottom: 20px;
            }

            .playlist-header .col-md-3,
            .playlist-header .col-md-9 {
                width: 100%;
            }
        }

        /* Ajustes dos textos da busca */
        .card-body h4 {
            color: #02416d;
        }

        .form-label {
            color: #666;
        }

        .text-muted {
            color: #666 !important;
        }

        .bg-light p {
            color: #444;
        }

        code {
            color: #02416d;
            background-color: rgba(2, 65, 109, 0.1);
            padding: 2px 4px;
            border-radius: 4px;
        }
    </style>

    <!-- Spotify Web Playback SDK -->
    <script src="https://sdk.scdn.co/spotify-player.js"></script>
    <script>
        // Armazena o token de acesso do PHP para usar no JavaScript
        const token = '<?php echo $spotify->getAccessToken(); ?>';
        let player;
        
        window.onSpotifyWebPlaybackSDKReady = () => {
            player = new Spotify.Player({
                name: 'Aplicativo Manancial',
                getOAuthToken: cb => { cb(token); },
                volume: 0.5
            });

            // Tratamento de erros
            player.addListener('initialization_error', ({ message }) => { 
                console.error('Failed to initialize', message);
            });
            player.addListener('authentication_error', ({ message }) => {
                console.error('Failed to authenticate', message);
            });
            player.addListener('account_error', ({ message }) => {
                console.error('Failed to validate Spotify account', message);
            });
            player.addListener('playback_error', ({ message }) => {
                console.error('Failed to perform playback', message);
            });

            // Playback status updates
            player.addListener('player_state_changed', state => {
                if (state) {
                    updatePlayButton(state);
                }
            });

            // Ready
            player.addListener('ready', ({ device_id }) => {
                console.log('Ready with Device ID', device_id);
                window.deviceId = device_id;
            });

            // Not Ready
            player.addListener('not_ready', ({ device_id }) => {
                console.log('Device ID has gone offline', device_id);
            });

            // Connect to the player
            player.connect().then(success => {
                if (success) {
                    console.log('Successfully connected to Spotify!');
                }
            });
        };

        // Adicione esta função para controlar a visibilidade do player
        function togglePlayerVisibility(show) {
            const playerControls = document.getElementById('player-controls');
            if (show) {
                playerControls.style.display = 'block';
                document.body.style.paddingBottom = '100px';
            } else {
                playerControls.style.display = 'none';
                document.body.style.paddingBottom = '0';
            }
        }

        // Inicialmente esconde o player
        window.onload = function() {
            togglePlayerVisibility(false);
        };

        // Atualiza a função playTrack
        async function playTrack(uri) {
            try {
                if (!window.deviceId) {
                    console.error('Device ID not found');
                    return;
                }

                const response = await fetch(`https://api.spotify.com/v1/me/player/play?device_id=${window.deviceId}`, {
                    method: 'PUT',
                    body: JSON.stringify({
                        uris: [uri]
                    }),
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': `Bearer ${token}`
                    },
                });

                if (!response.ok) {
                    throw new Error('Failed to start playback');
                }

                // Mostra o player quando começar a tocar
                togglePlayerVisibility(true);

                // Atualiza o botão de play/pause no player fixo
                document.getElementById('play-pause-btn').innerHTML = '<i class="fas fa-pause fa-lg"></i>';

                // Atualiza o botão na lista de músicas
                updatePlayButtons();
                const button = document.querySelector(`[data-uri="${uri}"]`);
                if (button) {
                    button.innerHTML = '<i class="fas fa-pause"></i>';
                }

            } catch (error) {
                console.error('Error playing track:', error);
                alert('Error playing track. Make sure you have Spotify Premium and try again.');
            }
        }

        // Atualiza a função togglePlayPause
        async function togglePlayPause() {
            const state = await player.getCurrentState();
            if (state?.paused) {
                await player.resume();
                document.getElementById('play-pause-btn').innerHTML = '<i class="fas fa-pause fa-lg"></i>';
                // Atualiza o botão na lista de músicas
                const currentTrackUri = state.track_window.current_track.uri;
                const button = document.querySelector(`[data-uri="${currentTrackUri}"]`);
                if (button) {
                    button.innerHTML = '<i class="fas fa-pause"></i>';
                }
            } else {
                await player.pause();
                document.getElementById('play-pause-btn').innerHTML = '<i class="fas fa-play fa-lg"></i>';
                // Atualiza o botão na lista de músicas
                const currentTrackUri = state.track_window.current_track.uri;
                const button = document.querySelector(`[data-uri="${currentTrackUri}"]`);
                if (button) {
                    button.innerHTML = '<i class="fas fa-play"></i>';
                }
            }
        }

        // Atualiza a função updatePlayButton
        function updatePlayButton(state) {
            if (!state) return;

            const currentTrackUri = state.track_window.current_track.uri;
            
            // Atualiza os botões na lista de músicas
            document.querySelectorAll('.play-button').forEach(button => {
                if (button.dataset.uri === currentTrackUri) {
                    button.innerHTML = state.paused ? 
                        '<i class="fas fa-play"></i>' : 
                        '<i class="fas fa-pause"></i>';
                } else {
                    button.innerHTML = '<i class="fas fa-play"></i>';
                }
            });

            // Atualiza o botão no player fixo
            document.getElementById('play-pause-btn').innerHTML = state.paused ? 
                '<i class="fas fa-play fa-lg"></i>' : 
                '<i class="fas fa-pause fa-lg"></i>';
        }

        // Função para pausar a reprodução
        async function pausePlayback() {
            try {
                await player.pause();
                updatePlayButtons();
            } catch (error) {
                console.error('Error pausing playback:', error);
            }
        }

        // Atualiza todos os botões de play
        function updatePlayButtons() {
            document.querySelectorAll('.play-button').forEach(button => {
                button.innerHTML = '<i class="fas fa-play"></i>';
            });
        }

        // Função atualizada para atualizar os detalhes da música atual
        function updateTrackInfo(state) {
            if (!state || !state.track_window.current_track) return;

            const track = state.track_window.current_track;
            const duration = state.duration;
            const position = state.position;
            
            // Atualiza imagem e informações da música
            document.getElementById('current-track-image').src = track.album.images[0].url;
            document.getElementById('current-track-name').textContent = track.name;
            document.getElementById('current-track-artist').textContent = track.artists.map(artist => artist.name).join(', ');
            
            // Atualiza duração e posição
            document.getElementById('current-time').textContent = formatTime(position);
            document.getElementById('total-time').textContent = formatTime(duration);
            
            // Atualiza a barra de progresso
            const progressBar = document.querySelector('.progress-bar');
            const percentage = (position / duration) * 100;
            progressBar.style.width = `${percentage}%`;
        }

        // Formata o tempo em minutos:segundos
        function formatTime(ms) {
            const minutes = Math.floor(ms / 60000);
            const seconds = Math.floor((ms % 60000) / 1000);
            return `${minutes}:${seconds.toString().padStart(2, '0')}`;
        }

        // Atualiza o estado do player a cada segundo
        setInterval(() => {
            if (player) {
                player.getCurrentState().then(state => {
                    if (state) {
                        updateTrackInfo(state);
                    }
                });
            }
        }, 1000);

        // Função para alternar entre play/pause
        async function togglePlayPause() {
            const state = await player.getCurrentState();
            if (state?.paused) {
                await player.resume();
                document.getElementById('play-pause-btn').innerHTML = '<i class="fas fa-pause fa-lg"></i>';
            } else {
                await player.pause();
                document.getElementById('play-pause-btn').innerHTML = '<i class="fas fa-play fa-lg"></i>';
            }
        }

        // Função atualizada para buscar uma posição específica na música
        async function seekToPosition(event) {
            const progressBar = event.currentTarget;
            const rect = progressBar.getBoundingClientRect();
            const clickPosition = (event.clientX - rect.left) / rect.width;
            
            const state = await player.getCurrentState();
            if (state) {
                const position = state.duration * clickPosition;
                await player.seek(position);
            }
        }

        // Atualiza o volume
        function updateVolume(value) {
            player.setVolume(value / 100);
        }

        // Adicione esta função para reproduzir a playlist completa
        async function playPlaylist(playlistId) {
            try {
                if (!window.deviceId) {
                    console.error('Device ID not found');
                    return;
                }

                const response = await fetch(`https://api.spotify.com/v1/me/player/play?device_id=${window.deviceId}`, {
                    method: 'PUT',
                    body: JSON.stringify({
                        context_uri: `spotify:playlist:${playlistId}`
                    }),
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': `Bearer ${token}`
                    },
                });

                if (!response.ok) {
                    throw new Error('Failed to start playback');
                }

                // Mostra o player quando começar a tocar
                togglePlayerVisibility(true);
                
                // Atualiza o botão de play/pause no player fixo
                document.getElementById('play-pause-btn').innerHTML = '<i class="fas fa-pause fa-lg"></i>';
                
                // Atualiza os botões na lista
                updatePlayButtons();

            } catch (error) {
                console.error('Error playing playlist:', error);
                alert('Error playing playlist. Make sure you have Spotify Premium and try again.');
            }
        }
        
        function buscarIdLink() {
            const linkPlaylist = document.getElementById('linkPlaylist').value.trim();
            const partes = linkPlaylist.split('/playlist/')[1];
            const id = partes.split('?')[0];
            return id;
        }

        const playlistId = buscarIdLink();

        function buscarPlaylist() {
            const playlistId = buscarIdLink();
            
            if (!playlistId) {
                alert('Por favor, insira o link da playlist');
                return;
            }

            // Remove qualquer parte da URL que não seja o ID
            const cleanId = playlistId.split('?')[0].split('/').pop();
            
            window.location.href = window.location.pathname + '?playlistId=' + cleanId;
        }
    </script>
</head>
<body>
    <?php
    if(isset($playlistId) && $playlistId !== null): ?>

    <!-- Cabeçalho da Playlist -->
    <div class="playlist-header">
        <div class="container">
            <!-- Adicionar botão voltar -->
            <div class="mb-4">
                <a href="index.php" class="btn btn-link text-white p-0">
                    <span class="material-symbols-outlined">arrow_back</span>
                </a>
            </div>
            <!-- Resto do conteúdo -->
            <div class="row align-items-end">
                <div class="col-md-3">
                    <img src="<?php echo $playlist['images'][0]['url']; ?>" alt="Playlist Cover" class="playlist-image">
                </div>
                <div class="col-md-9 playlist-info">
                    <p class="mb-0">PLAYLIST</p>
                    <h1><?php echo htmlspecialchars($playlist['name']); ?></h1>
                    <p><?php echo htmlspecialchars($playlist['description']); ?> • <?php echo count($playlist['tracks']['items']); ?> músicas</p>
                </div>
            </div>
        </div>
    </div>

    <div class="tracks-container">
        <!-- Botões de Ação -->
        <div class="action-buttons">
            <button class="btn btn-success me-2" onclick="playPlaylist('<?php echo $playlistId; ?>')">
                <i class="fas fa-play me-2"></i>Reproduzir
            </button>
        </div>

        <!-- Lista de Músicas -->
        <div class="table-responsive">
            <table class="table table-dark table-hover">
                <thead>
                    <tr class="header">
                        <th scope="col" style="width: 50px">#</th>
                        <th scope="col">TÍTULO</th>
                        <th scope="col" class="text-end" style="width: 120px">DURAÇÃO</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (isset($tracks['error'])) {
                        echo "<tr><td colspan='3' class='text-center'>Erro na API do Spotify: " . $tracks['error']['message'] . "</td></tr>";
                        exit;
                    }

                    if (!isset($tracks['items']) || !is_array($tracks['items'])) {
                        echo "<tr><td colspan='3' class='text-center'>Nenhuma música encontrada</td></tr>";
                        exit;
                    }

                    $trackNumber = 1;
                    foreach ($tracks['items'] as $item) {
                        $track = $item['track'];
                        $trackName = $track['name'];
                        $artists = array_map(function($artist) {
                            return $artist['name'];
                        }, $track['artists']);
                        
                        // Converter duração
                        $duration_ms = $track['duration_ms'];
                        $minutes = floor($duration_ms / 60000);
                        $seconds = floor(($duration_ms % 60000) / 1000);
                        $duration = sprintf("%d:%02d", $minutes, $seconds);

                        echo "<tr class='track'>";
                        echo "<td class='track-number align-middle'>";
                        echo "<span class='number'>{$trackNumber}</span>";
                        echo "<button class='btn btn-link p-0 play-button' data-uri='{$track['uri']}' onclick='playTrack(\"{$track['uri']}\")'>";
                        echo "<i class='fas fa-play'></i>";
                        echo "</button>";
                        echo "</td>";
                        echo "<td class='align-middle'>";
                        echo "<div class='track-name'>" . htmlspecialchars($trackName) . "</div>";
                        echo "<div class='track-artists'>" . htmlspecialchars(implode(', ', $artists)) . "</div>";
                        echo "</td>";
                        echo "<td class='track-duration align-middle text-end'>{$duration}</td>";
                        echo "</tr>";
                        
                        $trackNumber++;
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <!-- Bootstrap JS e Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>

    <!-- Atualize o player fixo -->
    <div class="fixed-bottom bg-dark p-3" id="player-controls" style="display: none;">
        <div class="container">
            <div class="row align-items-center">
                <!-- Informações da música -->
                <div class="col-md-3">
                    <div class="d-flex align-items-center">
                        <img id="current-track-image" src="" width="60" height="60" class="me-3 rounded">
                        <div>
                            <div id="current-track-name" class="text-white text-truncate"></div>
                            <div id="current-track-artist" class="text-white text-truncate"></div>
                        </div>
                    </div>
                </div>

                <!-- Controles de reprodução -->
                <div class="col-md-6">
                    <div class="d-flex flex-column align-items-center">
                        <div class="mb-2">
                            <button class="btn btn-link text-white mx-2" onclick="togglePlayPause()" id="play-pause-btn">
                                <i class="fas fa-play fa-lg"></i>
                            </button>
                        </div>
                        <div class="w-100 d-flex align-items-center">
                            <span id="current-time" class="text-white small me-2">0:00</span>
                            <div class="progress w-100" style="cursor: pointer; height: 4px;" onclick="seekToPosition(event)">
                                <div class="progress-bar" role="progressbar" style="width: 0%"></div>
                            </div>
                            <span id="total-time" class="text-white small ms-2">0:00</span>
                        </div>
                    </div>
                </div>

                <!-- Controle de volume -->
                <div class="col-md-3">
                    <div class="d-flex align-items-center justify-content-end">
                        <i class="fas fa-volume-down text-white me-2"></i>
                        <input type="range" class="form-range" min="0" max="100" value="50" 
                               style="width: 100px;" onchange="updateVolume(this.value)">
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php else: ?>
    <!-- Interface para solicitar ID da playlist -->
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="mb-3 mb-md-0">
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <a href="../../index.php" class="btn btn-link text-white p-0">
                            <span class="material-symbols-outlined">arrow_back</span>
                        </a>
                        <h1 class="h3 fw-bold mb-0">Buscar Playlist</h1>
                    </div>
                </div>
                <div class="card border-0 shadow mb-4">
                    <div class="card-body p-4">
                        <h4 class="text-center mb-4">Buscar Playlist do Spotify</h4>
                        <div class="mb-4">
                            <label class="form-label text-muted">Informe o link da playlist</label>
                            <div class="bg-light p-3 rounded mb-3">
                                <p class="mb-2">1. Abra a playlist no Spotify</p>
                                <p class="mb-2">2. Clique com o botão direito do mouse na playlist</p>
                                <p class="mb-0">3. Clique em 'Compartilhar'</p>
                                <p class="mb-0">4. Clique em 'Copiar link da playlist'</p>
                                <p class="mb-0">5. Cole o link na caixa de texto abaixo</p>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="linkPlaylist" class="form-label">Link da Playlist</label>
                            <input type="text" 
                                   class="form-control" 
                                   id="linkPlaylist" 
                                   placeholder="Ex: https://open.spotify.com/playlist/1buSJB6XrEQyaGuoaVi0LD"
                                   required>
                        </div>
                        <button class="btn btn-primary w-100" onclick="buscarPlaylist()">
                            <i class="fas fa-search me-2"></i>Buscar Playlist
                        </button>
                    </div>
                </div>

                <!-- Lista de Playlists Acessadas -->
                <?php if($playlistsAcessadas && !empty($playlistsAcessadas)): ?>
                <div class="card border-0 shadow">
                    <div class="card-body p-4">
                        <h5 class="mb-4">Playlists Recentes</h5>
                        <div class="list-group">
                            <?php foreach($playlistsAcessadas as $playlist): ?>
                                <a href="?playlistId=<?php echo $playlist['playlist_id']; ?>" 
                                   class="list-group-item list-group-item-action d-flex align-items-center gap-3">
                                    <img src="<?php echo $playlist['imagem_url']; ?>" 
                                         alt="<?php echo htmlspecialchars($playlist['nome']); ?>" 
                                         width="48" 
                                         height="48" 
                                         class="rounded">
                                    <div class="flex-grow-1">
                                        <h6 class="mb-0"><?php echo htmlspecialchars($playlist['nome']); ?></h6>
                                        <small class="text-muted">
                                            <?php echo $playlist['total_musicas']; ?> músicas
                                        </small>
                                    </div>
                                    <i class="fas fa-chevron-right text-muted"></i>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
<?php endif; ?>
</body>
</html>