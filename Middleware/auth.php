<?php
// Verifica si ya existe una sesión activa antes de llamar a session_start()
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Definir constantes para las rutas
define('RUTA_LOGIN', '../Views/login.php');
define('RUTA_INICIO', '../Views/Index.php');
define('RUTA_ACCESO_NO_AUTORIZADO', './Views/layouts/errors/accesoNoAutorizado.php');

/**
 * Redirige al usuario basado en su estado de autenticación.
 */
function redirigirBasadoEnAutenticacion() {
    if (isset($_SESSION['usuario_id'])) {
        // Si el usuario está autenticado, redirige a la página de inicio.
        header("Location: " . RUTA_INICIO);
        exit();
    } else {
        // Si el usuario no está autenticado, redirige a la página de error de acceso no autorizado.
        header("Location: " . RUTA_ACCESO_NO_AUTORIZADO);
        exit();
    }
}

/**
 * Redirige al usuario a la página de login si no está autenticado.
 *//**
 * Redirige al usuario a la página de login si no está autenticado y está intentando acceder a una página protegida.
 * No redirige si el usuario está accediendo a la página de inicio.
 */
function redirigirSiNoAutenticado($paginaActual = '') {
    if (!isset($_SESSION['usuario_id'])) {
        // Lista de páginas que no requieren autenticación para ser accedidas.
        $paginasPermitidasSinAutenticacion = ['../Views/Index.php', '../Views/login.php', '../Views/register.php'];

        // Si la página actual no está en la lista de permitidas y el usuario no está autenticado, redirige a login.
        if (!in_array($paginaActual, $paginasPermitidasSinAutenticacion)) {
            header("Location: " . RUTA_LOGIN);
            exit();
        }
    }
}

/**
 * Redirige al usuario a la página principal si ya está autenticado.
 * Útil para páginas como login y registro, donde el usuario autenticado no debería tener acceso.
 */
function redirigirSiAutenticado() {
    if (isset($_SESSION['usuario_id'])) {
        header("Location: " . RUTA_INICIO);
        exit();
    }
}

