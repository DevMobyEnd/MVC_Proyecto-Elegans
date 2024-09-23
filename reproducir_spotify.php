<?php
require_once 'Controller/UsuarioController.php';

$controller = new UsuarioController();
$resultado = $controller->reproducirListaSpotify();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reproductor de Spotify</title>
</head>
<body>
    <h1>Reproductor de Spotify</h1>
    <div id="spotify-player">
        <?php echo $resultado['script']; ?>
    </div>
</body>
</html>