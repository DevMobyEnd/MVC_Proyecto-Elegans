<?php
require_once "./layouts/head.php";
require_once "./layouts/header.php";
require_once '../Middleware/auth.php'; // Este archivo puede contener funciones útiles, pero no forzaremos la autenticación aquí.
require_once '../Views/layouts/main-menu.php';
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Inicia la sesión si aún no ha sido iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verificar si el usuario está autenticado
if (!isset($_SESSION['usuario_id'])) {
    // Si no hay una sesión de usuario, imprime un script de JavaScript para alertar y luego ofrecer opciones
    echo "<script>
   Swal.fire({
    icon: 'error',
    title: 'Ver más en Elegans.',
    text: '¿Deseas iniciar sesión o registrarte?',
    showCancelButton: true,
    confirmButtonText: 'Iniciar sesión',
    cancelButtonText: 'Registrarse',
    showCloseButton: true, // Habilita el botón de cierre
    reverseButtons: true
}).then((result) => {
    if (result.isConfirmed) {
        window.location = '../Views/login.php';
    } else if (result.dismiss === Swal.DismissReason.cancel) {
        window.location = '../Views/register.php';
    }
    // No es necesario manejar el caso 'isDenied' ya que hemos eliminado el botón de denegación
    // El botón de cierre permite al usuario cerrar la alerta sin realizar ninguna acción
});
  </script>";
    exit();
} // Falta esta llave para cerrar correctamente el bloque if

// Aquí puedes incluir contenido que sea visible para todos los usuarios
// Por ejemplo, un mensaje de bienvenida, información sobre tu sitio, etc.

// Ahora, puedes decidir mostrar contenido específico para usuarios autenticados
if (isset($_SESSION['usuario_id'])) {
    // Aquí puedes incluir contenido o enlaces que solo quieres mostrar a los usuarios autenticados
    // Por ejemplo, un enlace a su perfil, o un mensaje de bienvenida personalizado
    // echo "<p style='font-size: 24px; font-weight: bold;'>Bienvenido, usuario autenticado. <a href='perfil.php' style='font-size: 20px;'>Ver perfil</a></p>";
    // echo "<div style='margin-top: 20px;'>";
    // echo "<h2 style='text-align: center;'>Bienvenidos a Elegans</h2>";
    // echo "<p style='text-align: justify; margin: 20px;'>Elegans es más que un bar, es una experiencia única donde la música y la cultura se encuentran para crear noches inolvidables. Ofrecemos una selección cuidadosamente curada de música en vivo y DJ sets que garantizan una banda sonora perfecta para cada ocasión. Desde jazz suave hasta electrónica vibrante, nuestra programación musical está diseñada para satisfacer a todos los gustos.</p>";
    // echo "<p style='text-align: justify; margin: 20px;'>Además de nuestra oferta musical, Elegans brinda un ambiente acogedor y sofisticado, ideal para disfrutar de cócteles artesanales y encuentros con amigos. Ya sea que busques relajarte después del trabajo o celebrar hasta altas horas de la noche, Elegans es el destino perfecto.</p>";
    // echo "</div>";
} else {
    // Y aquí puedes incluir contenido o enlaces para usuarios no autenticados
    // Por ejemplo, enlaces para iniciar sesión o registrarse
    echo "<p>¿Eres nuevo aquí? <a href='../Views/register.php'>Regístrate ahora</a> o <a href='../Views/login.php'>inicia sesión</a>.</p>";
}

// El resto de tu página de inicio sigue aquí...
?>