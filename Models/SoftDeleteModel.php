<?php
require_once '../Config/conexion.php';

class SoftDeleteModel  // Implementar la lógica de borrado lógico en lugar de físico para la tabla 'tb_usuarios'
{
    private $conexion;

    public function __construct()
    {
        $conexion = new Conexion();
        $this->conexion = $conexion->obtenerConexion();
    }

   // En tu SoftDeleteModel, añade una transacción:
public function desactivarUsuario($userId, $datos)
{
    try {
        $this->conexion->begin_transaction();
        
        $sql = "UPDATE tb_usuarios SET
                estado = 'inactivo',
                fecha_desactivacion = ?,
                motivo_desactivacion = ?,
                detalle_desactivacion = ?,
                fecha_reactivacion_limite = DATE_ADD(NOW(), INTERVAL 30 DAY)
                WHERE id = ?";
        
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param(
            "sssi",
            $datos['fecha_desactivacion'],
            $datos['motivo'],
            $datos['motivo_detalle'],
            $userId
        );
        
        $result = $stmt->execute();
        
        if ($result) {
            $this->conexion->commit();
            return true;
        } else {
            $this->conexion->rollback();
            return false;
        }
    } catch (Exception $e) {
        $this->conexion->rollback();
        throw $e;
    }
}

    // Obtener usuarios inactivos para proceso periódico
    public function obtenerUsuariosInactivosPorTiempo($diasInactividad)
    {
        $sql = "SELECT id, nombres, apellidos, Gmail, Apodo, foto_perfil 
                FROM tb_usuarios 
                WHERE estado = 'inactivo' 
                AND fecha_desactivacion <= DATE_SUB(NOW(), INTERVAL ? DAY)
                AND datos_anonimizados = 0";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $diasInactividad);
        $stmt->execute();
        $result = $stmt->get_result();

        $usuarios = [];
        while ($row = $result->fetch_assoc()) {
            $usuarios[] = $row;
        }
        return $usuarios;
    }

    // Anonimizar usuario
    public function anonimizarUsuario($userId, $datosAnonimos)
    {
        $sql = "UPDATE tb_usuarios SET 
                nombres = ?,
                apellidos = ?,
                Gmail = ?,
                Apodo = ?,
                foto_perfil = ?,
                datos_anonimizados = 1,
                fecha_anonimizacion = NOW()
                WHERE id = ?";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param(
            "sssssi",
            $datosAnonimos['nombres'],
            $datosAnonimos['apellidos'],
            $datosAnonimos['correoElectronico'],
            $datosAnonimos['apodo'],
            $datosAnonimos['foto_perfil'],
            $userId
        );

        return $stmt->execute();
    }

    // Método para reactivación durante período de gracia
    public function reactivarUsuario($userId)
    {
        $sql = "UPDATE tb_usuarios SET 
            estado = 'activo',
            fecha_desactivacion = NULL,
            motivo_desactivacion = NULL
            WHERE id = ? 
            AND fecha_reactivacion_limite > NOW()";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $userId);
        return $stmt->execute();
    }
    
}
