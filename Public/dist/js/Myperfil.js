document.addEventListener('DOMContentLoaded', function () {
    console.log('Script cargado correctamente');

    // Configura la cookie para SameSite=None y Secure
    document.cookie = "name=value; SameSite=None; Secure";

    // Función para sanitizar input y prevenir ataques XSS
    function sanitizeInput(input) {
        const div = document.createElement('div');
        div.textContent = input;
        return div.innerHTML;
    }

    // Constantes para los umbrales de fuerza de contraseña
    const WEAK_THRESHOLD = 40;
    const MODERATE_THRESHOLD = 60;
    const STRONG_THRESHOLD = 80;

    // Función de debounce para limitar la frecuencia de ejecución de una función
    const debounce = (func, delay) => {
        let timeoutId;
        return (...args) => {
            clearTimeout(timeoutId);
            timeoutId = setTimeout(() => func.apply(null, args), delay);
        };
    };

    // Función para cargar contenido dinámicamente
    function loadContent(url) {
        console.log('Cargando contenido desde:', url);
        fetch(url)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.text();
            })
            .then(data => {
                console.log('Contenido cargado correctamente');
                const profileContent = document.getElementById('profileContent');
                if (url === '/Myperfil.php') {
                    window.location.reload();
                } else {
                    profileContent.innerHTML = '';
                    profileContent.insertAdjacentHTML('beforeend', data);
                    console.log('Contenido de perfil actualizado');
                    addEventListeners();

                    // Inicializar el formulario de edición de perfil si es la página correspondiente
                    if (url.includes('editarperfil.php')) {
                        initializeEditProfileForm();
                    }
                }
            })
            .catch(error => console.error('Error al cargar el contenido:', error));
    }

    // Función para agregar event listeners a los elementos del perfil
    function addEventListeners() {
        console.log('Agregando eventos en profileContent');
        const editProfileBtn = document.getElementById('editProfileBtn');
        if (editProfileBtn) {
            console.log('editProfileBtn encontrado');
            editProfileBtn.addEventListener('click', function () {
                console.log('Botón de editar perfil clickeado');
                loadContent('../Views/layout/Myperfil/partials/editarperfil.php');
            });
        } else {
            console.log('editProfileBtn no encontrado');
        }

        const backToProfileBtn = document.getElementById('backToProfile');
        if (backToProfileBtn) {
            backToProfileBtn.addEventListener('click', function () {
                console.log('Botón de volver al perfil clickeado');
                loadContent('/Myperfil.php');
            });
        }
    }

    // Función para inicializar el formulario de edición de perfil
    function initializeEditProfileForm() {
        let currentStep = 1;
        const totalSteps = 2;

        // Función para mostrar un paso específico del formulario
        function showStep(step) {
            document.querySelectorAll('.register-step').forEach(s => s.style.display = 'none');
            document.getElementById(`step${step}`).style.display = 'block';

            const prevBtn = document.getElementById('prevBtn');
            const nextBtn = document.getElementById('nextBtn');
            const submitBtn = document.getElementById('updateButton');

            if (prevBtn) prevBtn.style.display = step > 1 ? 'inline-block' : 'none';
            if (nextBtn) nextBtn.style.display = step < totalSteps ? 'inline-block' : 'none';
            if (submitBtn) submitBtn.style.display = step === totalSteps ? 'inline-block' : 'none';
        }

        // Función para mostrar el siguiente paso
        function showNextStep() {
            if (currentStep < totalSteps) {
                currentStep++;
                showStep(currentStep);
            }
        }

        // Función para mostrar el paso anterior
        function showPreviousStep() {
            if (currentStep > 1) {
                currentStep--;
                showStep(currentStep);
            }
        }

        // Agregar event listeners a los botones de navegación
        const nextBtn = document.getElementById('nextBtn');
        const prevBtn = document.getElementById('prevBtn');

        if (nextBtn) {
            nextBtn.addEventListener('click', showNextStep);
        }

        if (prevBtn) {
            prevBtn.addEventListener('click', showPreviousStep);
        }

        // Mostrar el primer paso inicialmente
        showStep(1);

        // Add form submission handler
        const form = document.getElementById('editProfileForm');
        if (form) {
            form.addEventListener('submit', function (e) {
                e.preventDefault();
                const formData = new FormData(form);
                const jsonData = {};
                let hasChanges = false;

                // Iterar sobre los campos del formulario
                formData.forEach((value, key) => {
                    const input = form.elements[key];
                    if (input.type === 'file') {
                        // Para inputs de tipo file, verifica si se seleccionó un archivo
                        if (input.files.length > 0) {
                            jsonData[key] = value;
                            hasChanges = true;
                        }
                    } else if (input.value !== input.defaultValue) {
                        // Para otros inputs, compara el valor actual con el valor por defecto
                        jsonData[key] = value;
                        hasChanges = true;
                    }
                });

                // Solo envía la solicitud si hay cambios
                if (hasChanges) {
                    fetch('/Myperfil.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify(jsonData)
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                alert('Perfil actualizado con éxito');
                                loadContent('/Myperfil.php');
                            } else {
                                alert('Error al actualizar el perfil: ' + data.message);
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('Ocurrió un error al procesar la solicitud');
                        });
                } else {
                    alert('No se han realizado cambios en el perfil');
                }
            });
        }
    }

    // Llamar a initializeEditProfileForm cuando se cargue el contenido de edición de perfil
    if (window.location.pathname.includes('/Views/layout/Myperfil/partials/editarperfil.php')) {
        initializeEditProfileForm();
    }

    addEventListeners();

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

        if (progressBar) {
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
        } else {
            console.error('Elemento progress-bar no encontrado');
        }
    };

    // Aplicar debounce a la función de validación de contraseña
    const debouncedValidatePassword = debounce(validatePassword, 300);

    // Agregar el event listener al campo de contraseña
    const passwordInput = document.getElementById('passwordInput');
    if (passwordInput) {
        passwordInput.addEventListener('input', debouncedValidatePassword);
    } else {
        console.error('Elemento passwordInput no encontrado');
    }
});