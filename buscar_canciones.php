<?php
require_once __DIR__ . '/Helpers/SpotifyHelper.php';
$spotifyHelper = new SpotifyHelper();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscar Canciones</title>
    <style>
        .track-container {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <h1>Buscar Canciones</h1>
    
    <form id="searchForm">
        <input type="text" id="songName" name="songName" placeholder="Nombre de la canción" required>
        <input type="text" id="artistName" name="artistName" placeholder="Nombre del artista (opcional)">
        <button type="submit">Buscar</button>
    </form>

    <div id="results"></div>

    <script>
        document.getElementById('searchForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const songName = document.getElementById('songName').value;
            const artistName = document.getElementById('artistName').value;
            
            fetch(`buscar_canciones_ajax.php?songName=${encodeURIComponent(songName)}&artistName=${encodeURIComponent(artistName)}`)
                .then(response => response.json())
                .then(data => {
                    const resultsDiv = document.getElementById('results');
                    resultsDiv.innerHTML = '';
                    
                    if (data.error) {
                        resultsDiv.innerHTML = `<p>Error: ${data.error}</p>`;
                    } else {
                        data.forEach(track => {
                            const trackDiv = document.createElement('div');
                            trackDiv.className = 'track-container';
                            trackDiv.innerHTML = `
                                <h3>${track.nombre_cancion} - ${track.nombre_artista}</h3>
                                <img src="${track.imagen_url}" alt="${track.nombre_cancion}" width="100">
                                <iframe src="https://open.spotify.com/embed/track/${track.spotify_track_id}" 
                                        width="300" 
                                        height="80" 
                                        frameborder="0" 
                                        allowtransparency="true" 
                                        allow="encrypted-media">
                                </iframe>
                            `;
                            resultsDiv.appendChild(trackDiv);
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    document.getElementById('results').innerHTML = '<p>Error al buscar canciones.</p>';
                });
        });
    </script>
</body>
</html>