const volumeIcons = {
    mute: `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"></polygon>
        <line x1="23" y1="9" x2="17" y2="15"></line>
        <line x1="17" y1="9" x2="23" y2="15"></line>
    </svg>`,
    low: `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"></polygon>
        <path d="M15.54 8.46a5 5 0 0 1 0 7.07"></path>
    </svg>`,
    medium: `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"></polygon>
        <path d="M19.07 4.93a10 10 0 0 1 0 14.14M15.54 8.46a5 5 0 0 1 0 7.07"></path>
    </svg>`
};

let lastVolume = 50;
let isMuted = false;

function updateVolumeIcon(value) {
    if (value == 0) {
        volumeIcon.innerHTML = volumeIcons.mute;
    } else if (value <= 50) {
        volumeIcon.innerHTML = volumeIcons.low;
    } else {
        volumeIcon.innerHTML = volumeIcons.medium;
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const volumeSlider = document.getElementById('volumeSlider');
    const volumeIcon = document.getElementById('volumeIcon');

    // Evento para el slider
    volumeSlider.addEventListener('input', function() {
        const currentVolume = parseInt(this.value);
        if (currentVolume > 0) {
            lastVolume = currentVolume;
            isMuted = false;
        }
        player.setVolume(currentVolume / 100);
        updateVolumeIcon(currentVolume);
    });

    // Evento para el ícono
    volumeIcon.addEventListener('click', function() {
        if (!isMuted) {
            // Guardar volumen actual y mutear
            lastVolume = parseInt(volumeSlider.value);
            volumeSlider.value = 0;
            player.setVolume(0);
            isMuted = true;
        } else {
            // Restaurar último volumen conocido
            volumeSlider.value = lastVolume;
            player.setVolume(lastVolume / 100);
            isMuted = false;
        }
        updateVolumeIcon(volumeSlider.value);
    });
});