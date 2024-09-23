    <?php
    require_once './Models/HomeModel.php'; // Asegúrate de ajustar la ruta según tu estructura de carpetas



    class HomeController
    {
        private $HomeModel;

        public function __construct()
        {
            $this->HomeModel = new HomeModel();
        }

        public function index()
        {
            $fullName = (isset($_SESSION['nombres']) ? $_SESSION['nombres'] : '') . ' ' .
                (isset($_SESSION['apellidos']) ? $_SESSION['apellidos'] : '');

            $userId = $_SESSION['usuario_id'] ?? null;
            $permissions = [];
            $role = null;
            $musicRequests = [];

            if ($userId) {
                // Obtiene los permisos y el rol del usuario usando el modelo
                $permissions = $this->HomeModel->getUserPermissions($userId);
                $role = $this->HomeModel->getUserRole($userId);
                $musicRequests = $this->HomeModel->getUserMusicRequests($userId);
            }

            $userData = [
                'isLoggedIn' => isset($_SESSION['usuario_id']),
                'username' => $_SESSION['username'] ?? '',
                'profilePicture' => $this->getProfilePictureUrl($_SESSION['profile_picture'] ?? ''),
                'fullName' => $fullName,
                'apodo' => $_SESSION['apodo'] ?? 'Usuario',
                'permissions' => $permissions,
                'role' => $role, // Añadir el rol al array de datos del usuario
                'musicRequests' => $musicRequests
            ];

            // Para depuración, puedes agregar esto:
            error_log("userData en HomeController: " . print_r($userData, true));

            

            return $userData;
        }

        public function loadEditProfilePartial()
        {
            $userId = $_SESSION['usuario_id'] ?? null;
            if ($userId) {
                $userData = $this->HomeModel->getUserData($userId);
                // Asegúrate de que $userData contiene todos los datos necesarios
                require_once './Views/layout/Myperfil/partials/editarperfil.php';
            } else {
                echo json_encode(['error' => 'Usuario no autenticado']);
            }
        }


        public function updateProfile()
        {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $userId = $_SESSION['usuario_id'] ?? null;
                if (!$userId) {
                    echo json_encode(['success' => false, 'message' => 'Usuario no autenticado.']);
                    return;
                }

                // Recoger los datos del formulario
                $newData = [
                    'nombres' => $_POST['nombres'] ?? '',
                    'apellidos' => $_POST['apellidos'] ?? '',
                    'apodo' => $_POST['apodo'] ?? '',
                    // Añade aquí más campos según sea necesario
                ];

                // Validar los datos (implementa esta función según tus necesidades)
                if (!$this->validateProfileData($newData)) {
                    echo json_encode(['success' => false, 'message' => 'Datos inválidos.']);
                    return;
                }

                // Actualizar el perfil
                $success = $this->HomeModel->updateUserProfile($userId, $newData);

                if ($success) {
                    // Actualizar la sesión con los nuevos datos
                    $_SESSION['nombres'] = $newData['nombres'];
                    $_SESSION['apellidos'] = $newData['apellidos'];
                    $_SESSION['apodo'] = $newData['apodo'];
                    // Actualiza más campos de sesión según sea necesario

                    echo json_encode(['success' => true, 'message' => 'Perfil actualizado con éxito.']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Error al actualizar el perfil.']);
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
            }
        }

        private function validateProfileData($data)
        {
            // Implementa la validación según tus requisitos
            // Por ejemplo:
            return !empty($data['nombres']) && !empty($data['apellidos']);
        }



        public function addMusicRequest()
        {
            error_log('POST data: ' . print_r($_POST, true));
            error_log('Session data: ' . print_r($_SESSION, true));
            header('Content-Type: application/json');

            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                http_response_code(405);
                echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
                return;
            }

            $userId = $_SESSION['usuario_id'] ?? null;
            $spotifyTrackId = $_POST['spotify_track_id'] ?? '';
            $songName = $_POST['nombre_cancion'] ?? '';
            $artistName = $_POST['nombre_artista'] ?? '';
            $imageUrl = $_POST['imagen_url'] ?? '';

            if (!$userId) {
                echo json_encode(['success' => false, 'message' => 'Usuario no autenticado.']);
                return;
            }

            if (!$spotifyTrackId || !$songName || !$artistName) {
                echo json_encode(['success' => false, 'message' => 'Campos faltantes.']);
                return;
            }

            try {
                $success = $this->HomeModel->addMusicRequest($userId, $spotifyTrackId, $songName, $artistName, $imageUrl);
                if ($success) {
                    echo json_encode(['success' => true]);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Error al procesar la solicitud.']);
                }
            } catch (Exception $e) {
                error_log('Error en addMusicRequest: ' . $e->getMessage());
                echo json_encode(['success' => false, 'message' => 'Error interno del servidor.']);
            }
        }




        private function getProfilePictureUrl($profilePicture)
        {
            $defaultImage = 'Public/dist/img/profile.jpg';
            if ($profilePicture && $profilePicture !== $defaultImage) {
                return '/uploads/' . $profilePicture;
            }
            return $profilePicture ?: $defaultImage;
        }
    }
