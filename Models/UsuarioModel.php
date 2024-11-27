<?php
require_once './Config/conexion.php';

class UsuarioModel
{
    private $conexion;

    public function __construct()
    {
        $conexion = new Conexion();
        $this->conexion = $conexion->obtenerConexion();
    }

    public function obtenerUsuarioPorEmail($email)
    {
        $sql = "SELECT id, nombres, apellidos, Gmail, password, foto_perfil, Apodo FROM tb_usuarios WHERE Gmail = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result && $row = $result->fetch_assoc()) {
            return $row;
        }
        return null;
    }

    public function actualizarContraseña($userId, $nuevaContrasena)
    {
        $hashedPassword = password_hash($nuevaContrasena, PASSWORD_DEFAULT);
        $sql = "UPDATE tb_usuarios SET password = ? WHERE id = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param('si', $hashedPassword, $userId);
        return $stmt->execute();
    }



    public function verificarCredenciales($email, $password)
    {
        $sql = "SELECT id, nombres, apellidos, password, foto_perfil, Apodo FROM tb_usuarios WHERE Gmail = ? LIMIT 1";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            if (password_verify($password, $row['password'])) {
                return $row;
            }
        }
        return false;
    }

    public function crearToken($userId, $tipo = 'login')
    {
        $token = bin2hex(random_bytes(16));  // Token seguro
        $expiresAt = (new DateTime())->modify('+1 day')->format('Y-m-d H:i:s');  // Expiración en 24 horas

        // Elimina tokens viejos
        $sqlDelete = "DELETE FROM user_tokens WHERE user_id = ? AND type = ?";
        $stmtDelete = $this->conexion->prepare($sqlDelete);
        $stmtDelete->bind_param('is', $userId, $tipo);
        $stmtDelete->execute();

        // Inserta el nuevo token
        $sql = "INSERT INTO user_tokens (user_id, token, type, expires_at) VALUES (?, ?, ?, ?)";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param('isss', $userId, $token, $tipo, $expiresAt);
        if ($stmt->execute()) {
            return $token;
        }
        return false;
    }


    public function verificarToken($token, $tipo = 'login')
    {
        $sql = "SELECT user_id FROM user_tokens WHERE token = ? AND type = ? AND expires_at > NOW() LIMIT 1";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param('ss', $token, $tipo);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result && $row = $result->fetch_assoc()) {
            return $row['user_id'];
        }
        return false;
    }


    public function incrementarIntentos($userId)
    {
        $sql = "UPDATE tb_usuarios SET login_attempts = login_attempts + 1, last_login_attempt = CURRENT_TIMESTAMP WHERE id = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
    }

    public function resetearIntentos($userId)
    {
        $sql = "UPDATE tb_usuarios SET login_attempts = 0 WHERE id = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
    }

    // Método actualizado para obtener el nombre completo del usuario
    public function obtenerNombreCompleto($userId)
    {
        $sql = "SELECT CONCAT(nombres, ' ', apellidos) AS nombre_completo FROM tb_usuarios WHERE id = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            return $row['nombre_completo'];
        }
        return 'Nombre no disponible';
    }

    public function obtenerUsuarioPorId($id)
    {
        $sql = "SELECT id, nombres, apellidos, Gmail, foto_perfil, Apodo FROM tb_usuarios WHERE id = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            return $row;
        }
        return null;
    }

    public function obtenerPermisosUsuario($usuarioId)
    {
        $sql = "SELECT p.nombre 
                FROM permisos p
                JOIN role_permiso rp ON p.id = rp.permiso_id
                JOIN tb_usuarios_role ur ON rp.role_id = ur.role_id
                WHERE ur.usuario_id = ?";
        $stmt = $this->conexion->prepare($sql);
        if ($stmt === false) {
            error_log("Error preparando la consulta: " . $this->conexion->error);
            return [];
        }
        $stmt->bind_param("i", $usuarioId);
        $stmt->execute();
        $result = $stmt->get_result();
        $permisos = [];
        while ($row = $result->fetch_assoc()) {
            $permisos[] = $row['nombre'];
        }
        if (empty($permisos)) {
            // Si no hay permisos, asignar un rol por defecto
            $permisos[] = 'usuario natural';
        }
        return $permisos;
    }

    public function obtenerRolUsuario($usuarioId)
    {
        // Consulta SQL para obtener el rol del usuario
        $sql = "SELECT r.nombre AS rol 
            FROM roles r
            JOIN tb_usuarios_role ur ON r.id = ur.role_id
            WHERE ur.usuario_id = ?";

        $stmt = $this->conexion->prepare($sql);
        if ($stmt === false) {
            error_log("Error preparando la consulta: " . $this->conexion->error);
            return null;
        }

        $stmt->bind_param("i", $usuarioId);
        $stmt->execute();
        $result = $stmt->get_result();

        // Verifica si hay un resultado
        if ($row = $result->fetch_assoc()) {
            return $row['rol'];
        } else {
            // Si no se encuentra el rol, puedes asignar un rol por defecto o manejarlo según tu lógica
            return 'rol_no_disponible';
        }
    }






    public function actualizarUsuario($userId, $datosUsuario)
    {
        $sql = "UPDATE tb_usuarios SET nombres = ?, apellidos = ?, numero_documento = ?, apodo = ?, correo_electronico = ?, foto_perfil = ? WHERE id = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param(
            'ssssssi',
            $datosUsuario['nombres'],
            $datosUsuario['apellidos'],
            $datosUsuario['numero_documento'],
            $datosUsuario['apodo'],
            $datosUsuario['correo_electronico'],
            $datosUsuario['foto_perfil'],
            $userId
        );
        return $stmt->execute();
    }

    // Métodos existentes

    public function obtenerInformacionSolicitudesMusica($usuarioId)
    {
        // Registrar el ID del usuario en el log para depuración
        error_log("ID del usuario en modelo: " . $usuarioId);

        $sql = "SELECT nombre_cancion, nombre_artista, imagen_url, estado 
                 FROM solicitudes_musica 
                 WHERE usuario_id = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $usuarioId);
        $stmt->execute();
        $result = $stmt->get_result();

        // Verificar si hay resultados
        if ($result->num_rows > 0) {
            $solicitudes = [];
            while ($row = $result->fetch_assoc()) {
                $solicitudes[] = $row;
            }
            return $solicitudes; // Devuelve las solicitudes
        } else {
            return []; // Retorna un array vacío si no hay solicitudes
        }
    }




    public function obtenerSolicitudesConUsuarios()
{
    // Consulta SQL para obtener las solicitudes de música pendientes junto con el apodo y la imagen de perfil del usuario
    $sql = "SELECT sm.id, sm.nombre_cancion, sm.spotify_track_id, sm.imagen_url AS imagen_cancion, 
               sm.estado, sm.fecha_solicitud, 
               u.Apodo, u.foto_perfil 
        FROM solicitudes_musica sm
        JOIN tb_usuarios u ON sm.usuario_id = u.id
        WHERE sm.estado = 'pendiente'
        ORDER BY sm.fecha_solicitud DESC"; // Ordena por la fecha de solicitud, de más reciente a más antigua

    $stmt = $this->conexion->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();

    // Verificar si hay resultados
    if ($result->num_rows > 0) {
        $solicitudes = [];
        while ($row = $result->fetch_assoc()) {
            $solicitudes[] = $row;
        }
        return $solicitudes; // Devuelve las solicitudes pendientes con la información del usuario
    } else {
        return []; // Retorna un array vacío si no hay solicitudes pendientes
    }
}

    public function actualizarEstadoSolicitudMusica($solicitudId, $nuevoEstado)
{
    // Verificar que el nuevo estado sea válido
    $estadosValidos = ['pendiente', 'aceptada', 'rechazada'];
    if (!in_array($nuevoEstado, $estadosValidos)) {
        return false; // Estado no válido
    }

    $sql = "UPDATE solicitudes_musica SET estado = ? WHERE id = ?";
    $stmt = $this->conexion->prepare($sql);

    if ($stmt === false) {
        error_log("Error preparando la consulta: " . $this->conexion->error);
        return false;
    }

    $stmt->bind_param("si", $nuevoEstado, $solicitudId);
    $resultado = $stmt->execute();

    if ($resultado) {
        return true; // Actualización exitosa
    } else {
        error_log("Error actualizando el estado de la solicitud: " . $stmt->error);
        return false;
    }
}

    public function obtenerSolicitudesAprobadas($usuarioId)
    {
        $sql = "SELECT spotify_track_id FROM solicitudes_musica WHERE usuario_id = ? AND estado = 'aprobada'";
        $stmt = $this->conexion->prepare($sql);

        if ($stmt === false) {
            error_log("Error preparando la consulta: " . $this->conexion->error);
            return [];
        }

        $stmt->bind_param("i", $usuarioId);
        $stmt->execute();
        $result = $stmt->get_result();

        $solicitudesAprobadas = [];
        while ($row = $result->fetch_assoc()) {
            $solicitudesAprobadas[] = $row['spotify_track_id'];
        }

        return $solicitudesAprobadas;
    }
}
