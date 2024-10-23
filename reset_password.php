<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($tituloPagina) ? $tituloPagina : 'Elegans'; ?></title>
    <link rel="website icon" type="png" href="Public/dist/img/Logo3.png">
    <title>Restablecer Contraseña</title>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    <link rel="stylesheet" href="Public/dist/css/reset_password.css">
</head>

<body>
    <form id="recoverForm" action="procesar_reset_password.php" method="POST">
        <div class="header">
            <h1>Restablecer Contraseña</h1>
        </div>
        <div class="form-group d-flex flex-column align-items-center position-relative">
            <input type="hidden" name="token" value="<?php echo htmlspecialchars($_GET['token']); ?>">
            <input class="form-control form-control-lg" placeholder="Contraseña" type="password" id="password" name="password" required>
            <label class="form-label long-label" for="password">
                <ion-icon name="lock-closed-outline"></ion-icon> Nueva Contraseña
            </label>
            <button type="submit">Actualizar Contraseña</button>
        </div>
    </form>



    <canvas id="CanvasParticle"></canvas>

    <script src="Public/dist/js/CanvasParticle.js"></script>
    <script>
        // Llama a la función CanvasParticle para iniciar la animación de partículas
        CanvasParticle();
    </script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>

</html>