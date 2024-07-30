<?php
require_once '../Config/global.php';
require_once '../Models/UsuarioModel.php';

class UsuarioController {
    private $modelo;

    public function __construct() {
        $this->modelo = new UsuarioModel();
    }

    public function verificarEmail($email) {
        $usuario = $this->modelo->obtenerUsuarioPorEmail($email);
        if ($usuario) {
            return ['success' => true, 'message' => 'Email verificado'];
        } else {
            return ['success' => false, 'message' => 'Email no encontrado'];
        }
    }

    public function login($email, $password) {
        $usuario = $this->modelo->verificarCredenciales($email, $password);
        if ($usuario) {
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['profile_picture'] = $usuario['foto_perfil']; // Guardar la ruta de la foto de perfil en la sesión
            return ['success' => true, 'message' => 'Login exitoso'];
        } else {
            return ['success' => false, 'message' => 'Credenciales incorrectas'];
        }
    }
}
