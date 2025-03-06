<?php
require_once 'musicas.php';

if (isset($_GET['code'])) {
    $spotify = new SpotifyAPI();
    $result = $spotify->getAccessTokenWithScopes($_GET['code']);
    
    if (isset($result['access_token'])) {
        header('Location: /aplicativoManancial/modules/musicas/index.php');
        exit;
    } else {
        echo "Erro ao obter token de acesso: ";
        print_r($result);
        header('Location: /aplicativoManancial/index.php');
    }
} else if (isset($_GET['error'])) {
    echo "Erro na autorização: " . $_GET['error'];
    header('Location: /aplicativoManancial/index.php');
}
