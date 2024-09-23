<?php
require_once __DIR__ . '/../Models/CanalDialogoModel.php';

header('Content-Type: application/json');
$data = json_decode(file_get_contents('php://input'), true);

$emisor_id = $data['emisor_id'] ?? null;
$receptor_id = $data['receptor_id'] ?? null;
$contenido = $data['contenido'] ?? null;
$es_global = $data['es_global'] ?? 0;

// Validación de entrada
if (!$emisor_id || !$contenido) {
    echo json_encode(['success' => false, 'error' => 'Emisor o contenido faltante']);
    exit;
}

$model = new CanalDialogoModel();
$resultado = $model->guardarMensaje($emisor_id, $receptor_id, $contenido, $es_global);

if ($resultado) {
    echo json_encode(['success' => true, 'message' => 'Mensaje guardado correctamente']);
} else {
    echo json_encode(['success' => false, 'error' => 'Error al guardar el mensaje']);
}
