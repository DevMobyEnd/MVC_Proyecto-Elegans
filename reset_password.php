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

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            overflow: hidden;
        }

        #CanvasParticle {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
        }

        #canvas {
            width: 100%;
            height: 100%;
            background-color: #ffffff;
        }



        /* Estilos para el formulario */
        form {
            max-width: 500px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 16px;
            width: 100%;

            position: relative;
        }

        /* Estilos para el header */
        .header {
            background-color: #3498db;
            color: #ffffff;
            padding: 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
            /* Redondeado solo en la parte superior */
            width: 100%;
            /* Asegura que el header se adapte al ancho del formulario */
            box-sizing: border-box;
            /* Asegura que los rellenos no afecten el tamaño */
        }

        /* Ajuste para el contenido dentro del header */
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: normal;
        }




        label {
            display: flex;
            align-items: center;
            margin-bottom: 8px;
            font-weight: bold;
            font-size: 16px;
        }

        label ion-icon {
            margin-right: 8px;
            font-size: 18px;
        }

        input[type="email"] {
            width: 100%;
            /* Ocupa todo el ancho disponible del formulario */
            padding: 12px;
            /* Un poco más de relleno para mayor espacio */
            margin-bottom: 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
            /* Aseguramos un tamaño de letra adecuado */
        }

        button {
            width: 80%;
            /* Hacemos el botón igual de ancho que el campo de entrada */
            background-color: #3498db;
            color: #ffffff;
            border: none;
            padding: 12px 20px;
            /* Aumenté el relleno para más espacio */
            border-radius: 4px;
            font-size: 16px;
            /* Asegura que el texto no se intercale */
            cursor: pointer;
            transition: background-color 0.3s;
            text-align: center;
            /* Alinea el texto dentro del botón */
        }

        button:hover {
            background-color: #2980b9;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 80%;
            /* Hacemos que el contenedor ocupe todo el ancho disponible */
            margin: 0 auto;
            position: relative;
            margin: 20px 0;
        }

        /* Ajuste para pantallas pequeñas */
        @media (max-width: 483px) {
            .form-group {
                width: 100%;
            }
        }

        .form-group input {
            width: 100%;
            padding: 12px 15px;
            font-size: 20px;
            border-radius: 5px;
            border: #acacac solid 2px;
            background-color: transparent;
            color: #000000;
            transition: 0.15s all ease;
        }

        .form-group input:focus {
            border-color: black;
            outline: none;
            box-shadow: none;
        }

        .form-group input::placeholder {
            color: transparent;
        }

        .form-group .form-label {
            position: absolute;
            top: 12px;
            left: 0;
            font-size: 16px;
            padding: 0 10px;
            color: #acacac;
            pointer-events: none;
            transition: 0.15s all ease;
        }

        .form-group input:focus+.form-label,
        .form-group input:not(:placeholder-shown)+.form-label {
            transform: translate(5px, -24px);
            background-color: #ffffff;
            font-size: 14px;
            color: #000000;
        }

        @media (max-width: 576px) {
            .form-group .form-label {
                font-size: 14px;
            }

            .form-group input:focus+.form-label,
            .form-group input:not(:placeholder-shown)+.form-label {
                font-size: 12px;
            }
        }
    </style>
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