<?php
// Incluye los archivos necesarios
require_once 'Controller/UsuarioController.php';

// Inicia la sesión si aún no ha sido iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Crea una instancia del controlador
$usuarioController = new UsuarioController();

// Verifica si es una solicitud AJAX
if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['email'])) {
    $email = $_POST['email'];

    // Llama a la función de recuperación de contraseña
    $result = $usuarioController->solicitarRecuperacionContrasena($email);

    // Devuelve el resultado como JSON
    header('Content-Type: application/json');
    echo json_encode($result);
    exit;
}

// Muestra un formulario de prueba si la solicitud no es AJAX
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($tituloPagina) ? $tituloPagina : 'Elegans'; ?></title>
    <link rel="website icon" type="png" href="Public/dist/img/Logo3.png">
    <link rel="stylesheet" href="Public/dist/css/test_recover_password.css">
    <title>Prueba Recuperación de Contraseña</title>
    <!-- Incluir SweetAlert CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</head>

<body>
    <form id="recoverForm">
        <div class="header">
            <h1>Restablecimiento de Contraseña</h1>
        </div>
        <div class="form-group d-flex flex-column align-items-center position-relative">
            <input placeholder="Correo Electrónico" type="email" class="form-control form-control-lg" id="email" name="email" required>
            <label class="form-label long-label" for="email">
                <ion-icon name="mail-outline"></ion-icon> Correo Electrónico
            </label>
            <div class="button-container">
            <button type="button" class="gray-button" onclick="window.location.href='login.php';">Volver</button>
                <button type="submit">Solicitar Recuperación</button>
            </div>
        </div>
    </form>
    <!-- Loader Overlay -->
    <div id="loaderOverlay">
        <div class="spinner-border"></div>
    </div>

    <canvas id="CanvasParticle"></canvas>

    <script src="Public/dist/js/CanvasParticle.js"></script>
    <script>
        // Llama a la función CanvasParticle para iniciar la animación de partículas
        CanvasParticle();
    </script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="Public/dist/js/test_recover_password.js"></script>
</body>

</html>