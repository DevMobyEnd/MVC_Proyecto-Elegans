    <?php
    require_once './Config/conexion.php';

    class HomeModel
    {
        private $conexion;

        public function __construct()
        {
            $conexion = new Conexion();
            $this->conexion = $conexion->obtenerConexion();
        }

        public function getUserPermissions($userId)
        {
            $permissions = [];

            if ($userId) {
                $query = "SELECT p.nombre AS permission_name 
                        FROM permisos p
                        JOIN role_permiso rp ON p.id = rp.permiso_id
                        JOIN tb_usuarios_role ur ON rp.role_id = ur.role_id
                        WHERE ur.usuario_id = ?";
                $stmt = $this->conexion->prepare($query);
                $stmt->bind_param("i", $userId);
                $stmt->execute();
                $result = $stmt->get_result();

                while ($row = $result->fetch_assoc()) {
                    $permissions[] = $row['permission_name'];
                }

                $stmt->close();
            }

            return $permissions;
        }

        public function getUserRole($userId)
        {
            $role = null;

            if ($userId) {
                $query = "SELECT r.nombre AS role_name 
                        FROM roles r
                        JOIN tb_usuarios_role ur ON r.id = ur.role_id
                        WHERE ur.usuario_id = ?";
                $stmt = $this->conexion->prepare($query);
                $stmt->bind_param("i", $userId);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($row = $result->fetch_assoc()) {
                    $role = $row['role_name'];
                }

                $stmt->close();
            }

            return $role;
        }

        public function getUserMusicRequests($userId)
        {
            $requests = [];

            if ($userId) {
                $query = "SELECT * FROM solicitudes_musica WHERE usuario_id = ?";
                $stmt = $this->conexion->prepare($query);
                $stmt->bind_param("i", $userId);
                $stmt->execute();
                $result = $stmt->get_result();

                while ($row = $result->fetch_assoc()) {
                    $requests[] = $row;
                }

                $stmt->close();
            }

            return $requests;
        }
        public function addMusicRequest($userId, $spotifyTrackId, $songName, $artistName, $imageUrl)
        {
            $query = "INSERT INTO solicitudes_musica (usuario_id, spotify_track_id, nombre_cancion, nombre_artista, imagen_url) 
              VALUES (?, ?, ?, ?, ?)";

            try {
                $stmt = $this->conexion->prepare($query);
                $stmt->bind_param("issss", $userId, $spotifyTrackId, $songName, $artistName, $imageUrl);
                $success = $stmt->execute();
                $stmt->close();
                return $success;
            } catch (mysqli_sql_exception $e) {
                error_log('Error en addMusicRequest: ' . $e->getMessage());
                throw new Exception('Error al guardar la solicitud de música.');
            }
        }

        /**
         * Obtiene el nombre de la canción, la imagen y el estado de las solicitudes de música de un usuario.
         *
         * @param int $usuarioId El ID del usuario.
         * @return array Un array con las solicitudes de música.
         */
        public function obtenerInformacionSolicitudesMusica($usuarioId)
        {
            $sql = "SELECT nombre_cancion, nombre_artista, imagen_url, estado 
            FROM solicitudes_musica 
            WHERE usuario_id = ?";
            $stmt = $this->conexion->prepare($sql);
            $stmt->bind_param("i", $usuarioId);
            $stmt->execute();
            $result = $stmt->get_result();

            $solicitudes = []; // Creamos un array vacío por defecto

            // Verificamos si la consulta tiene resultados
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $solicitudes[] = $row;
                }
            }

            return $solicitudes; // Siempre retornamos un array, aunque esté vacío
        }

        public function getUserData($userId)
        {
            $userData = null;
            if ($userId) {
                $query = "SELECT * FROM tb_usuarios WHERE id = ?";
                $stmt = $this->conexion->prepare($query);
                $stmt->bind_param("i", $userId);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($row = $result->fetch_assoc()) {
                    $userData = $row;
                }

                $stmt->close();
            }
            return $userData;
        }

        public function updateUserProfile($userId, $newData)
        {
            $query = "UPDATE tb_usuarios SET nombres = ?, apellidos = ?, apodo = ? WHERE id = ?";
            $stmt = $this->conexion->prepare($query);
            $stmt->bind_param("sssi", $newData['nombres'], $newData['apellidos'], $newData['apodo'], $userId);
            $success = $stmt->execute();
            $stmt->close();
            return $success;
        }
    }
