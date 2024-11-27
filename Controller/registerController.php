<?php
// Obtenemos el directorio raíz del proyecto
$root_directory = dirname(__DIR__);

$files_to_check = [
    $root_directory . '/Helpers/Logger.php',
    $root_directory . '/Models/registerModel.php',
    $root_directory . '/Config/config.php',
    $root_directory . '/Helpers/CaptchaVerifier.php',
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

use GuzzleHttp\Client;
use Respect\Validation\Validator as v;

class RegisterController
{
    private $modelo;
    private $logger;
    private $captchaVerifier;
    private $imageProcessor;
    private $passwordValidator;
    private $csrfTokenGenerator;
    private $httpClient;

    public function __construct()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $this->modelo = new RegisterModel();
        $this->logger = new Logger();
        $this->captchaVerifier = new CaptchaVerifier();
        $this->imageProcessor = new ImageProcessor();
        $this->passwordValidator = new PasswordValidator();
        $this->csrfTokenGenerator = new CSRFTokenGenerator();
        $this->httpClient = new Client();
    }

    public function registrar()
    {
        header('Content-Type: application/json');
        ob_start();

        try {
            $this->validateRequest();
            $this->validateCSRFToken();
            $this->verifyCaptcha();

            $userData = $this->sanitizeUserData();
            $this->validateUserData($userData);

            $ruta_foto = $this->processProfilePicture();

            $this->modelo->iniciarTransaccion();
            $userId = $this->modelo->registrarUsuario(
                $ruta_foto,
                $userData['nombres'],
                $userData['apellidos'],
                $userData['numeroDocumento'],
                $userData['apodo'],
                $userData['correoElectronico'],
                password_hash($userData['password'], PASSWORD_DEFAULT)
            );

            if ($userId) {
                $rolId = $this->modelo->obtenerRolPorNombre('usuario natural');
                if (!$rolId) {
                    throw new Exception('El rol de "usuario natural" no existe');
                }

                $this->modelo->asignarRolUsuario($userId, $rolId);

                $this->modelo->finalizarTransaccion();
                unset($_SESSION['csrf_token']);

                $this->setSessionData($userId, $userData, $ruta_foto);

                $output = ob_get_clean();
                return [
                    'status' => 'success',
                    'message' => 'Usuario registrado exitosamente',
                    'redirect' => '/login',
                    'debug' => $output
                ];
            } else {
                throw new Exception('Error al registrar el usuario');
            }
        } catch (Exception $e) {
            $this->modelo->revertirTransaccion();
            $this->logger->error('Error en el registro: ' . $e->getMessage());
            $output = ob_get_clean();
            return [
                'status' => 'error',
                'message' => $e->getMessage(),
                'debug' => $output
            ];
        }
    }

    private function validateRequest()
    {
        if ($_SERVER["REQUEST_METHOD"] != "POST") {
            throw new Exception('Método no permitido');
        }
    }

    private function validateCSRFToken()
    {
        $tokenRecibido = $_POST['csrf_token'] ?? '';
        $tokenAlmacenado = $_SESSION['csrf_token'] ?? '';

        error_log("Token recibido: " . $tokenRecibido);
        error_log("Token almacenado: " . $tokenAlmacenado);

        if (!isset($tokenRecibido) || !$this->csrfTokenGenerator->validate($tokenRecibido)) {
            throw new Exception("CSRF token validation failed");
        }
    }
    

    private function verifyCaptcha()
    {
        if (!isset($_POST['cf-turnstile-response'])) {
            $this->logger->info("La respuesta del captcha no está presente en la solicitud POST");
            return false;
        }
    
        $captchaResponse = $_POST['cf-turnstile-response'];
        $this->logger->info("Respuesta del captcha recibida: " . $captchaResponse);
    
        $result = $this->captchaVerifier->verify($captchaResponse);
        $this->logger->info("Resultado de la verificación del captcha: " . ($result ? "Éxito" : "Fallo"));
    
        return $result;
    }

    private function sanitizeUserData()
    {
        return [
            'nombres' => htmlspecialchars($_POST['Nombres'] ?? '', ENT_QUOTES, 'UTF-8'),
            'apellidos' => htmlspecialchars($_POST['Apellidos'] ?? '', ENT_QUOTES, 'UTF-8'),
            'numeroDocumento' => htmlspecialchars($_POST['NumerodeDocumento'] ?? '', ENT_QUOTES, 'UTF-8'),
            'apodo' => htmlspecialchars($_POST['Apodo'] ?? '', ENT_QUOTES, 'UTF-8'),
            'correoElectronico' => filter_input(INPUT_POST, 'CorreoElectronico', FILTER_SANITIZE_EMAIL),
            'password' => $_POST['password'] ?? '',
            'confirmPassword' => $_POST['confirmPassword'] ?? ''
        ];
    }

    private function validateUserData($userData)
    {
        $validator = v::key('nombres', v::notEmpty()->stringType())
            ->key('apellidos', v::notEmpty()->stringType())
            ->key('numeroDocumento', v::notEmpty()->stringType())
            ->key('apodo', v::notEmpty()->stringType())
            ->key('correoElectronico', v::notEmpty()->email())
            ->key('password', v::notEmpty()->stringType())
            ->key('confirmPassword', v::notEmpty()->equals($userData['password']));

        try {
            $validator->assert($userData);
        } catch (\Respect\Validation\Exceptions\NestedValidationException $exception) {
            throw new Exception(implode(', ', $exception->getMessages()));
        }

        $this->passwordValidator->validate($userData['password']);

        if ($this->modelo->verificarCorreoExistente($userData['correoElectronico'])) {
            throw new Exception('El correo electrónico ya está registrado');
        }

        if ($this->modelo->verificarApodoExistente($userData['apodo'])) {
            throw new Exception('El Apodo ya está en uso');
        }
    }

    private function processProfilePicture()
    {
        if (isset($_POST['croppedImageData']) && !empty($_POST['croppedImageData'])) {
            $ruta_foto = $this->imageProcessor->processCroppedImage($_POST['croppedImageData']);
        } elseif (isset($_FILES['Foto_Perfil']) && $_FILES['Foto_Perfil']['error'] === UPLOAD_ERR_OK) {
            $ruta_foto = $this->imageProcessor->process($_FILES['Foto_Perfil']);
        } else {
            // Usa una imagen por defecto si no se proporciona ninguna
            $ruta_foto = 'default_profile.jpg';
        }
    
        if (!$ruta_foto) {
            throw new Exception('Error al procesar la imagen de perfil');
        }
    
        $_SESSION['profile_picture'] = $ruta_foto;
        return $ruta_foto;
    }

    private function setSessionData($userId, $userData, $ruta_foto)
    {
        $_SESSION['usuario_id'] = $userId;
        $_SESSION['apodo'] = $userData['apodo'];
        $_SESSION['foto_perfil'] = $ruta_foto;
        $_SESSION['nombres'] = $userData['nombres'];
        $_SESSION['apellidos'] = $userData['apellidos'];
        $_SESSION['correoElectronico'] = $userData['correoElectronico'];
        $_SESSION['rol'] = $userData['rol'];
        $_SESSION['numeroDocumento'] = $userData['numeroDocumento'];
        
    }

    public function generateCSRFToken()
    {
        return $this->csrfTokenGenerator->generate();
    }

    public function mostrarAlerta($tipo, $titulo, $mensajes = [], $redireccion = '')
    {
        $mensajesHtml = implode('</li><li>', array_map('htmlspecialchars', $mensajes));
        $script = "<script>
            Swal.fire({
                icon: '" . htmlspecialchars($tipo) . "',
                title: '" . htmlspecialchars($titulo) . "',
                html: '<ul><li>" . $mensajesHtml . "</li></ul>',
                showConfirmButton: true,
                customClass: {
                    container: 'my-swal'
                }
            })";

        if ($redireccion) {
            $script .= ".then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '" . htmlspecialchars($redireccion) . "';
                }
            })";
        }

        $script .= ";</script>";
        return $script;
    }
}   