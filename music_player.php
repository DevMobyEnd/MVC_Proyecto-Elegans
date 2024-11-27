<?php
// echo "Versión de PHP: " . phpversion();
// echo "<br>cURL habilitado: " . (function_exists('curl_version') ? 'Sí' : 'No');
// echo "<br>Información de cURL: ";
// print_r(curl_version());

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

require_once __DIR__ . '/Controller/UsuarioController.php';
require_once __DIR__ . '/Helpers/SpotifyHelper.php';

// Crear una instancia del controlador
$homeController = new UsuarioController();

// Obtener las solicitudes con la información de los usuarios
$solicitudes = $homeController->verSolicitudesConUsuarios();

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
                    <div id="spotifyEmbedContainer" class="flex-grow mx-4"></div>
                </div>
            </div>
            <!-- <div class="flex justify-center space-x-4">
                <button id="prevButton" class="px-4 py-2 bg-gray-700 rounded">Anterior</button>
                <button id="nextButton" class="px-4 py-2 bg-gray-700 rounded">Siguiente</button>
            </div> -->
            <!-- <button id="manualPlayButton">Reproducir</button> -->
        </div>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            let playlist = [];
            let currentIndex = 0;
            let isPlaying = false;

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

                    // Crear el iframe de Spotify
                    const embedContainer = document.getElementById('spotifyEmbedContainer');
                    if (embedContainer) {
                        embedContainer.innerHTML = `
                <iframe
                    src="https://open.spotify.com/embed/track/${song.spotify_track_id}?autoplay=1"
                    width="100%"
                    height="80"
                    frameborder="0"
                    allowtransparency="true"
                    allow="encrypted-media"
                    id="spotifyPlayer"
                ></iframe>
            `;
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
        </script>
</body>

</html>