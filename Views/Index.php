

<?php
require_once "./layouts/head.php";
require_once "./layouts/header.php";
?>

<?php
// Define el título de la página
$tituloPagina = "Inicio - Elegans";
// Incluye el archivo de encabezado después de definir la variable
require_once "./layouts/head.php";
require_once '../Middleware/auth.php';
?>
<?php
// Inicia la sesión si aún no ha sido iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verificar si el usuario está autenticado
if (!isset($_SESSION['usuario_id'])) {
    // Si no hay una sesión de usuario, imprime un script de JavaScript para alertar y luego redirigir
    echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'No estás autenticado.',
                text: 'Por favor, inicia sesión para continuar.',
                showConfirmButton: false,
                timer: 1500
            }).then(function() {
                window.location = 'login.php';
            });
          </script>";
    exit();
}
?>

