document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('searchButton').addEventListener('click', async () => {
        const query = document.getElementById('searchSong').value.trim();

        if (query === '') {
            Swal.fire({
                icon: 'warning',
                title: 'Campo vacío',
                text: 'Por favor, ingresa el nombre de una canción.'
            });
            return;
        }

        // Mostrar el loader
        document.getElementById('loaderOverlay').style.display = 'flex';

        try {
            console.log('Buscando canción:', query);
            const response = await fetch(`/test_spotify.php?songName=${encodeURIComponent(query)}`);
            if (!response.ok) {
                throw new Error(`Error de red: ${response.status}`);
            }
            const text = await response.text();
            console.log('Respuesta del servidor:', text);

            let data;
            try {
                data = JSON.parse(text);
            } catch (error) {
                throw new Error('Error al analizar JSON: ' + error.message);
            }

            if (data.error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: `Error del servidor: ${data.error}`
                });
                return;
            }

            displaySearchResults(data);
        } catch (error) {
            console.error('Error al buscar la canción:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Hubo un problema al buscar la canción. Por favor, intenta de nuevo.'
            });
        } finally {
            // Ocultar el loader
            document.getElementById('loaderOverlay').style.display = 'none';
        }
    });

    // Mostrar resultados de búsqueda
    function displaySearchResults(tracks) {
        const searchResults = document.getElementById('searchResults');
        searchResults.innerHTML = '';

        if (!Array.isArray(tracks) || tracks.length === 0) {
            searchResults.innerHTML = '<p>No se encontraron resultados.</p>';
            return;
        }

        tracks.forEach(track => {
            // Utilizar valores por defecto si las propiedades no están disponibles
            const imageUrl = track.imagen_url || 'ruta/por/defecto/a/imagen.png';
            const trackName = track.nombre_cancion || 'Nombre desconocido';
            const trackId = track.spotify_track_id || 'ID desconocido';
            const trackNombreArtista = track.nombre_artista || 'Artista desconocido';

            // Crear y agregar un nuevo elemento para el resultado
            const resultDiv = document.createElement('div');
            resultDiv.classList.add('result-item');
            resultDiv.innerHTML = `
                <div class="d-flex align-items-center mb-3">
                    <img src="${imageUrl}" alt="${trackName}" class="img-thumbnail me-3" style="max-width: 100px;" aria-label="Imagen del álbum">
                    <div>
                        <p><strong>${trackName}</strong> - ${trackNombreArtista}</p>
                        <button class="btn btn-info selectSongButton" data-track-id="${trackId}" data-track-name="${trackName}" data-artist-name="${trackNombreArtista}" data-image-url="${imageUrl}" aria-label="Seleccionar canción">Seleccionar</button>
                    </div>
                </div>
            `;
            searchResults.appendChild(resultDiv);
        });

        // Mostrar el modal
        showModal();
    }

    // Manejar el clic en el botón de selección de canción
    function handleSelectSong(event) {
        const button = event.target;
        const trackId = button.getAttribute('data-track-id');
        const trackName = button.getAttribute('data-track-name');
        const artistName = button.getAttribute('data-artist-name');
        const imageUrl = button.getAttribute('data-image-url');

        // Actualizar el formulario con la información de la canción seleccionada
        document.getElementById('searchSong').value = trackName; // Mostrar el nombre de la canción en el campo de búsqueda

        // Crear y mostrar una vista previa de la canción seleccionada
        const songPreview = document.getElementById('selectedSongPreview');
        songPreview.innerHTML = `
            <div class="d-flex align-items-center mb-3">
                <img src="${imageUrl}" alt="${trackName}" class="img-thumbnail me-3" style="max-width: 100px;">
                <div>
                    <p><strong>${trackName}</strong> - ${artistName}</p>
                </div>
            </div>
        `;

        // Actualizar los campos ocultos en el formulario
        document.getElementById('selectedTrackId').value = trackId;
        document.getElementById('selectedTrackName').value = trackName;
        document.getElementById('selectedArtistName').value = artistName;
        document.getElementById('selectedImageUrl').value = imageUrl;

        // Ocultar el modal después de seleccionar una canción
        hideModal();
    }

    // Añadir evento a los botones de selección
    document.addEventListener('click', function (event) {
        if (event.target.classList.contains('selectSongButton')) {
            handleSelectSong(event);
        }
    });

    // Enviar solicitud de canción
    document.getElementById('songRequestForm').addEventListener('submit', async (event) => {
        event.preventDefault();

        const selectedSong = document.getElementById('selectedTrackName').value;
        const artistName = document.getElementById('selectedArtistName').value;
        const trackId = document.getElementById('selectedTrackId').value;
        const imageUrl = document.getElementById('selectedImageUrl').value;


        if (!selectedSong || !artistName || !trackId) {
            Swal.fire({
                icon: 'warning',
                title: 'Campos incompletos',
                text: 'Por favor, selecciona una canción antes de enviar la solicitud.'
            });
            return;
        }

        // Mostrar el loader
        document.getElementById('loaderOverlay').style.display = 'flex';

        try {
            console.log('Enviando solicitud de canción:', { selectedSong, artistName, trackId });
            const response = await fetch('/Index.php', {
                method: 'POST',
                body: new URLSearchParams({
                    'spotify_track_id': trackId,
                    'nombre_cancion': selectedSong,
                    'nombre_artista': artistName,
                    'imagen_url': imageUrl
                }),
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
            });
            

            // Verifica el estado de la respuesta
            if (!response.ok) {
                throw new Error(`Error de red: ${response.status}`);
            }

            // Lee la respuesta como texto
            const text = await response.text();
            console.log('Respuesta del servidor (texto):', text);

            // Intenta analizar el texto como JSON
            let data;
            try {
                data = JSON.parse(text);
            } catch (error) {
                throw new Error('Error al analizar JSON: ' + error.message);
            }

            // Procesa la respuesta JSON
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Éxito',
                    text: 'Solicitud enviada con éxito.'
                });
                // Restablecer el formulario después de enviar la solicitud
                document.getElementById('songRequestForm').reset();
                document.getElementById('selectedSongPreview').innerHTML = '';
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: `Hubo un problema al enviar la solicitud: ${data.message}`
                });
            }
        } catch (error) {
            console.error('Error al enviar la solicitud:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Hubo un problema al enviar la solicitud. Por favor, intenta de nuevo.'
            });
        } finally {
            // Ocultar el loader
            document.getElementById('loaderOverlay').style.display = 'none';
        }

    });

    // Función para mostrar el modal
    function showModal() {
        const modal = document.getElementById('searchModal');
        modal.style.display = 'block';
        modal.classList.add('show');
        modal.setAttribute('aria-modal', 'true');
        modal.removeAttribute('aria-hidden');
    }

    // Función para ocultar el modal
    function hideModal() {
        const modal = document.getElementById('searchModal');
        modal.style.display = 'none';
        modal.classList.remove('show');
        modal.setAttribute('aria-hidden', 'true');
        modal.removeAttribute('aria-modal');
    }
});
