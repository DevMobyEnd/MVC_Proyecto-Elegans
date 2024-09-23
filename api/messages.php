<?php
require_once __DIR__ . '/../Models/CanalDialogoModel.php';
header('Content-Type: application/json');

$model = new CanalDialogoModel();

$chatType = $_GET['chatType'] ?? 'global';
$userId = $_GET['userId'] ?? null;

if ($chatType === 'global') {
    $mensajes = $model->obtenerMensajesGlobales();
} elseif ($chatType === 'private' && $userId) {
    $mensajes = $model->obtenerConversacionesPrivadas($userId);
} else {
    echo json_encode(['error' => 'Tipo de chat no válido o usuario no especificado']);
    exit;
}

// Después de obtener los mensajes
if ($mensajes) {
    echo json_encode($mensajes);
} else {
    echo json_encode([]); // Asegúrate de que esta línea esté siempre activa
}

// Agrega esto para depurar
error_log(print_r($mensajes, true));



