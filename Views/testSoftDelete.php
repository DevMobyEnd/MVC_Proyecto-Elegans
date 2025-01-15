<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Verifica si el archivo existe antes de requerirlo
if (file_exists('../Models/SoftDeleteModel.php')) {
    require_once '../Models/SoftDeleteModel.php';
} else {
    die('Error: El archivo SoftDeleteModel.php no se encuentra en la ruta especificada.');
}


$softDeleteModel = new SoftDeleteModel();

// Función para mostrar mensajes
function showMessage($message, $isError = false) {
    echo '<div style="color: ' . ($isError ? 'red' : 'green') . '; margin-bottom: 10px;">' . $message . '</div>';
}

// Procesar formularios
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['desactivar'])) {
        $userId = $_POST['userId'];
        $datos = [
            'fecha_desactivacion' => date('Y-m-d H:i:s'),
            'motivo' => $_POST['motivo'],
            'motivo_detalle' => $_POST['motivo_detalle']
        ];
        $result = $softDeleteModel->desactivarUsuario($userId, $datos);
        showMessage($result ? "Usuario desactivado con éxito" : "Error al desactivar usuario", !$result);
    } elseif (isset($_POST['reactivar'])) {
        $userId = $_POST['userId'];
        $result = $softDeleteModel->reactivarUsuario($userId);
        showMessage($result ? "Usuario reactivado con éxito" : "Error al reactivar usuario", !$result);
    } elseif (isset($_POST['anonimizar'])) {
        $userId = $_POST['userId'];
        $datosAnonimos = [
            'nombres' => 'Anónimo',
            'apellidos' => 'Anónimo',
            'correoElectronico' => 'anonimo' . $userId . '@example.com',
            'apodo' => 'Anónimo' . $userId,
            'foto_perfil' => 'default.jpg'
        ];
        $result = $softDeleteModel->anonimizarUsuario($userId, $datosAnonimos);
        showMessage($result ? "Usuario anonimizado con éxito" : "Error al anonimizar usuario", !$result);
    }
}

// Obtener usuarios inactivos
$usuariosInactivos = $softDeleteModel->obtenerUsuariosInactivosPorTiempo(30);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test SoftDelete Model</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        form { margin-bottom: 20px; }
        input, select { margin-bottom: 10px; }
    </style>
</head>
<body>
    <h1>Test SoftDelete Model</h1>

    <h2>Desactivar Usuario</h2>
    <form method="POST">
        <input type="number" name="userId" placeholder="ID del usuario" required>
        <input type="text" name="motivo" placeholder="Motivo de desactivación" required>
        <textarea name="motivo_detalle" placeholder="Detalle del motivo"></textarea>
        <button type="submit" name="desactivar">Desactivar Usuario</button>
    </form>

    <h2>Reactivar Usuario</h2>
    <form method="POST">
        <input type="number" name="userId" placeholder="ID del usuario" required>
        <button type="submit" name="reactivar">Reactivar Usuario</button>
    </form>

    <h2>Anonimizar Usuario</h2>
    <form method="POST">
        <input type="number" name="userId" placeholder="ID del usuario" required>
        <button type="submit" name="anonimizar">Anonimizar Usuario</button>
    </form>

    <h2>Usuarios Inactivos (últimos 30 días)</h2>
    <?php if (empty($usuariosInactivos)): ?>
        <p>No hay usuarios inactivos en los últimos 30 días.</p>
    <?php else: ?>
        <ul>
        <?php foreach ($usuariosInactivos as $usuario): ?>
            <li>
                ID: <?php echo $usuario['id']; ?>, 
                Nombre: <?php echo $usuario['nombres'] . ' ' . $usuario['apellidos']; ?>, 
                Apodo: <?php echo $usuario['Apodo']; ?>
            </li>
        <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</body>
</html>