<?php
require_once __DIR__ . '/../Models/AdminUsuarioModel.php';

class AdminUsuarioController {
    
    private $usuarioModel;

    public function __construct() {
        $this->usuarioModel = new UsuarioModel();
    }

    // public function dashboard() {
    //     $totalUsuarios = $this->usuarioModel->contarUsuarios();
    //     $registrosPorMes = $this->usuarioModel->obtenerRegistrosPorMes();
    //     $this->cargarVista('partials/admin_dashboard', [
    //         'totalUsuarios' => $totalUsuarios,
    //         'registrosPorMes' => $registrosPorMes
    //     ]);
    // }

    // public function obtenerDatosUsuarios() {
    //     try {
    //         $registrosPorMes = $this->usuarioModel->obtenerRegistrosPorMes();
    //         header('Content-Type: application/json');
    //         echo json_encode($registrosPorMes);
    //     } catch (Exception $e) {
    //         header('HTTP/1.1 500 Internal Server Error');
    //         echo json_encode(['error' => $e->getMessage()]);
    //     }
    //     exit;
    // }

    // public function listarUsuarios($currentPage, $itemsPerPage) {
    //     $offset = ($currentPage - 1) * $itemsPerPage;
    //     $usuarios = $this->usuarioModel->obtenerUsuarios($offset, $itemsPerPage);
    //     $this->cargarVista('partials/lista_usuarios', ['usuarios' => $usuarios]);
    // }

    // private function cargarVista($vista, $datos = []) {
    //     extract($datos);
    //     include __DIR__ . "/../Views/layout/Admin/$vista.php";
    // }

    //Funciones para acomodar analisar bien 
    public function handleRequest()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action'])) {
            $this->handleGetRequest();
        } elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action'])) {
            $this->handlePostRequest();
        }
    }

    private function handleGetRequest()
    {
        header('Content-Type: application/json');
        $action = $_GET['action'];
        $response = [];

        switch ($action) {
            case 'obtenerUsuarios':
                $response = $this->obtenerUsuarios();
                break;
            case 'obtenerRegistrosPorMes':
                $response['registrosPorMes'] = $this->usuarioModel->obtenerRegistrosPorMes();
                break;
            case 'buscarUsuarios':
                $response = $this->buscarUsuarios();
                break;
            default:
                $response['error'] = 'Acción no válida';
        }

        echo json_encode($response);
        exit;
    }

    private function handlePostRequest()
    {
        header('Content-Type: application/json');
        $action = $_GET['action'];
        $response = [];

        if ($action === 'actualizarRol') {
            $response = $this->actualizarRol();
        }

        echo json_encode($response);
        exit;
    }

    private function obtenerUsuarios()
    {
        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $limit = isset($_GET['limit']) ? intval($_GET['limit']) : 10;
        $offset = ($page - 1) * $limit;
        return [
            'usuarios' => $this->usuarioModel->obtenerUsuarios($offset, $limit),
            'total' => $this->usuarioModel->contarUsuarios()
        ];
    }

    private function buscarUsuarios()
    {
        $criterios = isset($_GET['criterios']) ? $_GET['criterios'] : '';
        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $limit = isset($_GET['limit']) ? intval($_GET['limit']) : 10;
        $offset = ($page - 1) * $limit;
        return [
            'usuarios' => $this->usuarioModel->buscarUsuarios($criterios, $offset, $limit)
        ];
    }

    private function actualizarRol()
    {
        $usuarioId = isset($_POST['usuario_id']) ? intval($_POST['usuario_id']) : null;
        $nombreRol = isset($_POST['nombre_rol']) ? $_POST['nombre_rol'] : null;

        if ($usuarioId && $nombreRol) {
            $resultado = $this->usuarioModel->actualizarRolUsuario($usuarioId, $nombreRol);
            if ($resultado === true) {
                return [
                    'success' => true,
                    'message' => 'Rol actualizado correctamente'
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Error al actualizar el rol: ' . $resultado
                ];
            }
        } else {
            return [
                'success' => false,
                'message' => 'Datos insuficientes'
            ];
        }
    }
}
