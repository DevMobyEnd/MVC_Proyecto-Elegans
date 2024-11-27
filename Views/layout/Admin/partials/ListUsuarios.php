<main class="content px-3 py-2">
    <div class="row justify-content-center">
    <div class="mb-4">
            <h2>Obtener Usuarios</h2>
            <form id="obtenerUsuariosForm" class="mb-3">
                <div class="row g-3 align-items-center">
                    <div class="col-auto">
                        <label for="page" class="col-form-label">Página:</label>
                    </div>
                    <div class="col-auto">
                        <input type="number" id="page" name="page" class="form-control" value="1" min="1">
                    </div>
                    <div class="col-auto">
                        <label for="limit" class="col-form-label">Límite:</label>
                    </div>
                    <div class="col-auto">
                        <input type="number" id="limit" name="limit" class="form-control" value="10" min="1">
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-primary">Obtener Usuarios</button>
                    </div>
                </div>
            </form>
            <div id="obtenerUsuariosResult"></div>
        </div>

        <!-- Modal para actualizar rol -->
        <div class="modal fade" id="actualizarRolModal" tabindex="-1" aria-labelledby="actualizarRolModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="actualizarRolModalLabel">Actualizar Rol</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="actualizarRolForm">
                            <input type="hidden" id="usuarioId" name="usuarioId">
                            <div class="mb-3">
                                <label for="nuevoRol" class="form-label">Nuevo Rol</label>
                                <select id="nuevoRol" name="nuevoRol" class="form-select">
                                    <option value="1">admin</option>
                                    <option value="2">DJ</option>
                                    <option value="3">usuario natural</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Actualizar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- <div id="dynamic-content" class="content-section" style="display: none;"></div> -->
</main>

