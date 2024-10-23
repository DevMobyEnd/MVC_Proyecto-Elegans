<!DOCTYPE html>
<html lang="es" data-bs-theme="dark">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($tituloPagina) ? $tituloPagina : 'Elegans'; ?></title>

    <!-- Favicon -->
    <link rel="website icon" type="png" href="/Public/dist/img/Logo3.png">

    <!-- CSS -->
    <link rel="stylesheet" href="/Public/dist/css/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


    

    <!-- Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-NFSYGPE6F3"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());
        gtag('config', 'G-NFSYGPE6F3');
    </script>

<style>
    .role-btn {
        padding: 5px 10px;
        border-radius: 5px;
        background-color: #17a2b8;
        color: white;
        cursor: pointer;
        font-size: 14px;
        border: none;
        text-align: center;
    }

    .role-btn:hover {
        background-color: #138496;
    }


    /* Loader Overlay (Fondo oscuro que cubre toda la pantalla) */
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
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* Tamaño del Spinner */
    .spinner-border {
        width: 3rem;
        height: 3rem;
        border-width: 0.3em;
    }
</style>

</head>

<body>

    <!-- Loader Overlay -->
    <!-- <div id="loaderOverlay" style="display: none;">
        <div class="spinner-border" role="status">
            <span class="visually-hidden">Buscando...</span>
        </div>
    </div> -->

    <!-- Contenido de la página -->
    <div class="wrapper">
        <aside id="sidebar" class="js-sidebar">
            <!-- Contenido de la barra lateral -->
            <?php include('./Views/layout/Admin/sidebar.php'); ?>
        </aside>
        <div class="main">
            <?php include('./Views/layout/home/navbar.php'); ?>
            <?php include('content.php'); ?>
            <?php include('./Views/layout/home/theme-toggle.php'); ?>
            <?php include('./Views/layout/home/footer.php'); ?>
        </div>
    </div>
    <!-- JavaScript -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="/Public/dist/js/theme-toggle.js"></script>
    <script src="/Public/dist/js/sidebar.js"></script>
    <script src="/Public/dist/js/admin.js"></script>

</body>
</html>