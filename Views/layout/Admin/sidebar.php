<div class="h-100">
    <div class="sidebar-logo" style="padding: 10px 0; text-align: center;">
        <a href="#"><img class="img-fluid" src="/Public/dist/img/Logo.png" alt="" style="width: 200px; height: 200px; margin-top: -30px; margin-left: -25px;"></a>
    </div>
    <ul class="sidebar-nav">
        <li class="sidebar-header">
            Elementos de Usuario
        </li>
        <li class="sidebar-item">
            <a href="index.php" class="sidebar-link">
                <i class="fas fa-home"></i>
                Inicio
            </a>
        </li>
        <li class="sidebar-header">
            Panel de Administración
        </li>
        <li class="sidebar-item">
            <a href="#" class="sidebar-link" data-page="lista_usuarios">
                <i class="fas fa-tachometer-alt"></i>
                Dashboard
            </a>
        </li>
        <li class="sidebar-item">
            <a href="#usuariosSubmenu" data-bs-toggle="collapse" aria-expanded="false" class="sidebar-link dropdown-toggle">
                <i class="fas fa-user-friends"></i>
                Gestión de Usuarios
            </a>
            <ul class="collapse" id="usuariosSubmenu">
                <li><a href="#" class="sidebar-link" data-page="lista_usuarios">Lista de Usuarios</a></li>
                <li><a href="#" class="sidebar-link" data-page="RolesPermisos">Roles y Permisos</a></li>
                <li><a href="#" class="sidebar-link" data-page="SolicitudesDJs">Solicitudes de DJs</a></li>
            </ul>
        </li>
        <li class="sidebar-item">
            <a href="#contenidoSubmenu" data-bs-toggle="collapse" aria-expanded="false" class="sidebar-link dropdown-toggle">
                <i class="fas fa-headphones"></i>
                Gestión de Contenido
            </a>
            <ul class="collapse" id="contenidoSubmenu">
                <li><a href="#" class="sidebar-link" data-page="ComentariosResenas">Comentarios y Reseñas</a></li>
            </ul>
        </li>
        <li class="sidebar-item">
            <a href="#configuracionSubmenu" data-bs-toggle="collapse" aria-expanded="false" class="sidebar-link dropdown-toggle">
                <i class="fas fa-sliders-h"></i>
                Configuraciones
            </a>
            <ul class="collapse" id="configuracionSubmenu">
                <li><a href="#" class="sidebar-link" data-page="ConfiguracionSistema">Configuración del Sistema</a></li>
                <li><a href="#" class="sidebar-link" data-page="Notificaciones">Notificaciones</a></li>
            </ul>
        </li>
    </ul>
</div>