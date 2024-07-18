<?php
require_once '../Config/conexion.php';

class RegisterModel {
    private $conexion;

    public function __construct() {
        $conexion = new Conexion();
        $this->conexion = $conexion->obtenerConexion();
    }

    public function registrarUsuario($foto_perfil, $nombres, $apellidos, $numeroDocumento, $apodo, $correoElectronico, $password) {
        if (empty($foto_perfil) || empty($nombres) || empty($apellidos) || empty($numeroDocumento) || empty($apodo) || empty($correoElectronico) || empty($password)) {
            return false;
        }
    
        $passwordEncriptado = password_hash($password, PASSWORD_DEFAULT);
    
        $sql = "INSERT INTO tb_usuarios (foto_perfil, nombres, apellidos, numero_documento, Apodo, Gmail, password) VALUES (?, ?, ?, ?, ?, ?, ?)";
    
        if ($stmt = $this->conexion->prepare($sql)) {
            $stmt->bind_param("sssssss", $foto_perfil, $nombres, $apellidos, $numeroDocumento, $apodo, $correoElectronico, $passwordEncriptado);
            $resultado = $stmt->execute();
            $stmt->close();
            return $resultado;
        }
        return false;
    }

    public function obtenerUltimoIdRegistrado() {
        return $this->conexion->insert_id;
    }

    public function verificarCorreoExistente($correoElectronico) {
        return $this->verificarExistencia("Gmail", $correoElectronico);
    }

    public function verificarApodoExistente($apodo) {
        return $this->verificarExistencia("Apodo", $apodo);
    }

    private function verificarExistencia($campo, $valor) {
        $sql = "SELECT COUNT(*) FROM tb_usuarios WHERE $campo = ?";
        if ($stmt = $this->conexion->prepare($sql)) {
            $stmt->bind_param("s", $valor);
            $stmt->execute();
            $count = 0;
            $stmt->bind_result($count);
            $stmt->fetch();
            $stmt->close();
            return $count > 0;
        }
        return false;
    }
}