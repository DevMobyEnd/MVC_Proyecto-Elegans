<div class="wrapper">
    <aside id="sidebar" class="js-sidebar">
        <!-- Content For Sidebar -->
        
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
                    <div class="col-md-12">
                        <div class="card">

                            <div class="card-body">
                                <div class="row">
                                    <div class="col">
                                        <h3><span>Inicia Sesión</span></h3>
                                    </div>
                                    <div class="col">
                                        <form action="/./Views/login.php" method="POST">
                                            <div class="mb-3">
                                                <section>
                                                    <div class="form-group d-flex flex-column align-items-center">
                                                        <input type="email" placeholder="Correo Electrónico" class="form-control" name="Gmail">
                                                        <label class="form-label">
                                                            <i class="fa-solid fa-envelope"></i> Correo Electrónico
                                                        </label>
                                                    </div>
                                                </section>
                                            </div>
                                            <div class="mb-3">
                                                <section>
                                                    <div class="form-group d-flex flex-column align-items-center">
                                                        <input type="password" placeholder="Contraseña" class="form-control" name="password">
                                                        <label class="form-label">
                                                            <i class="fa-solid fa-lock"></i> Contraseña
                                                        </label>
                                                    </div>
                                                </section>
                                            </div>
                                            <!-- Agrega cualquier otro campo de formulario aquí -->
                                            <button  type="submit" name="btningresar"  class="btn btn-primary">Ingresar</button>
                                        </form>
                                    </div>
                                </div>



                            </div>
                        </div>
                    </div>
                </div>
                <!-- <div class="card-header text-center" style="font-size: 24px; font-weight: bold;">
                        Iniciar Sesión
                    </div> -->
                <!-- <div class="form-group">
                        <input type="password" placeholder="Contraseña" class="form-control">
                        <label class="form-label">
                            <i class="fa-solid fa-lock"></i> Contraseña
                        </label>
                    </div>
                    <div class="text-center mt-3">
                        <p>¿Aún no tienes una cuenta? <a href="Seccionregisters.html">Regístrate</a></p>
                    </div> -->
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