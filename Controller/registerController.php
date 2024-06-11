<?php
require_once '../Models/registerModel.php'; // Asegúrate de ajustar la ruta según tu estructura de proyecto

class RegisterController {
    private $modelo;

    public function __construct() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $this->modelo = new registerModel(); // Crea una instancia del modelo de registro
    }

    public function registrar() {
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['Nombres'], $_POST['Apellidos'], $_POST['NumeroDocumento'], $_POST['Usuario'], $_POST['CorreoElectronico'], $_POST['password'])) {
            $Nombres = $_POST['Nombres'];
            $Apellidos = $_POST['Apellidos'];
            $NumerodeDocumento = $_POST['NumerodeDocumento'];
            $Usuario = $_POST['Usuario'];
            $CorreoElectronico = $_POST['CorreoElectronico'];
            $password = $_POST['password'];

            // Intenta registrar al usuario utilizando el modelo
            $resultado = $this->modelo->registrarUsuario($Nombres, $Apellidos, $NumerodeDocumento, $Usuario, $CorreoElectronico, $password);

            if ($resultado) {
                // Si el registro es exitoso, inicia sesión y redirige al usuario
                $_SESSION['usuario_id'] = $this->modelo->obtenerUltimoIdRegistrado(); // Asume que tienes un método para obtener el ID del último usuario registrado
                header("Location: ../Views/login.php"); // Ajusta la ruta según sea necesario
                exit();
            } else {
                // Si el registro falla, muestra un mensaje de error o redirige a una página de error
                echo "Error al registrar el usuario. Por favor, inténtelo de nuevo.";
                // Aquí podrías redirigir a una página de error o mostrar un mensaje directamente
            }
        } else {
            // Si el formulario no se envió correctamente, muestra un mensaje de error
            echo "Por favor, complete el formulario de registro.";
        }
    }
}