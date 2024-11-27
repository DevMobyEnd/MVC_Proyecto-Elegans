<div class="sidebar h-100">
    <div class="sidebar-logo" style="padding: 10px 0; text-align: center;">
        <a href="#"><img class="img-fluid" src="./Public/dist/img/Logo.png" alt="" style="width: 200px; height: 200px; margin-top: -30px; margin-left: -25px;"></a>
    </div>
    <ul class="sidebar-nav">
        <li class="sidebar-header">
            <?php
            if (isset($userData['role'])) {
                switch ($userData['role']) {
                    case 'admin':
                        echo "Panel de Administración";
                        break;
                    case 'DJ':
                        echo "Panel de DJ";
                        break;
                    default:
                        echo "Elementos de Usuario";
                }
            } else {
                echo "Elementos de Usuario";
            }
            ?>
        </li>

        <?php if (isset($userData['role']) && $userData['role'] === 'usuario natural'): ?>
            <?php if (basename($_SERVER['PHP_SELF']) !== 'index.php'): ?>
                <li class="sidebar-item">
                    <a href="/index.php" class="sidebar-link">
                        <i class="fas fa-home"></i>
                        Inicio
                    </a>
                </li>
            <?php endif; ?>
            <li class="sidebar-item"> 
                <a href="/test_CanalDialogo.php" class="sidebar-link">
                    <i class="fa-solid fa-user-shield"></i>
                    Canal de Dialogo
                </a>
            </li>
        <?php endif; ?>

        <?php if (isset($userData['role']) && $userData['role'] === 'DJ'): ?>
            <li class="sidebar-item">
                <a href="/CanalDialogo.php" class="sidebar-link">
                    <i class="fa-solid fa-user-shield"></i>
                    Canal de Dialogo
                </a>
            </li>
        <?php endif; ?>

        <!-- Agrega más elementos del menú lateral aquí según los permisos -->

        <?php if (isset($userData['role']) && $userData['role'] === 'admin'): ?>
            <li class="sidebar-item">
                <a href="/AdminDashboard.php" class="sidebar-link">
                    <i class="fa-solid fa-user-shield"></i>
                    Panel de Administración
                </a>
            </li>
        <?php endif; ?>

        <?php if (isset($userData['role']) && $userData['role'] === 'DJ'): ?>
            <li class="sidebar-item">
                <a href="/DJDashboard.php" class="sidebar-link">
                    <i class="fa-solid fa-headphones"></i>
                    Panel de DJ
                </a>
            </li>
        <?php endif; ?>
    </ul>
</div>