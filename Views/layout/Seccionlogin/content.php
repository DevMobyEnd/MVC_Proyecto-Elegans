<main class="content px-3 py-2">
<div class="container mt-5">
        <div class="row justify-content-center">
            <div class="container-fluid">
                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-header text-center">
                                Inicia Sesión
                            </div>
                            <div class="card-body">
                                <form id="loginForm" action="/./Views/login.php" method="POST">
                                    <div id="emailStep" class="mb-3">
                                        <section>
                                            <div class="form-group d-flex flex-column align-items-center position-relative">
                                                <input type="email" placeholder="Correo Electrónico" class="form-control form-control-lg" name="Gmail" id="emailInput" style="width: 100%; max-width: 400px;" required>
                                                <label class="form-label long-label">
                                                    <i class="fa-solid fa-envelope"></i> Correo Electrónico
                                                </label>
                                                <a href="#" id="editEmail" style="display: none; position: absolute; right: 10px; top: 50%; transform: translateY(-50%);">Editar</a>
                                            </div>
                                        </section>
                                    </div>
                                    <div id="passwordStep" class="mb-3" style="display: none;">
                                        <section>
                                            <div class="form-group d-flex flex-column align-items-center">
                                                <input type="password" placeholder="Contraseña" class="form-control form-control-lg" name="password" style="width: 100%; max-width: 400px;" required>
                                                <label class="form-label long-label">
                                                    <i class="fa-solid fa-lock"></i> Contraseña
                                                </label>
                                            </div>
                                        </section>
                                        <p class="text-center">
                                            <a href="#" id="forgotPassword">¿Olvidó su contraseña?</a>
                                        </p>
                                    </div>
                                    <div class="text-center">
                                        <button type="button" id="continueBtn" class="btn btn-primary btn-lg">Continuar</button>
                                        <button type="submit" id="submitBtn" name="btningresar" class="btn btn-primary btn-lg" style="display: none;">Ingresar</button>
                                    </div>
                                    <p class="text-center mt-3">
                                        ¿No tiene una cuenta? <a href="#">Regístrese</a>
                                    </p>
                                </form>
                            </div>
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



</main>