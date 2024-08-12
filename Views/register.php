<?php


// Error reporting (consider moving to a config file)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once '../Controller/RegisterController.php';

$controller = new RegisterController();
$csrf_token = $controller->generateCSRFToken();


// Handle AJAX requests
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    header('Content-Type: application/json');
    try {
        $response = $controller->registrar();
        echo json_encode($response);
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
    exit;
}

// Handle form submission for non-AJAX requests
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $result = $controller->registrar();
    if ($result['status'] === 'success') {
        header('Location: ' . ($result['redirect'] ?? '/'));
        exit;
    } else {
        $errores = $result['message'];
    }
}

// Define el título de la página
$tituloPagina = "Registro - Elegans";

// Incluye el head
require_once "./layout/Seccionregisters/head.php";

// Aquí incluirías el resto del HTML de la página, incluyendo el formulario
// ...

// Resto del código HTML y lógica de la vista

// Puedes agregar aquí lógica adicional para manejar estilos específicos si es necesario
// $seccion = basename($_SERVER['PHP_SELF']);
// if ($seccion == "register.php") {
//     echo '<link rel="stylesheet" href="../Public/dist/css/style.css">';
// } else {
//     echo '<link rel="stylesheet" href="../Public/dist/css/Estilo.css">';
// }