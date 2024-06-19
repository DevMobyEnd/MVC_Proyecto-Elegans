<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once 'Config/Conexion.php';
require_once 'Controller/registerController.php';
require_once 'Models/registerModel.php';

// Verifica si el formulario ha sido enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Captura los datos del formulario
    $nombres = $_POST['nombre']; // Asegúrate de que los nombres de los campos coincidan con los de tu formulario
    $documento = $_POST['documento'];
    $fechaNacimiento = $_POST['fechaNacimiento'];
    // Para el archivo, necesitarás manejar la carga del archivo aquí
    $clave = $_POST['clave'];

    // Aquí podrías manejar la carga de la foto si es necesario

    // Crea una instancia de tu modelo de registro
    $modeloRegistro = new registerModel();

    // Intenta registrar al usuario (ajusta los parámetros según tu método registrarUsuario)
    $resultado = $modeloRegistro->registrarUsuarioc($nombre, $documento, $fechaNacimiento, $rutaFoto, $clave);

    if ($resultado) {
        // Si el registro es exitoso, redirige o muestra un mensaje
        header("Location: Views/login.php"); // Ajusta la ruta según sea necesario
        exit();
    } else {
        // Si hay un error en el registro, muestra un mensaje o maneja el error
        echo "Hubo un error en el registro. Por favor, inténtalo de nuevo.";
    }
} else {
    // Redirige al formulario de registro si se accede a este archivo directamente sin enviar el formulario
    header("Location: Views/register.php"); // Ajusta la ruta según sea necesario
    exit();
}

// Define la variable $seccion basada en el archivo PHP actual
$seccion = basename($_SERVER['PHP_SELF']);

if ($seccion == "register.php") {
    echo '<link rel="stylesheet" href="../Public/dist/css/style.css">';
} else {
    echo '<link rel="stylesheet" href="../Public/dist/css/Estilo.css">';
}
?>