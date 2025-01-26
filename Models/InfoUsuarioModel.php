<?php

include_once(__DIR__ . '../../Config/conexion.php');

class InfoUsuarioModel
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
            if (!$stmt) {
                throw new Exception("Error al preparar la consulta: " . $this->conexion->error);
            }

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
            // Log del error
            error_log("Error en obtenerInformacionUsuario: " . $e->getMessage());
            return false;
        }
    }

    public function obtenerUsuarioSesion()
    {
        if (!isset($_SESSION)) {
            session_start();
        }

        return $_SESSION['usuario_data'] ?? false;
    }

    public function actualizarSesionUsuario($userId)
    {
        return $this->obtenerInformacionUsuario($userId);
    }

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