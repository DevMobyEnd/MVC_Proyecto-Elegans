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
}