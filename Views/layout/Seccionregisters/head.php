<!DOCTYPE html>
<html lang="es" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($tituloPagina) ? $tituloPagina : 'Elegans'; ?></title>
    <link rel="website icon" type="png" href="./Public/dist/img/Logo3.png">

    <!-- CSS -->
    <link rel="stylesheet" href="./Public/dist/css/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-NFSYGPE6F3"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());
        gtag('config', 'G-NFSYGPE6F3');
    </script>
</head>

<body>
    <!-- Contenido de la página -->
    <div class="wrapper">
        <aside id="sidebar" class="js-sidebar">
            <!-- Contenido de la barra lateral -->
            <?php include('./Views/layout/Seccionlogin/sidebar.php'); ?>
        </aside>
        <div class="main">
            <?php include('./Views/layout/Seccionlogin/navbar.php'); ?>
            <?php include('content.php'); ?>
            <?php include('./Views/layout/home/theme-toggle.php'); ?>
            <?php include('./Views/layout/home/footer.php'); ?>
        </div>
    </div>

    <!-- JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>
    <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
    <script src="./Public/dist/js/sidebar.js"></script>
    <script src="./Public/dist/js/Cropper.js"></script>
    <script src="./Public/dist/js/theme-toggle.js"></script>
    <script src="./Public/dist/js/register.js"></script>
    <script src="./Public/dist/js/dynamictext.js"></script>
</body>

</html>
