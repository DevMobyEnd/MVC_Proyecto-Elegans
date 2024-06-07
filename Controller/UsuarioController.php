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

            // Aquí iría la lógica para consultar la base de datos a través del modelo
            $resultado = $this->modelo->verificarUsuario($email, $password);

            if ($resultado) {
                // Iniciar sesión y redirigir al usuario
                session_start();
                $_SESSION['usuario_id'] = $resultado['id'];
                header("Location: ../Views/Index.php");
                exit();
            } else {
                // Manejar el error de autenticación
                echo "Las credenciales no son válidas.";
            }
        } else {
            // Mostrar el formulario de login o un mensaje de error
            echo "Por favor, complete el formulario de login.";
        }
    }
}