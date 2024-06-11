<?php
require_once '../Config/conexion.php'; // Asegúrate de ajustar la ruta según tu estructura de proyecto

class UsuarioModel {
    private $conexion;

    public function __construct() {
        $conexion = new Conexion(); // Asume que tienes una clase Conexion que establece la conexión a la base de datos
        $this->conexion = $conexion->obtenerConexion(); // Método para obtener la instancia de mysqli
    }

    public function verificarUsuario($email) {
        $email = $this->conexion->real_escape_string($email);
        $sql = "SELECT id, Gmail, password FROM tb_usuarios WHERE Gmail = '$email' LIMIT 1;";
        $resultado = $this->conexion->query($sql);

        if ($resultado && $resultado->num_rows > 0) {
            return $resultado->fetch_assoc(); // Retorna el usuario si se encuentra
        }
        return false; // Retorna falso si no encuentra el usuario
    }
}