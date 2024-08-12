<main class="content px-3 py-2">
    <div class="row justify-content-center">

        <!-- Aquí se cargará el contenido dinámico de otras secciones -->
        <div class="container mt-4">
            <h2>Gestión de Usuarios</h2>
            <div class="row mb-3">
                <div class="col-md-6">
                    <input type="text" class="form-control" id="buscarUsuario" placeholder="Buscar usuario por nombre, correo o apodo...">
                </div>
                <div class="col-md-6">
                    <button class="btn btn-primary" onclick="buscarUsuarios()">Buscar</button>
                </div>
            </div>
            <div id="listaUsuarios">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Correo</th>
                            <th>Apodo</th>
                            <th>Rol</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (isset($usuarios) && is_array($usuarios)) : ?>
                            <?php foreach ($usuarios as $usuario) : ?>
                                <tr>
                                    <td><?php echo $usuario['nombres'] . ' ' . $usuario['apellidos']; ?></td>
                                    <td><?php echo $usuario['Gmail']; ?></td>
                                    <td><?php echo $usuario['Apodo']; ?></td>
                                    <td><?php echo $usuario['rol']; ?></td>
                                    <td><?php echo $usuario['estado'] ? 'Activo' : 'Inactivo'; ?></td>
                                    <td>
                                        <button class="btn btn-warning btn-sm" onclick="editarUsuario(<?php echo $usuario['id']; ?>)">Editar</button>
                                        <button class="btn btn-danger btn-sm" onclick="eliminarUsuario(<?php echo $usuario['id']; ?>)">Eliminar</button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="6">No se encontraron usuarios.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>

                <!-- Navegación de paginación -->
                <nav aria-label="Page navigation">
                    <ul class="pagination">
                        <li class="page-item">
                            <a class="page-link" href="?page=listaUsuarios&offset=<?php echo max(0, $offset - $limit); ?>">Anterior</a>
                        </li>
                        <li class="page-item">
                            <a class="page-link" href="?page=listaUsuarios&offset=<?php echo $offset + $limit; ?>">Siguiente</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
        <div id="dynamic-content" class="content-section" style="display: none;"></div>
    </div>
</main>