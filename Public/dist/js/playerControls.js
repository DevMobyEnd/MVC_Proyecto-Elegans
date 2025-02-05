// // Funciones para el control de reproducción
// let currentTrackIndex = 0;

// // Función para obtener el índice de la canción actual en la playlist
// function getCurrentTrackIndex() {
//     return playlist.findIndex(song => 
//         song.spotify_track_id === player._options.currentTrack?.uri?.split(':')[2]
//     );
// }

// // Reproducir canción anterior
// async function playPreviousTrack() {
//     const currentIndex = getCurrentTrackIndex();
//     if (currentIndex > 0) {
//         currentTrackIndex = currentIndex - 1;
//         await playSong(playlist[currentTrackIndex]);
//     }
// }

// // Reproducir siguiente canción
// async function playNextTrack() {
//     const currentIndex = getCurrentTrackIndex();
//     if (currentIndex < playlist.length - 1) {
//         currentTrackIndex = currentIndex + 1;
//         await playSong(playlist[currentTrackIndex]);
//     }
// }

// // Alternar entre reproducir y pausar
// function togglePlayPause() {
//     player.getCurrentState().then(state => {
//         if (state) {
//             if (state.paused) {
//                 player.resume();
//                 updatePlayPauseButton(false);
//             } else {
//                 player.pause();
//                 updatePlayPauseButton(true);
//             }
//         }
//     });
// }

// // Actualizar el botón de play/pause
// function updatePlayPauseButton(isPaused) {
//     const playPauseButton = document.getElementById('playPauseButton');
//     if (playPauseButton) {
//         playPauseButton.innerHTML = isPaused ? 
//             '<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="5 3 19 12 5 21 5 3"></polygon></svg>' :
//             '<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="6" y="4" width="4" height="16"></rect><rect x="14" y="4" width="4" height="16"></rect></svg>';
//     }
// }

// // Configurar event listeners para los controles
// function setupPlayerControls() {
//     // Botones de control
//     document.getElementById('previousButton').addEventListener('click', playPreviousTrack);
//     document.getElementById('nextButton').addEventListener('click', playNextTrack);
//     document.getElementById('playPauseButton').addEventListener('click', togglePlayPause);

//     // Escuchar el evento de cambio de estado del reproductor
//     player.addListener('player_state_changed', state => {
//         if (state) {
//             updatePlayPauseButton(state.paused);
            
//             // Verificar si la canción actual ha terminado
//             if (state.position === 0 && state.duration === 0) {
//                 playNextTrack();
//             }
//         }
//     });
// }

// // Inicializar controles cuando el reproductor esté listo
// player.addListener('ready', () => {
//     setupPlayerControls();
// });