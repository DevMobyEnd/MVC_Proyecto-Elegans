<main class="content px-3 py-2">
    <div class="container-fluid">
        <div class="row">
            <!-- Tarjeta de bienvenida -->
            <div class="col-12 col-md-6 d-flex">
                <div id="card" class="card flex-fill border-0 illustration">
                    <div class="card-body p-0">
                        <div class="row g-0">
                            <div class="col-12 col-md-8">
                                <div class="p-3">
                                    <h4>
                                        <?php if (isset($userData['Apodo'])): ?>
                                            ¡Bienvenido, <strong><?php echo htmlspecialchars($userData['Apodo']); ?></strong>!
                                        <?php else: ?>
                                            Bienvenido a solicitudes bar Elegans!
                                        <?php endif; ?>
                                    </h4>
                                    <p>
                                        <?php echo $userData['isLoggedIn'] ? "¿Listo para hacer tu solicitud de música? ¡Comencemos!" : "Aquí podrás hacer la solicitud de tus canciones favoritas desde la comodidad de tu mesa."; ?>
                                    </p>
                                </div>
                            </div>
                            <div class="col-12 col-md-4 text-center">
                                <img id="imghome" src="/Public/dist/img/customer-support.jpg" class="img-fluid illustration-img" alt="">
                            </div>
                            <div class="col-12">
                                <hr style="background-color: #cee1fe; height: 4px; margin-top: 0;">
                                <p>
                                    <?php echo $userData['isLoggedIn'] ? "¿Qué canción te gustaría escuchar hoy?" : "Recuerda que antes de realizar cualquier solicitud, debes iniciar sesión"; ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Formulario de solicitud o información adicional -->
            <div class="col-12 col-md-6 d-flex">
                <div class="card flex-fill border-0">
                    <div class="card-body">
                        <?php if ($userData['isLoggedIn']): ?>
                            <h4>Hacer una solicitud de canción</h4>
                            <form id="songRequestForm">
                                <!-- Campo de búsqueda -->
                                <div class="mb-3">
                                    <label for="searchSong" class="form-label">Buscar canción</label>
                                    <div class="d-flex gap-2"> <!-- Contenedor flex para input y botón -->
                                        <input type="text" class="form-control" id="searchSong" placeholder="Buscar canción...">
                                        <button type="button" class="btn btn-secondary" id="searchButton">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>

                                <!-- Vista previa de la canción seleccionada -->
                                <div id="selectedSongPreview" class="mb-3"></div>

                                <!-- Campos ocultos para la información de la canción -->
                                <input type="hidden" id="selectedTrackId" name="spotify_track_id">
                                <input type="hidden" id="selectedTrackName" name="nombre_cancion">
                                <input type="hidden" id="selectedArtistName" name="nombre_artista">
                                <input type="hidden" id="selectedImageUrl" name="imagen_url">

                                <!-- Contenedor para los botones del formulario -->
                                <div class="d-flex justify-content-end gap-2 mt-3">
                                    <button type="submit" class="edit-profile-btn2 btn-lg fw-semibold">
                                        <i class="fas fa-paper-plane me-1"></i>
                                        Enviar solicitud
                                    </button>
                                </div>
                            </form>
                        <?php else: ?>
                            <h4>¡Bienvenido a Elegans!</h4>
                            <p>Somos tu plataforma dedicada a hacer que cada noche sea inolvidable...</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Carrusel de generos musicales -->
            <div class="col-12 mt-2">
                <h5 class="text-center mb-4">Explora nuestros géneros musicales</h5> <!-- Título agregado -->
                <div class="card border-0 mb-4">
                    <div class="card-body p-4"> <!-- Aumentado el padding -->
                        <div id="generosMusicalesCarousel" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-inner">
                                <div class="carousel-item active">
                                    <div class="row g-4"> <!-- Aumentado el espaciado entre cards -->
                                        <div class="col-3">
                                            <div class="genre-card text-center p-3 rounded" style="background: linear-gradient(45deg, #2c3e50, #3498db);">
                                                <i class="fas fa-guitar text-white mb-2" style="font-size: 2rem;"></i>
                                                <h6 class="text-white mb-0">Rock</h6>
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="genre-card text-center p-3 rounded" style="background: linear-gradient(45deg, #8e44ad, #9b59b6);">
                                                <i class="fas fa-music text-white mb-2" style="font-size: 2rem;"></i>
                                                <h6 class="text-white mb-0">Pop</h6>
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="genre-card text-center p-3 rounded" style="background: linear-gradient(45deg, #e67e22, #f39c12);">
                                                <i class="fas fa-drum text-white mb-2" style="font-size: 2rem;"></i>
                                                <h6 class="text-white mb-0">Latino</h6>
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="genre-card text-center p-3 rounded" style="background: linear-gradient(45deg, #16a085, #1abc9c);">
                                                <i class="fas fa-compact-disc text-white mb-2" style="font-size: 2rem;"></i>
                                                <h6 class="text-white mb-0">Electrónica</h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="carousel-item">
                                    <div class="row g-4"> <!-- Aumentado el espaciado entre cards -->
                                        <div class="col-3">
                                            <div class="genre-card text-center p-3 rounded" style="background: linear-gradient(45deg, #c0392b, #e74c3c);">
                                                <i class="fas fa-microphone text-white mb-2" style="font-size: 2rem;"></i>
                                                <h6 class="text-white mb-0">Reggae</h6>
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="genre-card text-center p-3 rounded" style="background: linear-gradient(45deg, #27ae60, #2ecc71);">
                                                <i class="fas fa-record-vinyl text-white mb-2" style="font-size: 2rem;"></i>
                                                <h6 class="text-white mb-0">Jazz</h6>
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="genre-card text-center p-3 rounded" style="background: linear-gradient(45deg, #2980b9, #3498db);">
                                                <i class="fas fa-headphones text-white mb-2" style="font-size: 2rem;"></i>
                                                <h6 class="text-white mb-0">Hip Hop</h6>
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="genre-card text-center p-3 rounded" style="background: linear-gradient(45deg, #8e44ad, #9b59b6);">
                                                <i class="fas fa-heart text-white mb-2" style="font-size: 2rem;"></i>
                                                <h6 class="text-white mb-0">Baladas</h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button class="carousel-control-prev" type="button" data-bs-target="#generosMusicalesCarousel" data-bs-slide="prev" style="width: 5%;">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Anterior</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#generosMusicalesCarousel" data-bs-slide="next" style="width: 5%;">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Siguiente</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Loader Overlay -->
            <div id="loaderOverlay" style="display: none;">
                <div class="spinner-border" role="status">
                    <span class="visually-hidden">Buscando...</span>
                </div>
            </div>


            <!-- Modal para resultados de búsqueda -->
            <div class="modal fade" id="searchModal" tabindex="-1" role="dialog" aria-labelledby="searchModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="searchModalLabel">Resultados de búsqueda</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div id="searchResults" class="row g-3"></div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>




        </div>
    </div>
</main>
<!-- Modal de Términos y Condiciones -->
<div class="modal fade" id="terminosCondicionesModal" tabindex="-1" aria-labelledby="terminosCondicionesModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="terminosCondicionesModalLabel">Términos y Condiciones de Uso</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <h6>Introducción</h6>
                <p>1.1 Bienvenido a Elegans. Estos términos y condiciones rigen el uso de nuestra plataforma de solicitudes de música en tiempo real.</p>
                <h6>Uso de la Plataforma</h6>
                <p>2.1 Al acceder y utilizar Elegans, aceptas cumplir con estos términos y cualquier otra política que podamos publicar.</p>
                <p>2.2 Solo puedes utilizar nuestra plataforma de acuerdo con todas las leyes y regulaciones aplicables.</p>
                <h6>Solicitudes de Música</h6>
                <p>3.1 Elegans permite a los usuarios enviar solicitudes de canciones a DJs en bares o discotecas participantes.</p>
                <p>3.2 Nos reservamos el derecho de moderar y filtrar las solicitudes para mantener un ambiente adecuado y seguro.</p>
                <h6>Propiedad Intelectual</h6>
                <p>4.1 Todos los derechos de propiedad intelectual relacionados con Elegans, incluidos derechos de autor y marcas comerciales, pertenecen a nosotros o a nuestros licenciatarios.</p>
                <h6>Privacidad</h6>
                <p>5.1 La privacidad de los usuarios es importante para nosotros. Consulta nuestra Política de Privacidad para obtener información sobre cómo recopilamos, usamos y protegemos tus datos personales.</p>
                <h6>Limitación de Responsabilidad</h6>
                <p>6.1 No somos responsables de ningún daño directo, indirecto, incidental, especial, consecuente o punitivo derivado del uso de Elegans.</p>
                <h6>Modificaciones</h6>
                <p>7.1 Nos reservamos el derecho de modificar estos términos y condiciones en cualquier momento. Las modificaciones entrarán en vigor al ser publicadas en la plataforma.</p>
                <h6>Legislación Aplicable</h6>
                <p>8.1 Estos términos y condiciones se rigen por las leyes vigentes en Colombia.</p>
                <h6>Contacto</h6>
                <p>9.1 Para cualquier pregunta sobre estos términos y condiciones, por favor contáctanos a Admin@Elegans.com.</p>
            </div>
        </div>
    </div>
</div>

<style>
    .genre-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        cursor: pointer;
        height: 120px;
        /* Altura fija para todas las tarjetas */
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }

    .genre-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    }

    .carousel-control-prev,
    .carousel-control-next {
        background: rgba(0, 0, 0, 0.1);
        border-radius: 50%;
        height: 40px;
        width: 40px;
        top: 50%;
        transform: translateY(-50%);
    }

    .carousel-control-prev {
        left: -20px;
    }

    .carousel-control-next {
        right: -20px;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.5rem 1rem;
        transition: all 0.3s ease;
    }

    .btn i {
        font-size: 1rem;
    }

    .btn-secondary {
        min-width: 45px;
    }

    /* Hover effects */
    .btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }
</style>