<main class="content px-3 py-2">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="container-fluid">
                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-body">
                                <form id="loginForm" method="POST" action="/login.php">
                                    <div id="emailStep" class="mb-3">
                                        <section>
                                            <h1 class="welcome-title">
                                                <span class="static-text">Bienvenidos</span>
                                                <span class="dynamic-text"></span>
                                            </h1>
                                            <div class="form-group d-flex flex-column align-items-center position-relative">
                                                <input type="email" placeholder="Correo Electrónico" class="form-control form-control-lg" name="Gmail" id="emailInput" required>
                                                <label class="form-label long-label">
                                                    <ion-icon name="mail-outline"></ion-icon> Correo Electrónico
                                                </label>
                                                <a href="#" id="editEmail" style="display: none; position: absolute; right: 10px; top: 50%; transform: translateY(-50%);">Editar</a>
                                            </div>
                                        </section>
                                    </div>
                                    <!-- <div id="passwordStep" class="mb-3" style="display: none;">
                                        <section>
                                            <div class="form-group d-flex flex-column align-items-center">
                                                <input type="password" placeholder="Contraseña" class="form-control form-control-lg" name="password" style="width: 100%; max-width: 400px;" required>
                                                <label class="form-label long-label">
                                                    <ion-icon name="lock-closed-outline"></ion-icon> Contraseña
                                                </label>
                                            </div>
                                        </section>
                                        <p class="text-center">
                                            <a href="/test_recover_password.php" id="forgotPassword">¿Olvidó su contraseña?</a>
                                        </p>
                                    </div> -->
                                    <div id="passwordStep" class="mb-3" style="display: none;">
                                        <section>
                                            <div class="form-group d-flex flex-column align-items-center">
                                                <input type="password" placeholder="Contraseña" class="form-control form-control-lg" name="password" style="width: 100%; max-width: 400px;" required>
                                                <label class="form-label long-label">
                                                    <ion-icon name="lock-closed-outline"></ion-icon> Contraseña
                                                </label>
                                                <div class="position-relative w-100" style="max-width: 400px;">
                                                    <!-- Indicador de intentos restantes -->
                                                    <div id="loginAttemptsInfo" class="text-danger small mt-1" style="display: none;">
                                                        Intentos restantes: <span id="remainingAttempts">3</span>
                                                    </div>
                                                    <!-- Indicador de bloqueo -->
                                                    <div id="lockoutInfo" class="text-danger small mt-1" style="display: none;">
                                                        Cuenta bloqueada. Tiempo restante: <span id="lockoutTimer">15:00</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </section>
                                        <p class="text-center">
                                            <a href="/test_recover_password.php" id="forgotPassword">¿Olvidó su contraseña?</a>
                                        </p>
                                    </div>
                                    <div class="text-center">
                                        <div class="d-flex justify-content-center edit-profile-btn-wrapper btn-wrapper-adjusted">
                                            <button type="submit" id="continueBtn" class="edit-profile-btn2 btn-lg fw-semibold">Continuar</button>
                                        </div>
                                        <div class="d-flex justify-content-center edit-profile-btn-wrapper btn-wrapper-adjusted">
                                            <button
                                                type="submit"
                                                id="submitBtn"
                                                name="btningresar"
                                                class="edit-profile-btn2 btn-lg fw-semibold"
                                                style="display: none;">
                                                <span class="button-text">Ingresar</span>
                                                <!-- Spinner para estado de bloqueo -->
                                                <div class="spinner-border spinner-border-sm ms-2"
                                                    role="status"
                                                    style="display: none;">
                                                    <span class="visually-hidden">Loading...</span>
                                                </div>
                                            </button>
                                        </div>
                                    </div>
                                    <p class="text-center mt-3">
                                        ¿No tiene una cuenta? <a href="register.php">Regístrese</a>
                                    </p>
                                    <div class="hr-with-text">
                                        <hr>
                                        <span>O</span>
                                    </div>
                                    <div class="social-login-buttons">
                                        <button type="button" class="btn btn-social btn-google">
                                            <ion-icon name="logo-google"></ion-icon> Continuar con Google
                                        </button>
                                        <button type="button" class="btn btn-social btn-github">
                                            <ion-icon name="logo-github"></ion-icon> Continuar con GitHub
                                        </button>
                                        <button type="button" class="btn btn-social btn-microsoft">
                                            <ion-icon name="logo-windows"></ion-icon> Continuar con Microsoft Account
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal de Recuperación de Contraseña -->
    <div class="modal fade" id="recuperarContrasenaModal" tabindex="-1" aria-labelledby="recuperarContrasenaModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="recuperarContrasenaModalLabel">Recuperación de Contraseña</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <form id="recuperarContrasenaForm">
                        <div class="mb-3">
                            <label for="recuperarEmail" class="form-label">Ingrese su correo electrónico</label>
                            <input type="email" class="form-control" id="recuperarEmail" name="recuperarEmail" required placeholder="Correo Electrónico">
                        </div>
                        <div id="mensajeRecuperacion" class="alert d-none"></div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" id="btnEnviarRecuperacion">Enviar</button>
                </div>
            </div>
        </div>
    </div>

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
</main>
<script>
    document.getElementById('btnEnviarRecuperacion').addEventListener('click', function() {
        const email = document.getElementById('recuperarEmail').value;
        const mensajeRecuperacion = document.getElementById('mensajeRecuperacion');

        if (email) {
            fetch('/login.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: new URLSearchParams({
                        action: 'solicitarRecuperacionContrasena',
                        Gmail: email
                    })
                })
                .then(response => {
                    console.log(response);
                    return response.json(); // Esto lanzará un error si la respuesta no es JSON
                })
                .then(data => {
                    console.log(data);
                    if (data.success) {
                        mensajeRecuperacion.classList.remove('d-none', 'alert-danger');
                        mensajeRecuperacion.classList.add('alert-success');
                        mensajeRecuperacion.innerText = data.message;
                    } else {
                        mensajeRecuperacion.classList.remove('d-none', 'alert-success');
                        mensajeRecuperacion.classList.add('alert-danger');
                        mensajeRecuperacion.innerText = data.message;
                    }
                })
                .catch(error => {
                    console.error(error);
                    mensajeRecuperacion.classList.remove('d-none', 'alert-success');
                    mensajeRecuperacion.classList.add('alert-danger');
                    mensajeRecuperacion.innerText = 'Error al procesar la solicitud.';
                });

        }
    });
</script>

<!-- Añadir estilos CSS -->
<style>
    .button-disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    #loginAttemptsInfo {
        font-size: 0.85rem;
        margin-top: 0.25rem;
        text-align: right;
    }

    #lockoutInfo {
        font-size: 0.85rem;
        margin-top: 0.25rem;
        text-align: center;
        padding: 0.5rem;
        background-color: #fff3cd;
        border: 1px solid #ffeeba;
        border-radius: 4px;
    }

    .error-shake {
        animation: shake 0.5s;
    }

    @keyframes shake {

        0%,
        100% {
            transform: translateX(0);
        }

        25% {
            transform: translateX(-10px);
        }

        75% {
            transform: translateX(10px);
        }
    }
</style>