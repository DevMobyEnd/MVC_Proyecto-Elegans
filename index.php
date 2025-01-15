<?php
// Muestra errores para depuración
// error_reporting(E_ALL);
// ini_set('display_errors', 1);

if (session_status() == PHP_SESSION_NONE) {
    session_start(); // Inicia la sesión solo si no ha sido iniciada
}

// if (!isset($_SESSION['usuario_id'])) {
//     header('Location: /login.php');
//     exit;
// }

require_once 'Controller/HomeController.php';

$homeController = new HomeController();
$userData = $homeController->index(); // Obtiene los datos del usuario desde el controlador

// Verifica si el usuario está en sesión
// if (isset($_SESSION['usuario_id'])) {
//     $userId = $_SESSION['usuario_id'];
// } else {
//     // Redirige o lanza un error si no hay un usuario en sesión.
//     die("Error: Usuario no autenticado.");
// }
    
// Función para verificar si todas las variables de configuración están definidas y no vacías
function configIsValid() {
    return (
        defined('DB_HOST') && !empty(trim(DB_HOST)) &&
        defined('DB_NAME') && !empty(trim(DB_NAME)) &&
        defined('DB_USERNAME') && !empty(trim(DB_USERNAME)) &&
        defined('DB_PASSWORD') && // Permitimos que la contraseña esté vacía
        defined('DB_ENCODE') && !empty(trim(DB_ENCODE))
    );
}

// Verifica si el archivo global.php existe y carga la configuración
$configLoaded = false;
if (file_exists('Config/global.php')) {
    require_once 'Config/global.php';
    $configLoaded = configIsValid();
}

// Si la configuración no se cargó correctamente, redirige al instalador
if (!$configLoaded) {
    header('Location: Views/InstallView.php');
    exit();
}

// A partir de aquí, sabemos que la configuración es válida
require_once 'Controller/HomeController.php';
$homeController = new HomeController();
$userData = $homeController->index(); // Obtiene los datos del usuario desde el controlador

// Verifica si se está haciendo una solicitud de búsqueda de canción
if (isset($_GET['songName']) && !empty(trim($_GET['songName']))) {
    require_once 'test_spotify.php';
    exit(); // Asegúrate de que el script se detenga después de procesar la solicitud de búsqueda
}

// Maneja la solicitud POST para agregar una solicitud de música
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $homeController->addMusicRequest();
    exit(); // Asegúrate de que el script se detenga después de procesar la solicitud POST
}
require_once "Views/layout/home/head.php";
