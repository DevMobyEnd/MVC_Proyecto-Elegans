// Script para manejar las interacciones del perfil
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar los elementos de la interfaz
    initializeUI();
});

function initializeUI() {
    // Formulario de actualización de perfil
    const updateProfileForm = document.getElementById('updateProfileForm');
    if (updateProfileForm) {
        updateProfileForm.addEventListener('submit', handleProfileUpdate);
    }

    // Formulario de eliminación de cuenta
    const deleteAccountForm = document.getElementById('deleteAccountForm');
    if (deleteAccountForm) {
        deleteAccountForm.addEventListener('submit', handleAccountDeletion);
    }

    // Manejo de la foto de perfil
    setupProfilePhotoHandlers();
}

async function handleProfileUpdate(e) {
    e.preventDefault();
    
    // Mostrar loading
    const updateButton = document.getElementById('updateButton');
    const originalButtonText = updateButton.innerHTML;
    updateButton.disabled = true;
    updateButton.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Actualizando...';

    try {
        const formData = new FormData(this);
        formData.append('action', 'update');

        const response = await fetch('ProfileController.php', {
            method: 'POST',
            body: formData
        });

        const data = await response.json();

        if (data.success) {
            // Actualizar la interfaz con los nuevos datos
            updateUIElements(formData);
            
            // Mostrar notificación de éxito
            Swal.fire({
                icon: 'success',
                title: '¡Perfil actualizado!',
                text: 'Los cambios se han guardado correctamente',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });
        } else {
            throw new Error(data.error || 'Error al actualizar el perfil');
        }
    } catch (error) {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: error.message,
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
        });
    } finally {
        // Restaurar el botón
        updateButton.disabled = false;
        updateButton.innerHTML = originalButtonText;
    }
}

async function handleAccountDeletion(e) {
    e.preventDefault();

    try {
        const result = await Swal.fire({
            title: '¿Estás seguro?',
            text: "Tu cuenta será desactivada y no podrás revertir esto después de 30 días",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminar cuenta',
            cancelButtonText: 'Cancelar',
            showLoaderOnConfirm: true,
            preConfirm: async () => {
                const formData = new FormData(this);
                formData.append('action', 'delete');

                try {
                    const response = await fetch('ProfileController.php', {
                        method: 'POST',
                        body: formData
                    });
                    const data = await response.json();
                    
                    if (!data.success) {
                        throw new Error(data.error || 'Error al eliminar la cuenta');
                    }
                    return data;
                } catch (error) {
                    Swal.showValidationMessage(error.message);
                }
            },
            allowOutsideClick: () => !Swal.isLoading()
        });

        if (result.isConfirmed) {
            await Swal.fire({
                title: 'Cuenta desactivada',
                text: 'Tu cuenta ha sido desactivada correctamente. Serás redirigido en breve.',
                icon: 'success',
                timer: 3000,
                timerProgressBar: true,
                showConfirmButton: false
            });

            // Redirigir al login
            window.location.href = '/login.php';
        }
    } catch (error) {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Ocurrió un error al procesar tu solicitud'
        });
    }
}

function updateUIElements(formData) {
    // Actualizar elementos visuales sin recargar la página
    const nombreCompleto = `${formData.get('Nombres')} ${formData.get('Apellidos')}`;
    const apodo = formData.get('Apodo');
    
    // Actualizar elementos en la página principal si existen
    const nombreElements = document.querySelectorAll('.user-name');
    const apodoElements = document.querySelectorAll('.user-nickname');
    
    nombreElements.forEach(el => el.textContent = nombreCompleto);
    apodoElements.forEach(el => el.textContent = apodo);

    // Si hay una nueva foto de perfil, actualizar todas las imágenes de perfil
    const croppedImageData = document.getElementById('croppedImageData').value;
    if (croppedImageData) {
        const profileImages = document.querySelectorAll('.profile-image');
        profileImages.forEach(img => img.src = croppedImageData);
    }
}

function setupProfilePhotoHandlers() {
    const profileInput = document.getElementById('Foto_PerfilInput');
    const selectImageBtn = document.getElementById('selectImageBtn');
    
    if (selectImageBtn && profileInput) {
        selectImageBtn.addEventListener('click', () => profileInput.click());
        
        profileInput.addEventListener('change', (e) => {
            if (e.target.files && e.target.files[0]) {
                const file = e.target.files[0];
                
                // Validar el tipo y tamaño del archivo
                if (!file.type.match('image.*')) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Por favor, selecciona una imagen válida'
                    });
                    return;
                }
                
                if (file.size > 5 * 1024 * 1024) { // 5MB
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'La imagen no debe superar los 5MB'
                    });
                    return;
                }
                
                openCropperModal(file);
            }
        });
    }
}

async function openCropperModal(file) {
    try {
        const imageUrl = await readFileAsDataURL(file);
        
        const result = await Swal.fire({
            title: 'Ajustar imagen',
            html: `
                <div id="cropper-container" style="max-width: 400px; margin: 0 auto;">
                    <img id="cropper-image" src="${imageUrl}" style="max-width: 100%;">
                </div>
            `,
            showCancelButton: true,
            confirmButtonText: 'Guardar',
            cancelButtonText: 'Cancelar',
            didOpen: () => {
                // Aquí puedes inicializar el cropper si usas una librería de cropping
                // Por ahora solo mostramos la vista previa
                document.getElementById('profilePreview').src = imageUrl;
                document.getElementById('croppedImageData').value = imageUrl;
            }
        });

        if (!result.isConfirmed) {
            // Restaurar la imagen anterior si se cancela
            const defaultImage = document.getElementById('profilePreview').getAttribute('data-default-image');
            document.getElementById('profilePreview').src = defaultImage;
            document.getElementById('croppedImageData').value = '';
        }
    } catch (error) {
        console.error('Error al procesar la imagen:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Error al procesar la imagen'
        });
    }
}

function readFileAsDataURL(file) {
    return new Promise((resolve, reject) => {
        const reader = new FileReader();
        reader.onload = e => resolve(e.target.result);
        reader.onerror = e => reject(e);
        reader.readAsDataURL(file);
    });
}