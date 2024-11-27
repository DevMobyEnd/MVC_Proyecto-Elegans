<?php
require_once 'Helpers/SpotifyHelper.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['song'])) {
    header('Content-Type: application/json');

    $songName = $_GET['song'] ?? '';
    $artistName = $_GET['artist'] ?? '';

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
            $firstTrack = $tracks[0];
            $playbackScript = '<iframe src="https://open.spotify.com/embed/track/' . $firstTrack['spotify_track_id'] . '" width="300" height="380" frameborder="0" allowtransparency="true" allow="encrypted-media"></iframe>';
            echo json_encode([
                'script' => $playbackScript,
                'trackInfo' => $firstTrack
            ]);
        }
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
    exit;
}

// Si no es una solicitud AJAX, muestra el HTML
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Buscador de Spotify</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <h1>Buscador de Spotify</h1>
    <input type="text" id="songInput" placeholder="Nombre de la canción">
    <input type="text" id="artistInput" placeholder="Nombre del artista (opcional)">
    <button onclick="searchSong()">Buscar</button>
    <div id="results"></div>
    <div id="player"></div>

    <script>
        function searchSong() {
            var song = $('#songInput').val();
            var artist = $('#artistInput').val();
            $.ajax({
                url: '<?php echo $_SERVER['PHP_SELF']; ?>',
                data: {
                    song: song,
                    artist: artist
                },
                success: function(response) {
                    if (response.script) {
                        $('#player').html(response.script);
                        $('#results').html('Canción: ' + response.trackInfo.nombre_cancion + '<br>Artista: ' + response.trackInfo.nombre_artista);
                        // Force iframe reload
                        $('#player iframe').attr('src', $('#player iframe').attr('src'));
                    } else {
                        $('#results').html('Error: ' + response.error);
                        $('#player').html('');
                    }
                }
            });
        }
    </script>
</body>

</html>
<?php
