<?php
// Inicia la sesión si aún no ha sido iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
// Verifica si el usuario ya está autenticado y redirígelo
if (isset($_SESSION['usuario_id'])) {
    header("Location: ../Views/Index.php");
    exit();
}

require_once "./layouts/head.php";
require_once "./layouts/header.php";
require_once "./layouts/login/index2.php";

require_once '../Config/Conexion.php';
require_once '../Controller/UsuarioController.php';
require_once '../Models/UsuarioModel.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['btningresar'])) {
    $documento  = $_POST['documento']; // Corregido para eliminar el espacio extra
    $contraseña = $_POST['contraseña']; // Captura la contraseña del formulario

    $usuarioModel = new UsuarioModel(); // Crea una instancia de UsuarioModel
    $usuario = $usuarioModel->verificarUsuario2($documento); // Verifica el usuario por documento

    if ($usuario && password_verify($contraseña, $usuario['contraseña'])) {
        // Si la contraseña es correcta, establece las variables de sesión
        $_SESSION['usuario_id'] = $usuario['id'];
        // Redirige al usuario a una página segura (dashboard, por ejemplo)
        header("Location: ../Views/Index.php");
        exit();
    } else {
        // Si las credenciales son incorrectas, muestra un mensaje de error
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'El documento o la contraseña son incorrectos.',
                showConfirmButton: false,
                timer: 1500
            });
          </script>";
    }
}
?>