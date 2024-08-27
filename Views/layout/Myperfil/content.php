<main id="profileContent" class="content px-3 py-2">
    <div class="container-fluid">
        <div class="mb-3">
            <h4>Un panel de control de Usuario</h4>
        </div>
        <div class="row">
            <div class="col-12 col-md-6 d-flex">
                <div class="card flex-fill border-0 illustration">
                    <div class="card-body p-0 d-flex flex-fill">
                        <div class="row g-0 w-100">
                            <div class="col-6">

                                <div class="p-3 m-1">
                                    <?php if (isset($_SESSION['apodo']) && !empty($_SESSION['apodo'])): ?>
                                        <h4>Bienvenido, <?php echo htmlspecialchars($_SESSION['apodo']); ?>!</h4>
                                    <?php else: ?>
                                        <h4>Bienvenido, Usuario!</h4>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="col-6 d-flex justify-content-center align-items-center" style="height: 100%; position: relative;">
                                <?php if (isset($_SESSION['foto_perfil'])): ?>
                                    <img id="usuario" src="../uploads/<?php echo htmlspecialchars($_SESSION['foto_perfil']); ?>"
                                        alt="Profile"
                                        onerror="this.onerror=null; this.src='../Public/dist/img/profile.jpg';">
                                <?php else: ?>
                                    <i class="fas fa-user-circle" style="font-size: 200px;"></i>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 d-flex">
                <div class="card flex-fill border-0">
                    <div class="card-body py-4">
                        <div class="d-flex align-items-start">
                            <div class="flex-grow-1">
                                <h4 class="mb-2">
                                    <?php echo htmlspecialchars($_SESSION['nombre_completo']); ?>
                                </h4>
                                <p class="mb-2">
                                    <?php echo isset($_SESSION['apodo']) ? htmlspecialchars($_SESSION['apodo']) : ''; ?>
                                </p>
                                <div class="user-stats-container">
                                    <div class="user-stats mb-0">
                                        <span class="user-stats__item  me-2">
                                            <span class="user-stats__count">0</span><br>
                                            <span class="user-stats__label">Solicitudes</span>
                                        </span>
                                    </div>
                                    <div class="user-stats mb-0">
                                        <span class="user-stats__item  me-2">
                                            <span class="user-stats__count">0</span><br>
                                            <span class="user-stats__label">Seguidores </span>
                                        </span>
                                    </div>
                                    <div class="user-stats mb-0">
                                        <span class="user-stats__item  me-2">
                                            <span class="user-stats__count">0</span><br>
                                            <span class="user-stats__label">Comentarios</span>
                                        </span>
                                    </div>
                                </div>
                                <div class="edit-profile-btn-wrapper">
                                    <button id="editProfileBtn" class="edit-profile-btn">Editar Perfil</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        Tabla de solicitudes
        <div class="card border-0">
            <img id="modeImage" src="../Public/dist/img/light.png" alt="" class="img-fluid img-small" style="width: 250px; height: auto;">
            <hr style="background-color: #cee1fe; height: 4px; margin-top: 0;">
            <div class="text-center">
                <h4><?php echo htmlspecialchars($_SESSION['apodo']); ?> aún no ha hecho solicitudes de música</h4>
                <p class="text-gray text-medium">Solicitudes de musica @Elsarco Tus solicitudes de musica aparecerán aquí acomodadas aqui en una tabla de no ser hasi.</p>
            </div>
            <div class="d-flex justify-content-center edit-profile-btn-wrapper btn-wrapper-adjusted">
                <button class="edit-profile-btn2">Realiza una solicitu de musica ¡Animate!</button>
            </div>
        </div>
    </div>
</main>
<!-- <button id="testButton">Test Button</button> -->
<!-- Modal de Términos y Condiciones -->
<div class="modal fade" id="terminosCondicionesModal" tabindex="-1" aria-labelledby="terminosCondicionesModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="terminosCondicionesModalLabel">Términos y
                    Condiciones de Uso</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <h6>Introducción</h6>
                <p>1.1 Bienvenido a Elegans. Estos términos y condiciones
                    rigen el uso de nuestra plataforma de solicitudes de música en tiempo
                    real.</p>
                <h6>Uso de la Plataforma</h6>
                <p>2.1 Al acceder y utilizar Elegans, aceptas cumplir con
                    estos términos y cualquier otra política que podamos publicar.</p>
                <p>2.2 Solo puedes utilizar nuestra plataforma de acuerdo con todas las
                    leyes y regulaciones aplicables.</p>
                <h6>Solicitudes de Música</h6>
                <p>3.1 Elegans permite a los usuarios enviar solicitudes de
                    canciones a DJs en bares o discotecas participantes.</p>
                <p>3.2 Nos reservamos el derecho de moderar y filtrar las solicitudes para
                    mantener un ambiente adecuado y seguro.</p>
                <h6>Propiedad Intelectual</h6>
                <p>4.1 Todos los derechos de propiedad intelectual relacionados con Elegans,
                    incluidos derechos de autor y marcas comerciales,
                    pertenecen a nosotros o a nuestros licenciatarios.</p>
                <h6>Privacidad</h6>
                <p>5.1 La privacidad de los usuarios es importante para nosotros. Consulta
                    nuestra Política de Privacidad para obtener información sobre cómo
                    recopilamos, usamos y protegemos tus datos personales.</p>
                <h6>Limitación de Responsabilidad</h6>
                <p>6.1 No somos responsables de ningún daño directo, indirecto, incidental,
                    especial, consecuente o punitivo derivado del uso de Elegans.</p>
                <h6>Modificaciones</h6>
                <p>7.1 Nos reservamos el derecho de modificar estos términos y condiciones
                    en cualquier momento. Las modificaciones entrarán en vigor al ser
                    publicadas en la plataforma.</p>
                <h6>Legislación Aplicable</h6>
                <p>8.1 Estos términos y condiciones se rigen por las leyes vigentes en [País
                    o Estado].</p>
                <h6>Contacto</h6>
                <p>9.1 Para cualquier pregunta sobre estos términos y condiciones, por favor
                    contáctanos a Admin@Elegans.com .</p>
            </div>
        </div>
    </div>
</div>