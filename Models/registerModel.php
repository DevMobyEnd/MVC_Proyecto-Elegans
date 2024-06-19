<?php
require_once '../Config/conexion.php'; // Asegúrate de ajustar la ruta según tu estructura de proyecto

class registerModel {
    private $conexion;

    public function __construct() {
        $conexion = new Conexion(); // Asume que tienes una clase Conexion que establece la conexión a la base de datos
        $this->conexion = $conexion->obtenerConexion(); // Método para obtener la instancia de mysqli
    }

    public function registrarUsuario($Nombres, $Apellidos, $NumerodeDocumento, $Usuario, $CorreoElectronico, $password) {
        // Validación de datos de entrada
        if (empty($Nombres) || empty($Apellidos) || empty($NumerodeDocumento) || empty($Usuario) || empty($CorreoElectronico) || empty($password)) {
            return false; // Considera lanzar una excepción o manejar de otra manera
        }

        // Aquí podrías incluir la lógica para encriptar la contraseña antes de guardarla
        $passwordEncriptado = password_hash($password, PASSWORD_DEFAULT);

        // Preparar la consulta SQL para insertar el nuevo usuario
        $sql = "INSERT INTO tb_usuarios (nombres, apellidos, numero_documento, usuario, Gmail, password) VALUES (?, ?, ?, ?, ?, ?)";

        if ($stmt = $this->conexion->prepare($sql)) {
            // Vincular los parámetros
            $stmt->bind_param("ssssss", $Nombres, $Apellidos, $NumerodeDocumento, $Usuario, $CorreoElectronico, $passwordEncriptado);

            // Ejecutar la sentencia
            $resultado = $stmt->execute();

            // Cerrar la sentencia
            $stmt->close();

            // Retornar true si se ejecuta correctamente, de lo contrario false
            return $resultado;
        } else {
            // Si ocurre un error al preparar la sentencia, considera manejar el error de manera más específica
            return false;
        }
    }

    // Método para obtener el último ID registrado
    public function obtenerUltimoIdRegistrado() {
        return $this->conexion->insert_id;
    }

    public function procesarRegistro() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Captura los datos del formulario
            $nombre = $_POST['nombre'];
            $documento = $_POST['documento'];
            $fechaNacimiento = $_POST['fechaNacimiento'];
            $clave = $_POST['clave'];
            
            // Procesa la carga del archivo de foto
            $rutaFoto = $this->cargarFoto($_FILES['foto']);
    
            // Aquí deberías validar los datos recibidos (por ejemplo, asegurarte de que no estén vacíos)
    
            // Crea una instancia de tu modelo de registro
            $modeloRegistro = new registerModel();
    
            // Intenta registrar al usuario
            $resultado = $modeloRegistro->registrarUsuarioc($nombre, $documento, $fechaNacimiento, $rutaFoto, $clave);
    
            if ($resultado) {
                // Si el registro es exitoso, redirige o muestra un mensaje
                // Por ejemplo, redirigir al usuario a la página de inicio de sesión
                header("Location: login.php");
                exit();
            } else {
                // Si hay un error en el registro, muestra un mensaje o maneja el error
                echo "Hubo un error en el registro. Por favor, inténtalo de nuevo.";
            }
        } else {
            // Si el método no es POST, redirige al formulario de registro
            header("Location: register.php");
            exit();
        }
    }
    
    private function cargarFoto($archivoFoto) {
        if ($archivoFoto['error'] == UPLOAD_ERR_OK) {
            $nombreTemporal = $archivoFoto['tmp_name'];
            $nombreArchivo = basename($archivoFoto['name']);
            $rutaDestino = "/ruta/a/tu/directorio/de/imagenes/" . $nombreArchivo;
            if (move_uploaded_file($nombreTemporal, $rutaDestino)) {
                return $rutaDestino; // Devuelve la ruta de la foto si la carga fue exitosa
            }
        }
        return null; // Devuelve null si no se pudo cargar la foto
    }
}