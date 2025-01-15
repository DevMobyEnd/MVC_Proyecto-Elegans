<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../Models/UpdateProfileModel.php';

$model = new UpdateProfileModel();

// Simulamos un ID de usuario para la prueba
$userId = 34;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update'])) {
        $datosUsuario = [
            'nombres' => $_POST['nombres'],
            'apellidos' => $_POST['apellidos'],
            'numero_documento' => $_POST['numero_documento'],
            'Apodo' => $_POST['Apodo'],
            'Gmail' => $_POST['Gmail'],
            'foto_perfil' => $_POST['foto_perfil'],
            // Añade la contraseña solo si se proporciona
            'password' => !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : null,
        ];

        $result = $model->actualizarPerfilCompleto($userId, $datosUsuario);
        $message = $result ? "Perfil actualizado con éxito" : "Error al actualizar el perfil";
    } elseif (isset($_POST['revert'])) {
        $result = $model->revertirUltimaActualizacion($userId);
        $message = $result ? "Última actualización revertida con éxito" : "Error al revertir la actualización";
    }
}

$usuario = $model->obtenerUsuarioPorId($userId);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Update Profile</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="container mt-5">
        <h2>Test Update Profile</h2>
        <?php if (isset($message)): ?>
            <div class="alert alert-info"><?php echo $message; ?></div>
        <?php endif; ?>

        <form method="post">
            <div class="form-group">
                <label for="nombres">Nombres:</label>
                <input type="text" class="form-control" id="nombres" name="nombres" value="<?php echo htmlspecialchars($usuario['nombres'] ?? ''); ?>">
            </div>
            <div class="form-group">
                <label for="apellidos">Apellidos:</label>
                <input type="text" class="form-control" id="apellidos" name="apellidos" value="<?php echo htmlspecialchars($usuario['apellidos'] ?? ''); ?>">
            </div>
            <div class="form-group">
                <label for="numero_documento">Número de Documento:</label>
                <input type="text" class="form-control" id="numero_documento" name="numero_documento" value="<?php echo htmlspecialchars($usuario['numero_documento'] ?? ''); ?>">
            </div>
            <div class="form-group">
                <label for="Apodo">Apodo:</label>
                <input type="text" class="form-control" id="Apodo" name="Apodo" value="<?php echo htmlspecialchars($usuario['Apodo'] ?? ''); ?>">
            </div>
            <div class="form-group">
                <label for="Gmail">Gmail:</label>
                <input type="email" class="form-control" id="Gmail" name="Gmail" value="<?php echo htmlspecialchars($usuario['Gmail'] ?? ''); ?>">
            </div>
            <div class="form-group">
                <label for="foto_perfil">Foto de Perfil (URL):</label>
                <input type="text" class="form-control" id="foto_perfil" name="foto_perfil" value="<?php echo htmlspecialchars($usuario['foto_perfil'] ?? ''); ?>">
            </div>
            <div class="form-group">
                <label for="password">Nueva Contraseña (dejar en blanco para no cambiar):</label>
                <input type="password" class="form-control" id="password" name="password">
            </div>
            <button type="submit" name="update" class="btn btn-primary">Actualizar Perfil</button>
            <button type="submit" name="revert" class="btn btn-warning">Revertir Última Actualización</button>
        </form>
    </div>
    <script>
        <?php if ($message): ?>
        Swal.fire({
            title: '<?php echo $messageType === "success" ? "¡Éxito!" : "Error"; ?>',
            text: '<?php echo $message; ?>',
            icon: '<?php echo $messageType; ?>',
            confirmButtonText: 'OK'
        });
        <?php endif; ?>

        document.getElementById('updateForm').addEventListener('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                title: '¿Estás seguro?',
                text: "¿Quieres proceder con esta acción?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, proceder',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit();
                }
            });
        });
    </script>
</body>
</html>