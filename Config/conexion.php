<?php
require_once "global.php";

class Conexion {
    private  $conexion_db;

    public function __construct() {
        $this->conexion_db = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
        
        if ($this->conexion_db->connect_errno) {
            echo "Error al conectar con la base de datos: " . $this->conexion_db->connect_error;
            exit();
        }
    
        $this->conexion_db->set_charset(DB_ENCODE);
    }

    // Método para obtener la instancia de mysqli
    public function obtenerConexion() {
        return $this->conexion_db;
    }

    public function ejecutarConsultaUnica($sql) {
        // Ejecuta la consulta SQL proporcionada
        $resultado = $this->conexion_db->query($sql);
        
        // Verifica si la consulta fue exitosa y si hay al menos una fila de resultado
        if ($resultado && $resultado->num_rows > 0) {
            $row = $resultado->fetch_assoc(); // Obtiene el resultado como un array asociativo
            return $row;
        } else {
            return null; // Retorna null si no hay resultados o si la consulta falló
        }
    }

    public function crearBaseDatos($dbname) {
        $sql = "CREATE DATABASE IF NOT EXISTS `$dbname`";
        return $this->conexion_db->query($sql);
    }

    public function verificarBaseDatos($dbname) {
        $sql = "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '$dbname'";
        $result = $this->conexion_db->query($sql);
        return $result->num_rows > 0;
    }

    public function conectarSinBaseDatos($host, $username, $password) {
        $this->conexion_db = new mysqli($host, $username, $password);
        return !$this->conexion_db->connect_errno;
    }

    // Cierra la conexión a la base de datos
    public function cerrarConexion() {
        $this->conexion_db->close();
    }

    // Aquí puedes agregar más métodos según sea necesario para tu aplicación
}