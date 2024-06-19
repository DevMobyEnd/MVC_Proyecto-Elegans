<?php
require_once '../Middleware/auth.php'; // Ajusta la ruta según sea necesario
class UsuarioController
{
    public function index()
    {
        $this->verificarSesion();
    }
    private $modelo;

    public function __construct($modelo)
    {
        $this->modelo = $modelo;
    }

    public function login()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['Gmail']) && isset($_POST['password'])) {
            $email = $_POST['Gmail'];
            $password = $_POST['password'];

            // Consulta la base de datos a través del modelo para obtener el usuario
            $resultado = $this->modelo->verificarUsuario($email);

            if ($resultado) {
                // Verifica si la contraseña ingresada coincide con la contraseña hasheada almacenada
                if (password_verify($password, $resultado['password'])) {
                    // Iniciar sesión y redirigir al usuario
                    session_start();
                    $_SESSION['usuario_id'] = $resultado['id'];
                    header("Location: ../Views/Index.php");
                    exit();
                } else {
                    // Manejar el error de autenticación si las contraseñas no coinciden
                    echo "Las credenciales no son válidas.";
                }
            } else {
                // Manejar el error de autenticación si el usuario no existe
                echo "Las credenciales no son válidas.";
            }
        } else {
            // Mostrar el formulario de login o un mensaje de error si no se envía el formulario correctamente
            echo "Por favor, complete el formulario de login.";
        }
    }
    function verificarSesion()
    {
        session_start(); // Inicia la sesión si aún no ha sido iniciada
        if (!isset($_SESSION['usuario_id'])) {
            // Si no hay una sesión de usuario, redirige al login
            header("Location: ../Views/login.php");
            exit(); // Asegúrate de llamar a exit() después de header() para detener la ejecución del script
        }
        // Si el usuario está autenticado, simplemente continúa la ejecución del script
    }
    public function loginConDocumento() {
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['documento']) && isset($_POST['contraseña'])) {
            $documento = $_POST['documento'];
            $contraseña = $_POST['contraseña'];
    
            // Consulta la base de datos a través del modelo para obtener el usuario por documento
            $resultado = $this->modelo->verificarUsuario2($documento);
    
            if ($resultado && password_verify($contraseña, $resultado['password'])) {
                // Iniciar sesión y redirigir al usuario
                session_start();
                $_SESSION['usuario_id'] = $resultado['id'];
                header("Location: ../Views/Index.php");
                exit();
            } else {
                // Manejar el error de autenticación si el usuario no existe o la contraseña no coincide
                echo "El documento o la contraseña son incorrectos.";
            }
        } else {
            // Mostrar el formulario de login o un mensaje de error si no se envía el formulario correctamente
            echo "Por favor, complete el formulario de login con su documento.";
        }
    }
}