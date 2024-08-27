<main class="content px-3 py-2">
    <div class="row justify-content-center">
        <!-- Panel Izquierdo: Lista de Usuarios -->
        <div id="user-list-area" class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Usuarios</h5>
                    <!-- Barra de Búsqueda -->
                    <input type="text" class="form-control mb-3" id="search-user" placeholder="Buscar usuario...">
                    
                    <!-- Lista de Usuarios -->
                    <ul class="list-group" id="user-list">
                        <!-- Aquí se llenará dinámicamente la lista de usuarios -->
                        <li class="list-group-item">Usuario 1</li>
                        <li class="list-group-item">Usuario 2</li>
                        <li class="list-group-item">Usuario 3</li>
                        <!-- Más usuarios -->
                    </ul>
                </div>
            </div>
        </div>

        <!-- Panel Derecho: Roles y Permisos -->
        <div id="role-permission-area" class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Roles y Permisos</h5>
                    
                    <!-- Información del Usuario Seleccionado -->
                    <div class="mb-3">
                        <label for="selected-user" class="form-label">Usuario Seleccionado:</label>
                        <input type="text" class="form-control" id="selected-user" readonly>
                    </div>

                    <!-- Editar Rol -->
                    <div class="mb-3">
                        <label for="role-selection" class="form-label">Rol del Usuario:</label>
                        <select class="form-select" id="role-selection">
                            <option value="admin">Administrador</option>
                            <option value="editor">Editor</option>
                            <option value="viewer">Visualizador</option>
                            <!-- Más roles -->
                        </select>
                    </div>

                    <!-- Lista de Permisos -->
                    <div id="permissions-list">
                        <h6>Permisos:</h6>
                        <!-- Aquí se llenarán los permisos según el rol seleccionado -->
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" id="permission-1">
                            <label class="form-check-label" for="permission-1">
                                Permiso 1
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" id="permission-2">
                            <label class="form-check-label" for="permission-2">
                                Permiso 2
                            </label>
                        </div>
                        <!-- Más permisos -->
                    </div>
                </div>
            </div>
        </div>
        <div id="dynamic-content" class="content-section" style="display: none;"></div>
    </div>
</main>
