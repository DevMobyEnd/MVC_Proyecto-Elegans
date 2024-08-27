<?php
session_start();
error_log("Session contents: " . print_r($_SESSION, true));

// Check if user is logged in
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php?error=Debes iniciar sesión para ver esta página.php");
    exit();
}

require_once __DIR__ . '/Controller/UsuarioController.php';
$usuarioController = new UsuarioController();

// Check if it's an AJAX request
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    // Handle AJAX request
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $postData = json_decode(file_get_contents('php://input'), true);
        $result = $usuarioController->actualizarPerfil($postData);
        echo json_encode($result);
        exit;
    }
}

// For non-AJAX requests, proceed with normal page load
$userData = $usuarioController->obtenerDatosUsuarioActual($_SESSION['usuario_id']);

require_once 'Views/layout/Myperfil/head.php';