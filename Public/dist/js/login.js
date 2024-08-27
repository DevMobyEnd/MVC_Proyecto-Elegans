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
        passwordInput.value = '';
        emailInput.focus();
    }

    continueBtn.addEventListener('click', function (e) {
        e.preventDefault();
        const email = emailInput.value;
        fetch('/login.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: `action=checkEmail&Gmail=${encodeURIComponent(email)}`
        })
        .then(response => response.text()) // Cambia a .text() para ver la respuesta cruda
        .then(data => {
            console.log(data); // Verifica la respuesta cruda
            const jsonData = JSON.parse(data); // Luego intenta parsear
            if (jsonData.success) {
                showPasswordStep();
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: jsonData.message
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Hubo un problema al procesar su solicitud. Por favor, inténtelo de nuevo.'
            });
        });
    });

    submitBtn.addEventListener('click', function (e) {
        e.preventDefault();
        const email = emailInput.value.trim();
        const password = passwordInput.value;
    
        if (!email || !password) {
            Swal.fire({
                icon: 'error',
                title: 'Campos vacíos',
                text: 'Por favor, complete todos los campos.'
            });
            return;
        }
    
        if (!isValidEmail(email)) {
            Swal.fire({
                icon: 'error',
                title: 'Email inválido',
                text: 'Por favor, ingrese un email válido.'
            });
            return;
        }
    
        fetch('/login.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: `action=login&Gmail=${encodeURIComponent(email)}&password=${encodeURIComponent(password)}`
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.text(); // Cambia a .text() para ver la respuesta cruda
        })
        .then(data => {
            console.log(data); // Verifica la respuesta cruda
            const jsonData = JSON.parse(data); // Luego intenta parsear
            if (jsonData.success) {
                Swal.fire({
                    icon: 'success',
                    title: '¡Bienvenido!',
                    text: 'Has iniciado sesión correctamente.',
                    showConfirmButton: false,
                    timer: 1500
                }).then(() => {
                    window.location.href = '/Index.php';
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: jsonData.message || 'Hubo un problema al iniciar sesión.'
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Hubo un problema al procesar su solicitud. Por favor, inténtelo de nuevo.'
            });
        });
    });
    
    function isValidEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }

    editEmail.addEventListener('click', function (e) {
        e.preventDefault();
        showEmailStep();
    });
});