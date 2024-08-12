<?php
require_once '../Config/global.php';
require_once '../Models/UsuarioModel.php';

class UsuarioController
{
    private $modelo;

    public function __construct()
    {
        $this->modelo = new UsuarioModel();
    }

    public function verificarEmail($email)
    {
        $usuario = $this->modelo->obtenerUsuarioPorEmail($email);
        if ($usuario) {
            return ['success' => true, 'message' => 'Email verificado'];
        } else {
            return ['success' => false, 'message' => 'Email no encontrado'];
        }
    }

    public function login($email, $password)
    {
        $usuario = $this->modelo->obtenerUsuarioPorEmail($email);
        if ($usuario) {
            if ($usuario['login_attempts'] >= 3 && time() - strtotime($usuario['last_login_attempt']) < 900) {
                return ['success' => false, 'message' => 'Cuenta bloqueada. Intente de nuevo en 15 minutos.'];
            }

            if (password_verify($password, $usuario['password'])) {
                $this->modelo->resetearIntentos($usuario['id']);
                $token = $this->modelo->crearToken($usuario['id'], 'login');

                // Establecer variables de sesión
                $_SESSION['usuario_id'] = $usuario['id'];
                $_SESSION['apodo'] = $usuario['Apodo']; // Asumiendo que el campo se llama 'Apodo'
                $_SESSION['foto_perfil'] = $usuario['foto_perfil'];

                return ['success' => true, 'message' => 'Login exitoso', 'token' => $token];
            } else {
                $this->modelo->incrementarIntentos($usuario['id']);
                return ['success' => false, 'message' => 'Credenciales incorrectas'];
            }
        } else {
            return ['success' => false, 'message' => 'Usuario no encontrado'];
        }
    }

    public function iniciarSesionConToken($token)
    {
        $userId = $this->modelo->verificarToken($token, 'login');
        if ($userId) {
            // Iniciar sesión
            $_SESSION['usuario_id'] = $userId;
            return ['success' => true, 'message' => 'Sesión iniciada con token'];
        }
        return ['success' => false, 'message' => 'Token inválido o expirado'];
    }

    public function solicitarRecuperacionContrasena($email)
    {
        $usuario = $this->modelo->obtenerUsuarioPorEmail($email);
        if ($usuario) {
            $token = $this->modelo->crearToken($usuario['id'], 'password_reset');
            // Aquí deberías enviar un email con el link de recuperación
            // que incluya el token
            return ['success' => true, 'message' => 'Se ha enviado un email de recuperación'];
        }
        return ['success' => false, 'message' => 'Email no encontrado'];
    }
}
