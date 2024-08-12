<?php
// At the top of your PHP file
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Verificar si el archivo de configuración ya existe
$instalacionCompleta = file_exists('../Config/global.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once '../Controller/InstallController.php';
    $controller = new InstallController();

    // Validación básica de entrada
    $db_host = htmlspecialchars(filter_input(INPUT_POST, 'db_host', FILTER_UNSAFE_RAW), ENT_QUOTES, 'UTF-8');
    $db_name = htmlspecialchars(filter_input(INPUT_POST, 'db_name', FILTER_UNSAFE_RAW), ENT_QUOTES, 'UTF-8');
    $db_username = htmlspecialchars(filter_input(INPUT_POST, 'db_username', FILTER_UNSAFE_RAW), ENT_QUOTES, 'UTF-8');
    $db_password = filter_input(INPUT_POST, 'db_password', FILTER_UNSAFE_RAW);

    if (!$db_host || !$db_name || !$db_username) {
        die('Datos de entrada inválidos');
    }

    try {
        // Si se está verificando la existencia de la base de datos
        if (isset($_POST['check_db'])) {
            $exists = $controller->verificarBaseDatos($db_host, $db_name, $db_username, $db_password);

            // Before sending the JSON response
            $response = ['exists' => $exists];
            error_log('PHP Response: ' . json_encode($response));
            header('Content-Type: application/json');
            echo json_encode($response);
            exit;
        }

        // Si se está guardando la configuración
        $result = $controller->guardarConfiguracion($db_host, $db_name, $db_username, $db_password);

        if ($result['success']) {
            echo $controller->mostrarMensaje('success', $result['message'], '/Views/Index.php');
        } else {
            echo $controller->mostrarMensaje('error', $result['message']);
        }
    } catch (Exception $e) {
        error_log('Error in InstallView.php: ' . $e->getMessage());
        $response = ['error' => 'An unexpected error occurred: ' . $e->getMessage()];
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }
} else {
    // Si no es una solicitud POST, incluye el formulario HTML
    require_once './layout/InstallView/head.php';

    if ($instalacionCompleta) {
        echo "<div class='alert alert-warning'>La instalación ya se ha completado. Puedes modificar la configuración si es necesario.</div>";
    }

    // ... (resto del código HTML para mostrar el formulario)
}
