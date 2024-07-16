<?php
require_once '../Models/registerModel.php';

class RegisterController
{
    private $modelo;

    public function __construct()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $this->modelo = new RegisterModel();
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

        $nombres = htmlspecialchars($_POST['Nombres'] ?? '', ENT_QUOTES, 'UTF-8');
        $apellidos = htmlspecialchars($_POST['Apellidos'] ?? '', ENT_QUOTES, 'UTF-8');
        $numeroDocumento = htmlspecialchars($_POST['NumerodeDocumento'] ?? '', ENT_QUOTES, 'UTF-8');
        $apodo = htmlspecialchars($_POST['Apodo'] ?? '', ENT_QUOTES, 'UTF-8');
        $correoElectronico = filter_input(INPUT_POST, 'CorreoElectronico', FILTER_SANITIZE_EMAIL);
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirmPassword'] ?? '';

        $errores = $this->validarDatos($nombres, $apellidos, $numeroDocumento, $apodo, $correoElectronico, $password, $confirmPassword);

        if (!empty($errores)) {
            return ['status' => 'error', 'message' => implode(', ', $errores)];
        }

        $resultado = $this->modelo->registrarUsuario($nombres, $apellidos, $numeroDocumento, $apodo, $correoElectronico, $password);

        $output = ob_get_clean(); // Get the buffered content and clear the buffer
        if ($resultado) {
            return ['status' => 'success', 'message' => 'Usuario registrado exitosamente', 'redirect' => '/login.php', 'debug' => $output];
        } else {
            return ['status' => 'error', 'message' => 'Hubo un error al registrar el usuario', 'debug' => $output];
        }
    }

    private function validarDatos($nombres, $apellidos, $numeroDocumento, $apodo, $correoElectronico, $password, $confirmPassword)
    {
        $errores = [];

        if (empty($nombres) || empty($apellidos) || empty($numeroDocumento) || empty($apodo) || empty($correoElectronico)) {
            $errores[] = 'Todos los campos son obligatorios';
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