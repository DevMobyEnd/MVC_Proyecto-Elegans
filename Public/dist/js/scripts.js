
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
document.getElementById('registerForm').addEventListener('submit', function(e) {
    e.preventDefault();
    let formData = new FormData(this);
    
    fetch(this.action, {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
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