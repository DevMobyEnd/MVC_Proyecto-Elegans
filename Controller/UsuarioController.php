    <?php
    require_once './Config/global.php';
    require_once './Models/UsuarioModel.php';
    require_once './Helpers/SpotifyHelper.php';

    //Para las conexión de el Envio de Gmail para cambiar la contraseña
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    require 'vendor/autoload.php'; // Asegúrate de que PHPMailer esté cargado

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

        public function verSolicitudesConUsuarios()
        {
            $usuarioModel = new UsuarioModel();
            $solicitudes = $usuarioModel->obtenerSolicitudesConUsuarios();

            // Extraer los Spotify IDs
            $spotifyIds = array_column($solicitudes, 'spotify_track_id');

            // Obtener la información detallada de las canciones
            $spotifyHelper = new SpotifyHelper();
            $trackInfo = $spotifyHelper->getTracksInfo($spotifyIds);

            // Combinar la información de las solicitudes con la información de las canciones
            foreach ($solicitudes as &$solicitud) {
                $trackData = array_filter($trackInfo, function ($track) use ($solicitud) {
                    return $track['id'] === $solicitud['spotify_track_id'];
                });

                if (!empty($trackData)) {
                    $solicitud['track_info'] = reset($trackData);
                }
            }

            return $solicitudes;
        }


        public function obtenerPermisos($usuarioId)
        {
            return $this->modelo->obtenerPermisosUsuario($usuarioId);
        }




        public function login($email, $password)
        {
            try {
                $usuario = $this->modelo->obtenerUsuarioPorEmail($email);
                if ($usuario) {
                    $loginAttempts = isset($usuario['login_attempts']) ? $usuario['login_attempts'] : 0;
                    $lastLoginAttempt = isset($usuario['last_login_attempt']) ? strtotime($usuario['last_login_attempt']) : 0;

                    if ($loginAttempts >= 3 && time() - $lastLoginAttempt < 900) {
                        return ['success' => false, 'message' => 'Cuenta bloqueada. Intente de nuevo en 15 minutos.'];
                    }

                    if (password_verify($password, $usuario['password'])) {
                        $this->modelo->resetearIntentos($usuario['id']);
                        $token = $this->modelo->crearToken($usuario['id'], 'login');

                        // Obtener permisos y rol del usuario
                        $permisos = $this->obtenerPermisos($usuario['id']);
                        $rol = $this->modelo->obtenerRolUsuario($usuario['id']);
                        // var_dump($rol); // Comenta o elimina esta línea
                        error_log("Rol del usuario: " . print_r($rol, true)); // Usa esto para depuración si es necesario

                        $_SESSION['usuario_id'] = $usuario['id'];
                        $_SESSION['apodo'] = $usuario['Apodo'] ?? '';
                        $_SESSION['foto_perfil'] = $usuario['foto_perfil'] ?? '';
                        $_SESSION['nombre_completo'] = $usuario['nombres'] . ' ' . $usuario['apellidos'];
                        $_SESSION['permisos'] = $permisos;
                        $_SESSION['rol'] = $rol; // Guardar rol en la sesión

                        // Determine the redirect URL based on the user's role
                        $redirectUrl = $this->getRedirectUrl($rol);

                        return ['success' => true, 'message' => 'Login exitoso', 'token' => $token, 'rol' => $rol, 'redirectUrl' => $redirectUrl];
                    } else {
                        $this->modelo->incrementarIntentos($usuario['id']);
                        return ['success' => false, 'message' => 'Credenciales incorrectas'];
                    }
                } else {
                    return ['success' => false, 'message' => 'Usuario no encontrado'];
                }
            } catch (Exception $e) {
                error_log("Error en login: " . $e->getMessage());
                return ['success' => false, 'message' => 'Error interno del servidor. Detalle: ' . $e->getMessage()];
            }
        }

        public function handleLogin()
        {
            $email = $_POST['email'];
            $password = $_POST['password'];

            $response = $this->login($email, $password);
            if ($response['success']) {
                header("Location: " . $response['redirectUrl']);
                exit;
            } else {
                // Mostrar mensaje de error
                echo "Error: " . $response['message'];
            }
        }

        public function getRedirectUrl($rol)
        {
            switch ($rol) {
                case 'admin':
                    return '/Admin.php';
                case 'DJ':
                    return '/DJs.php';
                case 'usuario natural':
                    return '/Index.php';
                default:
                    return '/Index.php';
            }
        }


        // Método para obtener las solicitudes de música
        public function mostrarSolicitudesMusica($usuarioId = null)
        {
            // Si no se pasa un usuarioId, toma el de la sesión
            if ($usuarioId === null) {
                $usuarioId = $_SESSION['usuario_id'] ?? null;
            }

            // Verifica que el usuarioId no sea nulo
            if ($usuarioId === null) {
                return ['success' => false, 'message' => 'ID de usuario no encontrado'];
            }

            // Llama al método del modelo para obtener las solicitudes de música
            $solicitudes = $this->modelo->obtenerInformacionSolicitudesMusica($usuarioId);

            if (!empty($solicitudes)) {
                return ['success' => true, 'data' => $solicitudes];
            } else {
                return ['success' => false, 'message' => 'No se encontraron solicitudes de música para este usuario'];
            }
        }

        public function reproducirListaSpotify()
        {
            // Usar un ID de lista de reproducción predeterminado si no hay sesión
            $playlistId = '37i9dQZF1DXcBWIGoYBM5M'; // Este es un ejemplo, usa el ID que prefieras

            $script = '<iframe src="https://open.spotify.com/embed/playlist/' . $playlistId . '" width="300" height="380" frameborder="0" allowtransparency="true" allow="encrypted-media"></iframe>';

            return [
                'success' => true,
                'script' => $script
            ];
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
            // Verifica si el usuario existe en la base de datos
            $usuario = $this->modelo->obtenerUsuarioPorEmail($email);

            if ($usuario) {
                // Crea el token para la recuperación de contraseña
                $token = $this->modelo->crearToken($usuario['id'], 'password_reset');

                // URL de recuperación con el token generado
                $resetLink = "http://localhost:3000/reset_password.php?token=$token";

                // Llama a la función para enviar el correo de recuperación
                if ($this->enviarCorreoRecuperacion($email, $resetLink, $usuario['nombres'])) {
                    return ['success' => true, 'message' => 'Se ha enviado un email de recuperación'];
                } else {
                    return ['success' => false, 'message' => 'Error al enviar el correo de recuperación'];
                }
            }
            return ['success' => false, 'message' => 'Email no encontrado'];
        }

        /**
         * Envía un correo electrónico con un enlace para recuperar la contraseña
         * del usuario.
         *
         * @param string $email Dirección de correo electrónico del usuario.
         * @param string $resetLink Enlace para recuperar la contraseña.
         * @param string $nombreUsuario Nombre del usuario.
         *
         * @return bool Verdadero si el correo se envía correctamente, falso en caso
         *         de error.
         */
        private function enviarCorreoRecuperacion($email, $resetLink, $nombreUsuario)
        {
            $mail = new PHPMailer(true);

            try {
                // Configuración del servidor SMTP (tu cuenta Gmail)
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';              // Servidor SMTP de Gmail
                $mail->SMTPAuth = true;
                $mail->Username = 'ElegansDevMobyEnd@gmail.com';      // Tu dirección de correo Gmail
                $mail->Password = 'hnzvajrtopzbcaai';           // Contraseña de tu cuenta de Gmail
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Usar PHPMailer::ENCRYPTION_STARTTLS en lugar de 'tls'
                $mail->Port = 587;                           // Puerto TCP para TLS

                // Configuración del correo electrónico
                $mail->setFrom('ElegansDevMobyEnd@gmail.com', 'Elegans'); // Dirección del remitente
                $mail->addAddress($email, $nombreUsuario);   // Dirección del destinatario

                // Contenido del correo
                $mail->isHTML(true);                         // Set email format to HTML
                $mail->Subject = 'Recuperación de Contraseña';

                $mail->Body    = "
                <html>
                <head>
                    <style>
                        body {
                            font-family: Arial, sans-serif;
                            margin: 0;
                            padding: 0;
                            background-color: #f4f4f4;
                        }
                        .container {
                            max-width: 600px;
                            margin: 0 auto;
                            background-color: #ffffff;
                            border-radius: 8px;
                            overflow: hidden;
                            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                        }
                        .header {
                            background-color: #3498db;
                            color: #ffffff;
                            padding: 20px;
                            text-align: center;
                        }
                        .content {
                            padding: 20px;
                        }
                        .button {
                            display: inline-block;
                            padding: 10px 20px;
                            margin: 20px 0;
                            font-size: 16px;
                            color: #ffffff;
                            background-color: #3498db;
                            text-decoration: none;
                            border-radius: 4px;
                        }
                        .footer {
                            background-color: #f4f4f4;
                            color: #888888;
                            text-align: center;
                            padding: 10px;
                            font-size: 12px;
                        }
                    </style>
                </head>
                <body>
                    <div class='container'>
                        <div class='header'>
                            <h1>Elegans</h1>
                        </div>
                        <div class='content'>
                            <p>Hola $nombreUsuario,</p>
                            <p>Hemos recibido una solicitud para restablecer tu contraseña. Haz clic en el siguiente enlace para continuar con el proceso:</p>
                            <a href='$resetLink' style='display: inline-block; padding: 10px 20px; font-size: 16px; color: #ffffff; background-color: #3498db; text-decoration: none; border-radius: 4px;'>Restablecer Contraseña</a>                            <p>Si no solicitaste este cambio, ignora este correo.</p>
                        </div>
                        <div class='footer'>
                            <p>&copy; 2024 Elegans. Todos los derechos reservados.</p>
                        </div>
                    </div>
                </body>
                </html>";

                // Enviar el correo
                $mail->send();
                return true;
            } catch (Exception $e) {
                // En caso de error, puedes registrar el error en los logs o mostrarlo
                error_log("Error al enviar el correo: " . $mail->ErrorInfo);
                return false;
            }
        }
    }
