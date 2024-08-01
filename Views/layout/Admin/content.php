<?php
if (isset($_GET['page'])) {
    $page = $_GET['page'];
    switch ($page) {
        case 'dashboard':
            include 'partials/admin_dashboard.php';
            break;
        case 'listaUsuarios':
            include 'partials/lista_usuarios.php';
            break;
        // Otros casos para diferentes secciones
        default:
            echo '<h2>Bienvenido al panel de administración</h2>';
            break;
    }
} else {
    echo '<h2>Bienvenido al panel de administración</h2>';
}
