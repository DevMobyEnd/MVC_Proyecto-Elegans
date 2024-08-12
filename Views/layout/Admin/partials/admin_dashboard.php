<main class="content px-3 py-2">
    <div class="row justify-content-center">
        <div id="content-area" class="col-md-9">
            <div id="dashboard-content" class="content-section">
                <div class="mt-4">
                    <h1>Dashboard</h1>
                    <div class="row">
                        <!-- Card Usuarios Registrados -->
                        <div class="col-md-4 mb-4">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Usuarios Registrados</h5>
                                    <canvas id="usuariosChart"></canvas>
                                </div>
                            </div>
                        </div>
                        <!-- Card Solicitudes Pendientes -->
                        <div class="col-md-4 mb-4">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Solicitudes Pendientes</h5>
                                    <canvas id="SolicitudesChart"></canvas>
                                </div>
                            </div>
                        </div>
                        <!-- Card DJs Activos -->
                        <div class="col-md-4 mb-4">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">DJs Activos</h5>
                                    <canvas id="DjChart"></canvas>
                                </div>
                            </div>
                        </div>


                    </div>
                    <!-- Se pueden agregar más tarjetas o gráficos aquí -->
                </div>
            </div>
            <!-- Aquí se cargará el contenido dinámico de otras secciones -->

            <div id="dynamic-content" class="content-section" style="display: none;"></div>
        </div>
</main>