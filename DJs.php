<?php
// Inicia la sesión si aún no ha sido iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Cargar el controlador
require_once 'Controller/UsuarioController.php';

// Crear una instancia del controlador
$homeController = new UsuarioController();

// Obtener las solicitudes con la información de los usuarios
$solicitudes = $homeController->verSolicitudesConUsuarios(); // Llamada correcta al método

// $controller = new UsuarioController();
// $resultado = $controller->reproducirListaSpotify();

// if ($resultado['success']) {
//     echo $resultado['script'];
// } else {
//     echo "Error al cargar la lista de reproducción.";
// }

// Ahora las solicitudes están disponibles para la vista
require_once 'Views/layout/DJs/head.php'; 
