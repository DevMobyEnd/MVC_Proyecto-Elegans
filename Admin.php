<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once "Controller/AdminUsuarioController.php";

$controller = new AdminUsuarioController();

if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    // Esta es una solicitud AJAX
    $controller->handleRequest();
} else {
    // Esta es una solicitud normal, carga la vista principal
    require_once "Views/layout/Admin/head.php";
    // Aquí puedes cargar el contenido inicial si lo deseas
}