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
            // echo 'Contraseña actualizada exitosamente.';
            //  redirigir al usuario a la página de inicio de sesión o mostrar un mensaje
            header('Location: login.php');


        } else {
            echo 'Error al actualizar la contraseña.';
            header('Location: prosesar_reset_password.php?token=' . $token);
        }
    } else {
        echo 'Token de recuperación no válido o expirado.';
    }
} else {
    echo 'Método de solicitud no válido.';
}
