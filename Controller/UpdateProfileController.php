<?php
// Asumiendo que usamos las mismas dependencias del RegisterController
$root_directory = dirname(__DIR__);

$files_to_check = [
    $root_directory . '/Helpers/Logger.php',
    $root_directory . '/Models/UserModel.php',
    $root_directory . '/Config/config.php',
    $root_directory . '/Helpers/ImageProcessor.php',
    $root_directory . '/Helpers/PasswordValidator.php',
    $root_directory . '/Helpers/CSRFTokenGenerator.php',
    $root_directory . '/vendor/autoload.php'
];

$missing_files = [];

foreach ($files_to_check as $file) {
    if (!file_exists($file)) {
        $missing_files[] = $file;
    } else {
        require_once $file;
    }
}

if (!empty($missing_files)) {
    throw new Exception("Los siguientes archivos no existen: " . implode(", ", $missing_files));
}

use Respect\Validation\Validator as v;

class UpdateProfileController 
{
    private $modelo;
    private $logger;
    private $imageProcessor;
    private $passwordValidator;
    private $csrfTokenGenerator;

    public function __construct()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $this->modelo = new UserModel();
        $this->logger = new Logger();
        $this->imageProcessor = new ImageProcessor();
        $this->passwordValidator = new PasswordValidator();
        $this->csrfTokenGenerator = new CSRFTokenGenerator();
    }

    public function actualizarPerfil()
    {
        header('Content-Type: application/json');
        ob_start();

        try {
            $this->validateRequest();
            $this->validateCSRFToken();
            
            if (!isset($_SESSION['usuario_id'])) {
                throw new Exception('Usuario no autenticado');
            }

            $userId = $_SESSION['usuario_id'];
            $userData = $this->sanitizeUserData();
            $this->validateUserData($userData, $userId);

            $this->modelo->iniciarTransaccion();

            // Procesar nueva foto de perfil si se proporciona
            if (isset($_POST['croppedImageData']) || isset($_FILES['Foto_Perfil'])) {
                $ruta_foto = $this->processProfilePicture();
                $userData['foto_perfil'] = $ruta_foto;
            }

            // Actualizar contraseña si se proporciona
            if (!empty($userData['new_password'])) {
                $userData['password'] = password_hash($userData['new_password'], PASSWORD_DEFAULT);
            }

            $actualizado = $this->modelo->actualizarUsuario(
                $userId,
                $userData
            );

            if ($actualizado) {
                $this->modelo->finalizarTransaccion();
                $this->updateSessionData($userData);

                $output = ob_get_clean();
                return [
                    'status' => 'success',
                    'message' => 'Perfil actualizado exitosamente',
                    'redirect' => '/perfil',
                    'debug' => $output
                ];
            } else {
                throw new Exception('Error al actualizar el perfil');
            }
        } catch (Exception $e) {
            $this->modelo->revertirTransaccion();
            $this->logger->error('Error en la actualización: ' . $e->getMessage());
            $output = ob_get_clean();
            return [
                'status' => 'error',
                'message' => $e->getMessage(),
                'debug' => $output
            ];
        }
    }

    private function sanitizeUserData()
    {
        $userData = [
            'nombres' => htmlspecialchars($_POST['Nombres'] ?? '', ENT_QUOTES, 'UTF-8'),
            'apellidos' => htmlspecialchars($_POST['Apellidos'] ?? '', ENT_QUOTES, 'UTF-8'),
            'apodo' => htmlspecialchars($_POST['Apodo'] ?? '', ENT_QUOTES, 'UTF-8'),
            'correoElectronico' => filter_input(INPUT_POST, 'CorreoElectronico', FILTER_SANITIZE_EMAIL),
            'current_password' => $_POST['current_password'] ?? '',
            'new_password' => $_POST['new_password'] ?? '',
            'confirm_new_password' => $_POST['confirm_new_password'] ?? ''
        ];

        return array_filter($userData, function($value) {
            return $value !== '';
        });
    }

    private function validateUserData($userData, $userId)
    {
        // Validar datos básicos si se proporcionan
        if (isset($userData['nombres'])) {
            v::stringType()->notEmpty()->assert($userData['nombres']);
        }
        if (isset($userData['apellidos'])) {
            v::stringType()->notEmpty()->assert($userData['apellidos']);
        }
        if (isset($userData['apodo'])) {
            if ($this->modelo->verificarApodoExistente($userData['apodo'], $userId)) {
                throw new Exception('El Apodo ya está en uso');
            }
        }
        if (isset($userData['correoElectronico'])) {
            v::email()->assert($userData['correoElectronico']);
            if ($this->modelo->verificarCorreoExistente($userData['correoElectronico'], $userId)) {
                throw new Exception('El correo electrónico ya está registrado');
            }
        }

        // Validar cambio de contraseña si se proporciona
        if (!empty($userData['new_password'])) {
            if (empty($userData['current_password'])) {
                throw new Exception('Debe proporcionar la contraseña actual');
            }

            $usuario = $this->modelo->obtenerUsuarioPorId($userId);
            if (!password_verify($userData['current_password'], $usuario['password'])) {
                throw new Exception('La contraseña actual es incorrecta');
            }

            if ($userData['new_password'] !== $userData['confirm_new_password']) {
                throw new Exception('Las nuevas contraseñas no coinciden');
            }

            $this->passwordValidator->validate($userData['new_password']);
        }
    }

    private function updateSessionData($userData)
    {
        $sessionFields = ['nombres', 'apellidos', 'apodo', 'correoElectronico', 'foto_perfil'];
        foreach ($sessionFields as $field) {
            if (isset($userData[$field])) {
                $_SESSION[$field] = $userData[$field];
            }
        }
    }

    // Otros métodos heredados del RegisterController (validateRequest, validateCSRFToken, processProfilePicture, etc.)

    public function revertirActualizacion()
    {
        if (!isset($_SESSION['usuario_id'])) {
            return ['status' => 'error', 'message' => 'Usuario no autenticado'];
        }

        $userId = $_SESSION['usuario_id'];
        $resultado = $this->modelo->revertirActualizacion($userId);

        if ($resultado) {
            return [
                'status' => 'success',
                'message' => 'Perfil revertido exitosamente',
                'redirect' => '/perfil'
            ];
        } else {
            return [
                'status' => 'error',
                'message' => 'Error al revertir el perfil'
            ];
        }
    }
}