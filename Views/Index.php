<?php
require_once "./layouts/head.php";
require_once "./layouts/header.php";
require_once '../Middleware/auth.php'; // Este archivo puede contener funciones útiles, pero no forzaremos la autenticación aquí.

ini_set('display_errors', 1);
error_reporting(E_ALL);

// Inicia la sesión si aún no ha sido iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Aquí puedes incluir contenido que sea visible para todos los usuarios
// Por ejemplo, un mensaje de bienvenida, información sobre tu sitio, etc.

// Ahora, puedes decidir mostrar contenido específico para usuarios autenticados
if (isset($_SESSION['usuario_id'])) {
    // Aquí puedes incluir contenido o enlaces que solo quieres mostrar a los usuarios autenticados
    // Por ejemplo, un enlace a su perfil, o un mensaje de bienvenida personalizado
    echo "<p>Bienvenido, usuario autenticado. <a href='perfil.php'>Ver perfil</a></p>";
} else {
    // Y aquí puedes incluir contenido o enlaces para usuarios no autenticados
    // Por ejemplo, enlaces para iniciar sesión o registrarse
    echo "<p>¿Eres nuevo aquí? <a href='../Views/register.php'>Regístrate ahora</a> o <a href='../Views/login.php'>inicia sesión</a>.</p>";
}

// El resto de tu página de inicio sigue aquí...