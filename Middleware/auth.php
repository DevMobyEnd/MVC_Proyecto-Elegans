<?php
// Verifica si ya existe una sesión activa antes de llamar a session_start()
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

/**
 * Verifica si el usuario está autenticado. Si no lo está, redirige a la página de error de acceso no autorizado.
 */
function verificarSesion() {
    if (!isset($_SESSION['usuario_id'])) {
        // Si no hay usuario_id en la sesión, consideramos que el acceso no está autorizado
        define('ACCESO_NO_AUTORIZADO', true);
        // Asegúrate de que la ruta sea relativa al punto donde se ejecuta este script
        header("Location: ./Views/layouts/errors/accesoNoAutorizado.php");
        exit();
    }
}

/**
 * Lanza una excepción si el usuario ya está autenticado.
 * Útil para páginas como login y registro, donde el usuario autenticado no debería tener acceso.
 */
function verificarNoAutenticado() {
    if (isset($_SESSION['usuario_id'])) {
        throw new Exception("Ya estás autenticado.");
    }
}

/**
 * Redirige al usuario a la página principal si ya está autenticado.
 */
function redirigirSiAutenticado() {
    if (isset($_SESSION['usuario_id'])) {
        header("Location: ../Views/Index.php");
        exit();
    }
}

/**
 * Redirige al usuario a la página de login si no está autenticado.
 */
function redirigirSiNoAutenticado() {
    if (!isset($_SESSION['usuario_id'])) {
        header("Location: ../Views/login.php");
        exit();
    }
}