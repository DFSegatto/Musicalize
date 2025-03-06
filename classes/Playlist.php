<?php
class Playlist {
    private $storage_file;
    private $playlists;

    public function __construct() {
        $this->storage_file = __DIR__ . '/../data/playlists.json';
        $this->initStorage();
        $this->loadPlaylists();
    }

    private function initStorage() {
        // Cria o diretório data se não existir
        $dir = dirname($this->storage_file);
        if (!file_exists($dir)) {
            mkdir($dir, 0777, true);
        }
        
        // Cria o arquivo se não existir
        if (!file_exists($this->storage_file)) {
            file_put_contents($this->storage_file, json_encode([]));
        }
    }

    private function loadPlaylists() {
        $content = file_get_contents($this->storage_file);
        $this->playlists = json_decode($content, true) ?? [];
    }

    private function savePlaylists() {
        file_put_contents($this->storage_file, json_encode($this->playlists, JSON_PRETTY_PRINT));
    }

    public function salvar($data) {
        // Adiciona timestamp
        $data['ultimo_acesso'] = date('Y-m-d H:i:s');
        
        // Atualiza ou adiciona a playlist
        $this->playlists[$data['playlist_id']] = $data;
        
        // Mantém apenas as 10 playlists mais recentes
        uasort($this->playlists, function($a, $b) {
            return strtotime($b['ultimo_acesso']) - strtotime($a['ultimo_acesso']);
        });
        
        $this->playlists = array_slice($this->playlists, 0, 10, true);
        
        $this->savePlaylists();
        return true;
    }

    public function listar() {
        // Ordena por último acesso
        uasort($this->playlists, function($a, $b) {
            return strtotime($b['ultimo_acesso']) - strtotime($a['ultimo_acesso']);
        });

        return $this->playlists;
    }
} 