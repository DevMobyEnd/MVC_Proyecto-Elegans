document.addEventListener('DOMContentLoaded', function() {
    const sidebarLinks = document.querySelectorAll('.sidebar-link');
    const contentArea = document.getElementById('content-area');

    if (contentArea) {
        sidebarLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const page = this.getAttribute('href').split('=')[1];
                
                fetch(`content.php?page=${page}`)
                    .then(response => response.text())
                    .then(html => {
                        contentArea.innerHTML = html;
                    })
                    .catch(error => console.error('Error:', error));
            });
        });
    } else {
        console.error('Content area not found');
    }

    let currentPage = 1;
    const itemsPerPage = 7;

    // Cargar usuarios iniciales
    loadUsers(currentPage);

    // Paginación
    function loadUsers(page) {
        const listaUsuarios = document.getElementById('listaUsuarios');
        if (listaUsuarios) {
            fetch(`content.php?page=listaUsuarios&currentPage=${page}&itemsPerPage=${itemsPerPage}`)
                .then(response => response.text())
                .then(html => {
                    listaUsuarios.innerHTML = html;
                    createPagination();
                })
                .catch(error => console.error('Error:', error));
        } else {
            console.error('Lista de usuarios not found');
        }
    }

    // Crear paginación
    function createPagination() {
        fetch('content.php?page=contarUsuarios')
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
    const searchButton = document.getElementById('searchButton');
    const searchInput = document.getElementById('searchInput');
    const listaUsuarios = document.getElementById('listaUsuarios');

    if (searchButton && searchInput && listaUsuarios) {
        searchButton.addEventListener('click', function() {
            const searchTerm = searchInput.value;

            fetch(`content.php?page=buscarUsuarios&query=${encodeURIComponent(searchTerm)}`)
                .then(response => response.text())
                .then(html => {
                    listaUsuarios.innerHTML = html;
                })
                .catch(error => console.error('Error:', error));
        });
    } else {
        console.error('Search elements not found');
    }
});


// Datos para las gráficas (reemplaza con tus datos reales)
const usuariosData = {
    labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun'],
    datasets: [{
        label: 'Usuarios Registrados',
        data: [12, 19, 3, 5, 2, 3],
        backgroundColor: 'rgba(75, 192, 192, 0.2)',
        borderColor: 'rgba(75, 192, 192, 1)',
        borderWidth: 1
    }]
};

// Crear la gráfica
new Chart(document.getElementById('usuariosChart'), {
    type: 'bar',
    data: usuariosData,
    options: {
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});


const SolicitudesData = {
    labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun'],
    datasets: [{
        label: 'Solicitudes Pendientes',
        data: [12, 19, 3, 5, 2, 3],
        backgroundColor: 'rgba(75, 192, 192, 0.2)',
        borderColor: 'rgba(75, 192, 192, 1)',
        borderWidth: 1
    }]
};

// Crear la gráfica
new Chart(document.getElementById('SolicitudesChart'), {
    type: 'bar',
    data: usuariosData,
    options: {
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

// Repite para las otras dos gráficas
const DjData = {
    labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun'],
    datasets: [{
        label: 'DJs Activos',
        data: [12, 19, 3, 5, 2, 3],
        backgroundColor: 'rgba(75, 192, 192, 0.2)',
        borderColor: 'rgba(75, 192, 192, 1)',
        borderWidth: 1
    }]
};

// Crear la gráfica
new Chart(document.getElementById('DjChart'), {
    type: 'bar',
    data: usuariosData,
    options: {
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

