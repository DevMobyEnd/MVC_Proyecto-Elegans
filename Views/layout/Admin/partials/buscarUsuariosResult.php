<main class="content px-3 py-2">
    <div class="row justify-content-center">
        <div class="mb-4">
            <h2>Buscar Usuarios</h2>
            <form id="buscarUsuariosForm" class="mb-3">
                <div class="row g-3 align-items-center">
                    <div class="col-auto">
                        <label for="criterios" class="col-form-label">Criterios:</label>
                    </div>
                    <div class="col-auto">
                        <input type="text" id="criterios" name="criterios" class="form-control">
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-primary">Buscar Usuarios</button>
                    </div>
                </div>
            </form>
            <div id="buscarUsuariosResult"></div>
        </div>
        <div id="dynamic-content" class="content-section" style="display: none;"></div>
    </div>
</main>