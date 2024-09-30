<?php
require_once 'Controller/UsuarioController.php';

// Crear una instancia del controlador
$homeController = new UsuarioController();

// Obtener las solicitudes con la información de los usuarios
$solicitudes = $homeController->verSolicitudesConUsuarios();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reproductor de Música</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.28.0/feather.min.js"></script>
    <script src="https://sdk.scdn.co/spotify-player.js"></script>
</head>

<body class="bg-gray-900 text-white">
    <div id="app" class="flex flex-col h-screen">
        <!-- Lista de canciones -->
        <div class="flex-grow overflow-auto p-6">
            <h2 class="text-2xl font-bold mb-4">Lista de reproducción</h2>
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-700">
                        <th class="text-left py-2">Usuario</th>
                        <th class="text-left py-2">Nombre de la Canción</th>
                        <th class="text-left py-2">Spotify ID</th>
                        <th class="text-left py-2">Imagen de la Canción</th>
                        <th class="text-left py-2">Estado</th>
                        <th class="text-left py-2">Fecha de Solicitud</th>
                    </tr>
                </thead>
                <tbody id="songList">
                    <?php if (!empty($solicitudes)): ?>
                        <?php foreach ($solicitudes as $solicitud): ?>
                            <tr class="hover:bg-gray-800 cursor-pointer" onclick="playSong(<?php echo htmlspecialchars(json_encode($solicitud), ENT_QUOTES, 'UTF-8'); ?>)">
                                <td class="py-2"><?php echo $solicitud['Apodo']; ?></td>
                                <td class="py-2"><?php echo $solicitud['nombre_cancion']; ?></td>
                                <td class="py-2"><?php echo $solicitud['spotify_track_id']; ?></td>
                                <td class="py-2">
                                    <img src="<?php echo $solicitud['imagen_cancion']; ?>" alt="Imagen de la canción" class="w-10 h-10 rounded">
                                </td>
                                <td class="py-2">
                                    <div class="dropdown">
                                        <button class="dropdown-toggle bg-blue-500 text-white px-4 py-2 rounded">
                                            <?php echo $solicitud['estado']; ?>
                                        </button>
                                        <div class="dropdown-menu hidden bg-white text-gray-800 rounded shadow-lg">
                                            <a class="block px-4 py-2 hover:bg-gray-200" href="actualizar_estado.php?id=<?php echo $solicitud['id']; ?>&estado=pendiente">Pendiente</a>
                                            <a class="block px-4 py-2 hover:bg-gray-200" href="actualizar_estado.php?id=<?php echo $solicitud['id']; ?>&estado=en_proceso">En Proceso</a>
                                            <a class="block px-4 py-2 hover:bg-gray-200" href="actualizar_estado.php?id=<?php echo $solicitud['id']; ?>&estado=completado">Completado</a>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-2"><?php echo $solicitud['fecha_solicitud']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="py-4 text-center">No hay solicitudes.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Reproductor -->
        <div class="bg-gray-800 p-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <img id="currentSongImage" src="/api/placeholder/56/56" alt="" class="w-14 h-14 mr-4 rounded hidden">
                    <div>
                        <h3 id="currentSongTitle" class="font-semibold"></h3>
                        <p id="currentSongArtist" class="text-gray-400"></p>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <i data-feather="skip-back" class="text-gray-400 hover:text-white cursor-pointer"></i>
                    <i id="playPauseBtn" data-feather="play" class="w-10 h-10 text-white bg-green-500 rounded-full p-2 cursor-pointer"></i>
                    <i data-feather="skip-forward" class="text-gray-400 hover:text-white cursor-pointer"></i>
                </div>
                <div class="flex items-center">
                    <i data-feather="volume-2" class="text-gray-400 mr-2"></i>
                    <div class="w-24 h-1 bg-gray-600 rounded-full">
                        <div class="w-3/4 h-full bg-green-500 rounded-full"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Cargar y renderizar los iconos
        feather.replace();

        // Variables globales
        let currentSong = null;
        let isPlaying = false;
        let player;

        // Función para reproducir una canción
        function playSong(song) {
            currentSong = song;
            document.getElementById('currentSongImage').src = song.imagen_cancion;
            document.getElementById('currentSongImage').classList.remove('hidden');
            document.getElementById('currentSongTitle').textContent = song.nombre_cancion;
            document.getElementById('currentSongArtist').textContent = song.Apodo;

            if (player) {
                player.play(`spotify:track:${song.spotify_track_id}`);
            }

            isPlaying = true;
            updatePlayPauseButton();
        }

        // Función para actualizar el botón de reproducción/pausa
        function updatePlayPauseButton() {
            const playPauseBtn = document.getElementById('playPauseBtn');
            if (isPlaying) {
                playPauseBtn.setAttribute('data-feather', 'pause');
            } else {
                playPauseBtn.setAttribute('data-feather', 'play');
            }
            feather.replace();
        }

        // Event listener para el botón de reproducción/pausa
        document.getElementById('playPauseBtn').addEventListener('click', () => {
            if (currentSong) {
                if (isPlaying) {
                    player.pause();
                } else {
                    player.resume();
                }
                isPlaying = !isPlaying;
                updatePlayPauseButton();
            }
        });

        // Manejar los dropdowns
        document.addEventListener('DOMContentLoaded', function() {
            var dropdowns = document.querySelectorAll('.dropdown');

            dropdowns.forEach(function(dropdown) {
                var button = dropdown.querySelector('.dropdown-toggle');
                var menu = dropdown.querySelector('.dropdown-menu');

                button.addEventListener('click', function(event) {
                    event.stopPropagation();
                    menu.classList.toggle('hidden');
                });

                window.addEventListener('click', function() {
                    menu.classList.add('hidden');
                });
            });
        });

        // Inicializar el reproductor de Spotify
        window.onSpotifyWebPlaybackSDKReady = () => {
            const token = 'TU_TOKEN_DE_ACCESO_DE_SPOTIFY';
            const player = new Spotify.Player({
                name: 'Web Playback SDK Quick Start Player',
                getOAuthToken: cb => {
                    cb(token);
                }
            });

            // Error handling
            player.addListener('initialization_error', ({
                message
            }) => {
                console.error(message);
            });
            player.addListener('authentication_error', ({
                message
            }) => {
                console.error(message);
            });
            player.addListener('account_error', ({
                message
            }) => {
                console.error(message);
            });
            player.addListener('playback_error', ({
                message
            }) => {
                console.error(message);
            });

            // Playback status updates
            player.addListener('player_state_changed', state => {
                console.log(state);
            });

            // Ready
            player.addListener('ready', ({
                device_id
            }) => {
                console.log('Ready with Device ID', device_id);
            });

            // Not Ready
            player.addListener('not_ready', ({
                device_id
            }) => {
                console.log('Device ID has gone offline', device_id);
            });

            // Connect to the player!
            player.connect();
        };
    </script>
</body>

</html>