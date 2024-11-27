<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start(); // Inicia la sesión solo si no ha sido iniciada
}

try {
    // Asegúrate de que la ruta al controlador sea correcta
    $controllerFile = 'Controller/ChatController.php';
    if (!file_exists($controllerFile)) {
        throw new Exception("El archivo del controlador no existe: $controllerFile");
    }
    require_once $controllerFile;

    if (!class_exists('CanalDialogoController')) {
        throw new Exception("La clase CanalDialogoController no está definida en $controllerFile");
    }

    $canalDialogoController = new CanalDialogoController();
    $canalDialogoController->index();
} catch (Exception $e) {
    // Loguea el error
    error_log($e->getMessage());
    
    // Muestra un mensaje de error amigable al usuario
    echo "Lo sentimos, ha ocurrido un error. Por favor, contacta al administrador del sistema.";
    
    // Si estás en modo de desarrollo, podrías mostrar el mensaje de error completo:
    // echo "Error: " . $e->getMessage();
}