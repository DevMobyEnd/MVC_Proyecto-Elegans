<?php
require_once '../Config/conexion.php';

class InstallModel {
    private $error;
    private $conexion;

    public function __construct() {
        $this->conexion = new Conexion();
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

    public function verificarBaseDatos($db_host, $db_name, $db_username, $db_password) {
        try {
            error_log("Intentando verificar base de datos con:");
            error_log("Host: $db_host");
            error_log("DB: $db_name");
            error_log("Usuario: $db_username");
            
            // Primero intentamos conectar sin seleccionar base de datos
            $mysqli = @new mysqli($db_host, $db_username, $db_password);
            
            if ($mysqli->connect_error) {
                error_log("Error de conexión MySQL: " . $mysqli->connect_error);
                throw new Exception("Error en la conexión: " . $mysqli->connect_error);
            }
            
            // Verificar si la base de datos existe
            $result = $mysqli->query("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '" . 
                $mysqli->real_escape_string($db_name) . "'");
                
            $exists = $result && $result->num_rows > 0;
            $mysqli->close();
            
            return $exists;
            
        } catch (Exception $e) {
            error_log("Excepción en verificarBaseDatos: " . $e->getMessage());
            error_log("Trace: " . $e->getTraceAsString());
            throw $e;
        }
    }
    
    public function crearBaseDatos($db_name) {
        try {
            $mysqli = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD);
            
            if ($mysqli->connect_error) {
                error_log("Error al conectar para crear DB: " . $mysqli->connect_error);
                throw new Exception($mysqli->connect_error);
            }
            
            $query = "CREATE DATABASE IF NOT EXISTS " . $mysqli->real_escape_string($db_name);
            $result = $mysqli->query($query);
            
            if (!$result) {
                error_log("Error al crear base de datos: " . $mysqli->error);
                throw new Exception("No se pudo crear la base de datos: " . $mysqli->error);
            }
            
            return true;
        } catch (Exception $e) {
            error_log("Error en crearBaseDatos: " . $e->getMessage());
            throw $e;
        }
    }

    public function getError() {
        return $this->error;
    }
}