document.addEventListener('DOMContentLoaded', function() {
    const sidebarLinks = document.querySelectorAll('.sidebar-link');
    const contentArea = document.getElementById('content-area');

    sidebarLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const page = this.getAttribute('data-page');
            if (page) {
                loadContent(page);
            }
        });
    });

    function loadContent(page) {
        fetch(`/Views/layout/Admin/partials/${page}.php`)
            .then(response => response.text())
            .then(data => {
                contentArea.innerHTML = data;
                // Inicializa la funcionalidad específica de la página después de cargar el contenido
                initializePageFunctionality(page);
            })
            .catch(error => {
                console.error('Error:', error);
                contentArea.innerHTML = '<p>Error al cargar el contenido.</p>';
            });
    }

    function initializePageFunctionality(page) {
        if (page === 'lista_usuarios') {
            loadUsers();
        }
        // Añade más casos según sea necesario para otras páginas
    }

    // function loadUsers() {
    //     const listaUsuarios = document.getElementById('listaUsuarios');
    //     if (listaUsuarios) {
    //         // Aquí puedes poner el código para cargar los usuarios
    //         fetch('/ruta/a/tu/api/usuarios') // Ajusta esta URL a tu API real
    //             .then(response => response.json())
    //             .then(users => {
    //                 const tbody = listaUsuarios.querySelector('tbody');
    //                 tbody.innerHTML = ''; // Limpia el contenido existente
    //                 users.forEach(user => {
    //                     const row = `
    //                         <tr>
    //                             <td>${user.nombre}</td>
    //                             <td>${user.correo}</td>
    //                             <td>${user.apodo}</td>
    //                             <td>${user.rol}</td>
    //                             <td>${user.estado}</td>
    //                             <td>
    //                                 <button class="btn btn-sm btn-primary">Editar</button>
    //                                 <button class="btn btn-sm btn-danger">Eliminar</button>
    //                             </td>
    //                         </tr>
    //                     `;
    //                     tbody.innerHTML += row;
    //                 });
    //             })
    //             .catch(error => {
    //                 console.error('Error al cargar usuarios:', error);
    //                 listaUsuarios.innerHTML = '<p>Error al cargar la lista de usuarios.</p>';
    //             });
    //     } else {
    //         console.error('El elemento listaUsuarios no se encontró en el DOM');
    //     }
    // }
});