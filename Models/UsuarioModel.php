<?php
require_once './Config/conexion.php';

class UsuarioModel {
    private $conexion;

    public function __construct() {
        $conexion = new Conexion();
        $this->conexion = $conexion->obtenerConexion();
    }

    public function obtenerUsuarioPorEmail($email) {
        $sql = "SELECT id, nombres, apellidos, Gmail, password, foto_perfil, Apodo FROM tb_usuarios WHERE Gmail = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            return $row;
        }
        return null;
    }

    public function verificarCredenciales($email, $password) {
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

    public function crearToken($userId, $type) {
        $token = bin2hex(random_bytes(32));
        $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));
        
        $sql = "INSERT INTO user_tokens (user_id, token, type, expires_at) VALUES (?, ?, ?, ?)";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("isss", $userId, $token, $type, $expires);
        $stmt->execute();
        
        return $token;
    }
    
    public function verificarToken($token, $type) {
        $sql = "SELECT user_id FROM user_tokens WHERE token = ? AND type = ? AND expires_at > NOW()";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("ss", $token, $type);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($row = $result->fetch_assoc()) {
            return $row['user_id'];
        }
        return false;
    }

    public function incrementarIntentos($userId) {
        $sql = "UPDATE tb_usuarios SET login_attempts = login_attempts + 1, last_login_attempt = CURRENT_TIMESTAMP WHERE id = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
    }
    
    public function resetearIntentos($userId) {
        $sql = "UPDATE tb_usuarios SET login_attempts = 0 WHERE id = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
    }

    // Método actualizado para obtener el nombre completo del usuario
    public function obtenerNombreCompleto($userId) {
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

    public function obtenerUsuarioPorId($id) {
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

    public function actualizarUsuario($idUsuario, $datosUsuario) {
        $sql = "UPDATE tb_usuarios SET 
                nombres = ?, 
                apellidos = ?, 
                numero_documento = ?, 
                Apodo = ?, 
                Gmail = ?";
        
        $params = [
            $datosUsuario['nombres'],
            $datosUsuario['apellidos'],
            $datosUsuario['numero_documento'],
            $datosUsuario['apodo'],
            $datosUsuario['correo_electronico']
        ];
        $types = "sssss";
    
        if (isset($datosUsuario['password'])) {
            $sql .= ", password = ?";
            $params[] = $datosUsuario['password'];
            $types .= "s";
        }
    
        if (isset($datosUsuario['foto_perfil'])) {
            $sql .= ", foto_perfil = ?";
            $params[] = $datosUsuario['foto_perfil'];
            $types .= "s";
        }
    
        $sql .= " WHERE id = ?";
        $params[] = $idUsuario;
        $types .= "i";
    
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param($types, ...$params);
        
        return $stmt->execute();
    }
    
}

