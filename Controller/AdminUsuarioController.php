<?php
require_once __DIR__ . '/../Models/AdminUsuarioModel.php';

class AdminUsuarioController {
    
    private $usuarioModel;

    public function __construct() {
        $this->usuarioModel = new UsuarioModel();
    }

    public function dashboard() {
        $totalUsuarios = $this->usuarioModel->contarUsuarios();
        $registrosPorMes = $this->usuarioModel->obtenerRegistrosPorMes();
        $this->cargarVista('partials/admin_dashboard', [
            'totalUsuarios' => $totalUsuarios,
            'registrosPorMes' => $registrosPorMes
        ]);
    }

    public function obtenerDatosUsuarios() {
        try {
            $registrosPorMes = $this->usuarioModel->obtenerRegistrosPorMes();
            header('Content-Type: application/json');
            echo json_encode($registrosPorMes);
        } catch (Exception $e) {
            header('HTTP/1.1 500 Internal Server Error');
            echo json_encode(['error' => $e->getMessage()]);
        }
        exit;
    }

    public function listarUsuarios($currentPage, $itemsPerPage) {
        $offset = ($currentPage - 1) * $itemsPerPage;
        $usuarios = $this->usuarioModel->obtenerUsuarios($offset, $itemsPerPage);
        $this->cargarVista('partials/lista_usuarios', ['usuarios' => $usuarios]);
    }

    private function cargarVista($vista, $datos = []) {
        extract($datos);
        include __DIR__ . "/../Views/layout/Admin/$vista.php";
    }
}
