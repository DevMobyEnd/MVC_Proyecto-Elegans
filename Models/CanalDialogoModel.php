<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/Config/conexion.php';

class CanalDialogoModel
{
    private $conexion;

    public function __construct()
    {
        $conexion = new Conexion();
        $this->conexion = $conexion->obtenerConexion();
    }

    public function obtenerConversacionesPrivadas($usuario_id, $otro_usuario_id = null)
    {
        if ($otro_usuario_id) {
            $sql = "SELECT m.id, m.contenido, m.fecha_envio, m.emisor_id, m.receptor_id,
                           u.Apodo as emisor_nombre, u.foto_perfil as emisor_foto
                    FROM mensajes m
                    JOIN tb_usuarios u ON m.emisor_id = u.id
                    WHERE ((m.emisor_id = ? AND m.receptor_id = ?) 
                           OR (m.emisor_id = ? AND m.receptor_id = ?))
                          AND m.es_global = 0
                    ORDER BY m.fecha_envio DESC
                    LIMIT 50";
            $stmt = $this->conexion->prepare($sql);
            $stmt->bind_param("iiii", $usuario_id, $otro_usuario_id, $otro_usuario_id, $usuario_id);
        } else {
            $sql = "SELECT DISTINCT 
                        CASE 
                            WHEN m.emisor_id = ? THEN m.receptor_id 
                            ELSE m.emisor_id 
                        END as otro_usuario_id,
                        u.Apodo, u.foto_perfil, 
                        MAX(m.fecha_envio) as ultima_fecha
                    FROM mensajes m
                    JOIN tb_usuarios u ON (
                        CASE 
                            WHEN m.emisor_id = ? THEN m.receptor_id 
                            ELSE m.emisor_id 
                        END = u.id
                    )
                    WHERE (m.emisor_id = ? OR m.receptor_id = ?) AND m.es_global = 0
                    GROUP BY otro_usuario_id, u.Apodo, u.foto_perfil
                    ORDER BY ultima_fecha DESC";
            $stmt = $this->conexion->prepare($sql);
            $stmt->bind_param("iiii", $usuario_id, $usuario_id, $usuario_id, $usuario_id);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function obtenerUsuarios()
    {
        $sql = "SELECT id, Apodo, foto_perfil FROM tb_usuarios";
        $result = $this->conexion->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function obtenerMensajesGlobales($limit = 50)
    {
        $sql = "SELECT m.*, u.Apodo as emisor_nombre, u.foto_perfil
            FROM mensajes m
            JOIN tb_usuarios u ON m.emisor_id = u.id
            WHERE m.es_global = 1
            ORDER BY m.fecha_envio ASC  
            LIMIT ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result && $result->num_rows > 0) {
            return $result->fetch_all(MYSQLI_ASSOC);
        }
        return [];  // Devuelve un array vacío si no hay resultados
    }

    public function buscarUsuarios($query)
    {
        try {
            $query = "%$query%";
            $sql = "SELECT id, Apodo FROM tb_usuarios WHERE Apodo LIKE ? LIMIT 10";
            $stmt = $this->conexion->prepare($sql);
            if ($stmt === false) {
                throw new Exception("Error preparing statement: " . $this->conexion->error);
            }
            $stmt->bind_param("s", $query);
            if (!$stmt->execute()) {
                throw new Exception("Error executing statement: " . $stmt->error);
            }
            $result = $stmt->get_result();
            return $result->fetch_all(MYSQLI_ASSOC);
        } catch (Exception $e) {
            error_log("Error in buscarUsuarios: " . $e->getMessage());
            return false;
        }
    }
    

    public function obtenerMensajesRecientes($limit = 50)
    {
        $sql = "SELECT m.*, u.Apodo as emisor_nombre 
                FROM mensajes m 
                JOIN tb_usuarios u ON m.emisor_id = u.id 
                ORDER BY m.fecha_envio ASC 
                LIMIT ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $limit);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function guardarMensaje($emisor_id, $receptor_id, $contenido, $es_global)
    {
        $sql = "INSERT INTO mensajes (emisor_id, receptor_id, contenido, es_global) 
                VALUES (?, ?, ?, ?)";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("iisi", $emisor_id, $receptor_id, $contenido, $es_global);
        return $stmt->execute();
    }

    public function obtenerLikes($mensaje_id)
    {
        return $this->obtenerContadorReacciones($mensaje_id, 'like');
    }

    public function obtenerDislikes($mensaje_id)
    {
        return $this->obtenerContadorReacciones($mensaje_id, 'dislike');
    }

    private function obtenerContadorReacciones($mensaje_id, $tipo_reaccion)
    {
        $sql = "SELECT COUNT(*) as count FROM reacciones_mensajes WHERE mensaje_id = ? AND tipo_reaccion = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("is", $mensaje_id, $tipo_reaccion);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            return $row['count'];
        }
        return 0;
    }


    public function agregarReaccion($mensaje_id, $usuario_id, $tipo_reaccion)
    {
        $sql = "INSERT INTO reacciones_mensajes (mensaje_id, usuario_id, tipo_reaccion)
                VALUES (?, ?, ?)
                ON DUPLICATE KEY UPDATE tipo_reaccion = VALUES(tipo_reaccion)";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("iis", $mensaje_id, $usuario_id, $tipo_reaccion);

        if ($stmt->execute()) {
            $this->actualizarContadoresReacciones($mensaje_id);
            return true;
        }
        return false;
    }

    public function quitarReaccion($mensaje_id, $usuario_id)
    {
        $sql = "DELETE FROM reacciones_mensajes WHERE mensaje_id = ? AND usuario_id = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("ii", $mensaje_id, $usuario_id);

        if ($stmt->execute()) {
            $this->actualizarContadoresReacciones($mensaje_id);
            return true;
        }
        return false;
    }

    private function actualizarContadoresReacciones($mensaje_id)
    {
        $sql = "UPDATE mensajes m
                SET likes = (SELECT COUNT(*) FROM reacciones_mensajes WHERE mensaje_id = m.id AND tipo_reaccion = 'like'),
                    dislikes = (SELECT COUNT(*) FROM reacciones_mensajes WHERE mensaje_id = m.id AND tipo_reaccion = 'dislike')
                WHERE m.id = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $mensaje_id);
        $stmt->execute();
    }

    public function obtenerReaccionUsuario($mensaje_id, $usuario_id)
    {
        $sql = "SELECT tipo_reaccion FROM reacciones_mensajes WHERE mensaje_id = ? AND usuario_id = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("ii", $mensaje_id, $usuario_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            return $row['tipo_reaccion'];
        }
        return null;
    }
}
