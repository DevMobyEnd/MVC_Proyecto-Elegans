<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/Models/CanalDialogoModel.php';

$model = new CanalDialogoModel();

// Determinar la ruta solicitada
$request_uri = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];

// Configurar encabezados CORS
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Manejar las diferentes rutas
switch (true) {
    case strpos($request_uri, '/api/users.php') !== false:
        if ($method === 'GET') {
            $users = $model->obtenerUsuarios();
            echo json_encode($users);
        }
        break;

    case strpos($request_uri, '/api/search-users.php') !== false:
        if ($method === 'GET' && isset($_GET['query'])) {
            $query = $_GET['query'];
            $users = $model->buscarUsuarios($query);
            echo json_encode($users);
        }
        break;

        case strpos($request_uri, '/api/messages.php') !== false:
            if ($method === 'GET') {
                $chatType = $_GET['chatType'] ?? 'global';
                $userId = $_GET['userId'] ?? null;
        
                if ($chatType === 'global') {
                    $messages = $model->obtenerMensajesGlobales(50);
                } elseif ($chatType === 'private' && $userId) {
                    $messages = $model->obtenerConversacionesPrivadas($_SESSION['user_id']);
                } else {
                    $messages = [];
                }
                echo json_encode($messages);
            }
            break;

    case strpos($request_uri, '/api/send-message.php') !== false:
        if ($method === 'POST') {
            $data = json_decode(file_get_contents('php://input'), true);
            $emisor_id = $_SESSION['user_id'] ?? 1; // Asume que el ID del usuario está en la sesión
            $receptor_id = $data['receptor_id'] ?? null;
            $contenido = $data['content'] ?? '';
            $es_global = $receptor_id === null ? 1 : 0;
            $result = $model->guardarMensaje($emisor_id, $receptor_id, $contenido, $es_global);
            echo json_encode(['success' => $result]);
        }
        break;

    case strpos($request_uri, '/api/react-message.php') !== false:
        if ($method === 'POST') {
            $data = json_decode(file_get_contents('php://input'), true);
            $mensaje_id = $data['mensaje_id'] ?? null;
            $usuario_id = $data['usuario_id'] ?? null;
            $tipo_reaccion = $data['tipo_reaccion'] ?? null;

            if (!$mensaje_id || !$usuario_id || !$tipo_reaccion) {
                echo json_encode(['error' => 'Faltan datos necesarios']);
                break;
            }

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
        }
        break;

    default:
        http_response_code(404);
        echo json_encode(['error' => 'Not Found']);
        break;
}