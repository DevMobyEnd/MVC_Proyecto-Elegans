<?php
require_once '../Config/conexion.php';

class UsuarioModel {
    private $conexion;

    public function __construct() {
        $conexion = new Conexion();
        $this->conexion = $conexion->obtenerConexion();
    }

    public function obtenerUsuarioPorEmail($email) {
        $query = "SELECT * FROM tb_usuarios WHERE Gmail = ?";
        $stmt = $this->conexion->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        return null; // Retorna null si no se encuentra el usuario
    }

    public function verificarCredenciales($email, $password) {
        $sql = "SELECT id, nombres, password, foto_perfil FROM tb_usuarios WHERE Gmail = ? LIMIT 1";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            if (password_verify($password, $row['password'])) {
                return $row;
            }
        }
        return false;
    }

}