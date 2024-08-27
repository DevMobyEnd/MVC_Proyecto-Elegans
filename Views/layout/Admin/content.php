<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/Controller/AdminUsuarioController.php';
$controller = new AdminUsuarioController();

$controller = new AdminUsuarioController();
$page = isset($_GET['page']) ? $_GET['page'] : 'N';

switch ($page) {
    case 'dashboard':
        $controller->dashboard();
        break;
    case 'listaUsuarios':
        $controller->listarUsuarios(isset($_GET['currentPage']) ? $_GET['currentPage'] : 1, 7);
        break;
    case 'admin_dashboard':
        $controller->obtenerDatosUsuarios();
        break;    
    case 'RolesPermisos':
        break;
}