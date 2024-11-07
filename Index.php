<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start(); // Inicia la sesión solo si no ha sido iniciada
}

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
    
// Verifica si se está haciendo una solicitud de búsqueda de canciónQ   
if (isset($_GET['songName']) && !empty(trim($_GET['songName']))) {
    require_once 'test_spotify.php';
    exit(); // Asegúrate de que el script se detenga después de procesar la solicitud de búsqueda
}

// Verifica si el archivo global.php existe
if (file_exists('Config/global.php')) {
    require_once 'Config/global.php';

    // Verifica si todas las variables necesarias están definidas y no están vacías
    if (
        defined('DB_HOST') && !empty(DB_HOST) &&
        defined('DB_NAME') && !empty(DB_NAME) &&
        defined('DB_USERNAME') && !empty(DB_USERNAME) &&
        defined('DB_PASSWORD') &&
        defined('DB_ENCODE') && !empty(DB_ENCODE)
    ) {
        // Maneja la solicitud POST para agregar una solicitud de música
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $homeController->addMusicRequest();
            exit(); // Asegúrate de que el script se detenga después de procesar la solicitud POST
        }

        // Carga normal de la vista de inicio
        require_once "Views/layout/home/head.php";
        // ... resto del código de la página de inicio
    } else {
        // Redirige al instalador si alguna variable está indefinida o vacía
        header('Location: Views/InstallView.php');
        exit();
    }
} else {
    // Redirige al instalador si el archivo global.php no existe
    header('Location: Views/InstallView.php');
    exit();
}


// Aquí puedes incluir el resto del código de tu página de inicio


// Aquí puedes incluir el resto del código de tu página de inicio
// Por ejemplo, contenido específico para usuarios autenticados o no autenticados

// ... resto de tu código actual
// require_once '../Middleware/auth.php'; // Este archivo puede contener funciones útiles, pero no forzaremos la autenticación aquí.


// ini_set('display_errors', 1);
// error_reporting(E_ALL);

// // Inicia la sesión si aún no ha sido iniciada
// if (session_status() == PHP_SESSION_NONE) {
//     session_start();
// }

// // Verificar si el usuario está autenticado
// if (!isset($_SESSION['usuario_id'])) {
//     // Si no hay una sesión de usuario, imprime un script de JavaScript para alertar y luego ofrecer opciones
//     echo "<script>
//    Swal.fire({
//     icon: 'error',
//     title: 'Ver más en Elegans.',
//     text: '¿Deseas iniciar sesión o registrarte?',
//     showCancelButton: true,
//     confirmButtonText: 'Iniciar sesión',
//     cancelButtonText: 'Registrarse',
//     showCloseButton: true, // Habilita el botón de cierre
//     reverseButtons: true
// }).then((result) => {
//     if (result.isConfirmed) {
//         window.location = '../Views/login.php';
//     } else if (result.dismiss === Swal.DismissReason.cancel) {
//         window.location = '../Views/register.php';
//     }
//     // No es necesario manejar el caso 'isDenied' ya que hemos eliminado el botón de denegación
//     // El botón de cierre permite al usuario cerrar la alerta sin realizar ninguna acción
// });
//   </script>";
//     exit();
// } // Falta esta llave para cerrar correctamente el bloque if

// // Aquí puedes incluir contenido que sea visible para todos los usuarios
// // Por ejemplo, un mensaje de bienvenida, información sobre tu sitio, etc.

// // Ahora, puedes decidir mostrar contenido específico para usuarios autenticados
// if (isset($_SESSION['usuario_id'])) {
//     // Aquí puedes incluir contenido o enlaces que solo quieres mostrar a los usuarios autenticados
//     // Por ejemplo, un enlace a su perfil, o un mensaje de bienvenida personalizado
//     // echo "<p style='font-size: 24px; font-weight: bold;'>Bienvenido, usuario autenticado. <a href='perfil.php' style='font-size: 20px;'>Ver perfil</a></p>";
//     // echo "<div style='margin-top: 20px;'>";
//     // echo "<h2 style='text-align: center;'>Bienvenidos a Elegans</h2>";
//     // echo "<p style='text-align: justify; margin: 20px;'>Elegans es más que un bar, es una experiencia única donde la música y la cultura se encuentran para crear noches inolvidables. Ofrecemos una selección cuidadosamente curada de música en vivo y DJ sets que garantizan una banda sonora perfecta para cada ocasión. Desde jazz suave hasta electrónica vibrante, nuestra programación musical está diseñada para satisfacer a todos los gustos.</p>";
//     // echo "<p style='text-align: justify; margin: 20px;'>Además de nuestra oferta musical, Elegans brinda un ambiente acogedor y sofisticado, ideal para disfrutar de cócteles artesanales y encuentros con amigos. Ya sea que busques relajarte después del trabajo o celebrar hasta altas horas de la noche, Elegans es el destino perfecto.</p>";
//     // echo "</div>";
// } else {
//     // Y aquí puedes incluir contenido o enlaces para usuarios no autenticados
//     // Por ejemplo, enlaces para iniciar sesión o registrarse
//     echo "<p>¿Eres nuevo aquí? <a href='../Views/register.php'>Regístrate ahora</a> o <a href='../Views/login.php'>inicia sesión</a>.</p>";
// }
