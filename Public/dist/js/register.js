document.cookie = "name=value; SameSite=None; Secure";

// Función de desinfección para evitar ataques XSS
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

// Función para validar el primer paso del formulario
function validateStep1() {
    let errors = [];
    const Foto_Perfil = document.getElementById('Foto_PerfilInput').files[0];
    const Nombres = sanitizeInput(document.getElementById('nombresInput').value.trim());
    const Apellidos = sanitizeInput(document.getElementById('apellidosInput').value.trim());
    const NumerodeDocumento = sanitizeInput(document.getElementById('NumerodeDocumentoInput').value.trim());
    const Apodo = sanitizeInput(document.getElementById('apodoInput').value.trim());

    if (!Foto_Perfil) errors.push('La foto de perfil es requerida.');
    if (!Nombres) errors.push('El nombre es requerido.');
    if (!Apellidos) errors.push('El apellido es requerido.');
    if (!NumerodeDocumento) errors.push('El número de documento es requerido.');
    if (!Apodo) errors.push('El apodo es requerido.');

    if (errors.length > 0) {
        Swal.fire({
            icon: 'error',
            title: 'Campos requeridos',
            html: errors.join('<br>'),
            confirmButtonText: 'Entendido'
        });
        return false;
    }

    return true;
}

// Función para validar el segundo paso del formulario
function validateStep2() {
    let errors = [];
    const CorreoElectronico = sanitizeInput(document.getElementById('emailInput').value);
    const password = document.getElementById('passwordInput').value;

    if (!CorreoElectronico) errors.push('El correo electrónico es requerido.');
    if (!password) errors.push('La contraseña es requerida.');

    // Email validation
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (CorreoElectronico && !emailRegex.test(CorreoElectronico)) {
        errors.push('Por favor, ingrese un email válido.');
    }

    return errors;
}

// Manejo del evento para avanzar al siguiente paso
document.getElementById('nextStepBtn').addEventListener('click', function(e) {
    e.preventDefault();

    let errors = validateStep1();

    if (errors.length > 0) {
        Swal.fire({
            icon: 'error',
            title: 'Campos requeridos',
            html: `<ul>${errors.map(error => `<li>${error}</li>`).join('')}</ul>`,
            confirmButtonText: 'Entendido'
        });
    } else {
        showStep(2);  // Mostrar el segundo paso solo si no hay errores
    }
});

// Manejo del evento submit del formulario
document.getElementById('registerButton').addEventListener('click', function(e) {
    e.preventDefault();

    let errors = validateStep2();

    if (errors.length > 0) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            html: `<ul>${errors.map(error => `<li>${error}</li>`).join('')}</ul>`
        });
        return;
    }

    const formData = new FormData(document.getElementById('registerForm'));
    formData.append('Foto_Perfil', document.getElementById('Foto_PerfilInput').files[0]);
    formData.set('Nombres', sanitizeInput(document.getElementById('nombresInput').value));
    formData.set('Apellidos', sanitizeInput(document.getElementById('apellidosInput').value));
    formData.set('NumerodeDocumento', sanitizeInput(document.getElementById('NumerodeDocumentoInput').value));
    formData.set('Apodo', sanitizeInput(document.getElementById('apodoInput').value));
    formData.set('CorreoElectronico', sanitizeInput(document.getElementById('emailInput').value));
    formData.append('cf-turnstile-response', document.getElementById('cf-turnstile-response').value);
    formData.append('croppedImageData', document.getElementById('croppedImageData').value);

    fetch(document.getElementById('registerForm').action, {
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
        console.error(error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Hubo un problema al registrar el usuario. Por favor, intente nuevamente.'
        });
    });
});


// Funcionalidades para la vista de la foto de perfil
document.getElementById('selectImageBtn').addEventListener('click', function() {
    document.getElementById('Foto_PerfilInput').click();
});

document.getElementById('Foto_PerfilInput').addEventListener('change', function() {
    previewImage(this);
});

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

function openCropperModal() {
    // Asegúrate de que estás utilizando jQuery o Bootstrap JS para abrir el modal
    $('#cropModal').modal('show'); // Usa el ID correcto del modal
}

// Función para mostrar el paso actual y ocultar los demás
function showStep(stepNumber) {
    // Obtén todos los elementos con la clase 'register-step'
    const steps = document.querySelectorAll('.register-step');

    // Itera sobre cada paso y ocúltalos
    steps.forEach((step) => {
        step.style.display = 'none';
    });

    // Muestra el paso actual
    const currentStep = document.getElementById(`step${stepNumber}`);
    if (currentStep) {
        currentStep.style.display = 'block';
    }
}

// Inicializar con el primer paso
showStep(1);
