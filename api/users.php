<?php
require_once __DIR__ . '/../Models/CanalDialogoModel.php';

header('Content-Type: application/json');

$model = new CanalDialogoModel();
$usuarios = $model->obtenerUsuarios();

if ($usuarios) {
    echo json_encode($usuarios);
} else {
    echo json_encode(['error' => 'No se encontraron usuarios']);
}
