<?php
require_once './Config/conexion.php';

class RegisterModel {
    private $conexion;

    public function __construct() {
        $conexion = new Conexion();
        $this->conexion = $conexion->obtenerConexion();
    }

    public function iniciarTransaccion() {
        $this->conexion->begin_transaction();
    }

    public function finalizarTransaccion() {
        $this->conexion->commit();
    }

    public function revertirTransaccion() {
        $this->conexion->rollback();
    }

    public function registrarUsuario($foto_perfil, $nombres, $apellidos, $numeroDocumento, $apodo, $correoElectronico, $password) {
        $sql = "INSERT INTO tb_usuarios (foto_perfil, nombres, apellidos, numero_documento, Apodo, Gmail, password) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("sssssss", $foto_perfil, $nombres, $apellidos, $numeroDocumento, $apodo, $correoElectronico, $password);
        if ($stmt->execute()) {
            return $this->conexion->insert_id; // Devuelve el ID del usuario insertado
        } else {
            throw new Exception("Error al registrar el usuario: " . $stmt->error);
        }
    }

    public function verificarCorreoExistente($correoElectronico) {
        $sql = "SELECT COUNT(*) FROM tb_usuarios WHERE Gmail = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("s", $correoElectronico);
        $stmt->execute();
        $result = $stmt->get_result();
        $count = $result->fetch_row()[0];
        return $count > 0;
    }

    public function verificarApodoExistente($apodo) {
        $sql = "SELECT COUNT(*) FROM tb_usuarios WHERE Apodo = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("s", $apodo);
        $stmt->execute();
        $result = $stmt->get_result();
        $count = $result->fetch_row()[0];
        return $count > 0;
    }
     // Nueva función para asignar un rol a un usuario
     public function asignarRolUsuario($usuarioId, $rolId) {
        $sql = "INSERT INTO tb_usuarios_role (usuario_id, role_id) VALUES (?, ?)";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("ii", $usuarioId, $rolId);
        if (!$stmt->execute()) {
            throw new Exception("Error al asignar rol al usuario: " . $stmt->error);
        }
    }

    // Obtener el ID del rol por nombre
    public function obtenerRolPorNombre($nombreRol) {
        $sql = "SELECT id FROM roles WHERE nombre = ?";
        $stmt = $this->conexion->prepare($sql);
        if (!$stmt) {
            throw new Exception("Error al preparar la consulta: " . $this->conexion->error);
        }
        $stmt->bind_param("s", $nombreRol);
        if (!$stmt->execute()) {
            throw new Exception("Error al ejecutar la consulta: " . $stmt->error);
        }
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['id'] ?? null;
    }
    
}