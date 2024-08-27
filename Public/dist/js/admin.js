document.addEventListener('DOMContentLoaded', function() {
    const contentArea = document.getElementById('content-area');
    const listaUsuarios = document.getElementById('listaUsuarios');
    const searchButton = document.getElementById('searchButton');
    const searchInput = document.getElementById('buscarUsuario');
    let currentPage = 1;
    const itemsPerPage = 7;

    // Cargar usuarios iniciales
    loadUsers(currentPage);

    // Manejo de enlaces de la barra lateral
    const sidebarLinks = document.querySelectorAll('.sidebar-link');
    sidebarLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const page = this.getAttribute('href').split('=')[1];
            fetchContent(page);
        });
    });

    function fetchContent(page) {
        fetch(`/Views/layout/Admin/content.php?page=${page}`)
            .then(response => response.text())
            .then(html => {
                contentArea.innerHTML = html;
            })
            .catch(error => console.error('Error:', error));
    }

    // Cargar usuarios con paginación
    function loadUsers(page) {
        fetch(`/Views/layout/Admin/content.php?page=listaUsuarios&currentPage=${page}&itemsPerPage=${itemsPerPage}`)
            .then(response => response.text())
            .then(html => {
                listaUsuarios.innerHTML = html;
                createPagination();
            })
            .catch(error => console.error('Error:', error));
    }

    // Crear paginación
    function createPagination() {
        fetch('/Views/layout/Admin/content.php?page=contarUsuarios')
            .then(response => response.json())
            .then(data => {
                const totalItems = data.total;
                const totalPages = Math.ceil(totalItems / itemsPerPage);
                let paginationHTML = '';

                for (let i = 1; i <= totalPages; i++) {
                    paginationHTML += `<button class="pagination-btn" data-page="${i}">${i}</button>`;
                }

                const paginationElement = document.getElementById('pagination');
                if (paginationElement) {
                    paginationElement.innerHTML = paginationHTML;

                    document.querySelectorAll('.pagination-btn').forEach(btn => {
                        btn.addEventListener('click', function() {
                            currentPage = parseInt(this.getAttribute('data-page'));
                            loadUsers(currentPage);
                        });
                    });
                } else {
                    console.error('Pagination element not found');
                }
            })
            .catch(error => console.error('Error:', error));
    }

    // Búsqueda de usuarios
    if (searchButton) {
        searchButton.addEventListener('click', function() {
            buscarUsuarios();
        });
    }

    function buscarUsuarios() {
        const searchTerm = searchInput.value;
        fetch(`/Views/layout/Admin/content.php?page=buscarUsuarios&criterios=${encodeURIComponent(searchTerm)}`)
            .then(response => response.json())
            .then(usuarios => {
                actualizarTablaUsuarios(usuarios);
            })
            .catch(error => console.error('Error:', error));
    }

    function actualizarTablaUsuarios(usuarios) {
        const tbody = document.querySelector('#listaUsuarios tbody');
        if (tbody) {
            tbody.innerHTML = '';
            usuarios.forEach(usuario => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>${usuario.nombres} ${usuario.apellidos}</td>
                    <td>${usuario.Gmail}</td>
                    <td>${usuario.Apodo}</td>
                    <td>${usuario.rol}</td>
                    <td>${usuario.estado ? 'Activo' : 'Inactivo'}</td>
                    <td>
                        <button class="btn btn-warning btn-sm" onclick="editarUsuario(${usuario.id})">Editar</button>
                        <button class="btn btn-danger btn-sm" onclick="eliminarUsuario(${usuario.id})">Eliminar</button>
                    </td>
                `;
                tbody.appendChild(tr);
            });
        } else {
            console.error('Table body not found');
        }
    }

    function editarUsuario(id) {
        console.log(`Editando usuario con ID: ${id}`);
        // Implementar lógica para editar usuario
    }

    function eliminarUsuario(id) {
        if (confirm('¿Estás seguro de que quieres eliminar este usuario?')) {
            fetch(`/Views/layout/Admin/content.php?page=eliminarUsuario&id=${id}`, { method: 'POST' })
                .then(response => response.json())
                .then(result => {
                    if (result.success) {
                        loadUsers(currentPage);
                    } else {
                        alert('Error al eliminar el usuario');
                    }
                })
                .catch(error => console.error('Error:', error));
        }
    }

});
// function crearGrafica(ctx, data, label) {
//     new Chart(ctx, {
//         type: 'bar',
//         data: data,
//         options: {
//             responsive: true,
//             scales: {
//                 y: {
//                     beginAtZero: true,
//                     title: {
//                         display: true,
//                         text: 'Número de Usuarios'
//                     }
//                 },
//                 x: {
//                     title: {
//                         display: true,
//                         text: 'Mes'
//                     }
//                 }
//             },
//             plugins: {
//                 legend: {
//                     display: true,
//                     position: 'top'
//                 },
//                 title: {
//                     display: true,
//                     text: label
//                 }
//             }
//         }
//     });
// }

// Función para obtener datos de usuarios registrados
//     function obtenerDatosUsuarios() {
//         fetch('/Views/layout/Admin/content.php?action=obtenerDatosUsuarios')
//             .then(response => response.text())
//             .then(text => {
//                 console.log('Raw response:', text);
//                 try {
//                     // Attempt to parse as JSON
//                     const data = JSON.parse(text);
//                     actualizarGraficaUsuarios(data);
//                 } catch (error) {
//                     console.error('Error parsing JSON:', error);
//                     // If parsing fails, treat it as HTML
//                     if (text.trim().startsWith('<')) {
//                         console.log('Received HTML response');
//                         // Handle HTML response (e.g., display an error message)
//                         document.getElementById('errorMessage').innerHTML = text;
//                     } else {
//                         console.log('Received unexpected data:', text);
//                     }
//                 }
//             })
//             .catch(error => console.error('Error:', error));
//     }
//      // Función para actualizar la gráfica de usuarios
//      function actualizarGraficaUsuarios(data) {
//         const labels = data.map(item => item.mes);
//         const valores = data.map(item => item.total);

//         const usuariosData = {
//             labels: labels,
//             datasets: [{
//                 label: 'Usuarios Registrados',
//                 data: valores,
//                 backgroundColor: 'rgba(75, 192, 192, 0.2)',
//                 borderColor: 'rgba(75, 192, 192, 1)',
//                 borderWidth: 1
//             }]
//         };

//         crearGrafica(document.getElementById('usuariosChart').getContext('2d'), usuariosData, 'Usuarios Registrados');
//     }

//     // Llamar a la función para obtener y mostrar los datos
//     obtenerDatosUsuarios();

//     // Datos para las gráficas
//     function crearGrafica(ctx, data, label) {
//         new Chart(ctx, {
//             type: 'bar',
//             data: data,
//             options: {
//                 scales: {
//                     y: {
//                         beginAtZero: true
//                     }
//                 }
//             }
//         });
//     }

//     // Gráficas
//     const usuariosData = {
//         labels: ['Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
//         datasets: [{
//             label: 'Usuarios Registrados',
//             data: [12, 19, 3, 5, 2],
//             backgroundColor: 'rgba(75, 192, 192, 0.2)',
//             borderColor: 'rgba(75, 192, 192, 1)',
//             borderWidth: 1
//         }]
//     };

//     // const solicitudesData = {
//     //     labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun'],
//     //     datasets: [{
//     //         label: 'Solicitudes Pendientes',
//     //         data: [12, 19, 3, 5, 2, 3],
//     //         backgroundColor: 'rgba(75, 192, 192, 0.2)',
//     //         borderColor: 'rgba(75, 192, 192, 1)',
//     //         borderWidth: 1
//     //     }]
//     // };

//     // const djData = {
//     //     labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun'],
//     //     datasets: [{
//     //         label: 'DJs Activos',
//     //         data: [12, 19, 3, 5, 2, 3],
//     //         backgroundColor: 'rgba(75, 192, 192, 0.2)',
//     //         borderColor: 'rgba(75, 192, 192, 1)',
//     //         borderWidth: 1
//     //     }]
//     // };

//     crearGrafica(document.getElementById('usuariosChart').getContext('2d'), usuariosData, 'Usuarios Registrados');
//     // crearGrafica(document.getElementById('SolicitudesChart').getContext('2d'), solicitudesData, 'Solicitudes Pendientes');
//     // crearGrafica(document.getElementById('DjChart').getContext('2d'), djData, 'DJs Activos');
// });
