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

    public function verificarUsuario2($Documento) {
        $Documento = $this->conexion->real_escape_string($Documento);
        // Corrige el nombre de la tabla y el campo
        $sql = "SELECT id, nombres, password FROM tb_usuarioos WHERE documento = '$Documento' LIMIT 1;";
        $resultado = $this->conexion->query($sql);
        if ($resultado && $resultado->num_rows > 0) {
            return $resultado->fetch_assoc(); // Retorna el usuario si se encuentra
        }
        return false; // Retorna falso si no encuentra el usuario
    }
    public function retornadorDato($des, $valor) {
        // Inicializar las variables para evitar errores de "undefined variable"
        $campo = '';
        $tabla = '';
        $busqueda = '';
    
        // Definir los valores de las variables según el descriptor
        if ($des == 1) {
            $campo = "nombres";
            $tabla = "tb_usuarioos";
            $busqueda = "documento";
        }
         // Definir los valores de las variables según el descriptor
         if ($des == 2) {
            $campo = "foto";
            $tabla = "tb_usuarioos";
            $busqueda = "documento";
        }
        // Asegúrate de agregar más condiciones si necesitas manejar más casos
    
        // Construye la consulta SQL
        $sql = "SELECT $campo FROM $tabla WHERE $busqueda = ?";
        if ($stmt = $this->conexion->prepare($sql)) {
            // Vincula el valor a la sentencia preparada como un string
            $stmt->bind_param("s", $valor);
    
            // Ejecuta la sentencia
            $stmt->execute();
    
            // Obtiene el resultado
            $resultado = $stmt->get_result();
    
            // Verifica si se encontró algún resultado
            if ($resultado->num_rows > 0) {
                // Obtiene el dato
                $row = $resultado->fetch_assoc();
                $dato = $row[$campo];
    
                // Cierra la sentencia
                $stmt->close();
    
                // Retorna el dato encontrado
                return $dato;
            } else {
                // Cierra la sentencia si no hay resultados
                $stmt->close();
                return null; // O maneja este caso según sea necesario
            }
        } else {
            // Manejo del error en caso de que la sentencia no se pueda preparar
            return null; // O maneja este caso según sea necesario
        }
    }
}

