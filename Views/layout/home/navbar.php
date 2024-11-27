<nav class="navbar navbar-expand px-3 border-bottom">
    <button class="btn" id="sidebar-toggle" type="button">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="navbar-collapse navbar">
        <ul class="navbar-nav ms-auto">
            <?php if (isset($_SESSION['usuario_id'])): ?>
                <li class="nav-item dropdown">
                    <a class="nav-link" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <?php if (isset($_SESSION['foto_perfil'])): ?>
                            <img src="../uploads/<?php echo htmlspecialchars($_SESSION['foto_perfil']); ?>"
                                alt="Profile"
                                class="rounded-circle avatar"
                                width="32"
                                height="32"
                                onerror="this.onerror=null; this.src='../Public/dist/img/profile.jpg';">
                        <?php else: ?>
                            <i class="fas fa-user-circle"></i>
                        <?php endif; ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="/Myperfil.php">Mi Perfil</a></li>
                        <li><a class="dropdown-item" href="../Helpers/logout.php">Cerrar Sesión</a></li>
                    </ul>
                </li>
            <?php else: ?>
                <li class="nav-item">
                    <a class="nav-link" href="/login.php">Iniciar Sesión</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/register.php">Registrarse</a>
                </li>
            <?php endif; ?>
        </ul>
    </div>
</nav>

<style>
    .navbar {
        padding-top: 0.25rem;
        padding-bottom: 0.25rem;
        min-height: 48px;
    }

    .avatar {
        width: 40px;
        height: 40cpx;
        object-fit: cover;
    }

    .fa-user-circle {
        font-size: 24px;
    }

    .navbar-nav {
        padding: 0;
    }

    .nav-item {
        display: flex;
        align-items: center;
    }
</style>