<?php
class UsuarioController {
    private $modelo;

    public function __construct($modelo) {
        $this->modelo = $modelo;
    }

    public function login() {
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
}