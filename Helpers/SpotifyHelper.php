<?php
require_once __DIR__ . '/../Config/config.php';
 /**
 * Clase base para el manejo de caché
 */
class BaseCache {
    protected string $cachePath;
    
    public function __construct($cachePath = null) {
        $this->cachePath = $cachePath ?? __DIR__ . '/../cache';
        if (!is_dir($this->cachePath)) {
            mkdir($this->cachePath, 0777, true);
        }
    }

    public function get($key) {
        $file = $this->getCacheFile($key);
        if (!file_exists($file)) {
            return null;
        }

        $data = json_decode(file_get_contents($file), true);
        if (!$data || $data['expires'] < time()) {
            $this->delete($key);
            return null;
        }

        return $data['value'];
    }

    public function set($key, $value, $ttl = 3600) {
        $data = [
            'value' => $value,
            'expires' => time() + $ttl
        ];
        file_put_contents($this->getCacheFile($key), json_encode($data));
    }

    public function delete($key) {
        $file = $this->getCacheFile($key);
        if (file_exists($file)) {
            unlink($file);
        }
    }

    protected function getCacheFile($key) {
        return $this->cachePath . '/' . md5($key) . '.cache';
    }
}

/**
 * Clase principal para interactuar con la API de Spotify
 */
class SpotifyHelper {
    private $clientId;
    private $clientSecret;
    private $accessToken;
    private $tokenExpiration;
    private $cache;
    
    // Constantes
    const TOKEN_URL = 'https://accounts.spotify.com/api/token';
    const API_BASE_URL = 'https://api.spotify.com/v1';
    const CACHE_TTL = 3600; // 1 hora
    const MAX_RETRIES = 3;

    /**
     * Constructor
     */
    public function __construct() {
        $this->clientId = SP_CLIENT_ID;
        $this->clientSecret = SP_CLIENT_SECRET;
        $this->cache = new BaseCache();
        $this->accessToken = null;
        $this->tokenExpiration = 0;

        $this->validateConfig();
    }

    /**
     * Valida la configuración inicial
     */
    private function validateConfig() {
        if (empty($this->clientId) || empty($this->clientSecret)) {
            throw new Exception('Error: Spotify credentials are not properly configured.');
        }
    }

    /**
     * Obtiene un token de acceso válido
     */
    public function getAccessToken() {
        if ($this->accessToken === null || $this->isTokenExpired()) {
            $this->authenticateSpotify();
        }
        return $this->accessToken;
    }

    /**
     * Verifica si el token actual ha expirado
     */
    private function isTokenExpired() {
        return time() >= $this->tokenExpiration;
    }

    /**
     * Autentica con Spotify y obtiene un nuevo token
     */
    private function authenticateSpotify() {
        try {
            $ch = curl_init(self::TOKEN_URL);
            curl_setopt_array($ch, [
                CURLOPT_POST => 1,
                CURLOPT_POSTFIELDS => "grant_type=client_credentials",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HTTPHEADER => [
                    "Authorization: Basic " . base64_encode($this->clientId . ":" . $this->clientSecret)
                ]
            ]);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            
            if (curl_errno($ch)) {
                throw new Exception('Curl error: ' . curl_error($ch));
            }
            
            curl_close($ch);

            if ($httpCode !== 200) {
                throw new Exception("HTTP Error: $httpCode - $response");
            }

            $data = json_decode($response, true);
            if (!$data || !isset($data['access_token'])) {
                throw new Exception('Invalid response from Spotify');
            }

            $this->accessToken = $data['access_token'];
            $this->tokenExpiration = time() + ($data['expires_in'] ?? 3600);
            
        } catch (Exception $e) {
            throw new Exception('Authentication failed: ' . $e->getMessage());
        }
    }

    /**
     * Busca una canción en Spotify
     */
    public function searchSpotifyTrack($songName, $artistName = '') {
        if (empty(trim($songName))) {
            throw new Exception('Song name cannot be empty');
        }
        
        $query = urlencode(trim($songName . ' ' . $artistName));
        $cacheKey = 'search_' . md5($query);

        // Intentar obtener de caché
        $cached = $this->cache->get($cacheKey);
        if ($cached !== null) {
            return $cached;
        }

        try {
            $url = self::API_BASE_URL . "/search?q={$query}&type=track&limit=10";
            $ch = curl_init();
            curl_setopt_array($ch, [
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HTTPHEADER => [
                    "Authorization: Bearer " . $this->getAccessToken()
                ]
            ]);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpCode !== 200) {
                throw new Exception("Search failed with HTTP code: $httpCode");
            }

            $data = json_decode($response, true);
            if (empty($data['tracks']['items'])) {
                return null;
            }

            $tracks = array_map(function($track) {
                return [
                    'spotify_track_id' => $track['id'],
                    'nombre_cancion' => $track['name'],
                    'nombre_artista' => $track['artists'][0]['name'],
                    'imagen_url' => $track['album']['images'][0]['url'] ?? '',
                    'preview_url' => $track['preview_url'] ?? null,
                    'duration_ms' => $track['duration_ms'] ?? 0
                ];
            }, $data['tracks']['items']);

            // Guardar en caché
            $this->cache->set($cacheKey, $tracks, self::CACHE_TTL);
            return $tracks;

        } catch (Exception $e) {
            throw new Exception('Search failed: ' . $e->getMessage());
        }
    }

    /**
     * Genera el script de reproducción para una canción
     */
    public function getPlaybackScript($songName, $artistName = '') {
        try {
            $tracks = $this->searchSpotifyTrack($songName, $artistName);

            if (empty($tracks) || !is_array($tracks)) {
                return '<p>No tracks found.</p>';
            }

            $firstTrack = $tracks[0];
            if (!isset($firstTrack['spotify_track_id'])) {
                return '<p>Track information not available.</p>';
            }

            return sprintf(
                '<iframe src="https://open.spotify.com/embed/track/%s" 
                         width="300" 
                         height="380" 
                         frameborder="0" 
                         allowtransparency="true" 
                         allow="encrypted-media">
                </iframe>',
                htmlspecialchars($firstTrack['spotify_track_id'])
            );
        } catch (Exception $e) {
            return '<p>Error: ' . htmlspecialchars($e->getMessage()) . '</p>';
        }
    }
     /**
     * Obtiene información detallada de múltiples tracks
     * @param array $trackIds Array de IDs de Spotify
     * @return array
     * @throws Exception
     */
    public function getTracksInfo($trackIds) {
        if (empty($trackIds)) {
            throw new Exception('Track IDs array cannot be empty');
        }

        // Validar formato de IDs
        foreach ($trackIds as $id) {
            if (!preg_match('/^[a-zA-Z0-9]{22}$/', $id)) {
                throw new Exception('Invalid Spotify track ID format: ' . $id);
            }
        }

        $tracks = [];
        $chunkedIds = array_chunk($trackIds, 50); // Spotify permite máximo 50 IDs por petición

        foreach ($chunkedIds as $chunk) {
            $ids = implode(',', $chunk);
            $cacheKey = 'tracks_' . md5($ids);

            // Intentar obtener de caché
            $cached = $this->cache->get($cacheKey);
            if ($cached !== null) {
                $tracks = array_merge($tracks, $cached);
                continue;
            }

            // Si no está en caché, hacer la petición a la API
            try {
                $attempts = 0;
                $maxAttempts = 3;
                $success = false;

                while (!$success && $attempts < $maxAttempts) {
                    try {
                        $url = self::API_BASE_URL . "/tracks?ids=" . $ids;
                        $ch = curl_init();
                        curl_setopt_array($ch, [
                            CURLOPT_URL => $url,
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_HTTPHEADER => [
                                "Authorization: Bearer " . $this->getAccessToken()
                            ]
                        ]);

                        $response = curl_exec($ch);
                        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                        
                        if (curl_errno($ch)) {
                            throw new Exception('Curl error: ' . curl_error($ch));
                        }
                        
                        curl_close($ch);

                        if ($httpCode === 429) { // Rate limiting
                            $attempts++;
                            if ($attempts < $maxAttempts) {
                                sleep(pow(2, $attempts)); // Exponential backoff
                                continue;
                            }
                            throw new Exception('Rate limit exceeded after ' . $maxAttempts . ' attempts');
                        }

                        if ($httpCode !== 200) {
                            throw new Exception("HTTP Error: $httpCode - $response");
                        }

                        $data = json_decode($response, true);
                        if (!isset($data['tracks'])) {
                            throw new Exception('Invalid response format from Spotify API');
                        }

                        $chunkTracks = [];
                        foreach ($data['tracks'] as $track) {
                            if ($track === null) continue; // Skip any null tracks

                            $chunkTracks[] = [
                                'id' => $track['id'],
                                'name' => $track['name'],
                                'artist' => $track['artists'][0]['name'],
                                'image' => $track['album']['images'][0]['url'] ?? '',
                                'preview_url' => $track['preview_url'] ?? null,
                                'duration_ms' => $track['duration_ms'] ?? 0,
                                'uri' => $track['uri'] ?? ('spotify:track:' . $track['id']),
                                'album_name' => $track['album']['name'] ?? '',
                                'release_date' => $track['album']['release_date'] ?? '',
                                'popularity' => $track['popularity'] ?? 0,
                                'explicit' => $track['explicit'] ?? false,
                                'artists' => array_map(function($artist) {
                                    return [
                                        'id' => $artist['id'],
                                        'name' => $artist['name']
                                    ];
                                }, $track['artists'])
                            ];
                        }

                        // Guardar en caché
                        $this->cache->set($cacheKey, $chunkTracks, self::CACHE_TTL);
                        $tracks = array_merge($tracks, $chunkTracks);
                        $success = true;

                    } catch (Exception $e) {
                        $attempts++;
                        if ($attempts >= $maxAttempts) {
                            throw new Exception('Failed to fetch track info after ' . $maxAttempts . ' attempts: ' . $e->getMessage());
                        }
                        sleep(pow(2, $attempts)); // Exponential backoff
                    }
                }
            } catch (Exception $e) {
                throw new Exception('Error fetching tracks info: ' . $e->getMessage());
            }
        }

        return $tracks;
    }
    
}


