<?php
require_once __DIR__ . '/Controller/AdminUsuarioController.php';



$controller = new AdminUsuarioController();
$controller->handleRequest();
?>



<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($tituloPagina) ? $tituloPagina : 'Panel de Administración - Elegans'; ?></title>
    <link rel="website icon" type="png" href="/Public/dist/img/Logo3.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</head>

<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
        <div class="container d-flex justify-content-between align-items-center">
            <a class="navbar-brand" href="#">Panel de Administración</a>
            <button onclick="window.location.href='../Helpers/logout.php'" class="btn btn-danger">
                <ion-icon name="log-out-outline" class="me-2"></ion-icon> Cerrar Sesión
            </button>
        </div>
    </nav>

    <div class="container">
        <!-- Dashboard Cards -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Total Usuarios</h5>
                        <div id="totalUsuarios" class="h2">-</div>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title d-flex justify-content-between align-items-center">
                            Registros por Mes
                            <button id="actualizarGrafico" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-arrow-clockwise"></i> Actualizar
                            </button>
                        </h5>
                        <canvas id="registrosPorMesChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Búsqueda y Filtros -->
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <form id="buscarUsuariosForm">
                            <div class="input-group">
                                <input type="text" id="criterios" name="criterios" class="form-control" placeholder="Buscar usuarios...">
                                <button class="btn btn-primary" type="submit">
                                    <i class="bi bi-search"></i> Buscar
                                </button>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-6">
                        <form id="obtenerUsuariosForm" class="d-flex gap-2">
                            <select id="limit" name="limit" class="form-select w-auto">
                                <option value="10">10 por página</option>
                                <option value="25">25 por página</option>
                                <option value="50">50 por página</option>
                            </select>
                            <div class="input-group w-auto">
                                <span class="input-group-text">Página</span>
                                <input type="number" id="page" name="page" class="form-control" value="1" min="1" style="width: 70px;">
                                <button type="submit" class="btn btn-primary">Ir</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabla de Usuarios -->
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title mb-3">Listado de Usuarios</h5>
                <div id="obtenerUsuariosResult" class="table-responsive"></div>
            </div>
        </div>

        <!-- Modal para actualizar rol -->
        <div class="modal fade" id="actualizarRolModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Actualizar Rol de Usuario</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <form id="actualizarRolForm">
                            <input type="hidden" id="usuarioId" name="usuarioId">
                            <div class="mb-3">
                                <label for="nuevoRol" class="form-label">Seleccionar Nuevo Rol</label>
                                <select id="nuevoRol" name="nuevoRol" class="form-select">
                                    <option value="1">admin</option>
                                    <option value="2">DJ</option>
                                    <option value="3">usuario natural</option>
                                </select>
                            </div>
                            <div class="text-end">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Loader -->
        <div id="loaderOverlay">
            <div class="loader-container">
                <div class="spinner-border text-light" role="status">
                    <span class="visually-hidden">Cargando...</span>
                </div>
            </div>
        </div>
    </div>

    <style>
        .card {
            border: none;
            border-radius: 10px;
        }

        .table th {
            background-color: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
        }

        .role-btn {
            padding: 6px 12px;
            border-radius: 6px;
            background-color: #17a2b8;
            color: white;
            cursor: pointer;
            font-size: 14px;
            border: none;
            transition: all 0.2s;
        }

        .role-btn:hover {
            background-color: #138496;
            transform: translateY(-1px);
        }

        #loaderOverlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1050;
            display: none;
        }

        .loader-container {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        .spinner-border {
            width: 3rem;
            height: 3rem;
        }

        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
    </style>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            let chartInstance = null;

            function formatDate(dateString) {
                if (!dateString) return 'N/A';
                const date = new Date(dateString);
                return date.toLocaleString('es-ES', {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                });
            }

            function actualizarTablaUsuarios(data) {
                if (!data || !data.usuarios) return;

                let tableHtml = '<table class="table table-hover">';
                tableHtml += `
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre Completo</th>
                            <th>Email</th>
                            <th>Fecha de Registro</th>
                            <th>Rol</th>
                        </tr>
                    </thead>`;
                tableHtml += '<tbody>';
                data.usuarios.forEach(function(usuario) {
                    tableHtml += `
                        <tr>
                            <td>${usuario.id || 'N/A'}</td>
                            <td>${(usuario.nombres || '') + ' ' + (usuario.apellidos || '')}</td>
                            <td>${usuario.Gmail || 'N/A'}</td>
                            <td>${formatDate(usuario.fecha_creacion)}</td>
                            <td>
                                <button class="role-btn" data-user-id="${usuario.id}" 
                                        data-role="${usuario.rol}" data-bs-toggle="modal" 
                                        data-bs-target="#actualizarRolModal">
                                    <i class="bi bi-person-gear"></i> 
                                    ${usuario.rol || 'Sin rol'}
                                </button>
                            </td>
                        </tr>`;
                });
                tableHtml += '</tbody></table>';
                tableHtml += `<div class="text-muted mt-2">Total de usuarios: ${data.total}</div>`;
                $('#obtenerUsuariosResult').html(tableHtml);
                $('#totalUsuarios').text(data.total || '-');
            }

            function actualizarGrafico() {
                $('#loaderOverlay').fadeIn();
                $.get('?action=obtenerRegistrosPorMes', function(data) {
                    if (chartInstance) {
                        chartInstance.destroy();
                    }

                    const ctx = document.getElementById('registrosPorMesChart').getContext('2d');
                    chartInstance = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: data.registrosPorMes.map(item => item.mes),
                            datasets: [{
                                label: 'Nuevos Usuarios',
                                data: data.registrosPorMes.map(item => item.total),
                                borderColor: '#0d6efd',
                                backgroundColor: 'rgba(13, 110, 253, 0.1)',
                                tension: 0.3,
                                fill: true
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: {
                                    position: 'top',
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                }).always(function() {
                    $('#loaderOverlay').fadeOut();
                });
            }

            // Cargar datos iniciales solo una vez
            $('#obtenerUsuariosForm').submit(function(e) {
                e.preventDefault();
                $('#loaderOverlay').fadeIn();
                $.get('?action=obtenerUsuarios&' + $(this).serialize(), function(data) {
                    actualizarTablaUsuarios(data);
                }).always(function() {
                    $('#loaderOverlay').fadeOut();
                });
            });

            // Ejecutar la carga inicial
            $('#obtenerUsuariosForm').submit();
            actualizarGrafico();

            // Actualizar gráfico solo cuando se hace clic en el botón
            $('#actualizarGrafico').click(function() {
                actualizarGrafico();
            });

            $('#buscarUsuariosForm').submit(function(e) {
                e.preventDefault();
                $('#loaderOverlay').fadeIn();
                $.get('?action=buscarUsuarios&' + $(this).serialize(), function(data) {
                    actualizarTablaUsuarios(data);
                }).always(function() {
                    $('#loaderOverlay').fadeOut();
                });
            });

            $('#actualizarRolForm').submit(function(e) {
                e.preventDefault();
                $('#loaderOverlay').fadeIn();

                const nuevoRolNombre = $('#nuevoRol option:selected').text();
                const formData = {
                    usuario_id: $('#usuarioId').val(),
                    nombre_rol: nuevoRolNombre
                };

                $.ajax({
                    url: '?action=actualizarRol',
                    method: 'POST',
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            $('#actualizarRolModal').modal('hide');
                            $('#obtenerUsuariosForm').submit();
                        } else {
                            alert('Error: ' + (response.error || 'Error desconocido'));
                        }
                    },
                    error: function(xhr, status, error) {
                        alert('Error: ' + error);
                    },
                    complete: function() {
                        $('#loaderOverlay').fadeOut();
                    }
                });
            });

            // Evento para el modal de actualización de rol
            $('#actualizarRolModal').on('show.bs.modal', function(event) {
                const button = $(event.relatedTarget);
                const userId = button.data('user-id');
                const currentRole = button.data('role');
                $('#usuarioId').val(userId);
                $('#nuevoRol').val(currentRole);
            });
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
</body>

</html>