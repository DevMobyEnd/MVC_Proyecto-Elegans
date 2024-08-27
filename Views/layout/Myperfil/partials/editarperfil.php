<main class="content px-3 py-2">
    <div class="container-fluid">
        <div class="card border-0">
            <div class="form__content">
                <form id="updateProfileForm" class="register-form" method="POST" action="/Myperfil.php" enctype="multipart/form-data>
                    <!-- CSRF token -->
                    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">

                    <div class="steps-container">
                        <!-- Step 1 -->
                        <section id="step1" class="register-step">
                            <h1 class="welcome-title">
                                <span class="static-text">Actualiza tu perfil</span>
                            </h1>
                            <div class="form-group">
                                <label for="Foto_PerfilInput">Foto de Perfil</label>
                                <img id="profilePreview" src="<?php echo $user['foto_perfil'] ?? '/Public/dist/img/profile.jpg'; ?>" alt="Vista Previa" style="width: 150px; height: 150px; margin-top: 10px; border-radius: 50%;" onclick="openCropperModal();">
                                <input type="file" id="Foto_PerfilInput" class="form-control" accept="image/*" style="display: none;">
                                <button type="button" id="selectImageBtn" class="btn btn-primary mt-2">Seleccionar Imagen</button>
                            </div>

                            <!-- Modal para recortar imagen (igual que en el formulario de registro) -->
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
                                    <input type="text" placeholder="Nombres" class="form-control form-control-lg" name="Nombres" id="nombresInput" value="<?php echo $user['nombres'] ?? ''; ?>" required>
                                    <label class="form-label long-label">
                                        <ion-icon name="person-outline"></ion-icon> Nombres
                                    </label>
                                </div>

                                <div class="form-group">
                                    <input type="text" placeholder="Apellidos" class="form-control form-control-lg" name="Apellidos" id="apellidosInput" value="<?php echo $user['apellidos'] ?? ''; ?>" required>
                                    <label class="form-label long-label">
                                        <ion-icon name="person-outline"></ion-icon> Apellidos
                                    </label>
                                </div>
                            </div>

                            <div class="form-container">
                                <div class="form-group">
                                    <input type="text" placeholder="Numero de Documento" class="form-control form-control-lg" name="NumerodeDocumento" id="NumerodeDocumentoInput" value="<?php echo $user['numero_documento'] ?? ''; ?>" required>
                                    <label class="form-label long-label">
                                        <ion-icon name="card-outline"></ion-icon> Numero de Documento
                                    </label>
                                </div>

                                <div class="form-group">
                                    <input type="text" placeholder="Apodo" class="form-control form-control-lg" name="Apodo" id="apodoInput" value="<?php echo $user['apodo'] ?? ''; ?>" required>
                                    <label class="form-label long-label">
                                        <ion-icon name="pricetag-outline"></ion-icon> Apodo
                                    </label>
                                </div>
                            </div>
                        </section>

                        <!-- Step 2 -->
                        <section id="step2" class="register-step" style="display: none;">
                            <div class="form-group">
                                <input type="email" placeholder="Correo Electrónico" class="form-control form-control-lg" name="CorreoElectronico" id="emailInput" value="<?php echo $user['correo_electronico'] ?? ''; ?>" required>
                                <label class="form-label long-label">
                                    <ion-icon name="mail-outline"></ion-icon> Correo Electrónico
                                </label>
                            </div>
                            <!-- Aquí puedes agregar más campos del paso 2 si los necesitas -->
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

                        </section>

                        <!-- Step 3 (si es necesario) -->
                        <section id="step3" class="register-step" style="display: none;">
                            <!-- Campos del paso 3 -->
                        </section>
                    </div>

                    <div class="button-container">
                        <button type="button" id="prevBtn" class="btn btn-secondary" onclick="showPreviousStep()">Atrás</button>
                        <button type="button" id="nextBtn" class="btn btn-primary" onclick="showNextStep()">Siguiente</button>
                        <button type="submit" id="updateButton" class="edit-profile-btn2 btn-lg fw-semibold">Actualizar Perfil</button>
                        <button id="backToProfile">Volver al Perfil</button>
                    </div>


                </form>
            </div>
        </div>
    </div>
</main>