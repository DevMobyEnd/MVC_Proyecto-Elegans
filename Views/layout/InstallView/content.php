<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configuración de Base de Datos</title>
</head>
<body>
    <h1>Configuración de Base de Datos</h1>
    <form action="guardar_config.php" method="POST">
        <label for="db_host">Host de la Base de Datos:</label>
        <input type="text" id="db_host" name="db_host" value="localhost" required><br><br>

        <label for="db_name">Nombre de la Base de Datos:</label>
        <input type="text" id="db_name" name="db_name" value="db_Pruebita" required><br><br>

        <label for="db_username">Usuario de la Base de Datos:</label>
        <input type="text" id="db_username" name="db_username" value="root" required><br><br>

        <label for="db_password">Contraseña de la Base de Datos:</label>
        <input type="password" id="db_password" name="db_password"><br><br>

        <input type="submit" name="guardar" value="Guardar Configuración">
    </form>
    <br>
    <form action="omitir_config.php" method="POST">
        <input type="submit" value="Omitir configuración (Ya tengo todo configurado)">
    </form>
</body>
</html>