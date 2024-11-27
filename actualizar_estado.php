<?php
require_once 'Controller/UsuarioController.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $solicitudId = $_POST['id'] ?? null;
    $nuevoEstado = $_POST['estado'] ?? null;

    if ($solicitudId && $nuevoEstado) {
        $controller = new UsuarioController();
        $resultado = $controller->actualizarEstadoSolicitud($solicitudId, $nuevoEstado);

        header('Content-Type: application/json');
        echo json_encode(['success' => $resultado]);
    } else {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
    }
} else {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}