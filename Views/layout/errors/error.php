<?php
if (isset($_GET['error'])) {
    $mensajeError = htmlspecialchars($_GET['error']);
} else {
    $mensajeError = "Error desconocido.";
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Error</title>
</head>
<body>
    <h1>Se ha producido un error</h1>
    <p><?php echo $mensajeError; ?></p>
</body>
</html>