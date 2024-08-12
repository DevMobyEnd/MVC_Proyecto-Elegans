<?php
require_once '../Config/conexion.php';

class InstallModel {
    private $error;
    private $conexion;

    public function __construct() {
        $this->conexion = new Conexion();
    }

    public function verificarBaseDatos($db_host, $db_name, $db_username, $db_password) {
        $conn = new mysqli($db_host, $db_username, $db_password);
        if ($conn->connect_error) {
            return false; // No se pudo conectar al servidor
        }
        $result = $conn->query("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '$db_name'");
        $exists = ($result->num_rows > 0);
        $conn->close();
        return $exists;
    }

    public function crearBaseDatos($db_host, $db_name, $db_username, $db_password) {
        $conn = new mysqli($db_host, $db_username, $db_password);
        if ($conn->connect_error) {
            $this->error = "Error de conexión: " . $conn->connect_error;
            return false;
        }
        
        $sql = "CREATE DATABASE IF NOT EXISTS $db_name";
        if ($conn->query($sql) === TRUE) {
            $conn->close();
            return true;
        } else {
            $this->error = "Error al crear la base de datos: " . $conn->error;
            $conn->close();
            return false;
        }
    }

    public function guardarConfiguracion($db_host, $db_name, $db_username, $db_password) {
        $config = "<?php\n";
        $config .= "define('DB_HOST', '$db_host');\n";
        $config .= "define('DB_NAME', '$db_name');\n";
        $config .= "define('DB_USER', '$db_username');\n";
        $config .= "define('DB_PASS', '$db_password');\n";
        
        if (file_put_contents('../Config/global.php', $config)) {
            return true;
        } else {
            $this->error = "No se pudo guardar la configuración";
            return false;
        }
    }

    public function crearTablas($db_name) {
        try {
            $this->conexion->obtenerConexion()->select_db($db_name);
            $sql = "CREATE TABLE IF NOT EXISTS usuarios (
                id INT AUTO_INCREMENT PRIMARY KEY,
                nombre VARCHAR(100) NOT NULL,
                email VARCHAR(100) UNIQUE NOT NULL,
                password VARCHAR(255) NOT NULL
            )";
            $this->conexion->obtenerConexion()->query($sql);
            return true;
        } catch (Exception $e) {
            $this->error = "Error al crear las tablas: " . $e->getMessage();
            return false;
        }
    }

    public function getError() {
        return $this->error;
    }
}