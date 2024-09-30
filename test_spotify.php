<?php
require_once __DIR__ . '/Helpers/SpotifyHelper.php';
header('Content-Type: application/json');

try {
    $spotify = new SpotifyHelper();

    $songName = isset($_GET['songName']) ? trim($_GET['songName']) : '';

    if (empty($songName)) {
        echo json_encode(['error' => 'El nombre de la canción es requerido.']);
        exit;
    }

    // Buscar canciones relacionadas
    $results = $spotify->searchSpotifyTrack($songName, '');

    if ($results) {
        // Devolver solo los datos necesarios para la base de datos
        echo json_encode($results);
    } else {
        echo json_encode(['error' => 'No se encontraron resultados.']);
    }
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}