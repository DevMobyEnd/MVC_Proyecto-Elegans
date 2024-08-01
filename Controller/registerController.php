<?php
require_once '../Models/registerModel.php';

class RegisterController
{
    private const ALLOWED_MIME_TYPES = ['image/jpeg', 'image/png', 'image/gif'];
    private const MAX_FILE_SIZE = 5 * 1024 * 1024; // 5MB
    private $modelo;

    public function __construct()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $this->modelo = new RegisterModel();
    }

    private function verificarCaptcha($captcha){
        $secretKey = "6Lc_EhwqAAAAAFP2Ls-IBwuNVebxqahruI16yZNH";
        $ip = $_SERVER['REMOTE_ADDR'];
        $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".$secretKey."&response=".$captcha."&remoteip=".$ip);
        $responseKeys = json_decode($response,true);
        return intval($responseKeys["success"]) === 1;
    }
    public function registrar()
    {
        // Set headers at the beginning
        header('Content-Type: application/json');
        ob_start(); // Start output buffering

        // Enable error reporting
        error_reporting(E_ALL);
        ini_set('display_errors', 1);

        if ($_SERVER["REQUEST_METHOD"] != "POST") {
            return ['status' => 'error', 'message' => 'Método no permitido'];
        }
        
        if(isset($_POST['g-recaptcha-response'])){
            $captcha = $_POST['g-recaptcha-response'];
            if(!$this->verificarCaptcha($captcha)){
                return ['status' => 'error', 'message' => 'Por favor, verifica que no eres un robot.'];
            }
        } else {
            return ['status' => 'error', 'message' => 'Por favor, completa el CAPTCHA.'];
        }

        $foto_perfil = $_FILES['profilePicture'] ?? null;
        $nombres = htmlspecialchars($_POST['Nombres'] ?? '', ENT_QUOTES, 'UTF-8');
        $apellidos = htmlspecialchars($_POST['Apellidos'] ?? '', ENT_QUOTES, 'UTF-8');
        $numeroDocumento = htmlspecialchars($_POST['NumerodeDocumento'] ?? '', ENT_QUOTES, 'UTF-8');
        $apodo = htmlspecialchars($_POST['Apodo'] ?? '', ENT_QUOTES, 'UTF-8');
        $correoElectronico = filter_input(INPUT_POST, 'CorreoElectronico', FILTER_SANITIZE_EMAIL);
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirmPassword'] ?? '';

        $errores = $this->validarDatos($foto_perfil, $nombres, $apellidos, $numeroDocumento, $apodo, $correoElectronico, $password, $confirmPassword);

        if (!empty($errores)) {
            return ['status' => 'error', 'message' => implode(', ', $errores)];
        }

        // Procesar la imagen si es válida
        $ruta_foto = '';
        if ($foto_perfil) {
            $ruta_foto = $this->procesarImagen($foto_perfil);
            if (!$ruta_foto) {
                return ['status' => 'error', 'message' => 'Error al procesar la imagen'];
            }

            // Guarda la ruta de la foto en la sesión
            $_SESSION['profile_picture'] = $ruta_foto;
        }

        $resultado = $this->modelo->registrarUsuario($ruta_foto, $nombres, $apellidos, $numeroDocumento, $apodo, $correoElectronico, $password);

        $output = ob_get_clean(); // Get the buffered content and clear the buffer
        if ($resultado) {
            return ['status' => 'success', 'message' => 'Usuario registrado exitosamente', 'redirect' => '/Views/login.php', 'debug' => $output];
        } else {
            return ['status' => 'error', 'message' => 'Hubo un error al registrar el usuario', 'debug' => $output];
        }
    }

    private function procesarImagen($foto)
    {
        if ($foto['error'] !== UPLOAD_ERR_OK) {
            return false;
        }

        if (!in_array($foto['type'], self::ALLOWED_MIME_TYPES)) {
            return false;
        }

        if ($foto['size'] > self::MAX_FILE_SIZE) {
            return false;
        }

        // Asegúrate de que la carpeta de carga exista y tenga permisos adecuados
        $upload_dir = '../uploads/';

        // Verifica si la carpeta existe y, si no, intenta crearla
        if (!is_dir($upload_dir)) {
            if (!mkdir($upload_dir, 0755, true)) {
                return false; // No se pudo crear la carpeta
            }
        }

        // Genera un nombre único para el archivo y construye la ruta completa
        $filename = uniqid() . '_' . basename($foto['name']);
        $upload_file = $upload_dir . $filename;

        // Mueve el archivo cargado a la carpeta de destino
        if (move_uploaded_file($foto['tmp_name'], $upload_file)) {
            return $filename; // Devuelve solo el nombre del archivo, no la ruta completa
        }

        return false; // Error al mover el archivo
    }

    private function validarDatos($foto_perfil, $nombres, $apellidos, $numeroDocumento, $apodo, $correoElectronico, $password, $confirmPassword)
    {
        $errores = [];

        if (empty($nombres) || empty($apellidos) || empty($numeroDocumento) || empty($apodo) || empty($correoElectronico)) {
            $errores[] = 'Todos los campos son obligatorios';
        }

        // Validación separada para la foto si es obligatoria
        if (empty($foto_perfil) || $foto_perfil['error'] === UPLOAD_ERR_NO_FILE) {
            $errores[] = 'La foto de perfil es obligatoria';
        }

        if ($password !== $confirmPassword) {
            $errores[] = 'Las contraseñas no coinciden';
        }

        $passwordStrength = $this->validarFortalezaPassword($password);
        if ($passwordStrength !== true) {
            $errores[] = $passwordStrength;
        }

        if (!filter_var($correoElectronico, FILTER_VALIDATE_EMAIL)) {
            $errores[] = 'El correo electrónico no es válido';
        } elseif ($this->modelo->verificarCorreoExistente($correoElectronico)) {
            $errores[] = 'El correo electrónico ya está registrado';
        }

        if ($this->modelo->verificarApodoExistente($apodo)) {
            $errores[] = 'El Apodo ya está en uso';
        }

        // Validación de la imagen
        if ($foto_perfil && $foto_perfil['error'] !== UPLOAD_ERR_NO_FILE) {
            if (!in_array($foto_perfil['type'], self::ALLOWED_MIME_TYPES)) {
                $errores[] = 'El archivo debe ser una imagen (JPEG, PNG o GIF)';
            }
            if ($foto_perfil['size'] > self::MAX_FILE_SIZE) {
                $errores[] = 'La imagen no debe exceder los 5MB';
            }
        }

        return $errores;
    }

    private function validarFortalezaPassword($password)
    {
        if (strlen($password) < 8) {
            return "La contraseña debe tener al menos 8 caracteres.";
        }
        if (!preg_match("/[A-Z]/", $password)) {
            return "La contraseña debe contener al menos una letra mayúscula.";
        }
        if (!preg_match("/[a-z]/", $password)) {
            return "La contraseña debe contener al menos una letra minúscula.";
        }
        if (!preg_match("/[0-9]/", $password)) {
            return "La contraseña debe contener al menos un número.";
        }
        if (!preg_match("/[!@#$%^&*()\-_=+{};:,<.>]/", $password)) {
            return "La contraseña debe contener al menos un carácter especial.";
        }
        return true;
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
