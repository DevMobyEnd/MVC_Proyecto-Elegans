<pre>
<?php
// Inspeccionar la variable userData y session
// var_dump($userData); 
// var_dump($_SESSION); 
?>
</pre>

<main id="profileContent" class="content px-3 py-2">
    <div class="container-fluid">
        <div class="mb-3">
            <h4>Un panel de control de Usuario</h4>
        </div>
        <div class="row">
            <div class="col-12 col-md-6 d-flex">
                <div class="card flex-fill border-0 illustration">
                    <div class="card-body p-0 d-flex flex-fill">
                        <div class="row g-0 w-100">
                            <div class="col-6">
                                <div class="p-3 m-1">
                                    <?php if (isset($_SESSION['apodo']) && !empty($_SESSION['apodo'])): ?>
                                        <h4>Bienvenido, <?php echo htmlspecialchars($_SESSION['apodo']); ?>!</h4>
                                    <?php else: ?>
                                        <h4>Bienvenido, Usuario!</h4>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="col-6 d-flex justify-content-center align-items-center" style="height: 100%; position: relative;">
                                <?php if (isset($_SESSION['foto_perfil'])): ?>
                                    <img id="usuario" src="../uploads/<?php echo htmlspecialchars($_SESSION['foto_perfil']); ?>"
                                        alt="Profile"
                                        onerror="this.onerror=null; this.src='../Public/dist/img/profile.jpg';">
                                <?php else: ?>
                                    <i class="fas fa-user-circle" style="font-size: 200px;"></i>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 d-flex">
                <div class="card flex-fill border-0">
                    <div class="card-body py-4">
                        <div class="d-flex align-items-start">
                            <div class="flex-grow-1">
                                <h4 class="mb-2">
                                    <?php echo htmlspecialchars($_SESSION['nombre_completo']); ?>
                                </h4>
                                <p class="mb-2">
                                    <?php echo isset($_SESSION['apodo']) ? htmlspecialchars($_SESSION['apodo']) : ''; ?>
                                </p>
                                <div class="user-stats-container">
                                    <div class="user-stats mb-0">
                                        <span class="user-stats__item me-2">
                                            <span class="user-stats__count">0</span><br>
                                            <span class="user-stats__label">Solicitudes</span>
                                        </span>
                                    </div>
                                    <div class="user-stats mb-0">
                                        <span class="user-stats__item me-2">
                                            <span class="user-stats__count">0</span><br>
                                            <span class="user-stats__label">Seguidores </span>
                                        </span>
                                    </div>
                                    <div class="user-stats mb-0">
                                        <span class="user-stats__item me-2">
                                            <span class="user-stats__count">0</span><br>
                                            <span class="user-stats__label">Comentarios</span>
                                        </span>
                                    </div>
                                </div>
                                <div class="edit-profile-btn-wrapper">
                                    <button id="editProfileBtn" class="edit-profile-btn">Editar Perfil</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabla de solicitudes de música -->
        <div class="card border-0 mt-4">
            <?php if (empty($solicitudesMusica)): ?>
                <!-- Si no hay solicitudes de música -->
                <img id="modeImage" src="../Public/dist/img/light.png" alt="" class="img-fluid img-small" style="width: 250px; height: auto; display: block; margin: 0 auto;">
                <hr style="background-color: #cee1fe; height: 4px; margin-top: 10px;">

                <div class="text-center">
                    <h4><?php echo htmlspecialchars($_SESSION['apodo']); ?> aún no ha hecho solicitudes de música</h4>
                    <p class="text-gray text-medium">Tus solicitudes de música aparecerán aquí acomodadas en una tabla.</p>
                </div>
                <div class="d-flex justify-content-center edit-profile-btn-wrapper btn-wrapper-adjusted">
                    <button class="edit-profile-btn2">Realiza una solicitud de música ¡Anímate!</button>
                </div>
            <?php else: ?>
                <!-- Si hay solicitudes de música -->
                <div class="table-responsive mt-3">
                    <table class="table table-striped table-hover table-bordered">
                        <thead>
                            <tr>
                                <th>Imagen</th>
                                <th>Nombre Canción</th>
                                <th>Nombre del Artista</th>
                                <th>Estado <i class="fas fa-info-circle" title="Estado de la solicitud"></i></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($solicitudesMusica as $solicitud): ?>
                                <tr>
                                    <td>
                                        <img src="<?php echo htmlspecialchars($solicitud['imagen_url']); ?>" alt="Imagen de la canción" class="img-thumbnail" style="width: 50px; height: auto;">
                                    </td>
                                    <td><?php echo htmlspecialchars($solicitud['nombre_cancion']); ?></td>
                                    <td><?php echo htmlspecialchars($solicitud['nombre_artista']); ?></td>
                                    <td class="<?php echo getEstadoClass($solicitud['estado']); ?>">
                                        <span><?php echo htmlspecialchars($solicitud['estado']); ?></span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>



        <style>
            .table thead th {
                background-color: #f8f9fa;
                color: #333;
            }

            .table-striped tbody tr:nth-of-type(odd) {
                background-color: #f2f2f2;
            }

            .table-hover tbody tr:hover {
                background-color: #e9ecef;
            }

            .table-bordered td,
            .table-bordered th {
                border: 1px solid #dee2e6;
            }

            .table img.img-thumbnail {
                border-radius: 4px;
            }

            /* Colores según el estado */
            .estado-aceptada span {
                color: green;
                font-weight: bold;
            }

            .estado-rechazada span {
                color: red;
                font-weight: bold;
            }

            .estado-pendiente span {
                color: orange;
                font-weight: bold;
            }

            /* Iconos en los títulos */
            .table thead th i {
                margin-left: 10px;
                color: #888;
            }
        </style>



        <?php
        function getEstadoClass($estado)
        {
            switch (strtolower($estado)) {
                case 'aceptada':
                    return 'estado-aceptada';
                case 'rechazada':
                    return 'estado-rechazada';
                case 'pendiente':
                    return 'estado-pendiente';
                default:
                    return '';
            }
        }
        ?>






    </div>
</main>