document.addEventListener("DOMContentLoaded", function () {
    // Mostrar la ruta actual para debugging
    console.log("Ruta actual:", window.location.pathname);
    console.log("URL completa:", window.location.href);

    let form = document.getElementById("dbConfigForm");
    if (!form) {
        console.error("No se encontró el formulario con ID 'dbConfigForm'");
        return;
    }

    form.addEventListener("submit", function (event) {
        event.preventDefault();

        // Obtener y validar los valores del formulario
        let host = form.querySelector("#db_host").value.trim(),
            name = form.querySelector("#db_name").value.trim(),
            username = form.querySelector("#db_username").value.trim(),
            password = form.querySelector("#db_password").value,
            errors = [];

        // Log de los valores para debugging
        console.log("Valores del formulario:", {
            host,
            name,
            username,
            passwordLength: password ? password.length : 0
        });

        if (!host) errors.push("El host de la base de datos es requerido.");
        if (!name) errors.push("El nombre de la base de datos es requerido.");
        if (!username) errors.push("El usuario de la base de datos es requerido.");

        if (errors.length > 0) {
            Swal.fire({
                icon: "error",
                title: "Error de validación",
                html: errors.join("<br>")
            });
            return;
        }

        // Mostrar loading
        Swal.fire({
            title: 'Verificando conexión...',
            text: 'Por favor espera mientras verificamos la conexión a la base de datos',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        // Verificar la base de datos
        verificarBaseDatos(host, name, username, password)
            .then(response => {
                console.log("Respuesta de verificación:", response);
                Swal.close();
                if (response.exists) {
                    return Swal.fire({
                        icon: "warning",
                        title: "Base de datos existente",
                        text: "La base de datos ya existe. ¿Desea continuar con la configuración?",
                        showCancelButton: true,
                        confirmButtonText: "Sí, continuar",
                        cancelButtonText: "No, cancelar"
                    });
                }
                return { isConfirmed: true };
            })
            .then(result => {
                if (result.isConfirmed) {
                    console.log("Procediendo a guardar configuración...");
                    return guardarConfiguracion(host, name, username, password);
                }
            })
            .then(saveResult => {
                if (saveResult && saveResult.success) {
                    Swal.fire({
                        icon: "success",
                        title: "Configuración guardada",
                        text: saveResult.message
                    }).then(() => {
                        window.location.href = "/index.php";
                    });
                }
            })
            .catch(error => {
                console.error('Error detallado:', error);

                // Mostrar diálogo de error con más detalles
                Swal.fire({
                    icon: "error",
                    title: "Error de conexión",
                    html: `
                        <p>Hubo un error al conectar con el servidor. Por favor verifica:</p>
                        <ul style="text-align: left; display: inline-block;">
                            <li>Que el servidor PHP esté activo</li>
                            <li>Que InstallView.php exista en la carpeta Views</li>
                            <li>Que la ruta al archivo sea correcta</li>
                        </ul>
                        <p><strong>Detalles técnicos:</strong></p>
                        <p style="font-size: 0.9em; color: #666;">${error.message}</p>
                    `
                });
            });
    });
});

function verificarBaseDatos(host, name, username, password) {
    // Determinar la ruta al archivo PHP
    let baseUrl = window.location.pathname.includes('Public')
        ? '../../Views/InstallView.php'
        : '../Views/InstallView.php';

    console.log('Intentando conectar a:', baseUrl);

    return fetch(baseUrl, {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: `check_db=1&db_host=${encodeURIComponent(host)}&db_name=${encodeURIComponent(name)}&db_username=${encodeURIComponent(username)}&db_password=${encodeURIComponent(password)}`
    })
        .then(response => {
            console.log('Estado de la respuesta:', response.status, response.statusText);
            if (!response.ok) {
                throw new Error(`Error HTTP: ${response.status} ${response.statusText}`);
            }
            return response.text();
        })
        .then(text => {
            console.log("Respuesta del servidor (raw):", text);
            try {
                let jsonResponse = JSON.parse(text);
                // Si la base de datos no existe, devolvemos un objeto con exists: false
                if (!jsonResponse.exists && jsonResponse.success) {
                    return { exists: false, success: true };
                }
                return jsonResponse;
            } catch (e) {
                console.error("Error al parsear respuesta:", text);
                throw new Error(`Error en la respuesta del servidor: ${text}`);
            }
        });
}

function guardarConfiguracion(host, name, username, password) {
    let baseUrl = window.location.pathname.includes('Public')
        ? '../../Views/InstallView.php'
        : '../Views/InstallView.php';

    console.log('Guardando configuración en:', baseUrl);

    return fetch(baseUrl, {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: `db_host=${encodeURIComponent(host)}&db_name=${encodeURIComponent(name)}&db_username=${encodeURIComponent(username)}&db_password=${encodeURIComponent(password)}`
    })
        .then(response => {
            if (!response.ok) {
                throw new Error(`Error HTTP: ${response.status} ${response.statusText}`);
            }
            return response.text();
        })
        .then(text => {
            console.log("Respuesta del servidor (guardar):", text);
            try {
                return JSON.parse(text);
            } catch (e) {
                throw new Error(`Error al procesar la respuesta del servidor: ${text}`);
            }
        });
}