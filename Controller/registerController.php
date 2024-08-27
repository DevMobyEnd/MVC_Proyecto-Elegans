<?php
require_once './Helpers/Logger.php';
require_once './Models/registerModel.php';
require_once './Config/config.php';
require_once './Helpers/CaptchaVerifier.php';
require_once './Helpers/ImageProcessor.php';
require_once './Helpers/PasswordValidator.php';
require_once './Helpers/CSRFTokenGenerator.php';
require_once './vendor/autoload.php'; // Para Guzzle y Respect\Validation

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
                $this->modelo->finalizarTransaccion();
                unset($_SESSION['csrf_token']);

                // Establece las variables de sesión
                $_SESSION['usuario_id'] = $userId;
                $_SESSION['apodo'] = $userData['apodo'];
                $_SESSION['foto_perfil'] = $ruta_foto;
                $_SESSION['nombres'] = $userData['nombres'];
                $_SESSION['apellidos'] = $userData['apellidos'];

                $output = ob_get_clean();
                return [
                    'status' => 'success',
                    'message' => 'Usuario registrado exitosamente',
                    'redirect' => '/login.php',
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
        if (!isset($_POST['csrf_token']) || !$this->csrfTokenGenerator->validate($_POST['csrf_token'])) {
            throw new Exception("CSRF token validation failed");
        }
    }

    private function verifyCaptcha()
    {
        if (!isset($_POST['cf-turnstile-response']) || !$this->captchaVerifier->verify($_POST['cf-turnstile-response'])) {
            throw new Exception('Por favor, verifica que no eres un robot.');
        }
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
        if (isset($_POST['croppedImageData'])) {
            $ruta_foto = $this->imageProcessor->processCroppedImage($_POST['croppedImageData']);
        } else {
            $foto_perfil = $_FILES['profilePicture'] ?? null;
            if (empty($foto_perfil) || $foto_perfil['error'] === UPLOAD_ERR_NO_FILE) {
                throw new Exception('La foto de perfil es obligatoria');
            }
            $ruta_foto = $this->imageProcessor->process($foto_perfil);
        }

        if (!$ruta_foto) {
            throw new Exception('Error al procesar la imagen');
        }

        $_SESSION['profile_picture'] = $ruta_foto;
        return $ruta_foto; // Asegúrate de que siempre se retorne $ruta_foto
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
