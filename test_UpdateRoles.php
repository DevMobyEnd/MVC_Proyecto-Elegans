<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prueba de Actualización de Roles</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            background-color: #f8f9fa;
        }

        .container {
            max-width: 600px;
            margin-top: 50px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1 class="mb-4">Prueba de Actualización de Roles</h1>
        <form id="updateRoleForm" class="mb-4">
            <div class="mb-3">
                <label for="usuarioId" class="form-label">ID de Usuario:</label>
                <input type="number" class="form-control" id="usuarioId" name="usuarioId" required>
            </div>
            <div class="mb-3">
                <label for="nuevoRol" class="form-label">Nuevo Rol:</label>
                <select class="form-select" id="nuevoRol" name="nuevoRol" required>
                    <option value="">Seleccione un rol</option>
                    <option value="usuario natural">Usuario Natural</option>
                    <option value="admin">Admin</option>
                    <option value="DJ">DJ</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Actualizar Rol</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('updateRoleForm').addEventListener('submit', function(e) {
            e.preventDefault();

            var usuarioId = document.getElementById('usuarioId').value;
            var nuevoRol = document.getElementById('nuevoRol').value;

            var formData = new FormData();
            formData.append('usuarioId', usuarioId);
            formData.append('nuevoRol', nuevoRol);

            fetch(window.location.href, {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.text())
                .then(result => {
                    if (result.includes('success')) {
                        Swal.fire({
                            icon: 'success',
                            title: '¡Éxito!',
                            text: `Se actualizó correctamente el rol del usuario ${usuarioId} a '${nuevoRol}'`,
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: `Hubo un error al actualizar el rol del usuario ${usuarioId}`,
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Hubo un error al procesar la solicitud',
                    });
                });
        });
    </script>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        require_once __DIR__ . '/Models/AdminUsuarioModel.php';
        $usuarioModel = new UsuarioModel();

        $usuarioId = $_POST['usuarioId'];
        $nuevoRol = $_POST['nuevoRol'];

        $resultado = $usuarioModel->actualizarRolUsuario($usuarioId, $nuevoRol);
        echo $resultado ? 'success' : 'error';
        exit;
    }
    ?>
</body>

</html>