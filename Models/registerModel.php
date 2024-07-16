<?php
require_once '../Config/conexion.php';

class RegisterModel {
    private $conexion;

    public function __construct() {
        $conexion = new Conexion();
        $this->conexion = $conexion->obtenerConexion();
    }

    public function registrarUsuario($nombres, $apellidos, $numeroDocumento, $apodo, $correoElectronico, $password) {
        if (empty($nombres) || empty($apellidos) || empty($numeroDocumento) || empty($apodo) || empty($correoElectronico) || empty($password)) {
            return false;
        }
    
        $passwordEncriptado = password_hash($password, PASSWORD_DEFAULT);
    
        $sql = "INSERT INTO tb_usuarios (nombres, apellidos, numero_documento, Apodo, Gmail, password) VALUES (?, ?, ?, ?, ?, ?)";
    
        if ($stmt = $this->conexion->prepare($sql)) {
            $stmt->bind_param("ssssss", $nombres, $apellidos, $numeroDocumento, $apodo, $correoElectronico, $passwordEncriptado);
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
        $sql = "SELECT COUNT(*) FROM tb_usuarios WHERE Gmail = ?";
        if ($stmt = $this->conexion->prepare($sql)) {
            $stmt->bind_param("s", $correoElectronico);
            $stmt->execute();
            $count = 0; // Declare and initialize $count
            $stmt->bind_result($count);
            $stmt->fetch();
            $stmt->close();
            return $count > 0;
        }
        return false;
    }

    public function verificarApodoExistente($apodo) {
        $sql = "SELECT COUNT(*) FROM tb_usuarios WHERE Apodo = ?";
        if ($stmt = $this->conexion->prepare($sql)) {
            $stmt->bind_param("s", $apodo);
            $stmt->execute();
            $count = 0; // Declare and initialize $count
            $stmt->bind_result($count);
            $stmt->fetch();
            $stmt->close();
            return $count > 0;
        }
        return false;
    }
}