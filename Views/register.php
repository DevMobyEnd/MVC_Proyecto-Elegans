<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


//  require_once '../Middleware/auth.php'; // Ajusta la ruta según sea necesario
 

// Define el título de la página
$tituloPagina = "Registro - Elegans";

require_once '../Config/Conexion.php';
require_once '../Controller/registerController.php';
require_once '../Models/registerModel.php';

// Incluye los layouts después de iniciar sesión y de los require_once necesarios

require_once "./layout/Seccionregisters/head.php";

// Verifica si el formulario ha sido enviado
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['btnregistrar'])) {
    // Captura los datos del formulario
    $nombres = $_POST['Nombres'];
    $apellidos = $_POST['Apellidos'];
    $numeroDocumento = $_POST['NumeroDocumento'];
    $usuario = $_POST['Usuario'];
    $email = $_POST['Gmail'];
    $password = $_POST['password'];

    // Crea una instancia de tu modelo de usuario
    $modeloRegistro = new registerModel();

    // Intenta registrar al usuario
    $resultado = $modeloRegistro->registrarUsuario($nombres, $apellidos, $numeroDocumento, $usuario, $email, $password);

    if ($resultado) {
        // Si el registro es exitoso, muestra un mensaje y redirige
        echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'Registro exitoso. Por favor, inicia sesión.',
                    showConfirmButton: false,
                    timer: 1500
                });
                setTimeout(function(){
                    window.location.href = '../Views/login.php';
                }, 1500);
              </script>";
    } else {
        // Si hay un error en el registro, muestra un mensaje
        echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Hubo un error en el registro. Por favor, inténtalo de nuevo.',
                    showConfirmButton: false,
                    timer: 1500
                });
              </script>";
    }
}

// Define la variable $seccion basada en el archivo PHP actual
$seccion = basename($_SERVER['PHP_SELF']);

// if ($seccion == "register.php") {
//     echo '<link rel="stylesheet" href="../Public/dist/css/style.css">';
// } else {
//     echo '<link rel="stylesheet" href="../Public/dist/css/Estilo.css">';
// }
?>