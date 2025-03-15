const dynamicText = document.querySelector('.dynamic-text');
let currentIndex = 0;

// Lista de saludos en diferentes idiomas
const saludos = [
    "Welcome!", // Inglés
    "Bienvenue!", // Francés
    "ようこそ!", // Japonés
    "Benvenuto!", // Italiano
    "欢迎", // Chino simplificado
    "환영합니다", // Coreano
];

// Función para crear los elementos <span> dinámicamente
function crearTextos() {
    saludos.forEach((saludo) => {
        const span = document.createElement('span');
        span.textContent = saludo;
        dynamicText.appendChild(span);
    });
}

// Función para mostrar el siguiente texto
function showNextText() {
    const textSpans = dynamicText.querySelectorAll('span');

    // Oculta el texto actual
    textSpans[currentIndex].classList.remove('active');
    textSpans[currentIndex].classList.add('prev');

    // Avanza al siguiente texto
    currentIndex = (currentIndex + 1) % textSpans.length;

    // Muestra el siguiente texto
    textSpans[currentIndex].classList.remove('prev');
    textSpans[currentIndex].classList.add('active');
}

// Inicializa la animación
crearTextos(); // Crea los elementos <span> dinámicamente
const textSpans = dynamicText.querySelectorAll('span');
textSpans[currentIndex].classList.add('active');
setInterval(showNextText, 2000); // Cambia el texto cada 2 segundos