<?php
require_once __DIR__ . '/../Config/config.php';

class SpotifyHelper
{
    private $clientId;
    private $clientSecret;
    private $accessToken;

    public function __construct()
    {
        // Accede a las constantes definidas en config.php
        $this->clientId = SP_CLIENT_ID;
        $this->clientSecret = SP_CLIENT_SECRET;
        $this->accessToken = null;

        // Verificación de que las claves se están cargando correctamente
        if (empty($this->clientId) || empty($this->clientSecret)) {
            throw new Exception("Error: Las claves de Spotify no están definidas correctamente.");
        }
    }

    public function getAccessToken()
    {
        if ($this->accessToken === null) {
            $this->accessToken = $this->authenticateSpotify();
        }
        return $this->accessToken;
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


    //Metodo para buscar un albun de spotify y reproducirlo en el reproductor
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

    //Metodo para obtener canciones individual mente y reproducirlas una a una 
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


    public function getTracksInfo(array $trackIds)
    {
        $token = $this->authenticateSpotify();
        $tracks = [];
        $chunkedIds = array_chunk($trackIds, 50);

        foreach ($chunkedIds as $chunk) {
            $ids = implode(',', $chunk);
            $url = "https://api.spotify.com/v1/tracks?ids=" . $ids;

            // Implementar un sistema de caché simple
            $cacheKey = md5($url);
            if ($cachedResult = $this->getFromCache($cacheKey)) {
                if ($cachedResult !== null) {
                    $tracks = array_merge($tracks, $cachedResult);
                    continue;
                }
            }

            // Implementar rate limiting
            $this->rateLimit();

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token
            ]);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpCode == 429) {
                // Too Many Requests - esperar y reintentar
                sleep(10);
                return $this->getTracksInfo($trackIds);
            } elseif ($httpCode != 200) {
                // Manejar el error de manera más elegante
                error_log("Error al obtener información de las canciones: " . $response);
                continue;
            }

            $responseData = json_decode($response, true);

            if (isset($responseData['tracks'])) {
                $chunkTracks = [];
                foreach ($responseData['tracks'] as $track) {
                    $chunkTracks[] = [
                        'id' => $track['id'],
                        'name' => $track['name'],
                        'artist' => $track['artists'][0]['name'],
                        'image' => $track['album']['images'][0]['url'] ?? '',
                        'preview_url' => $track['preview_url'] ?? null,
                        'duration_ms' => $track['duration_ms'] ?? 0,
                        'uri' => $track['uri'] ?? ('spotify:track:' . $track['id'])
                    ];
                }
                $this->saveToCache($cacheKey, $chunkTracks);
                $tracks = array_merge($tracks, $chunkTracks);
            }
        }

        return $tracks;
    }

    private function getFromCache($key)
    {
        // Implementa la lógica para obtener datos de la caché
        // Por ejemplo, usando un archivo:
        $cacheFile = __DIR__ . '/../cache/' . $key . '.json';
        if (file_exists($cacheFile) && (time() - filemtime($cacheFile) < 3600)) { // Cache válida por 1 hora
            $cachedData = file_get_contents($cacheFile);
            return json_decode($cachedData, true);
        }
        return null; // Retorna null si no hay datos en caché o si la caché ha expirado
    }

    private function saveToCache($key, $data)
    {
        //lógica para guardar datos en la caché
        $cacheFile = __DIR__ . '/../cache/' . $key . '.json';
        file_put_contents($cacheFile, json_encode($data));
    }

    private function rateLimit()
    {
        static $lastRequestTime = 0;
        $minTimeBetweenRequests = 1; // 1 segundo

        $currentTime = microtime(true);
        $timeToWait = $minTimeBetweenRequests - ($currentTime - $lastRequestTime);

        if ($timeToWait > 0) {
            usleep($timeToWait * 1000000);
        }

        $lastRequestTime = microtime(true);
    }
}
