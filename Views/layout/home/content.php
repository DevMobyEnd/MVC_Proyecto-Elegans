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
                                    <input type="text" class="form-control" id="searchSong" placeholder="Buscar canción...">
                                    <button type="button" class="btn btn-secondary mt-2" id="searchButton">Buscar</button>
                                </div>

                                <!-- Vista previa de la canción seleccionada -->
                                <div id="selectedSongPreview" class="mb-3"></div>

                                <!-- Campos ocultos para la información de la canción -->
                                <input type="hidden" id="selectedTrackId" name="spotify_track_id">
                                <input type="hidden" id="selectedTrackName" name="nombre_cancion">
                                <input type="hidden" id="selectedArtistName" name="nombre_artista">
                                <input type="hidden" id="selectedImageUrl" name="imagen_url">

                                <button type="submit" class="btn btn-primary">Enviar solicitud</button>
                            </form>
                        <?php else: ?>
                            <h4>¡Bienvenido a Elegans!</h4>
                            <p>Somos tu plataforma dedicada a hacer que cada noche sea inolvidable...</p>
                        <?php endif; ?>
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
            <div id="searchModal" class="modal" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Resultados de búsqueda</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div id="searchResults"></div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
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
                <p>8.1 Estos términos y condiciones se rigen por las leyes vigentes en [País o Estado].</p>
                <h6>Contacto</h6>
                <p>9.1 Para cualquier pregunta sobre estos términos y condiciones, por favor contáctanos a Admin@Elegans.com.</p>
            </div>
        </div>
    </div>
</div>