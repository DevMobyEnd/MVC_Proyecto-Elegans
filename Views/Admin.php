<?php
require_once __DIR__ . "/layout/Admin/head.php";
require_once __DIR__ . "/../Controller/AdminUsuarioController.php";

$controller = new AdminController();

$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';

ob_start();
switch ($page) {
    case 'dashboard':
        $controller->dashboard();
        break;
    case 'listaUsuarios':
        $controller->listaUsuarios();
        break;
    default:
        echo '<h2>Bienvenido al panel de administración</h2>';
        break;
}
$content = ob_get_clean();

require_once __DIR__ . "/layout/Admin/head.php";