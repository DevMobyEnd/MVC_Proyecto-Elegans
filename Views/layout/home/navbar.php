<?php
session_start();
// Rest of your PHP code
?>
<nav class="navbar navbar-expand px-3 border-bottom">
    <button class="btn" id="sidebar-toggle" type="button">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="navbar-collapse navbar">
        <ul class="navbar-nav">
            <?php if (!isset($_SESSION['usuario_id'])): ?>
                <li class="nav-item">
                    <a href="login.php" class="nav-link">Iniciar Sesión</a>
                </li>
                <li class="nav-item">
                    <a href="register.php" class="nav-link">Registrarse</a>
                </li>
            <?php else: ?>
                <li class="nav-item dropdown">
                    <a href="#" data-bs-toggle="dropdown" class="nav-icon pe-md-0">
                        <img src="/Public/dist/img/profile.jpg" class="avatar img-fluid rounded" alt="">
                    </a>
                    <div class="dropdown-menu dropdown-menu-end">
                        <a href="#" class="dropdown-item">Ajustes</a>
                        <a href="logout.php" class="dropdown-item">Cerrar sesión</a>
                    </div>
                </li>
            <?php endif; ?>
        </ul>
    </div>
</nav>