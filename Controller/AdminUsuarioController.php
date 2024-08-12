<?php
require_once __DIR__ . '/../Models/AdminUsuarioModel.php';

class AdminController {
    private $usuarioModel;

    public function __construct() {
        $this->usuarioModel = new UsuarioModel();
    }

    public function dashboard() {
        // Lógica para el dashboard
        $totalUsuarios = $this->usuarioModel->contarUsuarios();
        // Obtén más datos según sea necesario
        
        include __DIR__ . '/../Views/partials/admin_dashboard.php';
    }

    public function listaUsuarios() {
        $offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;
        $limit = 10;
        $usuarios = $this->usuarioModel->obtenerUsuarios($offset, $limit);
        
        include __DIR__ . '/../Views/partials/lista_usuarios.php';
    }

    public function buscarUsuarios() {
        $criterios = $_GET['criterios'] ?? '';
        $usuarios = $this->usuarioModel->buscarUsuarios($criterios);
        
        // Devolver los resultados como JSON para AJAX
        header('Content-Type: application/json');
        echo json_encode($usuarios);
    }
}