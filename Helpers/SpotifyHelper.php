<?php
require_once __DIR__ . '/../Config/config.php';

class SpotifyHelper
{
    private $clientId;
    private $clientSecret;

    public function __construct()
    {
        // Accede a las constantes definidas en config.php
        $this->clientId = SP_CLIENT_ID;
        $this->clientSecret = SP_CLIENT_SECRET;

        // Verificación de que las claves se están cargando correctamente
        if (empty($this->clientId) || empty($this->clientSecret)) {
            throw new Exception("Error: Las claves de Spotify no están definidas correctamente.");
        }
    }

    private function authenticateSpotify()
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://accounts.spotify.com/api/token");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Basic " . base64_encode($this->clientId . ":" . $this->clientSecret)
        ]);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            $errorMsg = 'Error de cURL: ' . curl_error($ch);
            curl_close($ch);
            throw new Exception($errorMsg);
        }

        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode != 200) {
            $errorMsg = 'Error HTTP: ' . $httpCode . ' - ' . $response;
            throw new Exception($errorMsg);
        }

        $responseData = json_decode($response, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('Error al decodificar JSON: ' . json_last_error_msg());
        }

        if (isset($responseData['error'])) {
            throw new Exception('Error de API de Spotify: ' . $responseData['error']['message']);
        }

        return $responseData['access_token'] ?? null;
    }

    public function searchSpotifyTrack($songName, $artistName = '')
    {
        $accessToken = $this->authenticateSpotify();
        $query = urlencode($songName . " " . $artistName);
        $url = "https://api.spotify.com/v1/search?q={$query}&type=track&limit=10"; // limit 10 para más opciones

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer " . $accessToken
        ]);

        $response = curl_exec($ch);
        if ($response === false) {
            throw new Exception('Error de conexión a Spotify: ' . curl_error($ch));
        }

        $responseData = json_decode($response, true);
        curl_close($ch);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('Error al decodificar JSON: ' . json_last_error_msg());
        }

        if (isset($responseData['error'])) {
            throw new Exception('Error en la búsqueda: ' . $responseData['error']['message']);
        }

        // Si no hay resultados
        if (empty($responseData['tracks']['items'])) {
            return null;
        }

        // Extraer la información relevante
        $tracks = [];
        foreach ($responseData['tracks']['items'] as $track) {
            $tracks[] = [
                'spotify_track_id' => $track['id'], // El ID de la canción en Spotify
                'nombre_cancion' => $track['name'], // El nombre de la canción
                'nombre_artista' => $track['artists'][0]['name'], // Nombre del primer artista
                'imagen_url' => $track['album']['images'][0]['url'] ?? '', // La imagen del álbum (generalmente es la primera)
            ];
        }

        return $tracks; // Devolver array con resultados
    }

    public function getPlaybackScript($songName, $artistName = '')
    {
        try {
            $tracks = $this->searchSpotifyTrack($songName, $artistName);

            if (empty($tracks) || !is_array($tracks)) {
                return '<p>No se encontraron canciones.</p>';
            }

            $firstTrack = $tracks[0];
            if (!isset($firstTrack['spotify_track_id'])) {
                return '<p>Información de la canción no disponible.</p>';
            }

            $trackId = $firstTrack['spotify_track_id'];
            $script = '<iframe src="https://open.spotify.com/embed/track/' . $trackId . '" width="300" height="380" frameborder="0" allowtransparency="true" allow="encrypted-media"></iframe>';

            return $script;
        } catch (Exception $e) {
            return '<p>Error: ' . $e->getMessage() . '</p>';
        }
    }
}
