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




    <!-- Modal de Términos y Condiciones -->
    <div class="modal fade" id="terminosCondicionesModal" tabindex="-1" aria-labelledby="terminosCondicionesModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="terminosCondicionesModalLabel">Términos y
                        Condiciones de Uso</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <h6>Introducción</h6>
                    <p>1.1 Bienvenido a Elegans. Estos términos y condiciones
                        rigen el uso de nuestra plataforma de solicitudes de música en tiempo
                        real.</p>
                    <h6>Uso de la Plataforma</h6>
                    <p>2.1 Al acceder y utilizar Elegans, aceptas cumplir con
                        estos términos y cualquier otra política que podamos publicar.</p>
                    <p>2.2 Solo puedes utilizar nuestra plataforma de acuerdo con todas las
                        leyes y regulaciones aplicables.</p>
                    <h6>Solicitudes de Música</h6>
                    <p>3.1 Elegans permite a los usuarios enviar solicitudes de
                        canciones a DJs en bares o discotecas participantes.</p>
                    <p>3.2 Nos reservamos el derecho de moderar y filtrar las solicitudes para
                        mantener un ambiente adecuado y seguro.</p>
                    <h6>Propiedad Intelectual</h6>
                    <p>4.1 Todos los derechos de propiedad intelectual relacionados con Elegans,
                        incluidos derechos de autor y marcas comerciales,
                        pertenecen a nosotros o a nuestros licenciatarios.</p>
                    <h6>Privacidad</h6>
                    <p>5.1 La privacidad de los usuarios es importante para nosotros. Consulta
                        nuestra Política de Privacidad para obtener información sobre cómo
                        recopilamos, usamos y protegemos tus datos personales.</p>
                    <h6>Limitación de Responsabilidad</h6>
                    <p>6.1 No somos responsables de ningún daño directo, indirecto, incidental,
                        especial, consecuente o punitivo derivado del uso de Elegans.</p>
                    <h6>Modificaciones</h6>
                    <p>7.1 Nos reservamos el derecho de modificar estos términos y condiciones
                        en cualquier momento. Las modificaciones entrarán en vigor al ser
                        publicadas en la plataforma.</p>
                    <h6>Legislación Aplicable</h6>
                    <p>8.1 Estos términos y condiciones se rigen por las leyes vigentes en [País
                        o Estado].</p>
                    <h6>Contacto</h6>
                    <p>9.1 Para cualquier pregunta sobre estos términos y condiciones, por favor
                        contáctanos a Admin@Elegans.com .</p>
                </div>
            </div>
        </div>
    </div>



</main>