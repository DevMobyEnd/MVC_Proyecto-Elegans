<?php
// Incluye el archivo de configuración y otros requerimientos
require_once "./layout/Admin/head.php";
require_once "./Models/AdminUsuarioModel.php"; // Incluye el modelo para gestionar usuarios

$usuarioModel = new UsuarioModel();
$offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0; // Obtiene el offset de la URL o 0 por defecto
$limit = 10; // Define el límite de usuarios por página
$usuarios = $usuarioModel->obtenerUsuarios($offset, $limit);


// Define el título de la página
$tituloPagina = "Panel de Administración - Elegans";

// Obtiene la página solicitada
$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';

// Inicia el almacenamiento en buffer de salida
ob_start();
switch ($page) {
    case 'dashboard':
        include 'partials/admin_dashboard.php';
        break;
    case 'listaUsuarios':
        include 'partials/lista_usuarios.php';
        break;
    // Otros casos para diferentes secciones
    default:
        echo '<h2>Bienvenido al panel de administración</h2>';
        break;
}
$content = ob_get_clean(); // Captura el contenido generado y limpia el buffer

// Incluye el layout principal
require_once "./layout/Admin/layout.php";
