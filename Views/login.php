<?php
// Inicia la sesión si aún no ha sido iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


require_once "./layouts/head.php";
require_once "./layouts/header.php";
require_once "./layouts/login/index.php";

require_once '../Config/Conexion.php';
require_once '../Controller/UsuarioController.php';
require_once '../Models/UsuarioModel.php';

// Verifica si el formulario ha sido enviado
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['btningresar'])) {
    $email = $_POST['Gmail']; // Captura el email del formulario
    $password = $_POST['password']; // Captura la contraseña del formulario

    $usuarioModel = new UsuarioModel(); // Crea una instancia de UsuarioModel
    $usuario = $usuarioModel->verificarUsuario($email); // Verifica el usuario por email

    if ($usuario && password_verify($password, $usuario['password'])) {
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
                title: 'El correo electrónico o la contraseña son incorrectos.',
                showConfirmButton: false,
                timer: 1500
            });
          </script>";
    }
}
?>