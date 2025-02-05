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
// echo "<pre>";
// echo "Access Token: " . substr($accessToken, 0, 10) . "...\n";
// echo "Refresh Token: " . substr($refreshToken, 0, 10) . "...\n";
// echo "Scopes: " . implode(", ", $scopes) . "\n";
// echo "</pre>";

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

    #playerControls {
        width: 100%;
        padding: 1rem;
    }

    #progress {
        transition: width 0.1s linear;
    }

    #volumeSlider {
        -webkit-appearance: none;
        appearance: none;
        height: 8px;
        border-radius: 4px;
        background: #4B5563;
    }

    #volumeSlider::-webkit-slider-thumb {
        -webkit-appearance: none;
        appearance: none;
        width: 16px;
        height: 16px;
        border-radius: 50%;
        background: #10B981;
        cursor: pointer;
    }

    .player-button {
        background: none;
        border: none;
        cursor: pointer;
        padding: 0.5rem;
    }

    .player-button:hover {
        color: #10B981;
    }
</style>


<body class="bg-gray-900 text-white">
    <!-- <h1>Music Player</h1> -->

    <!-- <?php if (isset($mensajeSpotify)): ?>
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
    <?php endif; ?> -->

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
                    <!-- Info de la canción -->
                    <div class="flex items-center">
                        <img id="currentSongImage" src="/api/placeholder/56/56" alt="" class="w-14 h-14 mr-4 rounded hidden">
                        <div>
                            <h3 id="currentSongTitle" class="font-semibold"></h3>
                            <p id="currentSongArtist" class="text-gray-400"></p>
                        </div>
                    </div>

                    <!-- Controles del reproductor -->
                    <div class="flex-grow mx-4">
                        <div id="playerControls" class="w-full">
                            <!-- Barra de progreso -->
                            <div class="relative h-2 bg-gray-700 rounded-full">
                                <div id="progress" class="absolute h-full bg-green-500 rounded-full" style="width: 0%"></div>
                            </div>

                            <!-- Tiempos -->
                            <div class="flex justify-between mt-1 text-sm">
                                <span id="currentTime">0:00</span>
                                <span id="totalTime">0:00</span>
                            </div>

                            <!-- Controles de reproducción -->
                            <div class="flex items-center justify-center space-x-6 ">
                                <button id="previousButton" class="text-gray-400 hover:text-green-500 focus:outline-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <polygon points="19 20 9 12 19 4 19 20"></polygon>
                                        <line x1="5" y1="19" x2="5" y2="5"></line>
                                    </svg>
                                </button>

                                <button id="playPauseButton" class="text-gray-400 hover:text-green-500 focus:outline-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <polygon points="5 3 19 12 5 21 5 3"></polygon>
                                    </svg>
                                </button>

                                <button id="nextButton" class="text-gray-400 hover:text-green-500 focus:outline-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <polygon points="5 4 15 12 5 20 5 4"></polygon>
                                        <line x1="19" y1="5" x2="19" y2="19"></line>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Control de volumen -->
                    <div class="flex items-center space-x-2 ">
                        <i id="volumeIcon" class="text-gray-400 hover:text-green-500">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-volume-2">
                                <polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"></polygon>
                                <path d="M19.07 4.93a10 10 0 0 1 0 14.14M15.54 8.46a5 5 0 0 1 0 7.07"></path>
                            </svg>
                        </i>
                        <input type="range" id="volumeSlider" min="0" max="100" value="50" class="w-24 h-2 bg-gray-700 rounded-full appearance-none cursor-pointer">
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
        <script src="Public/dist/js/volumeControl.js"></script>
        <script src="Public/dist/js/playerControls.js"></script>
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
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('HTTP status ' + response.status);
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.access_token) {
                            spotifyAccessToken = data.access_token;
                            console.log('Token refreshed successfully');
                        } else {
                            console.error('Failed to refresh token');
                            handleAuthenticationError();
                        }
                    })
                    .catch(error => {
                        console.error('Error refreshing token:', error);
                        handleAuthenticationError();
                    });
            }

            function handleAuthenticationError() {
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
        <script> let player;
            let playlist = <?php echo json_encode($solicitudes); ?>;
            let currentIndex = 0;
            let deviceId;
            let isPlayerReady = false;
            let currentTrackId = null;
            let isPlaying = false;

            // Al cargar la página
            document.addEventListener('DOMContentLoaded', () => {
                initializePlayer(<?php echo json_encode($solicitudes); ?>);
            });

            // Función principal para reproducir una canción
            // Función para reproducir una canción
            async function playSong(song, index) {
                try {
                    // Validar dispositivo listo
                    if (!deviceId) {
                        throw new Error('Dispositivo no listo');
                    }

                    // Actualizar índice actual
                    if (typeof index !== 'undefined') {
                        currentTrackIndex = index;
                    } else {
                        currentTrackIndex = playlist.findIndex(t => t.spotify_track_id === song.spotify_track_id);
                    }

                    // Iniciar reproducción
                    await fetch(`https://api.spotify.com/v1/me/player/play?device_id=${deviceId}`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'Authorization': `Bearer ${spotifyAccessToken}`
                        },
                        body: JSON.stringify({
                            uris: [`spotify:track:${song.spotify_track_id}`]
                        })
                    });

                    // Actualizar UI
                    updateTrackInfo(song);
                    currentTrackId = song.spotify_track_id;
                    isPlaying = true;
                    updatePlayButton();

                } catch (error) {
                    console.error('Error al reproducir:', error);
                    Swal.fire('Error', 'No se pudo reproducir la canción', 'error');
                }
            }

            // Función para actualizar botón de play/pause
            function updatePlayButton() {
                const button = document.getElementById('playPauseButton');
                if (button) {
                    button.innerHTML = isPlaying ?
                        '<i data-feather="pause"></i>' :
                        '<i data-feather="play"></i>';
                    feather.replace();
                }
            }
         
                // Controladores de eventos mejorados
                document.getElementById('playPauseButton').addEventListener('click', async () => {
                    try {
                        if (!deviceId) return;

                        if (isPlaying) {
                            await player.pause();
                        } else {
                            await player.resume();
                        }
                        isPlaying = !isPlaying;
                        updatePlayButton();

                    } catch (error) {
                        console.error('Error al pausar/reanudar:', error);
                    }
                });
               

            if (!window.nextButtonListenerAdded) {
                document.getElementById('nextButton').addEventListener('click', async () => {
                    // Verifica que haya una siguiente canción
                    if (currentTrackIndex < playlist.length - 1) {
                        const nextIndex = currentTrackIndex + 1; // Calcula el índice de la siguiente canción
                        await playSong(playlist[nextIndex], nextIndex); // Reproduce la siguiente canción
                    } else {
                        console.log('No hay más canciones en la lista'); // Mensaje de depuración
                    }
                });
                window.nextButtonListenerAdded = true; //marca como agregado
            }


       
                document.getElementById('previousButton').addEventListener('click', async () => {
                    if (currentTrackIndex > 0) {
                        const prevIndex = currentTrackIndex - 1;
                        await playSong(playlist[prevIndex], prevIndex);
                    }
                });
                



            function playTrack(spotify_track_id, device_id) {
                // Mostrar loader mientras se carga la canción
                document.getElementById('loaderOverlay').style.display = 'flex';

                fetch(`https://api.spotify.com/v1/me/player`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'Authorization': `Bearer ${spotifyAccessToken}`
                        },
                        body: JSON.stringify({
                            device_ids: [device_id],
                            play: false
                        })
                    })
                    .then(() => {
                        // Una vez seleccionado el dispositivo, reproducir la canción
                        return fetch(`https://api.spotify.com/v1/me/player/play`, {
                            method: 'PUT',
                            headers: {
                                'Content-Type': 'application/json',
                                'Authorization': `Bearer ${spotifyAccessToken}`
                            },
                            body: JSON.stringify({
                                uris: [`spotify:track:${spotify_track_id}`]
                            })
                        });
                    })
                    .then(() => {
                        document.getElementById('loaderOverlay').style.display = 'none';
                        updatePlayerControls();
                    })
                    .catch(error => {
                        console.error('Error playing track:', error);
                        document.getElementById('loaderOverlay').style.display = 'none';
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'No se pudo reproducir la canción. Por favor, intente nuevamente.',
                        });
                    });
            }

            // Función para actualizar los controles del reproductor
            function updatePlayerControls() {
                const progressContainer = document.getElementById('playerControls');
                if (!progressContainer) return;

                // Actualizar la UI del reproductor
                progressContainer.innerHTML = `
                    <div class="flex items-center justify-between w-full">
                        <div class="flex-1 mx-4">
                            <div class="relative pt-1">
                                <div class="flex mb-2 items-center justify-between">
                                    <div>
                                        <span id="currentTime" class="text-xs font-semibold inline-block text-white">
                                            0:00
                                        </span>
                                    </div>
                                    <div>
                                        <span id="totalTime" class="text-xs font-semibold inline-block text-white">
                                            0:00
                                        </span>
                                    </div>
                                </div>
                                <div class="flex h-2 mb-4">
                                    <div class="flex-1 bg-gray-700 rounded-full">
                                        <div id="progress" class="h-2 bg-green-500 rounded-full" style="width: 0%"></div>
                                    </div>
                                </div>
                                <div class="flex items-center justify-between">
                                    <input type="range" id="volumeSlider" min="0" max="100" value="50"
                                        class="w-32 h-2 bg-gray-700 rounded-lg appearance-none cursor-pointer">
                                    <div class="flex space-x-4">
                                        <button id="prevButton" class="text-white hover:text-green-500">
                                            <i data-feather="skip-back"></i>
                                        </button>
                                        <button id="playPauseButton" class="text-white hover:text-green-500">
                                            <i data-feather="play"></i>
                                        </button>
                                        <button id="nextButton" class="text-white hover:text-green-500">
                                            <i data-feather="skip-forward"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;

                // Inicializar los íconos de Feather
                feather.replace();

                // Añadir event listeners para los controles
                setupPlayerEventListeners();
            }



            // Función para actualizar el estado del reproductor
            function updatePlayerState() {
                if (!player) return;

                player.getCurrentState().then(state => {
                    if (!state) return;

                    const {
                        position,
                        duration
                    } = state;
                    const progress = (position / duration) * 100;

                    // Actualizar barra de progreso
                    const progressElement = document.getElementById('progress');
                    if (progressElement) {
                        progressElement.style.width = `${progress}%`;
                    }

                    // Actualizar tiempos
                    document.getElementById('currentTime').textContent = formatTime(position);
                    document.getElementById('totalTime').textContent = formatTime(duration);

                    // Actualizar ícono de play/pause
                    const playPauseButton = document.getElementById('playPauseButton');
                    if (playPauseButton) {
                        const icon = state.paused ? 'play' : 'pause';
                        playPauseButton.innerHTML = `<i data-feather="${icon}"></i>`;
                        feather.replace();
                    }
                });
            }

            // Funciones auxiliares para los controles
            function togglePlayPause() {
                player.getCurrentState().then(state => {
                    if (state?.paused) {
                        player.resume();
                    } else {
                        player.pause();
                    }
                });
            }

            function handleVolumeChange(e) {
                const volume = e.target.value / 100;
                player.setVolume(volume);
            }

            function handleProgressBarClick(e) {
                const progressBar = e.currentTarget;
                const clickPosition = e.offsetX / progressBar.offsetWidth;

                player.getCurrentState().then(state => {
                    if (state) {
                        const position = state.duration * clickPosition;
                        player.seek(position);
                    }
                });
            }

            function formatTime(ms) {
                const seconds = Math.floor(ms / 1000);
                const minutes = Math.floor(seconds / 60);
                const remainingSeconds = seconds % 60;
                return `${minutes}:${remainingSeconds.toString().padStart(2, '0')}`;
            }





            // Inicialización del SDK de Spotify
            // Configuración mejorada del SDK
            window.onSpotifyWebPlaybackSDKReady = () => {
                player = new Spotify.Player({
                    name: 'Elegans Player',
                    getOAuthToken: cb => cb(spotifyAccessToken),
                    volume: 0.5
                });

                // Manejo de eventos del reproductor
                player.addListener('ready', ({
                    device_id
                }) => {
                    deviceId = device_id;
                    console.log('Dispositivo listo:', device_id);
                });

                player.addListener('player_state_changed', async state => {
                    if (!state) return;

                    // Actualizar estado de reproducción
                    isPlaying = !state.paused;
                    updatePlayButton();

                    // Manejar fin de canción
                    if (state.position === 0 && !state.paused && currentTrackId !== state.track_window.current_track.id) {
                        // Canción terminada, avanzar a la siguiente
                        if (currentTrackIndex < playlist.length - 1) {
                            currentTrackIndex++;
                            await playSong(playlist[currentTrackIndex], currentTrackIndex);
                        }
                    }

                    // Actualizar progreso
                    updateProgress(state);
                });

                player.connect();
            };

            // Función para actualizar la UI del reproductor
            function updatePlayerUI(state) {
                if (!state) return;

                const {
                    position,
                    duration,
                    paused,
                    track_window: {
                        current_track
                    }
                } = state;

                // Actualizar barra de progreso
                const progressBar = document.getElementById('progress');
                const progressPercentage = (position / duration) * 100;
                if (progressBar) {
                    progressBar.style.width = `${progressPercentage}%`;
                }

                // Actualizar tiempos
                document.getElementById('currentTime').textContent = formatTime(position);
                document.getElementById('totalTime').textContent = formatTime(duration);

            }

            // Event listeners para los controles
            document.getElementById('volumeSlider').addEventListener('input', (e) => {
                const volume = Number(e.target.value) / 100;
                player.setVolume(volume);
            });

            document.addEventListener('click', (e) => {
                if (e.target.closest('#progress')) {
                    const progressBar = e.target.closest('#progress');
                    const rect = progressBar.getBoundingClientRect();
                    const clickPosition = (e.clientX - rect.left) / rect.width;

                    player.getCurrentState().then(state => {
                        if (state) {
                            const seekPosition = state.duration * clickPosition;
                            player.seek(seekPosition);
                        }
                    });
                }
            });


            // Función para formatear el tiempo
            function formatTime(ms) {
                const minutes = Math.floor(ms / 60000);
                const seconds = Math.floor((ms % 60000) / 1000);
                return `${minutes}:${seconds.toString().padStart(2, '0')}`;
            }

            // Función para actualizar la posición de reproducción
            function updatePosition() {
                if (!player) return;

                player.getCurrentState().then(state => {
                    if (state) {
                        updatePlayerUI(state);
                    }
                });
            }

            // Actualizar la posición cada segundo
            setInterval(updatePosition, 1000);

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
            const progressBar = document.getElementById('progressBar');
            const progress = document.getElementById('progress');
            const currentTimeSpan = document.getElementById('currentTime');
            const totalTimeSpan = document.getElementById('totalTime');



            // Función para actualizar progreso
            function updateProgress(state) {
                const progress = (state.position / state.duration) * 100;
                document.getElementById('progress').style.width = `${progress}%`;
                document.getElementById('currentTime').textContent = formatTime(state.position);
                document.getElementById('totalTime').textContent = formatTime(state.duration);
            }

            // Función auxiliar para formatear tiempo
            function formatTime(ms) {
                const minutes = Math.floor(ms / 60000);
                const seconds = ((ms % 60000) / 1000).toFixed(0);
                return `${minutes}:${seconds.toString().padStart(2, '0')}`;
            }

            // Función para actualizar información de la canción
            function updateTrackInfo(song) {
                const imgElement = document.getElementById('currentSongImage');
                imgElement.src = song.imagen_cancion;
                imgElement.classList.remove('hidden'); // Asegurar que la imagen sea visible
                imgElement.style.display = 'block'; // Forzar display block

                document.getElementById('currentSongTitle').textContent = song.nombre_cancion;
                document.getElementById('currentSongArtist').textContent = song.Apodo;

                // Asegurar que el contenedor del reproductor sea visible
                document.getElementById('spotifyPlayerContainer').style.display = 'flex';
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
