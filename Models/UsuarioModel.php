<?php
require_once '../Config/conexion.php';

class UsuarioModel {
    private $conexion;

    public function __construct() {
        $conexion = new Conexion();
        $this->conexion = $conexion->obtenerConexion();
    }

    public function verificarEmail($email) {
        $sql = "SELECT id, nombres, Gmail, password FROM tb_usuarios WHERE Gmail = ? LIMIT 1";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado && $resultado->num_rows > 0) {
            return $resultado->fetch_assoc();
        }
        return null;
    }

    public function verificarCredenciales($email, $password) {
        $usuario = $this->verificarEmail($email);
        if ($usuario && password_verify($password, $usuario['password'])) {
            return $usuario;
        }
        return false;
    }

    // Método adicional para obtener usuario por número de documento
    public function obtenerUsuarioPorDocumento($documento) {
        $sql = "SELECT id, nombres, Gmail, password FROM tb_usuarios WHERE numero_documento = ? LIMIT 1";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("s", $documento);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado && $resultado->num_rows > 0) {
            return $resultado->fetch_assoc();
        }
        return null;
    }
}