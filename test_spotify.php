<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Configurar el logging
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/spotify_error.log');

require_once __DIR__ . '/Helpers/SpotifyHelper.php';
header('Content-Type: application/json');

try {
    error_log("Iniciando búsqueda de canción en Spotify");
    
    $spotify = new SpotifyHelper();

    $songName = isset($_GET['songName']) ? trim($_GET['songName']) : '';

    error_log("Nombre de canción recibido: " . $songName);

    if (empty($songName)) {
        throw new Exception('El nombre de la canción es requerido.');
    }

    // Buscar canciones relacionadas
    error_log("Buscando canción: " . $songName);
    $results = $spotify->searchSpotifyTrack($songName, '');

    if ($results) {
        error_log("Resultados encontrados: " . count($results));
        echo json_encode($results);
    } else {
        error_log("No se encontraron resultados para: " . $songName);
        echo json_encode(['error' => 'No se encontraron resultados.']);
    }
} catch (Exception $e) {
    error_log("Error en test_spotify.php: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}