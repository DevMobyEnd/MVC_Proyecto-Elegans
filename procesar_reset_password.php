<?php
require_once 'Models/UsuarioModel.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['token'];
    $nuevaContrasena = $_POST['password'];

    $modelo = new UsuarioModel();
    
    // Verificar el token
    $userId = $modelo->verificarToken($token, 'password_reset');
    
    if ($userId) {
        // Actualizar la contraseña
        if ($modelo->actualizarContraseña($userId, $nuevaContrasena)) {
            echo 'Contraseña actualizada exitosamente.';
            // Puedes redirigir al usuario a la página de inicio de sesión o mostrar un mensaje
        } else {
            echo 'Error al actualizar la contraseña.';
        }
    } else {
        echo 'Token de recuperación no válido o expirado.';
    }
} else {
    echo 'Método de solicitud no válido.';
}
