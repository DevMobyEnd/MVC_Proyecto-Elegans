<?php
// Asegúrate de que la ruta sea correcta según tu estructura de directorios
require_once $_SERVER['DOCUMENT_ROOT'] . '/Models/CanalDialogoModel.php';

class CanalDialogoController {
    private $canalDialogoModel;

    public function __construct() {
        $this->canalDialogoModel = new CanalDialogoModel();
    }

    public function index() {
        $usuarioActual = $_SESSION['user_id'] ?? null;

        // Obtener conversaciones privadas del usuario
        $conversacionesPrivadas = $this->canalDialogoModel->obtenerConversacionesPrivadas($usuarioActual);

        // Obtener mensajes del canal global
        $mensajesGlobales = $this->canalDialogoModel->obtenerMensajesGlobales(50);

        // Obtener lista de usuarios (modificado para usar CanalDialogoModel)
        $usuarios = $this->canalDialogoModel->obtenerUsuarios();

        // Preparar datos para la vista
        $data = [
            'usuarioActual' => $usuarioActual,
            'conversacionesPrivadas' => $conversacionesPrivadas,
            'mensajesGlobales' => $mensajesGlobales,
            'usuarios' => $usuarios
        ];

        // Incluir la vista
        require_once 'Views/layout/CanalDialogo/head.php';
   
    }

    public function enviarMensaje($emisor_id, $receptor_id, $contenido, $es_global) {
        return $this->canalDialogoModel->guardarMensaje($emisor_id, $receptor_id, $contenido, $es_global);
    }
}