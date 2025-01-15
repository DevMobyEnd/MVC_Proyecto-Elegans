<?php
require_once '../Config/conexion.php';

class UpdateProfileModel // Implementar la lógica de actualización de perfil de usuario
{
    private $conexion;

    public function __construct()
    {
        $conexion = new Conexion();
        $this->conexion = $conexion->obtenerConexion();
    }

    public function iniciarTransaccion()
    {
        $this->conexion->begin_transaction();
    }

    public function finalizarTransaccion()
    {
        $this->conexion->commit();
    }

    public function revertirTransaccion()
    {
        $this->conexion->rollback();
    }

    public function actualizarPerfilCompleto($userId, $datosUsuario)
    {
        try {
            $this->iniciarTransaccion();

            // Crear backup antes de actualizar
            $this->crearBackupUsuario($userId);

            $sql = "UPDATE tb_usuarios SET 
                    nombres = ?, 
                    apellidos = ?, 
                    numero_documento = ?, 
                    Apodo = ?, 
                    Gmail = ?, 
                    foto_perfil = ?";

            $params = [
                $datosUsuario['nombres'],
                $datosUsuario['apellidos'],
                $datosUsuario['numero_documento'],
                $datosUsuario['Apodo'],
                $datosUsuario['Gmail'],
                $datosUsuario['foto_perfil']
            ];
            $types = "ssssss";

            if (isset($datosUsuario['password'])) {
                $sql .= ", password = ?";
                $params[] = $datosUsuario['password'];
                $types .= "s";
            }

            $sql .= " WHERE id = ?";
            $params[] = $userId;
            $types .= "i";

            $stmt = $this->conexion->prepare($sql);
            $stmt->bind_param($types, ...$params);

            $result = $stmt->execute();

            if ($result) {
                $this->finalizarTransaccion();
                return true;
            } else {
                throw new Exception("Error al actualizar el perfil");
            }
        } catch (Exception $e) {
            $this->revertirTransaccion();
            // Aquí podrías agregar un log del error
            return "Error: " . $e->getMessage();
        }
    }

    public function revertirUltimaActualizacion($userId)
    {
        try {
            $this->iniciarTransaccion();

            // Obtener el último backup del usuario
            $sql = "SELECT * FROM usuarios_backup WHERE usuario_id = ? ORDER BY fecha_backup DESC LIMIT 1";
            $stmt = $this->conexion->prepare($sql);
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $result = $stmt->get_result();
            $backup = $result->fetch_assoc();

            if (!$backup) {
                throw new Exception("No se encontró un backup para revertir");
            }

            // Actualizar el usuario con los datos del backup
            $sql = "UPDATE tb_usuarios SET 
                    nombres = ?, 
                    apellidos = ?, 
                    numero_documento = ?, 
                    Apodo = ?, 
                    Gmail = ?, 
                    foto_perfil = ?,
                    password = ?
                    WHERE id = ?";

            $stmt = $this->conexion->prepare($sql);
            $stmt->bind_param(
                "sssssssi",
                $backup['nombres'],
                $backup['apellidos'],
                $backup['numero_documento'],
                $backup['Apodo'],
                $backup['Gmail'],
                $backup['foto_perfil'],
                $backup['password'],
                $userId
            );

            $result = $stmt->execute();

            if ($result) {
                // Eliminar el backup utilizado
                $sql = "DELETE FROM usuarios_backup WHERE id = ?";
                $stmt = $this->conexion->prepare($sql);
                $stmt->bind_param("i", $backup['id']);
                $stmt->execute();

                $this->finalizarTransaccion();
                return true;
            } else {
                throw new Exception("Error al revertir la actualización");
            }
        } catch (Exception $e) {
            $this->revertirTransaccion();
            // Aquí podrías agregar un log del error
            return "Error: " . $e->getMessage();
        }
    }

   
    
    //  backup antes de actualización
    public function crearBackupUsuario($userId)
    {
        // Primero, verifica si ya existe un backup para este usuario
        $checkQuery = "SELECT id FROM tb_usuarios_backup WHERE id = ?";
        $checkStmt = $this->conexion->prepare($checkQuery);
        $checkStmt->bind_param("i", $userId);
        $checkStmt->execute();
        $result = $checkStmt->get_result();

        if ($result->num_rows > 0) {
            // Si ya existe un backup, actualízalo en lugar de insertar uno nuevo
            $query = "UPDATE tb_usuarios_backup SET nombres = ?, apellidos = ?, numero_documento = ?, apodo = ?, Gmail = ?, foto_perfil = ? WHERE id = ?";
        } else {
            // Si no existe, inserta un nuevo registro
            $query = "INSERT INTO tb_usuarios_backup (id, nombres, apellidos, numero_documento, apodo, Gmail, foto_perfil) SELECT id, nombres, apellidos, numero_documento, apodo, Gmail, foto_perfil FROM tb_usuarios WHERE id = ?";
        }

        $stmt = $this->conexion->prepare($query);

        if ($result->num_rows > 0) {
            $usuario = $this->obtenerUsuarioPorId($userId);
            $stmt->bind_param("ssssssi", $usuario['nombres'], $usuario['apellidos'], $usuario['numero_documento'], $usuario['Apodo'], $usuario['Gmail'], $usuario['foto_perfil'], $userId);
        } else {
            $stmt->bind_param("i", $userId);
        }

        return $stmt->execute();
    }

    public function obtenerUsuarioPorId($userId)
    {
        $query = "SELECT id, nombres, apellidos, numero_documento, Apodo, Gmail, foto_perfil FROM tb_usuarios WHERE id = ?";
        $stmt = $this->conexion->prepare($query);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
    public function crearTablaBackupSiNoExiste()
    {
        $sql = "CREATE TABLE IF NOT EXISTS tb_usuarios_backup LIKE tb_usuarios";
        return $this->conexion->query($sql);
    }

    public function verificarConexion()
    {
        return $this->conexion !== null;
    }

    // Implementar rate limiting
    // private function checkRateLimit($userId)
    // {
    //     $key = "profile_update_" . $userId;
    //     $attempts = cache()->get($key, 0);
    //     if ($attempts > 5) {
    //         throw new Exception("Demasiados intentos. Intente más tarde.");
    //     }
    //     cache()->increment($key);
    //     cache()->expire($key, 3600);
    // }

    // Implementar caché para datos frecuentes
    // public function obtenerUsuarioCacheado($userId)
    // {
    //     $key = "user_" . $userId;
    //     if ($cached = cache()->get($key)) {
    //         return $cached;
    //     }
    //     $usuario = $this->obtenerUsuarioPorId($userId);
    //     cache()->set($key, $usuario, 3600);
    //     return $usuario;
    // }

    // // Agregar logging detallado
    // private function logError($action, $userId, $error)
    // {
    //     $this->logger->error("Error en $action para usuario $userId: " . $error->getMessage(), [
    //         'trace' => $error->getTraceAsString(),
    //         'input' => $_POST,
    //         'timestamp' => date('Y-m-d H:i:s')
    //     ]);
    // }

    // Verificar si existe el correo (excepto para el usuario actual)
    public function verificarCorreoExistente($correo, $userId = null)
    {
        $sql = "SELECT id FROM tb_usuarios WHERE Gmail = ? AND id != ? AND estado != 'eliminado'";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("si", $correo, $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows > 0;
    }

    // Verificar si existe el apodo (excepto para el usuario actual)
    public function verificarApodoExistente($apodo, $userId = null)
    {
        $sql = "SELECT id FROM tb_usuarios WHERE Apodo = ? AND id != ? AND estado != 'eliminado'";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("si", $apodo, $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows > 0;
    }
}
