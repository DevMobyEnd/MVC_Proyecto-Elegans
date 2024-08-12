//Funciones de la Vista del Instalador 
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('dbConfigForm');
    form.addEventListener('submit', function(e) {
        e.preventDefault();

        // Validación del lado del cliente
        const dbHost = form.querySelector('#db_host').value.trim();
        const dbName = form.querySelector('#db_name').value.trim();
        const dbUsername = form.querySelector('#db_username').value.trim();
        const dbPassword = form.querySelector('#db_password').value;

        let errors = [];
        if (!dbHost) errors.push('El host de la base de datos es requerido.');
        if (!dbName) errors.push('El nombre de la base de datos es requerido.');
        if (!dbUsername) errors.push('El usuario de la base de datos es requerido.');

        if (errors.length > 0) {
            Swal.fire({
                icon: 'error',
                title: 'Error de validación',
                html: errors.join('<br>')
            });
            return;
        }

        // Verificar si la base de datos ya existe
        fetch('/Views/InstallView.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `check_db=1&db_host=${dbHost}&db_name=${dbName}&db_username=${dbUsername}&db_password=${dbPassword}`
        })
        .then(response => {
            console.log('Raw response:', response);
            return response.text();  // Get the raw text first
        })
        .then(text => {
            console.log('Response text:', text);
            return JSON.parse(text);  // Now parse it as JSON
        })
        .then(data => {
            if (data.exists) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Base de datos existente',
                    text: 'La base de datos ya existe. ¿Desea continuar y sobrescribirla?',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, continuar',
                    cancelButtonText: 'No, cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            } else {
                form.submit();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Ocurrió un error al verificar la base de datos.'
            });
        });
    });
});