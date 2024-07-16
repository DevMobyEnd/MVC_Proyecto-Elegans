<?php
require_once '../Middleware/auth.php';
require_once '../Models/UsuarioModel.php';

class UsuarioController {
    private $modelo;

    public function __construct() {
        $this->modelo = new UsuarioModel();
    }

    public function index() {
        $this->verificarSesion();
    }

    public function login() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $email = $_POST['Gmail'] ?? '';
            $password = $_POST['password'] ?? '';
            
            if (!empty($email) && empty($password)) {
                // Only email provided, verify email
                $result = $this->verificarEmail($email);
                echo json_encode($result);
            } elseif (!empty($email) && !empty($password)) {
                // Both email and password provided, verify credentials
                $this->verificarCredenciales($email, $password);
            } else {
                echo json_encode(['success' => false, 'message' => 'Invalid parameters']);
            }
            exit;
        }
        require_once '../Views/login.php';
    }

    public function verificarEmail($email) {
        $usuario = $this->modelo->verificarEmail($email);
        return [
            'success' => (bool)$usuario,
            'message' => $usuario ? 'Correo electrónico verificado' : 'Correo electrónico no encontrado'
        ];
    }

    private function verificarCredenciales($email, $password) {
        $usuario = $this->modelo->verificarCredenciales($email, $password);
        if ($usuario) {
            session_start();
            $_SESSION['usuario_id'] = $usuario['id'];
            echo json_encode(['success' => true, 'message' => 'Inicio de sesión exitoso', 'redirect' => '../Views/Index.php']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Credenciales inválidas']);
        }
        exit;
    }

    public function loginConDocumento() {
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['documento'], $_POST['contraseña'])) {
            $documento = $_POST['documento'];
            $contraseña = $_POST['contraseña'];
            
            $usuario = $this->modelo->obtenerUsuarioPorDocumento($documento);
            
            if ($usuario && password_verify($contraseña, $usuario['password'])) {
                session_start();
                $_SESSION['usuario_id'] = $usuario['id'];
                echo $this->generarAlerta('success', 'Inicio de sesión exitoso', 'Redirigiendo...', false, 1500, '../Views/Index.php');
            } else {
                echo $this->generarAlerta('error', 'Error', 'El documento o la contraseña son incorrectos.');
            }
        } else {
            echo $this->generarAlerta('warning', 'Formulario incompleto', 'Por favor, complete el formulario de login con su documento.');
        }
    }

    private function verificarSesion() {
        session_start();
        if (!isset($_SESSION['usuario_id'])) {
            echo $this->generarAlerta('warning', 'Sesión no iniciada', 'Por favor, inicie sesión para continuar.', false, 1500, '../Views/login.php');
            exit();
        }
    }

    private function generarAlerta($icono, $titulo, $texto, $mostrarConfirmar = true, $temporizador = null, $redireccion = null) {
        $script = "<script>
            Swal.fire({
                icon: '$icono',
                title: '$titulo',
                text: '$texto',
                showConfirmButton: " . ($mostrarConfirmar ? 'true' : 'false');
        
        if ($temporizador !== null) {
            $script .= ", timer: $temporizador";
        }
        
        $script .= "})";

        if ($redireccion !== null) {
            $script .= ".then(() => { window.location.href = '$redireccion'; })";
        }

        $script .= ";</script>";

        return $script;
    }
}