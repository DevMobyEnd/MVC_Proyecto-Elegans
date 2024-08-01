<?php
require_once '../Config/conexion.php';

class UsuarioModel
{
    private $conexion;

    public function __construct()
    {
        $conexion = new Conexion();
        $this->conexion = $conexion->obtenerConexion();
    }

    // Método para obtener la lista de usuarios
    // UsuarioModel.php
    public function obtenerUsuarios($offset = 0, $limit = 10) {
        $sql = "SELECT * FROM tb_usuarios LIMIT ?, ?";
        $stmt = $this->conexion->prepare($sql);
    
        if ($stmt === false) {
            die('Error en la consulta: ' . $this->conexion->error);
        }
    
        $stmt->bind_param("ii", $offset, $limit);
        $stmt->execute();
    
        $result = $stmt->get_result();
        $usuarios = $result->fetch_all(MYSQLI_ASSOC);
    
        $stmt->close();
    
        return $usuarios;
    }
    
    

    public function contarUsuarios() {
        $total = 0; // Inicializar la variable $total
    
        $sql = "SELECT COUNT(*) as total FROM tb_usuarios";
        $stmt = $this->conexion->prepare($sql);
    
        if ($stmt === false) {
            die('Error en la consulta: ' . $this->conexion->error);
        }
    
        $stmt->execute();
        $stmt->bind_result($total); // Liga el resultado a la variable $total
    
        if ($stmt->fetch()) {
            // $total ya está asignado con el valor correcto
        } else {
            $total = 0; // Asignar un valor por defecto si no se pudo obtener el resultado
        }
    
        $stmt->close();
    
        return $total;
    }
    
    
    

    public function buscarUsuarios($criterios) {
        $sql = "SELECT * FROM tb_usuarios WHERE Gmail LIKE ? OR Apodo LIKE ?";
        $stmt = $this->conexion->prepare($sql);
    
        if ($stmt === false) {
            die('Error en la consulta: ' . $this->conexion->error);
        }
    
        $likeCriteria = "%" . $criterios . "%";
        $stmt->bind_param('ss', $likeCriteria, $likeCriteria); // 'ss' indica dos cadenas de texto
    
        $stmt->execute();
        $result = $stmt->get_result(); // Obtiene el resultado
        $usuarios = $result->fetch_all(MYSQLI_ASSOC); // Obtiene todos los resultados como un array asociativo
    
        $stmt->close();
        return $usuarios;
    }
    


    // Otros métodos para buscar, editar, eliminar usuarios, etc.
}
