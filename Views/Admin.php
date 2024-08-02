<?php
// Incluye el archivo de configuración y otros requerimientos
require_once __DIR__ . "/layout/Admin/head.php";
require_once __DIR__ . "/../Models/AdminUsuarioModel.php"; // Ajusta la ruta según tu estructura

$usuarioModel = new UsuarioModel();
$offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;
$limit = 10;
$usuarios = $usuarioModel->obtenerUsuarios($offset, $limit);

$tituloPagina = "Panel de Administración - Elegans";

$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';

ob_start();
switch ($page) {
    case 'dashboard':
        include __DIR__ . '/partials/admin_dashboard.php';
        break;
    case 'listaUsuarios':
        include __DIR__ . '/partials/lista_usuarios.php';
        break;
    default:
        echo '<h2>Bienvenido al panel de administración</h2>';
        break;
}
$content = ob_get_clean();

require_once __DIR__ . "/layout/Admin/head.php"; // Ajusta la ruta según tu estructura

