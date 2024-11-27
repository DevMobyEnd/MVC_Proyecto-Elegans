<?php
// Iniciar la sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verificar si el usuario está logueado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: /login.php');
    exit;
}

// Separar el nombre completo en nombres y apellidos
$nombreCompleto = explode(' ', $_SESSION['nombre_completo']);
$nombres = $nombreCompleto[0] ?? '';
$apellidos = $nombreCompleto[1] ?? '';
?>

<main class="content px-3 py-2">
    <div class="container-fluid">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h2 class="card-title mb-4">Actualiza tu perfil</h2>
                <form id="updateProfileForm" method="POST" action="/Myperfil.php" enctype="multipart/form-data">
                    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">

                    <div class="row">
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
                            <input type="file" id="Foto_PerfilInput" accept="image/*" style="display: none;">
                            <div class="d-flex justify-content-center">
                                <button type="button" id="selectImageBtn" class="btn btn-outline-primary btn-sm">Cambiar foto</button>
                            </div>
                        </div>
                        <div class="col-md-9">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="nombresInput" class="form-label">Nombres</label>
                                    <input type="text" 
                                           class="form-control" 
                                           name="Nombres" 
                                           id="nombresInput" 
                                           value="<?php echo htmlspecialchars($nombres); ?>" 
                                           required>
                                </div>
                                <div class="col-md-6">
                                    <label for="apellidosInput" class="form-label">Apellidos</label>
                                    <input type="text" 
                                           class="form-control" 
                                           name="Apellidos" 
                                           id="apellidosInput" 
                                           value="<?php echo htmlspecialchars($apellidos); ?>" 
                                           required>
                                </div>
                                <div class="col-md-6">
                                    <label for="NumerodeDocumentoInput" class="form-label">Número de Documento</label>
                                    <input type="text" 
                                           class="form-control" 
                                           name="NumerodeDocumento" 
                                           id="NumerodeDocumentoInput" 
                                           value="<?php echo htmlspecialchars($_SESSION['numeroDocumento'] ?? ''); ?>" 
                                           required>
                                </div>
                                <div class="col-md-6">
                                    <label for="apodoInput" class="form-label">Apodo</label>
                                    <input type="text" 
                                           class="form-control" 
                                           name="Apodo" 
                                           id="apodoInput" 
                                           value="<?php echo htmlspecialchars($_SESSION['apodo'] ?? ''); ?>" 
                                           required>
                                </div>
                                <div class="col-md-6">
                                    <label for="rolInput" class="form-label">Rol</label>
                                    <input type="text" 
                                           class="form-control" 
                                           name="Rol" 
                                           id="rolInput" 
                                           value="<?php echo htmlspecialchars($_SESSION['rol'] ?? ''); ?>" 
                                           readonly>
                                </div>
                                <div class="col-md-6">
                                    <label for="passwordInput" class="form-label">Nueva Contraseña (opcional)</label>
                                    <input type="password" 
                                           class="form-control" 
                                           name="password" 
                                           id="passwordInput">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-end mt-4">
                        <button type="submit" id="updateButton" class="btn btn-primary">Actualizar Perfil</button>
                        <button type="button" id="backToProfile" class="btn btn-secondary ms-2">Volver al Perfil</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>