<?php
// header('Content-Type: application/json');

// Inicia la sesión si aún no ha sido iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once '../Controller/UsuarioController.php';

$usuarioController = new UsuarioController();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verifica si es una solicitud AJAX
    if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        // Es una solicitud AJAX
        if(isset($_POST['Gmail']) && !isset($_POST['password'])) {
            // Primera fase: verificación de email
            $result = $usuarioController->verificarEmail($_POST['Gmail']);
            echo json_encode($result);
        } elseif(isset($_POST['Gmail']) && isset($_POST['password'])) {
            // Segunda fase: inicio de sesión completo
            $result = $usuarioController->login($_POST['Gmail'], $_POST['password']);
            echo json_encode($result);
        } else {
            echo json_encode(['success' => false, 'message' => 'Datos inválidos']);
        }
        exit;
    }
}

// Si no es una solicitud AJAX o no es POST, continúa con el resto del código HTML
require_once './layout/Seccionlogin/head.php';