<?php
require_once './Models/UpdateProfileModel.php';

// Función para imprimir resultados de prueba
function printTestResult($testName, $result) {
    echo "$testName: " . ($result ? "PASSED" : "FAILED") . "\n";
}

// Crear una instancia del modelo
$model = new UpdateProfileModel();

// Test 1: Verificar conexión
$testConnection = $model->verificarConexion();
printTestResult("Test de conexión", $testConnection);

// Test 2: Verificar correo existente
$testCorreoExistente = $model->verificarCorreoExistente("correo@ejemplo.com");
printTestResult("Test de verificación de correo existente", $testCorreoExistente !== null);

// Test 3: Verificar apodo existente
$testApodoExistente = $model->verificarApodoExistente("apodoEjemplo");
printTestResult("Test de verificación de apodo existente", $testApodoExistente !== null);

// Test 4: Actualizar perfil
$userId = 43; // ID del usuario de prueba
$datosUsuarioNuevos = [
    'nombres' => 'Xampp Actualizado',
    'apellidos' => 'Root Actualizado',
    'numero_documento' => '1116230906',
    'Apodo' => 'Xxx Xampp Actualizado',
    'Gmail' => 'xampp_actualizado@gmail.com',
    'foto_perfil' => 'uploads/img_nuevo.png'
];

// Realizar la actualización
$testActualizacion = $model->actualizarPerfilCompleto($userId, $datosUsuarioNuevos);
printTestResult("Test de actualización de perfil", $testActualizacion);

// Verificar la actualización
$usuarioActualizado = $model->obtenerUsuarioPorId($userId);
$actualizacionCorrecta = true;
foreach ($datosUsuarioNuevos as $campo => $valor) {
    if ($usuarioActualizado[$campo] !== $valor) {
        $actualizacionCorrecta = false;
        echo "Campo $campo no se actualizó correctamente. Esperado: $valor, Obtenido: " . $usuarioActualizado[$campo] . "\n";
    }
}
printTestResult("Verificación de actualización de perfil", $actualizacionCorrecta);

// Test 5: Crear backup de usuario
$testBackup = $model->crearBackupUsuario($userId);
printTestResult("Test de creación de backup", $testBackup);

// Test 6: Intentar actualizar con un correo ya existente
$testCorreoDuplicado = !$model->actualizarPerfilCompleto($userId, [
    'Gmail' => 'correo_existente@ejemplo.com'
]);
printTestResult("Test de correo duplicado", $testCorreoDuplicado);

// Test 7: Intentar actualizar con un apodo ya existente
$testApodoDuplicado = !$model->actualizarPerfilCompleto($userId, [
    'Apodo' => 'apodo_existente'
]);
printTestResult("Test de apodo duplicado", $testApodoDuplicado);

// Test 8: Intentar actualizar con datos inválidos
$testDatosInvalidos = !$model->actualizarPerfilCompleto($userId, [
    'nombres' => str_repeat('a', 256), // Nombre demasiado largo
    'Gmail' => 'correo_invalido'
]);
printTestResult("Test de datos inválidos", $testDatosInvalidos);

// Test 9: Intentar actualizar un usuario que no existe
$testUsuarioInexistente = !$model->actualizarPerfilCompleto(9999, [
    'nombres' => 'Usuario Inexistente'
]);
printTestResult("Test de usuario inexistente", $testUsuarioInexistente);

// Test 10: Probar la reversión manual de una actualización exitosa
echo "Probando reversión manual de actualización exitosa:\n";

// Guardar datos originales
$usuarioOriginal = $model->obtenerUsuarioPorId($userId);
echo "Nombre original: " . $usuarioOriginal['nombres'] . "\n";

// Realizar una actualización exitosa
$actualizacionExitosa = $model->actualizarPerfilCompleto($userId, [
    'nombres' => 'Nombre Actualizado Exitosamente'
]);

// Verificar la actualización
$usuarioDespuesDeActualizacion = $model->obtenerUsuarioPorId($userId);
echo "Nombre después de actualización: " . $usuarioDespuesDeActualizacion['nombres'] . "\n";

// Revertir la actualización manualmente
$reversion = $model->revertirUltimaActualizacion($userId);

// Verificar la reversión
$usuarioDespuesDeReversion = $model->obtenerUsuarioPorId($userId);
echo "Nombre después de reversión: " . $usuarioDespuesDeReversion['nombres'] . "\n";

$testReversion = $usuarioDespuesDeReversion['nombres'] === $usuarioOriginal['nombres'];
printTestResult("Test de reversión manual de actualización exitosa", $testReversion);

echo "Pruebas completadas.\n";