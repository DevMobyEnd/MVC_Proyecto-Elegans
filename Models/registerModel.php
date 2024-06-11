<?php
require_once '../Config/conexion.php'; // Asegúrate de ajustar la ruta según tu estructura de proyecto

class registerModel {
    private $conexion;

    public function __construct() {
        $conexion = new Conexion(); // Asume que tienes una clase Conexion que establece la conexión a la base de datos
        $this->conexion = $conexion->obtenerConexion(); // Método para obtener la instancia de mysqli
    }

    public function registrarUsuario($Nombres, $Apellidos, $NumerodeDocumento, $Usuario, $CorreoElectronico, $password) {
        // Validación de datos de entrada
        if (empty($Nombres) || empty($Apellidos) || empty($NumerodeDocumento) || empty($Usuario) || empty($CorreoElectronico) || empty($password)) {
            return false; // Considera lanzar una excepción o manejar de otra manera
        }

        // Aquí podrías incluir la lógica para encriptar la contraseña antes de guardarla
        $passwordEncriptado = password_hash($password, PASSWORD_DEFAULT);

        // Preparar la consulta SQL para insertar el nuevo usuario
        $sql = "INSERT INTO tb_usuarios (nombres, apellidos, numero_documento, usuario, Gmail, password) VALUES (?, ?, ?, ?, ?, ?)";

        if ($stmt = $this->conexion->prepare($sql)) {
            // Vincular los parámetros
            $stmt->bind_param("ssssss", $Nombres, $Apellidos, $NumerodeDocumento, $Usuario, $CorreoElectronico, $passwordEncriptado);

            // Ejecutar la sentencia
            $resultado = $stmt->execute();

            // Cerrar la sentencia
            $stmt->close();

            // Retornar true si se ejecuta correctamente, de lo contrario false
            return $resultado;
        } else {
            // Si ocurre un error al preparar la sentencia, considera manejar el error de manera más específica
            return false;
        }
    }

    // Método para obtener el último ID registrado
    public function obtenerUltimoIdRegistrado() {
        return $this->conexion->insert_id;
    }
}