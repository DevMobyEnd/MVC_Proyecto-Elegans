<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/Models/CanalDialogoModel.php';

header('Content-Type: application/json');

if (isset($_GET['query'])) {
    $query = $_GET['query'];

    $model = new CanalDialogoModel();
    $results = $model->buscarUsuarios($query);

    echo json_encode($results);
} else {
    http_response_code(400);
    echo json_encode(['error' => 'No se proporcionó una consulta de búsqueda']);
}