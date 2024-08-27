    <?php
    require_once './Config/global.php';
    require_once './Models/UsuarioModel.php';

    class UsuarioController
    {
        private $modelo;

        public function __construct()
        {
            $this->modelo = new UsuarioModel();
        }

        public function actualizarPerfil($postData = null)
        {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Use $postData if provided, otherwise use $_POST
                $data = $postData ?? $_POST;

                // Verificar el token CSRF
                // if (!$this->verificarCSRFToken($data['csrf_token'])) {
                //     return ['success' => false, 'message' => 'Error de token CSRF'];
                // }

                // Recoger los datos del formulario
                $datosUsuario = [
                    'nombres' => $data['Nombres'],
                    'apellidos' => $data['Apellidos'],
                    'numero_documento' => $data['NumerodeDocumento'],
                    'apodo' => $data['Apodo'],
                    'correo_electronico' => $data['CorreoElectronico'],
                    // Otros campos...
                ];

                // Si se proporcionó una nueva contraseña, hashearla
                if (!empty($data['password'])) {
                    $datosUsuario['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
                }

                // Manejar la imagen de perfil si se subió una nueva
                if (!empty($data['croppedImageData'])) {
                    $nombreArchivo = $this->procesarImagenPerfil($data['croppedImageData']);
                    if ($nombreArchivo) {
                        $datosUsuario['foto_perfil'] = $nombreArchivo;
                    }
                }

                // Actualizar el usuario en la base de datos
                $resultado = $this->modelo->actualizarUsuario($_SESSION['usuario_id'], $datosUsuario);

                if ($resultado) {
                    // Actualización exitosa
                    $_SESSION['nombre_completo'] = $datosUsuario['nombres'] . ' ' . $datosUsuario['apellidos'];
                    $_SESSION['apodo'] = $datosUsuario['apodo'];
                    // ... actualizar otros datos de sesión según sea necesario
                    return ['success' => true, 'message' => 'Perfil actualizado con éxito'];
                } else {
                    // Error en la actualización
                    return ['success' => false, 'message' => 'Hubo un problema al actualizar el perfil'];
                }
            }

            // Si no es una solicitud POST, devolver un error
            return ['success' => false, 'message' => 'Método de solicitud inválido'];
        }

        public function procesarImagenPerfil($croppedImageData)
        {
            // Decodificar la imagen base64
            $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $croppedImageData));

            // Generar un nombre único para el archivo
            $fileName = uniqid() . '.png';

            // Definir la ruta donde se guardará la imagen
            $uploadPath = './uploads/' . $fileName;

            // Guardar la imagen en el servidor
            if (file_put_contents($uploadPath, $imageData)) {
                return $fileName; // Devuelve el nombre del archivo para guardarlo en la base de datos
            }

            return null; // Retorna null si hubo un error al guardar la imagen
        }

        public function verificarEmail($email)
        {
            $usuario = $this->modelo->obtenerUsuarioPorEmail($email);
            if ($usuario) {
                return ['success' => true, 'message' => 'Email verificado'];
            } else {
                return ['success' => false, 'message' => 'Email no encontrado'];
            }
        }

        public function obtenerDatosUsuarioActual($usuarioId)
        {
            $usuario = $this->modelo->obtenerUsuarioPorId($usuarioId);
            if ($usuario) {
                return [
                    'id' => $usuario['id'],
                    'nombres' => $usuario['nombres'],
                    'apellidos' => $usuario['apellidos'],
                    'email' => $usuario['Gmail'],
                    'foto_perfil' => $usuario['foto_perfil'],
                    'apodo' => $usuario['Apodo']
                ];
            }
            return null;
        }

        public function login($email, $password)
        {
            $usuario = $this->modelo->obtenerUsuarioPorEmail($email);
            if ($usuario) {
                // Verifica si las claves existen antes de acceder a ellas
                $loginAttempts = isset($usuario['login_attempts']) ? $usuario['login_attempts'] : 0;
                $lastLoginAttempt = isset($usuario['last_login_attempt']) ? strtotime($usuario['last_login_attempt']) : 0;

                if ($loginAttempts >= 3 && time() - $lastLoginAttempt < 900) {
                    return ['success' => false, 'message' => 'Cuenta bloqueada. Intente de nuevo en 15 minutos.'];
                }

                if (password_verify($password, $usuario['password'])) {
                    $this->modelo->resetearIntentos($usuario['id']);
                    $token = $this->modelo->crearToken($usuario['id'], 'login');

                    // Establecer variables de sesión
                    $_SESSION['usuario_id'] = $usuario['id'];
                    $_SESSION['apodo'] = $usuario['Apodo'] ?? '';  // Use null coalescing operator
                    $_SESSION['foto_perfil'] = $usuario['foto_perfil'] ?? '';
                    $_SESSION['nombre_completo'] = $usuario['nombres'] . ' ' . $usuario['apellidos'];
                    // Depuración
                    error_log("Sesión iniciada: " . print_r($_SESSION, true));

                    return ['success' => true, 'message' => 'Login exitoso', 'token' => $token];
                } else {
                    $this->modelo->incrementarIntentos($usuario['id']);
                    return ['success' => false, 'message' => 'Credenciales incorrectas'];
                }
            } else {
                return ['success' => false, 'message' => 'Usuario no encontrado'];
            }
        }

        public function iniciarSesionConToken($token)
        {
            $userId = $this->modelo->verificarToken($token, 'login');
            if ($userId) {
                // Iniciar sesión
                $_SESSION['usuario_id'] = $userId;
                return ['success' => true, 'message' => 'Sesión iniciada con token'];
            }
            return ['success' => false, 'message' => 'Token inválido o expirado'];
        }

        public function solicitarRecuperacionContrasena($email)
        {
            $usuario = $this->modelo->obtenerUsuarioPorEmail($email);
            if ($usuario) {
                $token = $this->modelo->crearToken($usuario['id'], 'password_reset');
                // Aquí deberías enviar un email con el link de recuperación
                // que incluya el token
                return ['success' => true, 'message' => 'Se ha enviado un email de recuperación'];
            }
            return ['success' => false, 'message' => 'Email no encontrado'];
        }
    }
