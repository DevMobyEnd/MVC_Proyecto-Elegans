<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);


// Iniciar la sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
echo "<pre>";
print_r($_SESSION['usuario_data']);
echo "</pre>";

require_once '../../../../Models/InfoUsuarioModel.php';

// Verificar si el usuario está logueado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: /login.php');
    exit;
}

// Crear instancia del modelo
$infoUsuarioModel = new InfoUsuarioModel();

// Actualizar la información del usuario en la sesión
$infoUsuarioModel->obtenerInformacionUsuario($_SESSION['usuario_id']);

// Para obtener y guardar en sesión
$usuario = $infoUsuarioModel->obtenerInformacionUsuario($_SESSION['usuario_id']);

// Separar el nombre completo en nombres y apellidos
$nombreCompleto = isset($_SESSION['nombre_completo']) ? explode(' ', $_SESSION['nombre_completo']) : ['', ''];
$nombres = $nombreCompleto[0];
$apellidos = $nombreCompleto[1];
?>

<main class="content px-3 py-2">
    <div class="container-fluid">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h2 class="card-title mb-4">Actualiza tu perfil</h2>
                <form id="updateProfileForm" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                    <input type="hidden" name="croppedImageData" id="croppedImageData">

                    <div class="row">
                        <!-- Sección de foto de perfil -->
                        <div class="col-md-3 text-center mb-3 d-flex flex-column align-items-center">
                            <?php
                            $defaultImage = '/Public/dist/img/profile.jpg';
                            $profileImage = isset($_SESSION['foto_perfil']) ? '/uploads/' . $_SESSION['foto_perfil'] : $defaultImage;
                            ?>
                            <img id="profilePreview"
                                src="<?php echo htmlspecialchars($profileImage); ?>"
                                alt="Vista Previa"
                                class="img-fluid rounded-circle mb-2"
                                style="width: 150px; height: 150px; cursor: pointer;"
                                onclick="openCropperModal();"
                                onerror="this.onerror=null; this.src='<?php echo $defaultImage; ?>';">
                            <input type="file" id="Foto_PerfilInput" name="Foto_Perfil" accept="image/*" style="display: none;">
                            <div class="d-flex justify-content-center">
                                <button type="button" id="selectImageBtn" class="btn btn-outline-primary btn-sm">Cambiar foto</button>
                            </div>
                        </div>

                        <!-- Sección de datos del perfil -->
                        <div class="col-md-9">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="nombresInput" class="form-label">Nombres</label>
                                    <input type="text" class="form-control" name="Nombres" id="nombresInput"
                                        value="<?php echo htmlspecialchars($nombres); ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="apellidosInput" class="form-label">Apellidos</label>
                                    <input type="text" class="form-control" name="Apellidos" id="apellidosInput"
                                        value="<?php echo htmlspecialchars($apellidos); ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="NumerodeDocumentoInput" class="form-label">Número de Documento</label>
                                    <input type="text" class="form-control" name="NumerodeDocumento" id="NumerodeDocumentoInput"
                                    value="<?php echo htmlspecialchars($_SESSION['usuario_data']['numero_documento'] ?? 'Undefined'); ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="apodoInput" class="form-label">Apodo</label>
                                    <input type="text" class="form-control" name="Apodo" id="apodoInput"
                                        value="<?php echo htmlspecialchars($_SESSION['apodo'] ?? ''); ?>" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-end mt-4">
                        <button type="submit" id="updateButton" class="btn btn-primary">Actualizar Perfil</button>
                        <button type="button" class="btn btn-danger ms-2" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
                            Eliminar Cuenta
                        </button>
                        <button type="button" id="backToProfile" class="btn btn-secondary ms-2">Volver al Perfil</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal para eliminar cuenta -->
    <div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-labelledby="deleteAccountModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteAccountModalLabel">¿Por qué deseas eliminar tu cuenta?</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="deleteAccountForm" method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">

                        <div class="mb-3">
                            <label class="form-label">Selecciona un motivo:</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="motivo" id="motivo1" value="no_util">
                                <label class="form-check-label" for="motivo1">No encuentro útil la plataforma</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="motivo" id="motivo2" value="privacidad">
                                <label class="form-check-label" for="motivo2">Preocupaciones de privacidad</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="motivo" id="motivo3" value="experiencia">
                                <label class="form-check-label" for="motivo3">Mala experiencia</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="motivo" id="motivo4" value="otro">
                                <label class="form-check-label" for="motivo4">Otro motivo</label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="motivoDetalle" class="form-label">Cuéntanos más (opcional):</label>
                            <textarea class="form-control" id="motivoDetalle" name="motivo_detalle" rows="3"></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Por favor, ingresa tu contraseña para confirmar:</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>

                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i>
                            Tu cuenta será desactivada y tus datos personales serán anonimizados después de 30 días.
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-danger">Confirmar Eliminación</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>