document.addEventListener("DOMContentLoaded", () => {
    // DOM Elements
    const elements = {
        emailStep: document.getElementById("emailStep"),
        passwordStep: document.getElementById("passwordStep"),
        continueBtn: document.getElementById("continueBtn"),
        submitBtn: document.getElementById("submitBtn"),
        emailInput: document.getElementById("emailInput"),
        passwordInput: document.querySelector('input[name="password"]'),
        editEmailBtn: document.getElementById("editEmail"),
        horizontalRule: document.querySelector(".hr-with-text"),
        socialLoginButtons: document.querySelector(".social-login-buttons"),
        forgotPasswordLink: document.getElementById("forgotPassword"),
        loginAttemptsInfo: document.getElementById("loginAttemptsInfo"),
        remainingAttempts: document.getElementById("remainingAttempts"),
        lockoutInfo: document.getElementById("lockoutInfo"),
        lockoutTimer: document.getElementById("lockoutTimer")
    };

    // Asegúrate de que submitBtn existe antes de acceder a sus propiedades
    if (elements.submitBtn) {
        elements.submitBtnSpinner = elements.submitBtn.querySelector('.spinner-border');
        elements.submitBtnText = elements.submitBtn.querySelector('.button-text');
    }

    let loginAttempts = 0;
    const MAX_ATTEMPTS = 3;

    // Email Validation
    const validateEmail = (email) => {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    };

    // Función para manejar el estado del botón
    const updateButtonState = (button, isDisabled = false, showSpinner = false) => {
        if (button) {
            button.disabled = isDisabled;
            button.classList.toggle('button-disabled', isDisabled);
            const spinner = button.querySelector('.spinner-border');
            const text = button.querySelector('.button-text');
            if (spinner) spinner.style.display = showSpinner ? 'inline-block' : 'none';
            if (text) text.style.display = showSpinner ? 'none' : 'inline-block';
        }
    };

    // Función para mostrar animación de error
    const showErrorAnimation = (element) => {
        element.classList.add('error-shake');
        setTimeout(() => element.classList.remove('error-shake'), 500);
    };

    // Show Error Message
    const showError = (title, message, attempts = null, lockoutTime = null) => {
        elements.loginAttemptsInfo.style.display = 'none';
        elements.lockoutInfo.style.display = 'none';
        
        if (attempts !== null) {
            elements.loginAttemptsInfo.style.display = 'block';
            elements.remainingAttempts.textContent = attempts;
            showErrorAnimation(elements.passwordInput);
        }

        if (lockoutTime) {
            startLockoutCountdown(lockoutTime);
        }

        Swal.fire({
            icon: "error",
            title: title,
            text: message,
            confirmButtonText: 'Entendido'
        });
    };

    // Función para la cuenta regresiva
    const startLockoutCountdown = (duration) => {
        updateButtonState(elements.submitBtn, true, false);
        elements.passwordInput.disabled = true;
        elements.lockoutInfo.style.display = 'block';

        let timeLeft = duration;
        const updateTimer = () => {
            const minutes = Math.floor(timeLeft / 60);
            const seconds = timeLeft % 60;
            elements.lockoutTimer.textContent = 
                `${minutes}:${seconds.toString().padStart(2, '0')}`;
        };

        const countdownInterval = setInterval(() => {
            timeLeft--;
            updateTimer();
            
            if (timeLeft <= 0) {
                clearInterval(countdownInterval);
                elements.lockoutInfo.style.display = 'none';
                elements.loginAttemptsInfo.style.display = 'none';
                elements.passwordInput.disabled = false;
                elements.passwordInput.value = '';
                updateButtonState(elements.submitBtn, false, false);

                Swal.fire({
                    icon: 'info',
                    title: 'Cuenta desbloqueada',
                    text: 'Ya puede intentar iniciar sesión nuevamente.',
                    confirmButtonText: 'Continuar'
                });
            }
        }, 1000);

        updateTimer();
    };

    // Handle Email Check
    const handleEmailCheck = async (email) => {
        if (!email || !validateEmail(email)) {
            showError("Email inválido", "Por favor, ingrese un email válido.");
            return;
        }

        updateButtonState(elements.continueBtn, true, true);

        try {
            const response = await fetch("/login.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded",
                    "X-Requested-With": "XMLHttpRequest"
                },
                body: `action=checkEmail&Gmail=${encodeURIComponent(email)}`
            });

            if (!response.ok) {
                throw new Error("Network response was not ok");
            }

            const data = await response.json();

            if (data.success) {
                elements.emailStep.style.display = "block";
                elements.passwordStep.style.display = "block";
                elements.continueBtn.style.display = "none";
                elements.submitBtn.style.display = "inline-block";
                elements.editEmailBtn.style.display = "inline-block";
                elements.emailInput.readOnly = true;
                elements.forgotPasswordLink.style.display = "block";
                
                if (elements.horizontalRule) elements.horizontalRule.style.display = "none";
                if (elements.socialLoginButtons) elements.socialLoginButtons.style.display = "none";
            } else {
                showError("Error", data.message);
            }
        } catch (error) {
            console.error("Error:", error);
            showError("Error", "Hubo un problema al procesar su solicitud. Por favor, inténtelo de nuevo.");
        } finally {
            updateButtonState(elements.continueBtn, false, false);
        }
    };

    // Handle Login Submission
    const handleLoginSubmit = async (email, password) => {
        if (!email || !password) {
            showError("Campos vacíos", "Por favor, complete todos los campos.");
            return;
        }

        if (!validateEmail(email)) {
            showError("Email inválido", "Por favor, ingrese un email válido.");
            return;
        }

        updateButtonState(elements.submitBtn, true, true);

        try {
            const response = await fetch("/login.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded",
                    "X-Requested-With": "XMLHttpRequest"
                },
                body: `action=login&Gmail=${encodeURIComponent(email)}&password=${encodeURIComponent(password)}`
            });

            if (!response.ok) {
                throw new Error("Network response was not ok");
            }

            const data = await response.json();

            if (data.success) {
                Swal.fire({
                    icon: "success",
                    title: "¡Bienvenido!",
                    text: "Has iniciado sesión correctamente.",
                    showConfirmButton: false,
                    timer: 1500
                }).then(() => {
                    window.location.href = data.redirect || "/index.php";
                });
            } else {
                loginAttempts++;
                if (loginAttempts >= MAX_ATTEMPTS) {
                    showError("Cuenta bloqueada", "Has excedido el número máximo de intentos. Tu cuenta ha sido bloqueada temporalmente.", 0, 300); // 300 segundos = 5 minutos
                } else {
                    showError("Error", `Contraseña incorrecta. Intento ${loginAttempts} de ${MAX_ATTEMPTS}.`, MAX_ATTEMPTS - loginAttempts);
                }
            }
        } catch (error) {
            console.error("Error:", error);
            showError("Error", "Hubo un problema al procesar su solicitud. Por favor, inténtelo de nuevo.");
        } finally {
            updateButtonState(elements.submitBtn, false, false);
        }
    };

    // Reset Form
    const resetForm = () => {
        elements.emailStep.style.display = "block";
        elements.passwordStep.style.display = "none";
        elements.continueBtn.style.display = "inline-block";
        elements.submitBtn.style.display = "none";
        elements.editEmailBtn.style.display = "none";
        elements.emailInput.readOnly = false;
        elements.forgotPasswordLink.style.display = "none";
        
        if (elements.horizontalRule) elements.horizontalRule.style.display = "block";
        if (elements.socialLoginButtons) elements.socialLoginButtons.style.display = "block";

        elements.passwordInput.value = "";
        elements.emailInput.focus();
        loginAttempts = 0;
        elements.loginAttemptsInfo.style.display = 'none';
        elements.lockoutInfo.style.display = 'none';
    };

    // Event Listeners
    elements.continueBtn.addEventListener("click", (e) => {
        e.preventDefault();
        const email = elements.emailInput.value;
        handleEmailCheck(email);
    });

    elements.submitBtn.addEventListener("click", (e) => {
        e.preventDefault();
        const email = elements.emailInput.value.trim();
        const password = elements.passwordInput.value;
        handleLoginSubmit(email, password);
    });

    elements.editEmailBtn.addEventListener("click", (e) => {
        e.preventDefault();
        resetForm();
    });
});