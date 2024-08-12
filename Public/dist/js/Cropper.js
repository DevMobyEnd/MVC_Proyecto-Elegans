document.addEventListener('DOMContentLoaded', function () {
    let cropper;

    document.getElementById('Foto_PerfilInput').addEventListener('change', function (e) {
        let file = e.target.files[0];
        if (file) {
            let reader = new FileReader();
            reader.onload = function (event) {
                document.getElementById('imageToCrop').src = event.target.result;
                $('#cropModal').modal('show');

                if (cropper) {
                    cropper.destroy();
                }

                cropper = new Cropper(document.getElementById('imageToCrop'), {
                    aspectRatio: 1,
                    viewMode: 1,
                });
            }
            reader.readAsDataURL(file);
        }
    });

    document.getElementById('cropImageBtn').addEventListener('click', function () {
        if (cropper) {
            let croppedCanvas = cropper.getCroppedCanvas({
                width: 100,
                height: 100
            });

            document.getElementById('profilePreview').src = croppedCanvas.toDataURL();
            document.getElementById('croppedImageData').value = croppedCanvas.toDataURL();

            $('#cropModal').modal('hide');
        }
    });
});