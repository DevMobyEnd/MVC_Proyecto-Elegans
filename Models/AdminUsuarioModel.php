<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/Config/conexion.php';
class UsuarioModel
{
    private $conexion;

    public function __construct()
    {
        $conexion = new Conexion();
        $this->conexion = $conexion->obtenerConexion();
    }

    // Método para obtener la lista de usuarios con paginación
    public function obtenerUsuarios($offset = 0, $limit = 10)
    {
        $sql = "SELECT * FROM tb_usuarios LIMIT ?, ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("ii", $offset, $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        $usuarios = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $usuarios;
    }

    // Método para contar el total de usuarios
    // Método para contar los usuarios
    public function contarUsuarios()
    {
        $sql = "SELECT COUNT(*) AS totalUsuarios FROM tb_usuarios"; // Asegúrate de que el nombre de la tabla sea correcto
        $stmt = $this->conexion->prepare($sql);

        if ($stmt === false) {
            die('Error en la preparación de la consulta: ' . $this->conexion->error);
        }

        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc(); // Cambia a fetch_assoc() para usar el alias en lugar de PDO

        $stmt->close();
        return $row['totalUsuarios'];
    }

    public function obtenerRegistrosPorMes()
    {
        $sql = "SELECT DATE_FORMAT(fecha_creacion, '%Y-%m') AS mes, COUNT(*) AS total 
                FROM tb_usuarios 
                WHERE fecha_creacion >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH) 
                GROUP BY mes 
                ORDER BY mes ASC";
        
        $stmt = $this->conexion->prepare($sql);
    
        if ($stmt === false) {
            die('Error en la preparación de la consulta: ' . $this->conexion->error);
        }
    
        $stmt->execute();
        $result = $stmt->get_result();
        $registrosPorMes = $result->fetch_all(MYSQLI_ASSOC);
    
        $stmt->close();
        return $registrosPorMes;
    }


    // Método para buscar usuarios
    // Método para buscar usuarios por criterios
    public function buscarUsuarios($criterios, $offset = 0, $limit = 10)
    {
        $sql = "SELECT * FROM tb_usuarios WHERE Gmail LIKE ? OR Apodo LIKE ? LIMIT ?, ?";
        $stmt = $this->conexion->prepare($sql);

        if ($stmt === false) {
            die('Error en la consulta: ' . $this->conexion->error);
        }

        $likeCriteria = "%" . $criterios . "%";
        $stmt->bind_param('ssii', $likeCriteria, $likeCriteria, $offset, $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        $usuarios = $result->fetch_all(MYSQLI_ASSOC);

        $stmt->close();
        return $usuarios;
    }

    // Otros métodos para buscar, editar, eliminar usuarios, etc.
}
