<main class="content px-3 py-2">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <form id="registerForm" class="register-form" method="POST" action="/register.php" enctype="multipart/form-data">
                    <!-- CSRF token -->
                    <input type="hidden" name="csrf_token"  value="<?php echo $csrf_token; ?>">
                    <!-- Resto de los campos del formulario -->
                    <!-- Step 1 -->
                    <div id="step1" class="register-step">
                        <section>
                            <h1 class="welcome-title">
                                <span class="static-text">Únete a nosotros</span>
                                <span class="dynamic-text">
                                    <span>Join us!</span>
                                    <span>Rejoignez-nous!</span>
                                    <span>私たちと一緒に！</span>
                                </span>
                            </h1>
                            <div class="form-group">
                                <label for="Foto_PerfilInput">Foto de Perfil</label>
                                <img id="profilePreview" src="/Public/dist/img/profile.jpg" alt="Vista Previa" style="width: 150px; height: 150px; margin-top: 10px; border-radius: 50%;" onclick="openCropperModal();">
                                <input type="file" id="Foto_PerfilInput" name="Foto_Perfil" accept="image/*" style="display: none;">
                                <button type="button" id="selectImageBtn" class="btn btn-primary mt-2">Seleccionar Imagen</button>
                            </div>
                            <!-- Modal -->
                            <div class="modal fade" id="cropModal" tabindex="-1" aria-labelledby="cropModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="cropModalLabel">Recortar Imagen</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <img id="imageToCrop" src="" alt="Imagen para Recortar">
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" id="cropImageBtn" class="btn btn-primary">Recortar</button>
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" id="croppedImageData" name="croppedImageData">
                            <div class="form-container">
                                <div class="form-group">
                                    <input type="text" placeholder="Nombres" class="form-control form-control-lg" name="Nombres" id="nombresInput" required>
                                    <label class="form-label long-label">
                                        <ion-icon name="person-outline"></ion-icon> Nombres
                                    </label>
                                </div>
                                <div class="form-group">
                                    <input type="text" placeholder="Apellidos" class="form-control form-control-lg" name="Apellidos" id="apellidosInput" required>
                                    <label class="form-label long-label">
                                        <ion-icon name="person-outline"></ion-icon> Apellidos
                                    </label>
                                </div>
                            </div>
                            <div class="form-container">
                                <div class="form-group">
                                    <input type="text" placeholder="Numero de Documento" class="form-control form-control-lg" name="NumerodeDocumento" id="NumerodeDocumentoInput" required>
                                    <label class="form-label long-label">
                                        <ion-icon name="card-outline"></ion-icon> Numero de Documento
                                    </label>
                                </div>
                                <div class="form-group">
                                    <input type="text" placeholder="Apodo" class="form-control form-control-lg" name="Apodo" id="apodoInput" required>
                                    <label class="form-label long-label">
                                        <ion-icon name="pricetag-outline"></ion-icon> Apodo
                                    </label>
                                </div>
                            </div>
                            <button type="button" id="nextStepBtn" class="edit-profile-btn2 btn-lg fw-semibold mb-4" onclick="showStep(2)">Siguiente</button>
                        </section>
                    </div>
                    <!-- Step 2 -->
                    <div id="step2" class="register-step" style="display: none;">
                        <section>
                            <div class="form-group">
                                <input type="email" placeholder="Correo Electrónico" class="form-control form-control-lg" name="CorreoElectronico" id="emailInput" required>
                                <label class="form-label long-label">
                                    <ion-icon name="mail-outline"></ion-icon> Correo Electrónico
                                </label>
                            </div>
                            <div class="form-group">
                                <input type="password" placeholder="Contraseña" class="form-control form-control-lg" name="password" id="passwordInput" required>
                                <label class="form-label long-label">
                                    <ion-icon name="lock-closed-outline"></ion-icon> Contraseña
                                </label>
                                <div class="progress mt-2" style="height: 5px; width: 100%;">
                                    <div id="password-strength" class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <small id="passwordHelp" class="form-text text-muted">La contraseña debe tener al menos 8 caracteres.</small>
                            </div>
                            <div class="form-group">
                                <input type="password" placeholder="Confirmar Contraseña" class="form-control form-control-lg" name="confirmPassword" id="confirmPasswordInput" required>
                                <label class="form-label long-label">
                                    <ion-icon name="lock-closed-outline"></ion-icon> Confirmar Contraseña
                                </label>
                            </div>

                            <div class="d-flex flex-column align-items-center mb-3">
                                <div class="cf-turnstile" data-sitekey="0x4AAAAAAAg7dIijZcb4rb5v" data-callback="onCaptchaSuccess"></div>
                            </div>

                            <input type="hidden" id="cf-turnstile-response" name="cf-turnstile-response">

                            <div id="formErrors" class="alert alert-danger" style="display: none;"></div>

                            <button type="button" class="btn btn-secondary" onclick="showStep(1)">Atrás</button>
                            <button type="submit" id="registerButton" class="edit-profile-btn2 btn-lg fw-semibold">Registrarse</button>
                        </section>
                    </div>
                </form>
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