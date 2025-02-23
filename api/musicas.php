<?php
require_once '../../classes/Musica.php';
require_once '../../classes/Database.php';

class SpotifyAPI {
    private $clientId = 'f96fadea181e452b9f4cc433405a46cb';
    private $clientSecret = 'de371cd0099f44539d86b783f717cf5a';
    private $redirectUri = 'http://localhost/aplicativoManancial/api/callback.php'; // URL de callback
    private $accessToken;
    private $scopes = [
        'streaming',
        'user-read-email',
        'user-read-private',
        'user-read-playback-state',
        'user-modify-playback-state'
    ];

    public function __construct() {
        session_start();
        if (isset($_SESSION['spotify_access_token'])) {
            $this->accessToken = $_SESSION['spotify_access_token'];
        }
    }


    public function cadastrarMusica($titulo, $artista, $duracao){
        $db = new Database();
        $db = $db->getConnection();
        $musica = new Musica($db);
        $musica->salvarMusica($titulo, $artista, $duracao);
    }

    public function getAuthUrl() {
        $scopes = implode(' ', $this->scopes);
        $params = [
            'client_id' => $this->clientId,
            'response_type' => 'code',
            'redirect_uri' => $this->redirectUri,
            'scope' => $scopes,
            'show_dialog' => 'true'
        ];
        
        return 'https://accounts.spotify.com/authorize?' . http_build_query($params);
    }

    public function getAccessTokenWithScopes($code) {
        $ch = curl_init();
        
        $params = [
            'grant_type' => 'authorization_code',
            'code' => $code,
            'redirect_uri' => $this->redirectUri,
        ];

        curl_setopt($ch, CURLOPT_URL, 'https://accounts.spotify.com/api/token');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Basic ' . base64_encode($this->clientId . ':' . $this->clientSecret),
            'Content-Type: application/x-www-form-urlencoded'
        ]);

        $result = curl_exec($ch);
        curl_close($ch);

        $data = json_decode($result, true);
        
        if (isset($data['access_token'])) {
            $this->accessToken = $data['access_token'];
            $_SESSION['spotify_access_token'] = $data['access_token'];
            $_SESSION['spotify_refresh_token'] = $data['refresh_token'];
            $_SESSION['token_expires_in'] = time() + $data['expires_in'];
        }

        return $data;
    }

    public function refreshAccessToken() {
        if (!isset($_SESSION['spotify_refresh_token'])) {
            return false;
        }

        $ch = curl_init();
        
        $params = [
            'grant_type' => 'refresh_token',
            'refresh_token' => $_SESSION['spotify_refresh_token']
        ];

        curl_setopt($ch, CURLOPT_URL, 'https://accounts.spotify.com/api/token');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Basic ' . base64_encode($this->clientId . ':' . $this->clientSecret),
            'Content-Type: application/x-www-form-urlencoded'
        ]);

        $result = curl_exec($ch);
        curl_close($ch);

        $data = json_decode($result, true);
        
        if (isset($data['access_token'])) {
            $this->accessToken = $data['access_token'];
            $_SESSION['spotify_access_token'] = $data['access_token'];
            $_SESSION['token_expires_in'] = time() + $data['expires_in'];
        }

        return $data;
    }

    public function getAccessToken() {
        if (isset($_SESSION['token_expires_in']) && $_SESSION['token_expires_in'] < time()) {
            $this->refreshAccessToken();
        }
        return $this->accessToken;
    }

    public function getPlaylistTracks($playlistId) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api.spotify.com/v1/playlists/{$playlistId}/tracks");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $this->accessToken
        ]);

        $result = curl_exec($ch);
        curl_close($ch);

        return json_decode($result, true);
    }

    public function getPlaylist($playlistId) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api.spotify.com/v1/playlists/{$playlistId}");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $this->accessToken
        ]);

        $result = curl_exec($ch);
        curl_close($ch);

        return json_decode($result, true);
    }
}