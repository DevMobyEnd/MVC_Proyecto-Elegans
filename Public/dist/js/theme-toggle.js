document.addEventListener("DOMContentLoaded", function () {
    console.log("DOMContentLoaded event fired");

    const savedTheme = localStorage.getItem("theme") || "light";
    console.log("Saved theme:", savedTheme);

    document.documentElement.setAttribute('data-bs-theme', savedTheme);
    console.log("Initial theme set to:", document.documentElement.getAttribute('data-bs-theme'));

    updateImage(savedTheme);

    document.querySelector(".theme-toggle").addEventListener("click", () => {
        console.log("Theme toggle clicked");
        toggleLocalStorage();
        toggleRootClass();
    });

    function toggleRootClass() {
        const current = document.documentElement.getAttribute('data-bs-theme');
        console.log("Current theme before toggle:", current);
        const inverted = current == 'dark' ? 'light' : 'dark';
        document.documentElement.setAttribute('data-bs-theme', inverted);
        console.log("Theme toggled to:", inverted);
        setTimeout(() => {
            updateImage(inverted);
            console.log("Image updated after timeout");
        }, 0);
    }

    function updateImage(mode) {
        const img = document.getElementById('modeImage');
        if (img) {
            img.src = mode === 'dark' ? "../Public/dist/img/dark.png" : "../Public/dist/img/light.png";
            console.log("Image updated to:", img.src);
        } else {
            console.log("modeImage element not found");
        }
    }

    updateImage(document.documentElement.getAttribute('data-bs-theme'));

    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
        console.log("Preferred color scheme changed:", e.matches ? "dark" : "light");
        updateImage(e.matches ? 'dark' : 'light');
    });

    function toggleLocalStorage() {
        const newTheme = document.documentElement.getAttribute('data-bs-theme') === 'light' ? "light" : "dark";
        localStorage.setItem("theme", newTheme);
        console.log("Theme saved to localStorage:", newTheme);
    }

    function isLight() {
        const theme = localStorage.getItem("theme");
        console.log("Current theme in localStorage:", theme);
        return theme === "light";
    }

    if (isLight()) {
        console.log("Initial theme is light, toggling to dark");
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


});