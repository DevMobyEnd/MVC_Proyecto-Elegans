<main class="content px-3 py-2">
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
                            <input type="text" class="form-control" id="registerFirstName"  name="Nombres">
                        </div>
                        <div class="mb-3">
                            <label for="registerLastName" class="form-label">Apellidos</label>
                            <input type="text" class="form-control" id="registerLastName" name="Apellidos">
                        </div>
                        <div class="mb-3">
                            <label for="registerDocumentNumber" class="form-label">NumeroDocumento</label>
                            <input type="text" class="form-control" id="registerDocumentNumber" name="NumeroDocumento">
                        </div>
                        <div class="mb-3">
                            <label for="registerAlias" class="form-label">Usuario</label>
                            <input type="text" class="form-control" id="registerAlias" name="Usuario">
                        </div>
                        <div class="mb-3">
                            <label for="registerEmail" class="form-label">Correo Electrónico</label>
                            <input type="email" class="form-control" id="registerEmail"  name="Gmail">
                        </div>
                        <div class="mb-3">
                            <label for="registerPassword" class="form-label">Contraseña</label>
                            <input type="password" class="form-control" id="registerPassword" name="password">
                        </div>
                        <div class="mb-3">
                            <label for="registerConfirmPassword" class="form-label">Confirmar
                                Contraseña</label>
                            <input type="password" class="form-control" id="registerConfirmPassword" name="password">
                        </div>
                        <div class="d-flex justify-content-center">
                            <button name="btnregistrar" type="submit" class="edit-profile-btn2">Registrarse</button>
                        </div>

                    </form>
                    <div class="text-center mt-3">
                        <p>¿Ya tienes una cuenta? <a href="Seccionlogin.html">Inicia sesión</a></p>
                    </div>
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



</main>