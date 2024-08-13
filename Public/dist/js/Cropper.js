document.addEventListener('DOMContentLoaded', function () {
    const selectImageBtn = document.getElementById('selectImageBtn');
    const fileInput = document.getElementById('Foto_PerfilInput');
    const profilePreview = document.getElementById('profilePreview');
    const imageToCrop = document.getElementById('imageToCrop');
    const cropModal = new bootstrap.Modal(document.getElementById('cropModal'));
    const cropImageBtn = document.getElementById('cropImageBtn');
    const croppedImageDataInput = document.getElementById('croppedImageData');

    let cropper;

    selectImageBtn.addEventListener('click', function () {
        fileInput.click();
    });

    fileInput.addEventListener('change', function (e) {
        previewImage(e.target); // Actualizar la vista previa de la imagen
    });

    profilePreview.addEventListener('click', function () {
        if (profilePreview.src !== "/Public/dist/img/profile.jpg") { // Verificar si hay una imagen cargada
            imageToCrop.src = profilePreview.src;
            cropModal.show();

            // Destruir el cropper anterior si existe
            if (cropper) {
                cropper.destroy();
            }

            // Inicializar Cropper
            cropper = new Cropper(imageToCrop, {
                aspectRatio: 1,
                viewMode: 1,
                autoCropArea: 1,
                ready: function () {
                    cropper.crop();
                },
                // Ajuste para mejorar la calidad y el tamaño del lienzo
                minContainerWidth: 600, // Ajusta según sea necesario
                minContainerHeight: 600, // Ajusta según sea necesario
                minCanvasWidth: 600, // Ajusta según sea necesario
                minCanvasHeight: 600, // Ajusta según sea necesario
                zoomable: true,
                scalable: true,
                responsive: true
            });

            // Ajusta el tamaño de la imagen dentro del modal
            $('#cropModal').on('shown.bs.modal', function () {
                imageToCrop.style.maxWidth = '70%';
                imageToCrop.style.maxHeight = '70%';
                imageToCrop.style.margin = 'auto'; // Centra la imagen horizontalmente
            });
        }
    });

    cropImageBtn.addEventListener('click', function () {
        if (cropper) {
            const croppedCanvas = cropper.getCroppedCanvas({
                width: 300, // Mayor resolución para evitar pérdida de calidad
                height: 300,
                imageSmoothingEnabled: true,
                imageSmoothingQuality: 'high'
            });

            croppedCanvas.toBlob(function (blob) {
                const url = URL.createObjectURL(blob);
                profilePreview.src = url;
                profilePreview.style.display = 'block';
                croppedImageDataInput.value = croppedCanvas.toDataURL('image/jpeg', 1);
                cropModal.hide();
            }, 'image/jpeg');
        }
    });

    // Limpiar el cropper cuando se cierre el modal
    document.getElementById('cropModal').addEventListener('hidden.bs.modal', function () {
        if (cropper) {
            cropper.destroy();
            cropper = null;
        }
    });
});
