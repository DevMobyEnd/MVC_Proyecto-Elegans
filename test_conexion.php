<?php
// 


// /opt/lampp/htdocs/MVC_Proyecto-Elegans/test.php
// ini_set('display_errors', 1);
// error_reporting(E_ALL);

// require_once __DIR__ . '/Config/global.php';
// require_once __DIR__ . '/Config/conexion.php';

// try {
//     $conexion = new Conexion();
//     echo "Conexión exitosa!";
// } catch (Exception $e) {
//     echo "Error: " . $e->getMessage();
// }


// test_db.php en la raíz del proyecto

echo "Contenido real de global.php:<br>";
echo "<pre>" . htmlspecialchars(file_get_contents(__DIR__ . '/Config/global.php')) . "</pre>";
echo "Ruta real del archivo cargado: " . realpath(__DIR__ . '/Config/global.php') . "<br>";
constant_name: 
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "Directorio actual: " . __DIR__ . "<br>";
echo "Ruta al global.php: " . __DIR__ . '/Config/global.php' . "<br>";
echo "¿Existe global.php? " . (file_exists(__DIR__ . '/Config/global.php') ? 'Sí' : 'No') . "<br>";

if (file_exists(__DIR__ . '/Config/global.php')) {
    echo "Contenido de global.php:<br>";
    echo "<pre>" . htmlspecialchars(file_get_contents(__DIR__ . '/Config/global.php')) . "</pre>";
}

require_once __DIR__ . '/Config/global.php';

echo "<br>Verificando constantes después de require:<br>";
echo "DB_HOST: " . (defined('DB_HOST') ? DB_HOST : 'No definido') . "<br>";
echo "DB_USERNAME: " . (defined('DB_USERNAME') ? DB_USERNAME : 'No definido') . "<br>";
echo "DB_PASSWORD: " . (defined('DB_PASSWORD') ? DB_PASSWORD : 'No definido') . "<br>";
echo "DB_NAME: " . (defined('DB_NAME') ? DB_NAME : 'No definido') . "<br>";

echo "<br>Intentando conexión:<br>";
try {
    $conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
    
    if ($conn->connect_error) {
        throw new Exception($conn->connect_error);
    }
    
    echo "¡Conexión exitosa!<br>";
    echo "Versión del servidor: " . $conn->server_info . "<br>";
    
    $conn->close();
} catch (Exception $e) {
    echo "Error de conexión: " . $e->getMessage() . "<br>";
    echo "Trace: " . $e->getTraceAsString() . "<br>";
}