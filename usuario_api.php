<?php
require_once __DIR__ . '/Models/AdminUsuarioModel.php';

class AdminUsuarioController
{
    private $usuarioModel;

    public function __construct()
    {
        $this->usuarioModel = new UsuarioModel();
    }

    public function handleRequest()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action'])) {
            $this->handleGetRequest();
        } elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action'])) {
            $this->handlePostRequest();
        }
    }

    private function handleGetRequest()
    {
        header('Content-Type: application/json');
        $action = $_GET['action'];
        $response = [];

        switch ($action) {
            case 'obtenerUsuarios':
                $response = $this->obtenerUsuarios();
                break;
            case 'obtenerRegistrosPorMes':
                $response['registrosPorMes'] = $this->usuarioModel->obtenerRegistrosPorMes();
                break;
            case 'buscarUsuarios':
                $response = $this->buscarUsuarios();
                break;
            default:
                $response['error'] = 'Acción no válida';
        }

        echo json_encode($response);
        exit;
    }

    private function handlePostRequest()
    {
        header('Content-Type: application/json');
        $action = $_GET['action'];
        $response = [];

        if ($action === 'actualizarRol') {
            $response = $this->actualizarRol();
        }

        echo json_encode($response);
        exit;
    }

    private function obtenerUsuarios()
    {
        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $limit = isset($_GET['limit']) ? intval($_GET['limit']) : 10;
        $offset = ($page - 1) * $limit;
        return [
            'usuarios' => $this->usuarioModel->obtenerUsuarios($offset, $limit),
            'total' => $this->usuarioModel->contarUsuarios()
        ];
    }

    private function buscarUsuarios()
    {
        $criterios = isset($_GET['criterios']) ? $_GET['criterios'] : '';
        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $limit = isset($_GET['limit']) ? intval($_GET['limit']) : 10;
        $offset = ($page - 1) * $limit;
        return [
            'usuarios' => $this->usuarioModel->buscarUsuarios($criterios, $offset, $limit)
        ];
    }

    private function actualizarRol()
    {
        $usuarioId = isset($_POST['usuario_id']) ? intval($_POST['usuario_id']) : null;
        $nombreRol = isset($_POST['nombre_rol']) ? $_POST['nombre_rol'] : null;

        if ($usuarioId && $nombreRol) {
            $resultado = $this->usuarioModel->actualizarRolUsuario($usuarioId, $nombreRol);
            if ($resultado === true) {
                return [
                    'success' => true,
                    'message' => 'Rol actualizado correctamente'
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Error al actualizar el rol: ' . $resultado
                ];
            }
        } else {
            return [
                'success' => false,
                'message' => 'Datos insuficientes'
            ];
        }
    }
}

$controller = new AdminUsuarioController();
$controller->handleRequest();
?>



<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API de Usuarios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <div class="container mt-5">
        <h1 class="mb-4">API de Usuarios</h1>

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

        <!-- Loader Overlay -->
        <div id="loaderOverlay" style="display: none;">
            <div class="spinner-border" role="status">
                <span class="visually-hidden">Buscando...</span>
            </div>
        </div>

        <div class="mb-4">
            <h2>Registros por Mes</h2>
            <button id="obtenerRegistrosPorMes" class="btn btn-primary">Obtener Registros por Mes</button>
            <canvas id="registrosPorMesChart" width="400" height="200"></canvas>
        </div>

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
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
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

            $('#obtenerUsuariosForm').submit(function(e) {
                e.preventDefault();

                // Mostrar el loader
                $('#loaderOverlay').fadeIn();

                $.get('?action=obtenerUsuarios&' + $(this).serialize(), function(data) {
                    let tableHtml = '<table class="table table-striped">';
                    tableHtml += '<thead><tr><th>ID</th><th>Nombre</th><th>Email</th><th>Fecha de Registro</th> <th>Rol</th></tr></thead>';
                    tableHtml += '<tbody>';
                    data.usuarios.forEach(function(usuario) {
                        tableHtml += `<tr>
                        <td>${usuario.id || 'N/A'}</td>
                        <td>${(usuario.nombres || '') + ' ' + (usuario.apellidos || '')}</td>
                        <td>${usuario.Gmail || 'N/A'}</td>
                        <td>${formatDate(usuario.fecha_creacion)}</td>
                        <td>
                            <button class="btn btn-info role-btn" data-user-id="${usuario.id}" data-role="${usuario.rol}" data-bs-toggle="modal" data-bs-target="#actualizarRolModal">
                                <i class="bi bi-pencil-fill"></i> ${usuario.rol || 'Sin rol asignado'}
                            </button>
                        </td>
                    </tr>`;
                    });
                    tableHtml += '</tbody></table>';
                    tableHtml += `<p>Total de usuarios: ${data.total}</p>`;
                    $('#obtenerUsuariosResult').html(tableHtml);

                    // Ocultar el loader
                    $('#loaderOverlay').fadeOut();
                });
            });

            $('#buscarUsuariosForm').submit(function(e) {
                e.preventDefault();

                // Mostrar el loader
                $('#loaderOverlay').fadeIn();

                $.get('?action=buscarUsuarios&' + $(this).serialize(), function(data) {
                    let tableHtml = '<table class="table table-striped">';
                    tableHtml += '<thead><tr><th>ID</th><th>Nombre</th><th>Email</th><th>Fecha de Registro</th></tr></thead>';
                    tableHtml += '<tbody>';
                    data.usuarios.forEach(function(usuario) {
                        tableHtml += `<tr>
                        <td>${usuario.id}</td>
                        <td>${usuario.nombres} ${usuario.apellidos}</td>
                        <td>${usuario.Gmail}</td>
                        <td>${usuario.fecha_creacion}</td>
                    </tr>`;
                    });
                    tableHtml += '</tbody></table>';
                    $('#buscarUsuariosResult').html(tableHtml);

                    // Ocultar el loader
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
                            alert('Rol actualizado correctamente');
                            $('#actualizarRolModal').modal('hide');
                            $('#obtenerUsuariosForm').submit();
                        } else {
                            alert('Error al actualizar el rol: ' + (response.error || 'Error desconocido'));
                        }
                    },
                    error: function(xhr, status, error) {
                        alert('Error al actualizar el rol: ' + error);
                    },
                    complete: function() {
                        $('#loaderOverlay').fadeOut();
                    }
                });
            });

            $('#obtenerRegistrosPorMes').click(function() {
                // Mostrar el loader
                $('#loaderOverlay').fadeIn();

                $.get('?action=obtenerRegistrosPorMes', function(data) {
                    const ctx = document.getElementById('registrosPorMesChart').getContext('2d');
                    new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: data.registrosPorMes.map(item => item.mes),
                            datasets: [{
                                label: 'Registros por Mes',
                                data: data.registrosPorMes.map(item => item.total),
                                borderColor: 'rgb(75, 192, 192)',
                                tension: 0.1
                            }]
                        }
                    });

                    // Ocultar el loader
                    $('#loaderOverlay').fadeOut();
                });
            });
        });
    </script>

    <!-- JS de Bootstrap y popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
</body>
<style>
    .role-btn {
        padding: 5px 10px;
        border-radius: 5px;
        background-color: #17a2b8;
        color: white;
        cursor: pointer;
        font-size: 14px;
        border: none;
        text-align: center;
    }

    .role-btn:hover {
        background-color: #138496;
    }


    /* Loader Overlay (Fondo oscuro que cubre toda la pantalla) */
    #loaderOverlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        /* Fondo semi-transparente */
        z-index: 1050;
        /* Asegúrate de que esté encima de otros elementos */
        display: none;
        /* Oculto por defecto */
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* Tamaño del Spinner */
    .spinner-border {
        width: 3rem;
        height: 3rem;
        border-width: 0.3em;
    }
</style>

</html>