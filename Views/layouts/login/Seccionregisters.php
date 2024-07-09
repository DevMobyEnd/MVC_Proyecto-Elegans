<div class="wrapper">
    <aside id="sidebar" class="js-sidebar">
        <!-- Content For Sidebar -->
        <div class="h-100">
            <div class="sidebar-logo">
                <a href="#"><img class="img-fluid" src="/Public/dist/img/Logo.png" alt="" style="width: 200px; height:200px;position: relative;    top: -60px;"></a>
            </div>
            <ul class="sidebar-nav">
                <li class="sidebar-header">
                    Elementos de Usuario
                </li>
                <li class="sidebar-item">
                    <a href="#" class="sidebar-link">
                        <i class="fa-solid fa-house"></i>
                        Inicio
                    </a>
                </li>




            </ul>
        </div>
    </aside>
    <div class="main">
        <nav class="navbar navbar-expand px-3 border-bottom">
            <button class="btn" id="sidebar-toggle" type="button">
                <span class="navbar-toggler-icon"></span>
            </button>

        </nav>
        <main class="content px-3 py-2">
            <div class="container mt-5">
                <div class="row justify-content-center">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header text-center" style="font-size: 24px; font-weight: bold;">
                                Registrarse
                            </div>
                            <div class="card-body">
                                <form action="/./Views/register.php" method="POST">
                                    <div class="mb-3">
                                        <label for="registerFirstName" class="form-label">Nombres</label>
                                        <input type="text" class="form-control" id="registerFirstName" value="Jhonny Alexander">
                                    </div>
                                    <div class="mb-3">
                                        <label for="registerLastName" class="form-label">Apellidos</label>
                                        <input type="text" class="form-control" id="registerLastName" value="Gonzalez Torres">
                                    </div>
                                    <div class="mb-3">
                                        <label for="registerAlias" class="form-label">Alias</label>
                                        <input type="text" class="form-control" id="registerAlias">
                                    </div>
                                    <div class="mb-3">
                                        <label for="registerEmail" class="form-label">Correo Electrónico</label>
                                        <input type="email" class="form-control" id="registerEmail" value="jhonnygonsalez7@gmail.com">
                                    </div>
                                    <div class="mb-3">
                                        <label for="registerPassword" class="form-label">Contraseña</label>
                                        <input type="password" class="form-control" id="registerPassword">
                                    </div>
                                    <div class="mb-3">
                                        <label for="registerConfirmPassword" class="form-label">Confirmar
                                            Contraseña</label>
                                        <input type="password" class="form-control" id="registerConfirmPassword">
                                    </div>
                                    <div class="d-flex justify-content-center">
                                        <a href="Seccionlogin.html" class="edit-profile-btn2">Registrarse</a>
                                    </div>
                                </form>
                                <div class="text-center mt-3">
                                    <p>¿Ya tienes una cuenta? <a href="Seccionlogin.html">Inicia sesión</a></p>
                                </div>
                            </div>
                        </div>
                    </div>
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
                </div>
            </div>

        </main>
        <a href="#" class="theme-toggle">
            <i class="fa-regular fa-moon"></i>
            <i class="fa-regular fa-sun"></i>
        </a>
        <footer class="footer">
            <div class="container-fluid">
                <div class="row text-muted">
                    <div class="col-6 text-start">
                        <p class="mb-0">
                            <a href="#" class="text-muted">
                                <strong>Elegans</strong>
                            </a>
                        </p>
                    </div>
                    <div class="col-6 text-end">
                        <ul class="list-inline">

                            <li class="list-inline-item">
                                <a href="#" class="text-muted" data-bs-toggle="modal" data-bs-target="#terminosCondicionesModal">Términos y Condiciones</a>
                            </li>

                        </ul>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</div>