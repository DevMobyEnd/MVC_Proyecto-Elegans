<?php
require_once 'Controller/UsuarioController.php';
require_once 'Helpers/SpotifyHelper.php';

// Verificar si es una petición AJAX
if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    $homeController = new UsuarioController();
    $solicitudes = $homeController->verSolicitudesConUsuarios();
    
    // Devolver las solicitudes en formato JSON
    header('Content-Type: application/json');
    echo json_encode($solicitudes);
    exit;
}
