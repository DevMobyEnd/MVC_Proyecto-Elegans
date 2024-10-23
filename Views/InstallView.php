<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once '../Controller/InstallController.php';
    $controller = new InstallController();

    $db_host = $_POST['db_host'];
    $db_name = $_POST['db_name'];
    $db_username = $_POST['db_username'];
    $db_password = $_POST['db_password'];

    // Si se está verificando la existencia de la base de datos
    if (isset($_POST['check_db'])) {
        $exists = $controller->verificarBaseDatos($db_host, $db_name, $db_username, $db_password);
        echo json_encode(['exists' => $exists]);
        exit;
    }

    // Si se está guardando la configuración
    $result = $controller->guardarConfiguracion($db_host, $db_name, $db_username, $db_password);

    $icon = $result['success'] ? 'success' : 'error';
    $message = addslashes($result['message']);
    $success = $result['success'] ? 'true' : 'false';

    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
    echo "<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            icon: '$icon',
            title: '$message',
            showConfirmButton: true
        }).then((result) => {
            if (result.isConfirmed && $success) {
                window.location.href = '../Index.php';
            }
        });
    });
    </script>";
}

// Si no es una solicitud POST, incluye el formulario HTML
require_once './layout/InstallView/head.php';
// ... (resto del código HTML)


