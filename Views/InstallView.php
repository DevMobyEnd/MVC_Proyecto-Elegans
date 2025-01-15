<?php
// var_dump($_POST);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Mejorar el logging
error_log("Iniciando InstallView.php");
error_log("POST data: " . print_r($_POST, true));
error_log("Current directory: " . __DIR__);

// Define la ruta raíz de manera más segura
define('ROOT_PATH', dirname(__DIR__));
error_log("ROOT_PATH definido como: " . ROOT_PATH);

// Verificar si el archivo del controlador existe
$controllerPath = ROOT_PATH . '/Controller/InstallController.php';
error_log("Intentando cargar controlador desde: " . $controllerPath);

if (!file_exists($controllerPath)) {
    error_log("Error: El archivo del controlador no existe en: " . $controllerPath);
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Controller file not found']);
    exit;
}

require_once $controllerPath;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Configurar headers CORS si es necesario
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Content-Type');
    header('Content-Type: application/json');

    try {
        $controller = new InstallController();
        
        // Obtener y validar los datos POST
        $db_host = filter_input(INPUT_POST, 'db_host', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $db_name = filter_input(INPUT_POST, 'db_name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $db_username = filter_input(INPUT_POST, 'db_username', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $db_password = filter_input(INPUT_POST, 'db_password', FILTER_UNSAFE_RAW);

        // Sanitización adicional para $db_password si es necesario
        $db_password = strip_tags($db_password);

        error_log("Datos recibidos - Host: $db_host, DB: $db_name, Usuario: $db_username");

        if (isset($_POST['check_db'])) {
            error_log("Iniciando verificación de base de datos");
            try {
                $exists = $controller->verificarBaseDatos($db_host, $db_name, $db_username, $db_password);
                error_log("Resultado de verificación: " . ($exists ? 'existe' : 'no existe'));
                echo json_encode(['exists' => $exists, 'success' => true]);
            } catch (Exception $e) {
                error_log("Error en verificación de DB: " . $e->getMessage());
                http_response_code(500);
                echo json_encode([
                    'success' => false, 
                    'error' => $e->getMessage(),
                    'details' => 'Error durante la verificación de la base de datos'
                ]);
            }
        } else {
            error_log("Iniciando guardado de configuración");
            try {
                $result = $controller->guardarConfiguracion($db_host, $db_name, $db_username, $db_password);
                error_log("Configuración guardada con éxito");
                echo json_encode($result);
            } catch (Exception $e) {
                error_log("Error al guardar configuración: " . $e->getMessage());
                http_response_code(500);
                echo json_encode([
                    'success' => false, 
                    'message' => $e->getMessage(),
                    'details' => 'Error al guardar la configuración'
                ]);
            }
        }
    } catch (Exception $e) {
        error_log("Error general en InstallView: " . $e->getMessage());
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => $e->getMessage(),
            'details' => 'Error general en el proceso de instalación'
        ]);
    }
    exit;
} else {
    // Para solicitudes no-POST
    require_once './layout/InstallView/head.php';

}