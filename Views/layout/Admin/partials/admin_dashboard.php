<main class="content px-3 py-2">
    <div class="row justify-content-center">
        <div id="content-area" class="col-md-9">
            <div id="dashboard-content" class="content-section">
                <div class="mt-4">
                    <h1>Dashboard</h1>
                    <div class="row">
                        <!-- Card Usuarios Registrados -->
                        <div class="col-md-8 mb-4">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Usuarios Registrados</h5>
                                    <p class="card-text">
                                        Total: <?php echo isset($totalUsuarios) ? $totalUsuarios : 'N/A'; ?>
                                    </p>
                                    <div style="height: 300px;">
                                        <canvas id="usuariosChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="dynamic-content" class="content-section" style="display: none;"></div>
        </div>
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var ctx = document.getElementById('usuariosChart').getContext('2d');
    var registrosPorMes = <?php echo json_encode($registrosPorMes); ?>;

    var labels = registrosPorMes.map(function(item) {
        return item.mes;
    });

    var datos = registrosPorMes.map(function(item) {
        return item.total;
    });

    var chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Usuarios registrados por mes',
                data: datos,
                borderColor: 'rgb(75, 192, 192)',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });
});
</script>