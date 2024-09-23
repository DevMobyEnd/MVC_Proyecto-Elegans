<?php
session_start();
error_log("Session contents: " . print_r($_SESSION, true));

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
// Check if user is logged in
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php?error=Debes iniciar sesión para ver esta página.php");
    exit();
}

// Asumiendo que tienes acceso a la sesión o a algún mecanismo para obtener el ID del usuario
$usuarioId = $_SESSION['usuario_id']; // Obtener el ID del usuario actual desde la sesión

// Registrar el ID del usuario en el log para depuración
error_log("Usuario ID: " . $usuarioId);

// require_once __DIR__ . '/Controller/UsuarioController.php';
// $usuarioController = new UsuarioController();

require_once 'Controller/HomeController.php';

$homeController = new HomeController();
$userData = $homeController->index(); // Obtiene los datos del usuario desde el controlador



require_once __DIR__ . '/Models/UsuarioModel.php';
$usuarioModel = new UsuarioModel();

$usuarioId = $_SESSION['usuario_id'];
$solicitudesMusica = $usuarioModel->obtenerInformacionSolicitudesMusica($usuarioId);

require_once 'Views/layout/Myperfil/head.php';

