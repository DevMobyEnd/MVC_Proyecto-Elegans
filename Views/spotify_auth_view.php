<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Autenticación Spotify</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-4">Autenticación Spotify</h1>
        
        <?php if (isset($userInfo)): ?>
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                <p class="font-bold">Autenticación exitosa</p>
            </div>
            
            <p class="mb-4">Redirigiendo al reproductor de música...</p>

            <?php
            // Almacenar la información en la sesión
            $_SESSION['spotify_scopes'] = $SPOTIFY_SCOPES;
            // Los tokens ya deberían estar en la sesión, pero nos aseguramos
            $_SESSION['spotify_access_token'] = $_SESSION['spotify_access_token'] ?? '';
            $_SESSION['spotify_refresh_token'] = $_SESSION['spotify_refresh_token'] ?? '';
            ?>

            <script>
                setTimeout(function() {
                    window.location.href = "music_player.php";
                }, 3000); // Redirige después de 3 segundos
            </script>

        <?php else: ?>
            <p class="mb-4">No se ha autenticado aún con Spotify.</p>
            <a href="spotify_auth.php" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded">
                Autenticar con Spotify
            </a>
        <?php endif; ?>
    </div>
</body>
</html>