
document.addEventListener("DOMContentLoaded", function () {
    const sidebarToggle = document.querySelector("#sidebar-toggle");
    sidebarToggle.addEventListener("click", function () {
        document.querySelector("#sidebar").classList.toggle("collapsed");
    });

    document.querySelector(".theme-toggle").addEventListener("click", () => {
        toggleLocalStorage();
        toggleRootClass();
    });

    function toggleRootClass() {
        const current = document.documentElement.getAttribute('data-bs-theme');
        const inverted = current == 'dark' ? 'light' : 'dark';
        document.documentElement.setAttribute('data-bs-theme', inverted);
    }

    function toggleLocalStorage() {
        if (isLight()) {
            localStorage.removeItem("light");
        } else {
            localStorage.setItem("light", "set");
        }
    }

    function isLight() {
        return localStorage.getItem("light");
    }

    if (isLight()) {
        toggleRootClass();
    }

    const radios = document.querySelectorAll('input[type="radio"]');
    radios.forEach(radio => {
        radio.addEventListener("click", function () {
            toggleRadio(this);
        });
    });

    function toggleRadio(radio) {
        if (radio === lastChecked) {
            radio.checked = false;
            lastChecked = null;
        } else {
            lastChecked = radio;
        }
    }

    const chatForm = document.querySelector("#chatForm");
    chatForm.addEventListener("submit", function (event) {
        event.preventDefault();
        postMessage();
    });

    function postMessage() {
        const message = document.getElementById('chatMessage').value;
        if (message.trim() === '') return;

        const chatWindow = document.getElementById('chatWindow');
        const newMessage = document.createElement('p');
        newMessage.textContent = message;
        chatWindow.appendChild(newMessage);

        document.getElementById('chatMessage').value = '';
        chatWindow.scrollTop = chatWindow.scrollHeight;
    }
});

document.addEventListener('DOMContentLoaded', function () {
    const emailStep = document.getElementById('emailStep');
    const passwordStep = document.getElementById('passwordStep');
    const continueBtn = document.getElementById('continueBtn');
    const submitBtn = document.getElementById('submitBtn');
    const emailInput = document.getElementById('emailInput');
    const passwordInput = document.querySelector('input[name="password"]');
    const editEmail = document.getElementById('editEmail');
    const hrWithText = document.querySelector('.hr-with-text');
    const socialLoginButtons = document.querySelector('.social-login-buttons');
    const forgotPassword = document.getElementById('forgotPassword');
    const loginForm = document.getElementById('loginForm');
    const originalFormContent = loginForm.innerHTML;

    function showPasswordStep() {
        emailStep.style.display = 'block';
        passwordStep.style.display = 'block';
        continueBtn.style.display = 'none';
        submitBtn.style.display = 'inline-block';
        editEmail.style.display = 'inline-block';
        emailInput.readOnly = true;
        forgotPassword.style.display = 'block';
        if (hrWithText) hrWithText.style.display = 'none';
        if (socialLoginButtons) socialLoginButtons.style.display = 'none';
    }

    function showEmailStep() {
        emailStep.style.display = 'block';
        passwordStep.style.display = 'none';
        continueBtn.style.display = 'inline-block';
        submitBtn.style.display = 'none';
        editEmail.style.display = 'none';
        emailInput.readOnly = false;
        forgotPassword.style.display = 'none';
        if (hrWithText) hrWithText.style.display = 'block';
        if (socialLoginButtons) socialLoginButtons.style.display = 'block';

        // Clear the password field
        passwordInput.value = '';

        // Focus on the email input
        emailInput.focus();
    }

    // Add this event listener after the other event listeners
    editEmail.addEventListener('click', function (e) {
        e.preventDefault();
        showEmailStep();
    });

    continueBtn.addEventListener('click', function (e) {
        e.preventDefault();
        console.log('Botón Continuar clickeado');
        const email = emailInput.value;

        fetch('/Views/login.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: `Gmail=${encodeURIComponent(email)}`
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.text();
            })
            .then(text => {
                try {
                    return JSON.parse(text);
                } catch (e) {
                    console.error("Server response was not JSON:", text);
                    throw new Error("Server response was not JSON");
                }
            })
            .then(data => {
                if (data.success) {
                    showPasswordStep();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message
                    });
                }
            })
            .catch(error => {
                console.error('There has been a problem with your fetch operation:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Hubo un problema al procesar su solicitud. Por favor, inténtelo de nuevo.'
                });
            });
    });

    submitBtn.addEventListener('click', function (e) {
        e.preventDefault();
        const email = emailInput.value;
        const password = passwordInput.value;

        fetch('/Views/login.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: `Gmail=${encodeURIComponent(email)}&password=${encodeURIComponent(password)}`
        })
            .then(response => response.json())
            .then(data => {
                console.log('Respuesta del servidor:', data);
                if (data.success) {
                    window.location.href = '/Views/Index.php';
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message
                    });
                }
            });
    });

    // Add this event listener after the other event listeners
    editEmail.addEventListener('click', function (e) {
        e.preventDefault();
        showEmailStep();
    });
});


//Funcionalidades para la vista de registro
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

document.getElementById('registerForm').addEventListener('submit', function(e) {
    e.preventDefault();

    let errors = [];

    const Foto_Perfil = document.getElementById('Foto_PerfilInput').value;
    const Nombres = document.getElementById('nombresInput').value;
    const Apellidos = document.getElementById('apellidosInput').value;
    const NumerodeDocumento = document.getElementById('NumerodeDocumentoInput').value;
    const Apodo = document.getElementById('apodoInput').value;
    const CorreoElectronico = document.getElementById('emailInput').value;
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

    if (errors.length > 0) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            html: `<ul>${errors.map(error => `<li>${error}</li>`).join('')}</ul>`
        });
        return;
    }

    const formData = new FormData(this);

    fetch(this.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.text()) // Change to text to debug
    .then(result => {
        console.log(result); // Print the result to the console
        return JSON.parse(result); // Then parse it as JSON
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
