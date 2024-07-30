<?php
session_start();

$profilePicture = isset($_SESSION['profile_picture']) ? $_SESSION['profile_picture'] : 'Public/dist/img/profile.jpg';

// Construir la ruta completa para el navegador si no es la imagen por defecto
$profilePictureUrl = $profilePicture !== 'Public/dist/img/profile.jpg' ? '/uploads/' . $profilePicture : $profilePicture;
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
                        <img src="<?php echo htmlspecialchars($profilePictureUrl); ?>" class="avatar img-fluid rounded-circle" alt="Foto de perfil">
                    </a>
                    <div class="dropdown-menu dropdown-menu-end">
                        <a href="settings.php" class="dropdown-item">Ajustes</a>
                        <a href="logout.php" class="dropdown-item">Cerrar sesión</a>
                    </div>
                </li>
            <?php endif; ?>
        </ul>
    </div>
</nav>

<style>
    .avatar {
        width: 40px;
        height: 40px;
        object-fit: cover;
    }
</style>
