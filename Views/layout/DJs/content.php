<main class="content px-3 py-2">
    <div class="container-fluid">
        <div class="row">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th class="col-imagen-perfil">Imagen de Perfil</th>
                        <th class="col-apodo">Apodo</th>
                        <th class="col-nombre-cancion">Nombre de la Canción</th>
                        <th class="col-spotify-id">Spotify ID</th>
                        <th class="col-imagen-cancion">Imagen de la Canción</th>
                        <th class="col-estado">Estado</th>
                        <th class="col-fecha-solicitud">Fecha de Solicitud</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($solicitudes)): ?>
                        <?php foreach ($solicitudes as $solicitud): ?>
                            <tr>
                                <td class="col-imagen-perfil">
                                    <img src="<?php echo $solicitud['foto_perfil']; ?>" alt="Imagen de perfil" class="img-thumbnail">
                                </td>
                                <td class="col-apodo"><?php echo $solicitud['Apodo']; ?></td>
                                <td class="col-nombre-cancion"><?php echo $solicitud['nombre_cancion']; ?></td>
                                <td class="col-spotify-id"><?php echo $solicitud['spotify_track_id']; ?></td>
                                <td class="col-imagen-cancion">
                                    <img src="<?php echo $solicitud['imagen_cancion']; ?>" alt="Imagen de la canción" class="img-thumbnail">
                                </td>
                                <td class="col-estado">
                                    <div class="dropdown">
                                        <button class="dropdown-toggle">
                                            <?php echo $solicitud['estado']; ?>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="actualizar_estado.php?id=<?php echo $solicitud['id']; ?>&estado=pendiente">Pendiente</a>
                                            <a class="dropdown-item" href="actualizar_estado.php?id=<?php echo $solicitud['id']; ?>&estado=en_proceso">En Proceso</a>
                                            <a class="dropdown-item" href="actualizar_estado.php?id=<?php echo $solicitud['id']; ?>&estado=completado">Completado</a>
                                        </div>
                                    </div>
                                </td>
                                <td class="col-fecha-solicitud"><?php echo $solicitud['fecha_solicitud']; ?></td>
                                <td>
                                    <!-- Aquí puedes poner más acciones si es necesario -->
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8">No hay solicitudes.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<style>

    /* Estilos para el dropdown */
.dropdown {
    position: relative;
    display: inline-block;
}

.dropdown-toggle {
    background-color: #007bff;
    color: white;
    border: none;
    padding: 10px 20px;
    cursor: pointer;
    border-radius: 4px;
}

.dropdown-toggle:hover {
    background-color: #0056b3;
}

.dropdown-menu {
    display: none;
    position: absolute;
    background-color: white;
    min-width: 160px;
    box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
    z-index: 1;
    border-radius: 4px;
}

.dropdown-menu a {
    color: black;
    padding: 12px 16px;
    text-decoration: none;
    display: block;
}

.dropdown-menu a:hover {
    background-color: #f1f1f1;
}

 /* Ajusta el ancho de las columnas de la tabla */
 table {
        width: 100%;
        border-collapse: collapse;
    }

    thead th {
        background-color: #f8f9fa;
        text-align: left;
        padding: 8px;
        border-bottom: 2px solid #dee2e6;
    }

    tbody td {
        padding: 8px;
        vertical-align: middle;
    }

    /* Especifica el ancho de las columnas */
    .col-imagen-perfil {
        width: 10%;
    }

    .col-apodo {
        width: 15%;
    }

    .col-nombre-cancion {
        width: 20%;
    }

    .col-spotify-id {
        width: 15%;
    }

    .col-imagen-cancion {
        width: 10%;
    }

    .col-estado {
        width: 15%;
    }

    .col-fecha-solicitud {
        width: 15%;
    }

    /* Asegura que las imágenes no desborden sus celdas */
    .img-thumbnail {
        max-width: 100%;
        height: auto;
    }

</style>
<script>

document.addEventListener('DOMContentLoaded', function() {
    // Obtener todos los botones de dropdown
    var dropdowns = document.querySelectorAll('.dropdown');

    dropdowns.forEach(function(dropdown) {
        var button = dropdown.querySelector('.dropdown-toggle');
        var menu = dropdown.querySelector('.dropdown-menu');

        button.addEventListener('click', function() {
            var isOpen = menu.style.display === 'block';
            // Cerrar todos los dropdowns abiertos
            document.querySelectorAll('.dropdown-menu').forEach(function(m) {
                m.style.display = 'none';
            });
            // Abrir el dropdown actual
            menu.style.display = isOpen ? 'none' : 'block';
        });

        // Cerrar el dropdown si se hace clic fuera de él
        window.addEventListener('click', function(event) {
            if (!dropdown.contains(event.target)) {
                menu.style.display = 'none';
            }
        });
    });
});

</script>