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

// Define el título de la página
$tituloPagina = "Inicio De Sesión - Elegans";

require_once "./layout/Seccionlogin/head.php";
require_once '../Controller/UsuarioController.php';

$usuarioController = new UsuarioController();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['btnContinuar'])) {
        $usuarioController->login();
    } elseif (isset($_POST['btningresar'])) {
        $usuarioController->login();
    }
}