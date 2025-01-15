<?php
// echo "Versión de PHP: " . phpversion();
// echo "<br>cURL habilitado: " . (function_exists('curl_version') ? 'Sí' : 'No');
// echo "<br>Información de cURL: ";
// print_r(curl_version());

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['spotify_access_token'])) {
    // Redirigir al usuario a la página de autenticación si no está autenticado
    header('Location: spotify_auth.php');
    exit();
}

// Recuperar la información de la sesión
$scopes = $_SESSION['spotify_scopes'] ?? [];
$accessToken = $_SESSION['spotify_access_token'] ?? '';
$refreshToken = $_SESSION['spotify_refresh_token'] ?? '';

// Depuración
echo "<pre>";
echo "Access Token: " . substr($accessToken, 0, 10) . "...\n";
echo "Refresh Token: " . substr($refreshToken, 0, 10) . "...\n";
echo "Scopes: " . implode(", ", $scopes) . "\n";
echo "</pre>";

require_once __DIR__ . '/Controller/UsuarioController.php';
require_once __DIR__ . '/Helpers/SpotifyHelper.php';

// Crear una instancia del controlador
$homeController = new UsuarioController();

// Obtener las solicitudes con la información de los usuarios
$solicitudes = $homeController->verSolicitudesConUsuarios();

// Inicializar el SpotifyHelper con el token de acceso de la sesión

// Obtener el token de Spotify
$spotifyHelper = new SpotifyHelper();
try {
    $spotifyToken = $spotifyHelper->getAccessToken();
    $mensajeSpotify = "Token de Spotify obtenido correctamente.";
    $tipoMensaje = "success";
} catch (Exception $e) {
    $mensajeSpotify = "Error al obtener el token de Spotify: " . $e->getMessage();
    $tipoMensaje = "error";
}



// Inicializar el array de scopes disponibles
$availableScopes = [];

// Verificar qué scopes están disponibles
$requiredScopes = [
    'user-read-private',
    'user-read-email',
    'user-modify-playback-state',
    'user-read-playback-state',
    'user-read-currently-playing',
    'streaming'
];

foreach ($requiredScopes as $scope) {
    $availableScopes[$scope] = in_array($scope, $scopes);
}

if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    header('Content-Type: application/json');
    echo json_encode([
        'access_token' => $accessToken,
        'scopes' => $availableScopes
    ]);
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($tituloPagina) ? $tituloPagina : 'Reproductor de Música - Elegans'; ?></title>
    <link rel="website icon" type="png" href="/Public/dist/img/Logo3.png">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/Public/dist/css/styles.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.28.0/feather.min.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <script src="https://sdk.scdn.co/spotify-player.js"></script>
</head>
<style>
    .mensaje {
        padding: 10px;
        border-radius: 5px;
        margin-bottom: 15px;
    }

    .success {
        background-color: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }

    .error {
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }

    #app {
        padding-bottom: 100px;
        /* Aumenta este valor para dejar más espacio en la parte inferior */
    }

    #spotifyPlayerContainer {
        height: 100px;
        /* Aumenta la altura del contenedor */
        bottom: 10px;
        /* Mueve el reproductor 10px hacia arriba desde la parte inferior */
        display: none;
        /* Ocultar el reproductor por defecto */
    }

    #spotifyEmbedContainer {
        width: 100%;
        max-width: 800px;
        /* Ajusta este valor según tus necesidades */
    }

    #spotifyEmbedContainer iframe {
        border: none;
        /* Elimina el borde del iframe */
        box-shadow: none;
        /* Elimina cualquier sombra */
        outline: none;
        /* Elimina el contorno */
    }


    /* Estilos para el dropdown */
    .dropdown {
        position: relative;
        display: inline-block;
    }

    .dropdown-menu {
        position: absolute;
        right: 0;
        min-width: 160px;
        z-index: 1000;
        margin-top: 0.5rem;
        border-radius: 0.375rem;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        transition: opacity 0.2s ease, transform 0.2s ease;
        opacity: 0;
        transform: translateY(-10px);
        pointer-events: none;
    }

    .dropdown-menu.show {
        opacity: 1;
        transform: translateY(0);
        pointer-events: auto;
    }

    .dropdown-toggle {
        white-space: nowrap;
        min-width: 120px;
        text-align: center;
        position: relative;
        transition: all 0.2s ease;
    }

    .dropdown-toggle:after {
        content: '';
        display: inline-block;
        width: 0.4em;
        height: 0.4em;
        margin-left: 0.5em;
        vertical-align: middle;
        border-right: 2px solid currentColor;
        border-bottom: 2px solid currentColor;
        transform: rotate(45deg);
        transition: transform 0.2s ease;
    }

    .dropdown-toggle.active:after {
        transform: rotate(-135deg);
    }

    .dropdown-menu a {
        transition: all 0.2s ease;
        position: relative;
    }

    .dropdown-menu a:hover {
        background-color: #f3f4f6;
        padding-left: 1.75rem;
    }

    .dropdown-menu a:before {
        content: '';
        position: absolute;
        left: 0.75rem;
        top: 50%;
        width: 0;
        height: 2px;
        background-color: #3b82f6;
        transition: width 0.2s ease;
        transform: translateY(-50%);
    }

    .dropdown-menu a:hover:before {
        width: 0.5rem;
    }

    /* Loader Overlay (Fondo oscuro que cubre toda la pantalla) */
    #loaderOverlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        /* Fondo semi-transparente */
        z-index: 1050;
        /* Asegúrate de que esté encima de otros elementos */
        display: none;
        /* Oculto por defecto */
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* Tamaño del Spinner */
    .spinner-border {
        width: 3rem;
        height: 3rem;
        border-width: 0.3em;
    }
</style>


<body class="bg-gray-900 text-white">
    <h1>Music Player</h1>

    <?php if (isset($mensajeSpotify)): ?>
        <div class="mensaje <?php echo $tipoMensaje; ?>">
            <?php echo $mensajeSpotify; ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($availableScopes)): ?>
        <div class="bg-gray-800 p-4 rounded-lg mb-4">
            <h2 class="text-xl font-bold mb-2">Permisos de Spotify disponibles:</h2>
            <ul>
                <?php foreach ($availableScopes as $scope => $available): ?>
                    <li class="<?php echo $available ? 'text-green-500' : 'text-red-500'; ?>">
                        <?php echo $scope; ?>: <?php echo $available ? 'Disponible' : 'No disponible'; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <div id="app" class="flex flex-col h-screen">
        <div class="flex-grow overflow-auto p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-2xl font-bold">Lista de reproducción</h2>
                <button onclick="window.location.href='../Helpers/logout.php'" class="btn btn-danger">
                    <ion-icon name="log-out-outline" class="me-2"></ion-icon> Cerrar Sesión
                </button>
            </div>

            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-700">
                        <th class="text-left py-2">Usuario</th>
                        <th class="text-left py-2">Nombre de la Canción</th>
                        <th class="text-left py-2">Imagen de la Canción</th>
                        <th class="text-left py-2">Estado</th>
                        <th class="text-left py-2">Fecha de Solicitud</th>
                    </tr>
                </thead>
                <tbody id="songList">
                    <?php if (!empty($solicitudes)): ?>
                        <?php foreach ($solicitudes as $solicitud): ?>
                            <tr class="hover:bg-gray-800">
                                <!-- Nota: Removido el onclick del tr -->
                                <td class="py-2 cursor-pointer" onclick="playSong(<?php echo htmlspecialchars(json_encode($solicitud), ENT_QUOTES, 'UTF-8'); ?>)"><?php echo $solicitud['Apodo']; ?></td>
                                <td class="py-2 cursor-pointer" onclick="playSong(<?php echo htmlspecialchars(json_encode($solicitud), ENT_QUOTES, 'UTF-8'); ?>)"><?php echo $solicitud['nombre_cancion']; ?></td>
                                <!-- <td class="py-2 cursor-pointer" onclick="playSong(<?php echo htmlspecialchars(json_encode($solicitud), ENT_QUOTES, 'UTF-8'); ?>)"><?php echo $solicitud['spotify_track_id']; ?></td> -->
                                <td class="py-2 cursor-pointer flex justify-center items-center" onclick="playSong(<?php echo htmlspecialchars(json_encode($solicitud), ENT_QUOTES, 'UTF-8'); ?>)">
                                    <img src="<?php echo $solicitud['imagen_cancion']; ?>" alt="Imagen de la canción" class="w-10 h-10 rounded">
                                </td>
                                <td class="py-2">
                                    <div class="dropdown" onclick="event.stopPropagation()">
                                        <button class="dropdown-toggle bg-blue-500 text-white px-4 py-2 rounded" data-solicitud-id="<?php echo $solicitud['id']; ?>" onclick="toggleDropdown(this)">
                                            <?php echo $solicitud['estado']; ?>
                                        </button>
                                        <div class="dropdown-menu hidden bg-white text-gray-800 rounded shadow-lg">
                                            <a class="block px-4 py-2 hover:bg-gray-200" href="#" onclick="actualizarEstado(<?php echo $solicitud['id']; ?>, 'pendiente', event)">Pendiente</a>
                                            <a class="block px-4 py-2 hover:bg-gray-200" href="#" onclick="actualizarEstado(<?php echo $solicitud['id']; ?>, 'aceptada', event)">Aceptada</a>
                                            <a class="block px-4 py-2 hover:bg-gray-200" href="#" onclick="actualizarEstado(<?php echo $solicitud['id']; ?>, 'rechazada', event)">Rechazada</a>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-2 cursor-pointer" onclick="playSong(<?php echo htmlspecialchars(json_encode($solicitud), ENT_QUOTES, 'UTF-8'); ?>)"><?php echo $solicitud['fecha_solicitud']; ?></td>
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

        <!-- Loader Overlay -->
        <div id="loaderOverlay" style="display: none;">
            <div class="spinner-border" role="status">
                <span class="visually-hidden">Buscando...</span>
            </div>
        </div>


        <!-- Reproductor -->
        <div id="spotifyPlayerContainer" class="fixed bottom-0 left-0 right-0 bg-gray-800">
            <div class="container mx-auto px-4 py-2">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <img id="currentSongImage" src="/api/placeholder/56/56" alt="" class="w-14 h-14 mr-4 rounded hidden">
                        <div>
                            <h3 id="currentSongTitle" class="font-semibold"></h3>
                            <p id="currentSongArtist" class="text-gray-400"></p>
                        </div>
                    </div>
                    <div id="spotifyEmbedContainer" class="flex-grow mx-4">
                        <div id="playerControls">
                            <input type="range" id="volumeSlider" min="0" max="100" value="50">
                            <div id="progressBar">
                                <div id="progress"></div>
                            </div>
                            <span id="currentTime">0:00</span> / <span id="totalTime">0:00</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- <div class="flex justify-center space-x-4">
            <button id="prevButton" class="px-4 py-2 bg-gray-700 rounded">Anterior</button>
            <button id="nextButton" class="px-4 py-2 bg-gray-700 rounded">Siguiente</button>
        </div> -->
        <!-- <button id="manualPlayButton">Reproducir</button> -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            //depuracion para ver si el sdk esta listo para usarse
            // player.addListener('ready', ({
            //     device_id
            // }) => {
            //     console.log('Ready with Device ID', device_id);
            //     deviceId = device_id;
            // });

            const spotifyAccessToken = <?php echo json_encode($accessToken); ?>;
            const spotifyScopes = <?php echo json_encode($availableScopes); ?>;

            // Función para mostrar la información de depuración
            function debugSpotifyInfo() {
                console.group('Información de Spotify');
                console.log('%cAccess Token:', 'color: #3498db; font-weight: bold;');
                console.log(spotifyAccessToken ? `${spotifyAccessToken.substr(0, 10)}...${spotifyAccessToken.substr(-10)}` : 'No disponible');

                console.log('%cScopes:', 'color: #2ecc71; font-weight: bold;');
                console.table(spotifyScopes);
                console.groupEnd();
            }

            // Ejecutar la función de depuración
            debugSpotifyInfo();
            //Manejo del token de acceso
            let tokenRefreshInterval;

            function refreshToken() {
                fetch('/refresh_token.php')
                    .then(response => response.json())
                    .then(data => {
                        if (data.access_token) {
                            spotifyAccessToken = data.access_token;
                            console.log('Token refreshed successfully');
                        } else {
                            console.error('Failed to refresh token');
                        }
                    })
                    .catch(error => console.error('Error refreshing token:', error));
            }

            // Refrescar el token cada 50 minutos (3000000 ms)
            tokenRefreshInterval = setInterval(refreshToken, 3000000);

            // Limpiar el intervalo cuando la página se cierre
            window.addEventListener('beforeunload', () => {
                clearInterval(tokenRefreshInterval);
            });

            fetch('https://api.spotify.com/v1/me', {
                headers: {
                    'Authorization': 'Bearer ' + spotifyAccessToken
                }
            }).then(response => {
                if (!response.ok) {
                    throw new Error('HTTP status ' + response.status);
                }
                return response.json();
            }).then(data => {
                console.log('Token is valid', data);
            }).catch(error => {
                console.error('Error verifying token', error);
            });
        </script>
        <script>
            let playlist = [];
            let currentIndex = 0;
            let isPlaying = false;
            let player;

            // Al cargar la página
            document.addEventListener('DOMContentLoaded', () => {
                initializePlayer(<?php echo json_encode($solicitudes); ?>);
            });

            // Función principal para reproducir una canción
            function playSong(song) {
                try {
                    if (!song || !song.spotify_track_id) {
                        console.error("Datos de canción inválidos:", song);
                        return;
                    }

                    // Actualizar la interfaz
                    const imgElement = document.getElementById('currentSongImage');
                    const titleElement = document.getElementById('currentSongTitle');
                    const artistElement = document.getElementById('currentSongArtist');
                    const playerContainer = document.getElementById('spotifyPlayerContainer');

                    if (imgElement && titleElement && artistElement && playerContainer) {
                        imgElement.src = song.imagen_cancion || '/api/placeholder/56/56';
                        imgElement.classList.remove('hidden');
                        titleElement.textContent = song.nombre_cancion || 'Sin título';
                        artistElement.textContent = song.Apodo || 'Artista desconocido';
                        playerContainer.style.display = 'block';
                    }

                    // Inicializar el reproductor de Spotify si aún no existe
                    if (!player) {
                        player = new Spotify.Player({
                            name: 'Web Playback SDK Quick Start Player',
                            getOAuthToken: cb => {
                                cb(spotifyAccessToken);
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
                            if (state.track_window.previous_tracks.find(x => x.id === state.track_window.current_track.id)) {
                                console.log('La canción ha terminado, reproduciendo la siguiente');
                                playNextSong();
                            }
                        });

                        // Ready
                        player.addListener('ready', ({
                            device_id
                        }) => {
                            console.log('Ready with Device ID', device_id);
                            playTrack(song.spotify_track_id, device_id);
                        });

                        // Connect to the player!
                        player.connect();
                    } else {
                        // Si el reproductor ya existe, solo reproducimos la nueva canción
                        player.getCurrentState().then(state => {
                            if (!state) {
                                console.error('User is not playing music through the Web Playback SDK');
                                return;
                            }
                            playTrack(song.spotify_track_id, state.device_id);
                        });
                    }

                    // Actualizar el índice actual
                    currentIndex = playlist.findIndex(s => s.id === song.id);
                    isPlaying = true;
                } catch (error) {
                    console.error('Error al reproducir la canción:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'No se pudo reproducir la canción. Por favor, intente nuevamente.',
                        confirmButtonColor: '#3b82f6'
                    });
                }
            }

            function playTrack(spotify_track_id, device_id) {
                fetch(`https://api.spotify.com/v1/me/player/play?device_id=${device_id}`, {
                    method: 'PUT',
                    body: JSON.stringify({
                        uris: [`spotify:track:${spotify_track_id}`]
                    }),
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': `Bearer ${spotifyAccessToken}`
                    },
                });
            }

            function playNextSong() {
                if (currentIndex < playlist.length - 1) {
                    playSong(playlist[currentIndex + 1]);
                } else {
                    console.log('Fin de la lista de reproducción');
                    // Aquí puedes decidir qué hacer cuando se acaba la lista
                    // Por ejemplo, volver al principio o detener la reproducción
                }
            }

            window.onSpotifyWebPlaybackSDKReady = () => {
                // El SDK está listo para ser usado
                console.log('Spotify Web Playback SDK is ready');
            };

            // Función para manejar los mensajes del reproductor de Spotify
            function handleSpotifyMessage(event) {
                if (event.origin !== 'https://open.spotify.com') return;

                try {
                    const data = typeof event.data === 'string' ? JSON.parse(event.data) : event.data;

                    // Detectar cuando una canción termina
                    if (data.type === 'playback_update') {
                        const {
                            isPlaying,
                            isPaused,
                            position
                        } = data.payload;

                        // Si la canción está en pausa, no hacemos nada
                        if (isPaused) {
                            console.log('La canción está en pausa.');
                            return;
                        }

                        // Obtener la duración de la canción actual
                        const currentTrack = playlist[currentIndex];
                        const duration = currentTrack ? currentTrack.duration_ms : 0;

                        // Si la canción está en reproducción y la posición es igual o mayor a la duración, la canción ha terminado
                        if (isPlaying && position >= duration) {
                            console.log('La canción ha terminado. Cambiando a la siguiente canción.');
                            handleSongEnd();
                        }
                    }
                } catch (error) {
                    console.error('Error parsing Spotify message:', error);
                }
            }

            function handleSongEnd() {
                if (currentIndex < playlist.length - 1) {
                    currentIndex++;
                    playSong(playlist[currentIndex]);
                } else {
                    // Si es la última canción, puedes reiniciar o detener el reproductor
                    resetPlayer(); // O simplemente no hacer nada
                }
            }

            // Función para resetear el reproductor
            function resetPlayer() {
                document.getElementById('currentSongImage').classList.add('hidden');
                document.getElementById('currentSongTitle').textContent = '';
                document.getElementById('currentSongArtist').textContent = '';
                document.getElementById('spotifyEmbedContainer').innerHTML = '';
                document.getElementById('spotifyPlayerContainer').style.display = 'none';
                isPlaying = false;
            }

            // Inicializar los event listeners
            window.addEventListener('message', handleSpotifyMessage);

            // Función para inicializar el reproductor
            function initializePlayer(initialPlaylist) {
                playlist = initialPlaylist;
                addSongClickListeners();

                // Agregar listeners para los botones de control si existen
                const prevButton = document.getElementById('prevButton');
                const nextButton = document.getElementById('nextButton');

                if (prevButton) {
                    prevButton.addEventListener('click', () => {
                        if (currentIndex > 0) {
                            playSong(playlist[currentIndex - 1]);
                        }
                    });
                }

                if (nextButton) {
                    nextButton.addEventListener('click', () => {
                        if (currentIndex < playlist.length - 1) {
                            playSong(playlist[currentIndex + 1]);
                        }
                    });
                }
            }

            //Funciones para el atualizado del estado de las canciones 
            function actualizarEstado(solicitudId, nuevoEstado, event) {
                event.preventDefault();
                event.stopPropagation();

                // Mostrar loading
                Swal.fire({
                    title: 'Actualizando...',
                    didOpen: () => {
                        Swal.showLoading();
                    },
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    allowEnterKey: false
                });

                fetch('actualizar_estado.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: `id=${solicitudId}&estado=${nuevoEstado}`
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            const boton = document.querySelector(`button[data-solicitud-id="${solicitudId}"]`);
                            if (boton) {
                                boton.textContent = nuevoEstado;
                                // Agregar clase de color según el estado
                                boton.className = 'dropdown-toggle text-white px-4 py-2 rounded ' + getEstadoClass(nuevoEstado);
                            }
                            // Cerrar el dropdown
                            const dropdownMenu = boton.nextElementSibling;
                            dropdownMenu.classList.remove('show');
                            dropdownMenu.classList.add('hidden');
                            boton.classList.remove('active');

                            // Mostrar mensaje de éxito
                            Swal.fire({
                                icon: 'success',
                                title: '¡Éxito!',
                                text: 'Estado actualizado correctamente',
                                timer: 1500,
                                showConfirmButton: false
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'No se pudo actualizar el estado',
                                confirmButtonText: 'Aceptar',
                                confirmButtonColor: '#3b82f6'
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Ocurrió un error al actualizar el estado',
                            confirmButtonText: 'Aceptar',
                            confirmButtonColor: '#3b82f6'
                        });
                    });
            }

            // Función para obtener la clase de color según el estado
            function getEstadoClass(estado) {
                switch (estado.toLowerCase()) {
                    case 'pendiente':
                        return 'bg-yellow-500';
                    case 'aceptada':
                        return 'bg-green-500';
                    case 'rechazada':
                        return 'bg-red-500';
                    default:
                        return 'bg-blue-500';
                }
            }

            // Cerrar dropdowns cuando se hace clic fuera
            document.addEventListener('click', function(event) {
                if (!event.target.closest('.dropdown')) {
                    document.querySelectorAll('.dropdown-menu').forEach(menu => {
                        menu.classList.remove('show');
                        menu.classList.add('hidden');
                        menu.previousElementSibling.classList.remove('active');
                    });
                }
            });

            // Aplicar colores iniciales a los botones según su estado
            document.addEventListener('DOMContentLoaded', function() {
                document.querySelectorAll('.dropdown-toggle').forEach(button => {
                    const estado = button.textContent.trim();
                    button.className = 'dropdown-toggle text-white px-4 py-2 rounded ' + getEstadoClass(estado);
                });
            });
            //funciones para el manejo del dropdown
            function toggleDropdown(button) {
                event.stopPropagation();
                const dropdownMenu = button.nextElementSibling;

                // Cerrar todos los otros dropdowns
                document.querySelectorAll('.dropdown-menu').forEach(menu => {
                    if (menu !== dropdownMenu) {
                        menu.classList.remove('show');
                        menu.classList.add('hidden');
                        menu.previousElementSibling.classList.remove('active');
                    }
                });

                // Toggle el dropdown actual
                dropdownMenu.classList.toggle('hidden');
                button.classList.toggle('active');

                // Usar un setTimeout para asegurar que la transición se active
                setTimeout(() => {
                    dropdownMenu.classList.toggle('show');
                }, 0);
            }



            function actualizarListaSolicitudes() {
                fetch('actualizacion_solicitudes.php', {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.json())
                    .then(solicitudes => {
                        const tbody = document.getElementById('songList');
                        let newHtml = '';

                        if (solicitudes.length > 0) {
                            solicitudes.forEach((solicitud, index) => {
                                // Almacena la solicitud en un data attribute en lugar de en el onclick
                                newHtml += `
                    <tr class="hover:bg-gray-800" data-song-index="${index}">
                        <td class="py-2 cursor-pointer">${solicitud.Apodo}</td>
                        <td class="py-2 cursor-pointer">${solicitud.nombre_cancion}</td>
                        <td class="py-2 cursor-pointer flex justify-center items-center">
                            <img src="${solicitud.imagen_cancion}" alt="Imagen de la canción" class="w-10 h-10 rounded">
                         </td>
                        <td class="py-2">
                            <div class="dropdown" onclick="event.stopPropagation()">
                                <button class="dropdown-toggle text-white px-4 py-2 rounded ${getEstadoClass(solicitud.estado)}" 
                                        data-solicitud-id="${solicitud.id}" 
                                        onclick="toggleDropdown(this)">
                                    ${solicitud.estado}
                                </button>
                                <div class="dropdown-menu hidden bg-white text-gray-800 rounded shadow-lg">
                                    <a class="block px-4 py-2 hover:bg-gray-200" href="#" 
                                       onclick="actualizarEstado(${solicitud.id}, 'pendiente', event)">Pendiente</a>
                                    <a class="block px-4 py-2 hover:bg-gray-200" href="#" 
                                       onclick="actualizarEstado(${solicitud.id}, 'aceptada', event)">Aceptada</a>
                                    <a class="block px-4 py-2 hover:bg-gray-200" href="#" 
                                       onclick="actualizarEstado(${solicitud.id}, 'rechazada', event)">Rechazada</a>
                                </div>
                            </div>
                        </td>
                        <td class="py-2 cursor-pointer">${solicitud.fecha_solicitud}</td>
                    </tr>
                `;
                            });
                        } else {
                            newHtml = `
                <tr>
                    <td colspan="6" class="py-4 text-center">No hay solicitudes.</td>
                </tr>
            `;
                        }

                        tbody.innerHTML = newHtml;

                        // Actualizar la playlist global
                        playlist = solicitudes;

                        // Agregar los event listeners después de actualizar el contenido
                        addSongClickListeners();
                    })
                    .catch(error => console.error('Error al actualizar las solicitudes:', error));
            }

            // Ejecutar la actualización cada 5 segundos
            setInterval(actualizarListaSolicitudes, 5000);

            // También actualizar cuando se cambia el estado de una solicitud
            const originalActualizarEstado = actualizarEstado;
            actualizarEstado = function(solicitudId, nuevoEstado, event) {
                originalActualizarEstado(solicitudId, nuevoEstado, event)
                    .then(() => actualizarListaSolicitudes());
            };

            //función para manejar los event listeners de las canciones
            function addSongClickListeners() {
                const rows = document.querySelectorAll('#songList tr[data-song-index]');
                rows.forEach(row => {
                    const cells = row.querySelectorAll('td:not(:nth-child(5))'); // Excluir la celda del dropdown
                    cells.forEach(cell => {
                        cell.addEventListener('click', function() {
                            const index = parseInt(row.getAttribute('data-song-index'));
                            if (!isNaN(index) && playlist[index]) {
                                playSong(playlist[index]);
                            }
                        });
                    });
                });
            }
            // // cambiar entre canciones
            // const prevButton = document.getElementById('prevButton');
            // const nextButton = document.getElementById('nextButton');

            // prevButton.addEventListener('click', () => {
            //     if (currentIndex > 0) {
            //         playSong(playlist[currentIndex - 1]);
            //     }
            // });

            // nextButton.addEventListener('click', () => {
            //     if (currentIndex < playlist.length - 1) {
            //         playSong(playlist[currentIndex + 1]);
            //     }
            // });

            //Controles de volumen y progreso
            const volumeSlider = document.getElementById('volumeSlider');
            const progressBar = document.getElementById('progressBar');
            const progress = document.getElementById('progress');
            const currentTimeSpan = document.getElementById('currentTime');
            const totalTimeSpan = document.getElementById('totalTime');

            volumeSlider.addEventListener('input', () => {
                player.setVolume(volumeSlider.value / 100);
            });

            function updateProgress(state) {
                if (state) {
                    const {
                        position,
                        duration
                    } = state;
                    const progressPercent = (position / duration) * 100;
                    progress.style.width = `${progressPercent}%`;
                    currentTimeSpan.textContent = formatTime(position);
                    totalTimeSpan.textContent = formatTime(duration);
                }
            }

            function formatTime(ms) {
                const seconds = Math.floor(ms / 1000);
                const minutes = Math.floor(seconds / 60);
                const remainingSeconds = seconds % 60;
                return `${minutes}:${remainingSeconds.toString().padStart(2, '0')}`;
            }

            player.addListener('player_state_changed', state => {
                updateProgress(state);
            });

            //Manejo de pérdida de conexión:
            let reconnectAttempts = 0;
            const maxReconnectAttempts = 5;

            function handleDisconnection() {
                console.log('Disconnected from Spotify. Attempting to reconnect...');
                reconnectAttempts++;

                if (reconnectAttempts <= maxReconnectAttempts) {
                    setTimeout(() => {
                        player.connect().then(success => {
                            if (success) {
                                console.log('Reconnected successfully');
                                reconnectAttempts = 0;
                            } else {
                                console.log('Reconnection attempt failed');
                                handleDisconnection();
                            }
                        });
                    }, 5000 * reconnectAttempts); // Incrementar el tiempo de espera en cada intento
                } else {
                    console.error('Max reconnection attempts reached');
                    Swal.fire({
                        icon: 'error',
                        title: 'Error de conexión',
                        text: 'No se pudo reconectar con Spotify. Por favor, recarga la página.',
                        confirmButtonText: 'Recargar',
                        allowOutsideClick: false
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.reload();
                        }
                    });
                }
            }

            player.addListener('initialization_error', ({
                message
            }) => {
                console.error(message);
                handleDisconnection();
            });

            player.addListener('authentication_error', ({
                message
            }) => {
                console.error('Authentication error:', message);
                Swal.fire({
                    icon: 'error',
                    title: 'Error de autenticación',
                    text: 'Por favor, vuelva a iniciar sesión en Spotify.',
                    confirmButtonText: 'Reiniciar sesión',
                    allowOutsideClick: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = 'spotify_auth.php';
                    }
                });
            });

            player.addListener('account_error', ({
                message
            }) => {
                console.error(message);
                handleDisconnection();
            });
        </script>
</body>

</html>