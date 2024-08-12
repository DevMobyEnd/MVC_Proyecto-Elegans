//Funcionalidades para la vista de registro

//Función de desinfección  para evitar ataques XSS
function sanitizeInput(input) {
    const div = document.createElement('div');
    div.textContent = input;
    return div.innerHTML;
}

// Constantes para los umbrales de fuerza de contraseña
const WEAK_THRESHOLD = 40;
const MODERATE_THRESHOLD = 60;
const STRONG_THRESHOLD = 80;

// Función para debounce
const debounce = (func, delay) => {
    let timeoutId;
    return (...args) => {
        clearTimeout(timeoutId);
        timeoutId = setTimeout(() => func.apply(null, args), delay);
    };
};

// Función para validar la contraseña
const validatePassword = (e) => {
    const password = e.target.value;
    let strength = 0;
    const progressBar = document.getElementById('password-strength');
    const passwordHelp = document.getElementById('passwordHelp');

    if (password.length >= 8) strength += 20;
    if (password.match(/[a-z]+/)) strength += 20;
    if (password.match(/[A-Z]+/)) strength += 20;
    if (password.match(/[0-9]+/)) strength += 20;
    if (password.match(/[$@#&!]+/)) strength += 20;

    progressBar.style.width = strength + '%';
    progressBar.setAttribute('aria-valuenow', strength);

    if (strength < WEAK_THRESHOLD) {
        progressBar.className = 'progress-bar bg-danger';
        passwordHelp.textContent = 'Contraseña débil';
    } else if (strength < MODERATE_THRESHOLD) {
        progressBar.className = 'progress-bar bg-warning';
        passwordHelp.textContent = 'Contraseña moderada';
    } else if (strength < STRONG_THRESHOLD) {
        progressBar.className = 'progress-bar bg-info';
        passwordHelp.textContent = 'Contraseña fuerte';
    } else {
        progressBar.className = 'progress-bar bg-success';
        passwordHelp.textContent = 'Contraseña muy fuerte';
    }
};

// Aplicar debounce a la función de validación de contraseña
const debouncedValidatePassword = debounce(validatePassword, 300);
document.getElementById('passwordInput').addEventListener('input', debouncedValidatePassword);

// Función para manejar el éxito del captcha
function onCaptchaSuccess(token) {
    document.getElementById('cf-turnstile-response').value = token;
}

document.getElementById('registerForm').addEventListener('submit', function(e) {
    e.preventDefault();

    let errors = validateForm(); // Use the validateForm function

    if (errors.length > 0) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            html: `<ul>${errors.map(error => `<li>${error}</li>`).join('')}</ul>`
        });
        return;
    }

    const formData = new FormData(this);
    // Añadir campos adicionales si es necesario
    formData.append('Foto_Perfil', document.getElementById('Foto_PerfilInput').files[0]);
    formData.set('Nombres', sanitizeInput(document.getElementById('nombresInput').value));
    formData.set('Apellidos', sanitizeInput(document.getElementById('apellidosInput').value));
    formData.set('NumerodeDocumento', sanitizeInput(document.getElementById('NumerodeDocumentoInput').value));
    formData.set('Apodo', sanitizeInput(document.getElementById('apodoInput').value));
    formData.set('CorreoElectronico', sanitizeInput(document.getElementById('emailInput').value));
    formData.append('cf-turnstile-response', document.getElementById('cf-turnstile-response').value);

    if (errors.length > 0) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            html: `<ul>${errors.map(error => `<li>${error}</li>`).join('')}</ul>`
        });
        return;
    }

    fetch(this.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.text())
    .then(result => {
        console.log(result);
        return JSON.parse(result);
    })
    .then(result => {
        if(result.status === 'success') {
            Swal.fire({
                icon: 'success',
                title: 'Éxito',
                text: result.message
            }).then(() => {
                window.location.href = result.redirect || '/';
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: result.message
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Ocurrió un error al procesar la solicitud: ' + error.message
        });
    });
});

function validateForm() {
    let errors = [];
    const Foto_Perfil = document.getElementById('Foto_PerfilInput').value;
    const Nombres = sanitizeInput(document.getElementById('nombresInput').value);
    const Apellidos = sanitizeInput(document.getElementById('apellidosInput').value);
    const NumerodeDocumento = sanitizeInput(document.getElementById('NumerodeDocumentoInput').value);
    const Apodo = sanitizeInput(document.getElementById('apodoInput').value);
    const CorreoElectronico = sanitizeInput(document.getElementById('emailInput').value);
    const password = document.getElementById('passwordInput').value;

    if (!Foto_Perfil) errors.push('La foto de perfil es requerida.');
    if (!Nombres) errors.push('El nombre es requerido.');
    if (!Apellidos) errors.push('El apellido es requerido.');
    if (!NumerodeDocumento) errors.push('El número de documento es requerido.');
    if (!Apodo) errors.push('El apodo es requerido.');
    if (!CorreoElectronico) errors.push('El correo electrónico es requerido.');
    if (!password) errors.push('La contraseña es requerida.');

    // Email validation
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (CorreoElectronico && !emailRegex.test(CorreoElectronico)) {
        errors.push('Por favor, ingrese un email válido.');
    }

    return errors;
}


// Funcionalidades para la vista la foto de perfil
function previewImage(input) {
    var preview = document.getElementById('profilePreview');
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
        }
        
        reader.readAsDataURL(input.files[0]);
    } else {
        preview.src = "/Public/dist/img/profile.jpg";
        preview.style.display = 'none';
    }
}


