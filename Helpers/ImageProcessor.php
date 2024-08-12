<?php
class ImageProcessor
{
    private const ALLOWED_MIME_TYPES = ['image/jpeg', 'image/png', 'image/gif'];
    private const MAX_FILE_SIZE = 5 * 1024 * 1024; // 5 MB
    private const ALLOWED_EXTENSIONS = ['jpg', 'jpeg', 'png', 'gif'];

    public function process($foto)
    {
        try {
            $this->validateUpload($foto);
            $this->validateMimeType($foto);
            $this->validateFileSize($foto);
            $this->validateFileExtension($foto);

            $upload_dir = '../uploads/';
            $this->ensureUploadDirectoryExists($upload_dir);

            $filename = $this->generateUniqueFilename($foto);
            $upload_file = $upload_dir . $filename;

            if (!move_uploaded_file($foto['tmp_name'], $upload_file)) {
                throw new Exception('Error al mover el archivo subido.');
            }

            return $filename;
        } catch (Exception $e) {
            // Log the error
            error_log('Error procesando imagen: ' . $e->getMessage());
            return false;
        }
    }

    public function processCroppedImage($croppedImageData)
{
    try {
        $image_parts = explode(";base64,", $croppedImageData);
        $image_type_aux = explode("image/", $image_parts[0]);
        $image_type = $image_type_aux[1];
        $image_base64 = base64_decode($image_parts[1]);

        $upload_dir = '../uploads/';
        $this->ensureUploadDirectoryExists($upload_dir);

        $filename = uniqid() . '.png';
        $file = $upload_dir . $filename;

        if (file_put_contents($file, $image_base64) === false) {
            throw new Exception('Error al guardar la imagen recortada.');
        }

        return $filename;
    } catch (Exception $e) {
        error_log('Error procesando imagen recortada: ' . $e->getMessage());
        return false;
    }
}

    private function validateUpload($foto)
    {
        if ($foto['error'] !== UPLOAD_ERR_OK) {
            throw new Exception('Error en la subida del archivo: ' . $this->getUploadErrorMessage($foto['error']));
        }
    }

    private function validateMimeType($foto)
    {
        if (!in_array($foto['type'], self::ALLOWED_MIME_TYPES)) {
            throw new Exception('Tipo de archivo no permitido.');
        }
    }

    private function validateFileSize($foto)
    {
        if ($foto['size'] > self::MAX_FILE_SIZE) {
            throw new Exception('El archivo excede el tamaño máximo permitido.');
        }
    }

    private function validateFileExtension($foto)
    {
        $extension = strtolower(pathinfo($foto['name'], PATHINFO_EXTENSION));
        if (!in_array($extension, self::ALLOWED_EXTENSIONS)) {
            throw new Exception('Extensión de archivo no permitida.');
        }
    }

    private function ensureUploadDirectoryExists($upload_dir)
    {
        if (!is_dir($upload_dir) && !mkdir($upload_dir, 0755, true)) {
            throw new Exception('No se pudo crear el directorio de carga.');
        }
    }

    private function generateUniqueFilename($foto)
    {
        return uniqid() . '_' . basename($foto['name']);
    }

    private function getUploadErrorMessage($errorCode)
    {
        $errorMessages = [
            UPLOAD_ERR_INI_SIZE => 'El archivo excede el tamaño máximo permitido por el servidor.',
            UPLOAD_ERR_FORM_SIZE => 'El archivo excede el tamaño máximo permitido por el formulario.',
            UPLOAD_ERR_PARTIAL => 'El archivo se subió parcialmente.',
            UPLOAD_ERR_NO_FILE => 'No se subió ningún archivo.',
            UPLOAD_ERR_NO_TMP_DIR => 'Falta la carpeta temporal.',
            UPLOAD_ERR_CANT_WRITE => 'No se pudo escribir el archivo en el disco.',
            UPLOAD_ERR_EXTENSION => 'Una extensión de PHP detuvo la carga del archivo.',
        ];

        return $errorMessages[$errorCode] ?? 'Error desconocido en la carga del archivo.';
    }
}