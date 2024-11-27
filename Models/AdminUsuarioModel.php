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

    public function obtenerUsuarios($offset = 0, $limit = 10)
    {
        $sql = "SELECT u.*, r.nombre AS rol
                FROM tb_usuarios u
                LEFT JOIN tb_usuarios_role ur ON u.id = ur.usuario_id
                LEFT JOIN roles r ON ur.role_id = r.id
                ORDER BY r.nombre IS NULL, r.nombre ASC
                LIMIT ?, ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("ii", $offset, $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        $usuarios = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $usuarios;
    }

    public function obtenerRoleIdPorNombre($nombreRol)
    {
        $sql = "SELECT id FROM roles WHERE nombre = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("s", $nombreRol);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row ? $row['id'] : null;
    }

    public function actualizarRolUsuario($usuarioId, $nombreRol)
    {
        $roleId = $this->obtenerRoleIdPorNombre($nombreRol);
        if ($roleId === null) {
            return false;
        }
        $sqlCheck = "SELECT COUNT(*) AS total FROM tb_usuarios_role WHERE usuario_id = ?";
        $stmtCheck = $this->conexion->prepare($sqlCheck);
        $stmtCheck->bind_param("i", $usuarioId);
        $stmtCheck->execute();
        $resultCheck = $stmtCheck->get_result();
        $rowCheck = $resultCheck->fetch_assoc();
        $exists = $rowCheck['total'];

        if ($exists > 0) {
            $sqlUpdate = "UPDATE tb_usuarios_role SET role_id = ? WHERE usuario_id = ?";
            $stmtUpdate = $this->conexion->prepare($sqlUpdate);
            $stmtUpdate->bind_param("ii", $roleId, $usuarioId);
            return $stmtUpdate->execute();
        } else {
            $sqlInsert = "INSERT INTO tb_usuarios_role (usuario_id, role_id) VALUES (?, ?)";
            $stmtInsert = $this->conexion->prepare($sqlInsert);
            $stmtInsert->bind_param("ii", $usuarioId, $roleId);
            return $stmtInsert->execute();
        }
    }


    public function contarUsuarios()
    {
        $sql = "SELECT COUNT(*) AS totalUsuarios FROM tb_usuarios";
        $stmt = $this->conexion->prepare($sql);
        if ($stmt === false) {
            die('Error en la preparación de la consulta: ' . $this->conexion->error);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
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
}
