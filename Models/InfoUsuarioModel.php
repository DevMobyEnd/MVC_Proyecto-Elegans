<?php
require_once '../Config/conexion.php';

class InfoUsuarioModel // Implementar la lógica de actualización de perfil de usuario
{
    private $conexion;

    public function __construct()
    {
        $conexion = new Conexion();
        $this->conexion = $conexion->obtenerConexion();
    }

    public function obtenerInformacionUsuario($userId)
    {
        try {
            $sql = "SELECT id, Gmail, nombres, apellidos, numero_documento, 
                       Apodo, fecha_creacion, activo, foto_perfil 
                FROM tb_usuarios 
                WHERE id = ? AND activo = 1";

            $stmt = $this->conexion->prepare($sql);
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($usuario = $result->fetch_assoc()) {
                // Eliminar datos sensibles antes de almacenar en sesión
                unset($usuario['password']);
                unset($usuario['login_attempts']);
                unset($usuario['last_login_attempt']);

                // Almacenar en sesión
                if (!isset($_SESSION)) {
                    session_start();
                }
                $_SESSION['usuario_data'] = $usuario;

                return $usuario;
            }

            return false;
        } catch (Exception $e) {
            // Aquí podrías agregar un log del error
            return false;
        }
    }

    // Método para obtener la información de sesión
    public function obtenerUsuarioSesion()
    {
        if (!isset($_SESSION)) {
            session_start();
        }

        return isset($_SESSION['usuario_data']) ? $_SESSION['usuario_data'] : false;
    }

    // Método para actualizar la información en sesión
    public function actualizarSesionUsuario($userId)
    {
        return $this->obtenerInformacionUsuario($userId);
    }

    // Método para limpiar la sesión
    public function limpiarSesionUsuario()
    {
        if (!isset($_SESSION)) {
            session_start();
        }

        if (isset($_SESSION['usuario_data'])) {
            unset($_SESSION['usuario_data']);
        }
    }
}
