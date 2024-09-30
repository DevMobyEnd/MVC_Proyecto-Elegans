<?php
require_once __DIR__ . '/Helpers/SpotifyHelper.php';

header('Content-Type: application/json');

$songName = $_GET['songName'] ?? '';
$artistName = $_GET['artistName'] ?? '';

if (empty($songName)) {
    echo json_encode(['error' => 'El nombre de la canción es requerido.']);
    exit;
}

try {
    $spotifyHelper = new SpotifyHelper();
    $tracks = $spotifyHelper->searchSpotifyTrack($songName, $artistName);

    if (empty($tracks)) {
        echo json_encode(['error' => 'No se encontraron canciones.']);
    } else {
        echo json_encode($tracks);
    }
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}