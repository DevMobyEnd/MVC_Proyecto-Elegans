<?php
require_once __DIR__ . '/../Models/CanalDialogoModel.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
$mensaje_id = $data['mensaje_id'] ?? null;
$usuario_id = $data['usuario_id'] ?? null;
$tipo_reaccion = $data['tipo_reaccion'] ?? null;

if (!$mensaje_id || !$usuario_id || !$tipo_reaccion) {
    echo json_encode(['error' => 'Faltan datos necesarios']);
    exit;
}

$model = new CanalDialogoModel();

if ($tipo_reaccion === 'none') {
    $resultado = $model->quitarReaccion($mensaje_id, $usuario_id);
} else {
    $resultado = $model->agregarReaccion($mensaje_id, $usuario_id, $tipo_reaccion);
}

if ($resultado) {
    $nuevasReacciones = [
        'likes' => $model->obtenerLikes($mensaje_id),
        'dislikes' => $model->obtenerDislikes($mensaje_id)
    ];
    echo json_encode(['success' => true, 'reacciones' => $nuevasReacciones]);
} else {
    echo json_encode(['error' => 'Error al procesar la reacción']);
}

