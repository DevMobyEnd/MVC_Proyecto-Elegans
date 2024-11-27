// Función para sanitizar input (prevenir XSS)
function sanitizeInput(input) {
    let div = document.createElement("div");
    div.textContent = input;
    return div.innerHTML;
}

// Constantes para los umbrales de fortaleza de contraseña
const WEAK_THRESHOLD = 40;
const MODERATE_THRESHOLD = 60;
const STRONG_THRESHOLD = 80;

// Función para debounce (retrasar la ejecución de eventos frecuentes)
const debounce = (func, delay) => {
    let timeoutId;
    return (...args) => {
        clearTimeout(timeoutId);
        timeoutId = setTimeout(() => func.apply(null, args), delay);
    };
};

// Validación de fortaleza de contraseña
const validatePassword = (event) => {
    let password = event.target.value;
    let strength = 0;
    let progressBar = document.getElementById("password-strength");
    let helpText = document.getElementById("passwordHelp");

    // Criterios de fortaleza
    if (password.length >= 8) strength += 20;
    if (password.match(/[a-z]+/)) strength += 20;
    if (password.match(/[A-Z]+/)) strength += 20;
    if (password.match(/[0-9]+/)) strength += 20;
    if (password.match(/[$@#&!]+/)) strength += 20;

    // Actualizar barra de progreso
    progressBar.style.width = strength + "%";
    progressBar.setAttribute("aria-valuenow", strength);

    // Actualizar mensaje según fortaleza
    if (strength < WEAK_THRESHOLD) {
        progressBar.className = "progress-bar bg-danger";
        helpText.textContent = "Contraseña débil";
    } else if (strength < MODERATE_THRESHOLD) {
        progressBar.className = "progress-bar bg-warning";
        helpText.textContent = "Contraseña moderada";
    } else if (strength < STRONG_THRESHOLD) {
        progressBar.className = "progress-bar bg-info";
        helpText.textContent = "Contraseña fuerte";
    } else {
        progressBar.className = "progress-bar bg-success";
        helpText.textContent = "Contraseña muy fuerte";
    }
};

// Aplicar debounce a la validación de contraseña
const debouncedValidatePassword = debounce(validatePassword, 300);

// Manejar respuesta del captcha
function onCaptchaSuccess(token) {
    document.getElementById("cf-turnstile-response").value = token;
}

// Validación del paso 1 del formulario
function validateStep1() {
    let errors = [];
    
    let nombres = sanitizeInput(document.getElementById("nombresInput").value.trim());
    let apellidos = sanitizeInput(document.getElementById("apellidosInput").value.trim());
    let documento = sanitizeInput(document.getElementById("NumerodeDocumentoInput").value.trim());
    let apodo = sanitizeInput(document.getElementById("apodoInput").value.trim());

    if (!nombres) errors.push("El nombre es requerido.");
    if (!apellidos) errors.push("El apellido es requerido.");
    if (!documento) errors.push("El número de documento es requerido.");
    if (!apodo) errors.push("El apodo es requerido.");

    if (errors.length > 0) {
        Swal.fire({
            icon: "error",
            title: "Campos requeridos",
            html: errors.join("<br>"),
            confirmButtonText: "Entendido"
        });
        return false;
    }
    return true;
}

// Validación del paso 2 del formulario
function validateStep2() {
    let errors = [];
    let email = sanitizeInput(document.getElementById("emailInput").value);
    let password = document.getElementById("passwordInput").value;

    if (!email) {
        errors.push("El correo electrónico es requerido.");
    }
    if (!password) {
        errors.push("La contraseña es requerida.");
    }
    if (email && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
        errors.push("Por favor, ingrese un email válido.");
    }

    return errors;
}

// Event Listeners y configuración inicial
document.getElementById("passwordInput").addEventListener("input", debouncedValidatePassword);

window.onload = function() {
    let turnstile = document.querySelector(".cf-turnstile");
    if (turnstile) {
        turnstile.addEventListener("success", function(e) {
            onCaptchaSuccess(e.detail.token);
        });
    }
};

// Manejo de navegación entre pasos
document.getElementById("nextStepBtn").addEventListener("click", function(e) {
    e.preventDefault();
    let validationResult = validateStep1();
    if (validationResult) {
        showStep(2);
    }
});

// Variables y funciones para manejo de imágenes
let croppedImageData = null;

function handleImageCrop(data) {
    croppedImageData = data;
}

function previewImage(input) {
    let preview = document.getElementById("profilePreview");
    if (input.files && input.files[0]) {
        let reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = "block";
        };
        reader.readAsDataURL(input.files[0]);
    } else {
        preview.src = "/Public/dist/img/profile.jpg";
        preview.style.display = "none";
    }
}

function openCropperModal() {
    $("#cropModal").modal("show");
}

function showStep(step) {
    let steps = document.querySelectorAll(".register-step");
    steps.forEach(step => {
        step.style.display = "none";
    });
    let currentStep = document.getElementById(`step${step}`);
    if (currentStep) {
        currentStep.style.display = "block";
    }
}

// Manejo del envío del formulario
document.getElementById("registerButton").addEventListener("click", function(e) {
    e.preventDefault();
    
    let errors = validateStep2();
    if (errors.length > 0) {
        Swal.fire({
            icon: "error",
            title: "Error",
            html: `<ul>${errors.map(error => `<li>${error}</li>`).join("")}</ul>`
        });
        return;
    }

    let formData = new FormData(document.getElementById("registerForm"));

    // Añadir el token CSRF al FormData
    // formData.append("csrf_token", document.getElementById("csrf_token").value);
    
    // Manejar datos de la imagen
    let croppedImage = document.getElementById("croppedImageData").value;
    let profileInput = document.getElementById("Foto_PerfilInput");
    
    if (croppedImage) {
        formData.set("croppedImageData", croppedImage);
    } else if (profileInput.files.length > 0) {
        formData.set("Foto_Perfil", profileInput.files[0]);
    }

    // Agregar datos sanitizados al FormData
    formData.set("Nombres", sanitizeInput(document.getElementById("nombresInput").value));
    formData.set("Apellidos", sanitizeInput(document.getElementById("apellidosInput").value));
    formData.set("NumerodeDocumento", sanitizeInput(document.getElementById("NumerodeDocumentoInput").value));
    formData.set("Apodo", sanitizeInput(document.getElementById("apodoInput").value));
    formData.set("CorreoElectronico", sanitizeInput(document.getElementById("emailInput").value));

    // Agregar respuesta del captcha
    formData.append("cf-turnstile-response", document.getElementById("cf-turnstile-response").value);
    formData.append("croppedImageData", document.getElementById("croppedImageData").value);

    // Log para debugging
    console.log("Datos del formulario:", Object.fromEntries(formData));

    // Enviar formulario
    fetch(document.getElementById("registerForm").action, {
        method: "POST",
        body: formData,
        headers: {
            "X-Requested-With": "XMLHttpRequest"
        }
    })
    .then(response => {
        console.log("Respuesta del servidor:", response);
        if (response.redirected) {
            // Si la respuesta es una redirección, seguirla
            window.location.href = response.url;
            return;
        }
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.text();
    })
    .then(result => {
        if (!result) return; // Si no hay resultado (debido a una redirección), terminar aquí
        console.log("Resultado en texto:", result);
        try {
            return JSON.parse(result);
        } catch (error) {
            console.error("Error al parsear JSON:", error);
            console.error("Respuesta del servidor:", result);
            throw new Error("Respuesta del servidor no es JSON válido");
        }
    })
    .then(data => {
        if (!data) return; // Si no hay datos (debido a una redirección), terminar aquí
        console.log("Resultado parseado:", data);
        if (data.status === "success") {
            Swal.fire({
                icon: "success",
                title: "Éxito",
                text: data.message
            }).then(() => {
                window.location.href = data.redirect || "/";
            });
        } else {
            console.error("Error del servidor:", data);
            Swal.fire({
                icon: "error",
                title: "Error",
                text: data.message,
                footer: data.debug ? `Detalles de depuración: ${data.debug}` : ""
            });
        }
    })
    .catch(error => {
        console.error("Error en la solicitud:", error);
        Swal.fire({
            icon: "error",
            title: "Error",
            text: "Hubo un problema al registrar el usuario. Por favor, intente nuevamente.",
            footer: `Detalles del error: ${error.message}`
        });
    });
});

// Event listeners para manejo de imágenes
document.getElementById("selectImageBtn").addEventListener("click", function() {
    document.getElementById("Foto_PerfilInput").click();
});

document.getElementById("Foto_PerfilInput").addEventListener("change", function() {
    previewImage(this);
});

// Mostrar el primer paso al cargar
showStep(1);