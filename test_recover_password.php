<?php
// Incluye los archivos necesarios
require_once 'Controller/UsuarioController.php';

// Inicia la sesión si aún no ha sido iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Crea una instancia del controlador
$usuarioController = new UsuarioController();

// Verifica si es una solicitud AJAX
if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['email'])) {
    $email = $_POST['email'];

    // Llama a la función de recuperación de contraseña
    $result = $usuarioController->solicitarRecuperacionContrasena($email);

    // Devuelve el resultado como JSON
    header('Content-Type: application/json');
    echo json_encode($result);
    exit;
}

// Muestra un formulario de prueba si la solicitud no es AJAX
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($tituloPagina) ? $tituloPagina : 'Elegans'; ?></title>
    <link rel="website icon" type="png" href="Public/dist/img/Logo3.png">
    <title>Prueba Recuperación de Contraseña</title>
    <!-- Incluir SweetAlert CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
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

        /* Estilos para el Loader Overlay */
        #loaderOverlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            /* Fondo semi-transparente */
            z-index: 1050;
            /* Asegúrate de que esté encima de otros elementos */
            display: none;
            /* Oculto por defecto */
            align-items: center;
            justify-content: center;
        }

        /* Tamaño del Spinner */
        .spinner-border {
            width: 3rem;
            height: 3rem;
            border-width: 0.3em;
            border-radius: 50%;
            border: 0.3em solid #f3f3f3;
            /* Color gris claro */
            border-top: 0.3em solid #3498db;
            /* Color azul */
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
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
    <form id="recoverForm">
        <div class="header">
            <h1>Restablecimiento de Contraseña</h1>
        </div>
        <div class="form-group d-flex flex-column align-items-center position-relative">
            <input placeholder="Correo Electrónico" type="email" class="form-control form-control-lg" id="email" name="email" required>
            <label class="form-label long-label" for="email">
                <ion-icon name="mail-outline"></ion-icon> Correo Electrónico
            </label>
            <button type="submit">Solicitar Recuperación</button>
        </div>
    </form>






    <!-- Loader Overlay -->
    <div id="loaderOverlay">
        <div class="spinner-border"></div>
    </div>

    <canvas id="CanvasParticle"></canvas>

    <script src="Public/dist/js/CanvasParticle.js"></script>
    <script>
        // Llama a la función CanvasParticle para iniciar la animación de partículas
        CanvasParticle();
    </script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.getElementById('recoverForm').addEventListener('submit', function(event) {
            event.preventDefault();
            const email = document.getElementById('email').value;
            const loaderOverlay = document.getElementById('loaderOverlay');

            // Mostrar el loader
            loaderOverlay.style.display = 'flex';

            fetch('test_recover_password.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: new URLSearchParams({
                        email: email
                    })
                })
                .then(response => response.json())
                .then(data => {
                    // Ocultar el loader
                    loaderOverlay.style.display = 'none';

                    if (data.success) {
                        Swal.fire({
                            title: 'Éxito',
                            text: data.message,
                            icon: 'success',
                            confirmButtonText: 'Aceptar'
                        });
                    } else {
                        Swal.fire({
                            title: 'Error',
                            text: data.message,
                            icon: 'error',
                            confirmButtonText: 'Aceptar'
                        });
                    }
                })
                .catch(error => {
                    // Ocultar el loader
                    loaderOverlay.style.display = 'none';

                    Swal.fire({
                        title: 'Error',
                        text: 'Ocurrió un error inesperado.',
                        icon: 'error',
                        confirmButtonText: 'Aceptar'
                    });
                    console.error('Error:', error);
                });
        });
    </script>
</body>

</html>