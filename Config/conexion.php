<?php
$rutaGlobal = __DIR__ . "/global.php";

if (!file_exists($rutaGlobal)) {
    die("Error: El archivo global.php no existe en la ruta especificada.");
}

require_once $rutaGlobal;

class Conexion {
    private $conexion_db;
    
    public function __construct() {
        $this->conectar();
    }

    private function conectar() {
        try {
            if (!defined('DB_HOST') || !defined('DB_USERNAME') || !defined('DB_PASSWORD') || !defined('DB_NAME') || !defined('DB_ENCODE')) {
                throw new Exception("Error: Algunas constantes de configuración no están definidas.");
            }

            $this->conexion_db = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
            
            if ($this->conexion_db->connect_errno) {
                throw new Exception("Error de conexión MySQL: " . $this->conexion_db->connect_error);
            }
            
            if (!$this->conexion_db->set_charset(DB_ENCODE)) {
                throw new Exception("Error al establecer charset: " . $this->conexion_db->error);
            }
            
        } catch (Exception $e) {
            die("Error en la conexión: " . $e->getMessage());
        }
    }

    public function verificarConexion($db_host, $db_username, $db_password) {
        try {
            $conexion = new mysqli($db_host, $db_username, $db_password);
            if ($conexion->connect_error) {
                throw new Exception("Error de conexión: " . $conexion->connect_error);
            }
            $conexion->close();
            return true;
        } catch (Exception $e) {
            error_log("Error en verificarConexion: " . $e->getMessage());
            return false;
        }
    }

    public function obtenerConexion() {
        return $this->conexion_db;
    }

    public function ejecutarConsultaUnica($sql) {
        $resultado = $this->conexion_db->query($sql);
        
        if ($resultado && $resultado->num_rows > 0) {
            return $resultado->fetch_assoc();
        }
        return null;
    }

    public function crearBaseDatos($dbname) {
        $dbname = $this->conexion_db->real_escape_string($dbname);
        $sql = "CREATE DATABASE IF NOT EXISTS `$dbname`";
        return $this->conexion_db->query($sql);
    }

    public function verificarBaseDatos($dbname) {
        $dbname = $this->conexion_db->real_escape_string($dbname);
        $sql = "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '$dbname'";
        $result = $this->conexion_db->query($sql);
        return $result && $result->num_rows > 0;
    }

    public function conectarSinBaseDatos($host, $username, $password) {
        $this->conexion_db = new mysqli($host, $username, $password);
        return !$this->conexion_db->connect_errno;
    }

    public function cerrarConexion() {
        if ($this->conexion_db) {
            $this->conexion_db->close();
        }
    }
}