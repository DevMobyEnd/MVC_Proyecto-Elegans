<?php
require_once '../Models/InstallModel.php';

class InstallController {
    private $model;

    public function __construct() {
        $this->model = new InstallModel();
    }
    
    public function verificarBaseDatos($db_host, $db_name, $db_username, $db_password) {
        return $this->model->verificarBaseDatos($db_host, $db_name, $db_username, $db_password);
    }

    public function guardarConfiguracion($db_host, $db_name, $db_username, $db_password) {
        if (!$this->validarDatosEntrada($db_host, $db_name, $db_username, $db_password)) {
            return ['success' => false, 'message' => 'Datos de entrada inválidos'];
        }
    
        // Verificar si la base de datos ya existe
        if ($this->model->verificarBaseDatos($db_host, $db_name, $db_username, $db_password)) {
            return ['success' => false, 'message' => 'La base de datos ya existe'];
        }
    
        // Intentar crear la base de datos
        if (!$this->model->crearBaseDatos($db_name)) {
            return ['success' => false, 'message' => 'No se pudo crear la base de datos'];
        }
    
        // Guardar la configuración en el archivo
        if (!$this->guardarConfiguracionArchivo($db_host, $db_name, $db_username, $db_password)) {
            return ['success' => false, 'message' => 'No se pudo guardar la configuración'];
        }
    
        return ['success' => true, 'message' => 'Configuración guardada y base de datos creada con éxito'];
    }


    private function mostrarMensaje($success, $message, $redirectUrl = '') {
        echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
        echo "<script>
            Swal.fire({
                icon: " . ($success ? "'success'" : "'error'") . ",
                title: '" . addslashes($message) . "',
                showConfirmButton: true
            }).then((result) => {
                if (result.isConfirmed) {
                    " . ($success && $redirectUrl ? "window.location.href = '$redirectUrl';" : "") . "
                }
            });
        </script>";
        exit;
    }
  
    private function validarDatosEntrada($db_host, $db_name, $db_username, $db_password) {
        return !empty($db_host) && !empty($db_name) && !empty($db_username);
    }

    private function guardarConfiguracionArchivo($db_host, $db_name, $db_username, $db_password) {
        $config = "<?php\n";
        $config .= "define('DB_HOST', '$db_host');\n";
        $config .= "define('DB_NAME', '$db_name');\n";
        $config .= "define('DB_USERNAME', '$db_username');\n";
        $config .= "define('DB_PASSWORD', '" . ($db_password ?: '') . "');\n";
        $config .= "define('DB_ENCODE', 'utf8');\n";
        return file_put_contents('../Config/global.php', $config) !== false;
    }
}