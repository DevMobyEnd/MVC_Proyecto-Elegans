<?php
require_once '../Models/InstallModel.php';

class InstallController {
    private $model;
    
    public function __construct() {
        $this->model = new InstallModel();
    }
    
    public function verificarBaseDatos($db_host, $db_name, $db_username, $db_password) {
        try {
            error_log("Controller: Iniciando verificación de base de datos");
            return $this->model->verificarBaseDatos($db_host, $db_name, $db_username, $db_password);
        } catch (Exception $e) {
            error_log("Controller: Error en verificación: " . $e->getMessage());
            throw $e;
        }
    }
    
    public function guardarConfiguracion($db_host, $db_name, $db_username, $db_password) {
        try {
            error_log("Controller: Iniciando guardado de configuración");
            
            if (!$this->validarDatosEntrada($db_host, $db_name, $db_username, $db_password)) {
                throw new Exception('Datos de entrada inválidos');
            }
            
            // Verificar si podemos crear la base de datos
            if ($this->model->verificarBaseDatos($db_host, $db_name, $db_username, $db_password)) {
                return ['success' => true, 'message' => 'La base de datos ya existe'];
            }

            if (!$this->model->verificarConexion($db_host, $db_username, $db_password)) {
                throw new Exception('No se pudo conectar al servidor MySQL');
            }
            
            
            if (!$this->model->crearBaseDatos($db_name)) {
                throw new Exception('No se pudo crear la base de datos');
            }
            
            if (!$this->guardarConfiguracionArchivo($db_host, $db_name, $db_username, $db_password)) {
                throw new Exception('No se pudo guardar el archivo de configuración');
            }
            
            return ['success' => true, 'message' => 'Configuración guardada y base de datos creada con éxito'];
            
        } catch (Exception $e) {
            error_log("Controller: Error en guardarConfiguracion: " . $e->getMessage());
            return ['success' => false, 'message' => 'Error: ' . $e->getMessage()];
        }
    }
    
    private function validarDatosEntrada($db_host, $db_name, $db_username, $db_password) {
        return !empty($db_host) && !empty($db_name) && !empty($db_username);
    }
    
    private function guardarConfiguracionArchivo($db_host, $db_name, $db_username, $db_password) {
        try {
            $configDir = dirname(__DIR__) . '/Config';
            
            // Verificar si el directorio existe, si no, crearlo
            if (!is_dir($configDir)) {
                if (!mkdir($configDir, 0755, true)) {
                    throw new Exception("No se pudo crear el directorio de configuración");
                }
            }
            
            $config = "<?php\n";
            $config .= "define('DB_HOST', '" . addslashes($db_host) . "');\n";
            $config .= "define('DB_NAME', '" . addslashes($db_name) . "');\n";
            $config .= "define('DB_USERNAME', '" . addslashes($db_username) . "');\n";
            $config .= "define('DB_PASSWORD', '" . addslashes($db_password) . "');\n";
            $config .= "define('DB_ENCODE', 'utf8');\n";
            
            $configFile = $configDir . '/global.php';
            
            if (file_put_contents($configFile, $config) === false) {
                throw new Exception("No se pudo escribir el archivo de configuración");
            }
            
            return true;
        } catch (Exception $e) {
            error_log("Error al guardar archivo de configuración: " . $e->getMessage());
            return false;
        }
    }
}
