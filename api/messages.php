<?php
require_once __DIR__ . '/../Models/CanalDialogoModel.php';
header('Content-Type: application/json');

$model = new CanalDialogoModel();

$chatType = $_GET['chatType'] ?? '';
$userId = $_GET['userId'] ?? '';

if (!$chatType || ($chatType === 'private' && !$userId)) {
    echo json_encode(['error' => 'Tipo de chat no válido o usuario no especificado']);
    exit;
}

if ($chatType === 'global') {
    $messages = $model->obtenerMensajesGlobales();
} elseif ($chatType === 'private' && $userId) {
    $messages = $model->obtenerConversacionesPrivadas($userId);
} else {
    $messages = [];
}

// Asegúrate de devolver un array de mensajes, incluso si está vacío
echo json_encode(['messages' => $messages]);

// Agrega esto para depurar
error_log(print_r($messages, true));