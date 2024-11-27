<?php
// Muestra errores para depuración
// error_reporting(E_ALL);
// ini_set('display_errors', 1);

// Inicia la sesión si aún no ha sido iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once 'Controller/UsuarioController.php';
$usuarioController = new UsuarioController();

// Verifica si es una solicitud AJAX
if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    header('Content-Type: application/json');

    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'checkEmail':
                if (isset($_POST['Gmail'])) {
                    $result = $usuarioController->verificarEmail($_POST['Gmail']);
                    echo json_encode($result);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Gmail no proporcionado']);
                }
                break;
            case 'login':
                if (isset($_POST['Gmail']) && isset($_POST['password'])) {
                    ob_clean(); // Limpia cualquier salida previa
                    $result = $usuarioController->login($_POST['Gmail'], $_POST['password']);
                    if ($result['success']) {
                        setcookie('auth_token', $result['token'], time() + 3600, '/', '', true, true);
                        $redirectUrl = $usuarioController->getRedirectUrl($result['rol']);
                        echo json_encode(['success' => true, 'redirect' => $redirectUrl]);
                    } else {
                        echo json_encode($result);
                    }
                } else {
                    echo json_encode(['success' => false, 'message' => 'Gmail o contraseña no proporcionados']);
                }
                exit;
            case 'solicitarRecuperacionContrasena':
                if (isset($_POST['Gmail'])) {
                    $result = $usuarioController->solicitarRecuperacionContrasena($_POST['Gmail']);
                    echo json_encode($result);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Gmail no proporcionado']);
                }
                break;
            default:
                echo json_encode(['success' => false, 'message' => 'Acción inválida']);
                break;
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Datos inválidos']);
    }
    exit;
}

// Si no es una solicitud AJAX o no es POST, intenta iniciar sesión con el token si existe
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_COOKIE['auth_token'])) {
    $result = $usuarioController->iniciarSesionConToken($_COOKIE['auth_token']);
    if ($result['success']) {
        header("Location: " . $result['redirect'] . ".php");
        exit;
    }
}

// Si no es una solicitud AJAX o no es POST, continúa con el resto del código HTML
require_once 'Views/layout/Seccionlogin/head.php';
