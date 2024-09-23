<?php
class ImageProcessor
{
    private const ALLOWED_MIME_TYPES = ['image/jpeg', 'image/png', 'image/gif'];
    private const MAX_FILE_SIZE = 5 * 1024 * 1024; // 5 MB
    private const ALLOWED_EXTENSIONS = ['jpg', 'jpeg', 'png', 'gif'];
    
    // Define la ruta de subida absoluta
    private $uploadDir;

    public function __construct()
    {
        // Ajusta la ruta según la estructura de tu proyecto
        $this->uploadDir = __DIR__ . '/../uploads/';
        $this->ensureUploadDirectoryExists($this->uploadDir);
    }

    public function process($foto)
    {
        try {
            $this->validateUpload($foto);
            $this->validateMimeType($foto);
            $this->validateFileSize($foto);
            $this->validateFileExtension($foto);
            $this->validateImageIntegrity($foto['tmp_name']); // Validación adicional

            $filename = $this->generateUniqueFilename($foto);
            $uploadFile = $this->uploadDir . $filename;

            if (!move_uploaded_file($foto['tmp_name'], $uploadFile)) {
                throw new Exception('Error al mover el archivo subido.');
            }

            return $filename;
        } catch (Exception $e) {
            // Log del error con más detalle
            error_log('Error procesando imagen: ' . $e->getMessage());
            return false;
        }
    }

    public function processCroppedImage($croppedImageData)
    {
        try {
            // Validar y decodificar la imagen base64
            if (strpos($croppedImageData, ';base64,') === false) {
                throw new Exception('Formato de datos base64 inválido.');
            }

            list($meta, $data) = explode(';base64,', $croppedImageData);
            if (!preg_match('/^data:image\/(\w+)$/', $meta, $matches)) {
                throw new Exception('Datos de imagen base64 inválidos.');
            }

            $imageType = strtolower($matches[1]);
            if (!in_array("image/$imageType", self::ALLOWED_MIME_TYPES)) {
                throw new Exception('Tipo de imagen no permitido.');
            }

            $imageData = base64_decode($data);
            if ($imageData === false) {
                throw new Exception('Decodificación base64 fallida.');
            }

            // Opcional: Verificar que los datos decodificados sean una imagen válida
            $tmpFile = tempnam(sys_get_temp_dir(), 'img_');
            file_put_contents($tmpFile, $imageData);
            if (!$this->isValidImage($tmpFile)) {
                unlink($tmpFile);
                throw new Exception('Datos de imagen inválidos.');
            }
            unlink($tmpFile);

            $filename = uniqid('cropped_', true) . '.png'; // Mantener el formato PNG
            $filePath = $this->uploadDir . $filename;

            if (file_put_contents($filePath, $imageData) === false) {
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
        if (!isset($foto['error']) || $foto['error'] !== UPLOAD_ERR_OK) {
            throw new Exception('Error en la subida del archivo: ' . $this->getUploadErrorMessage($foto['error'] ?? UPLOAD_ERR_NO_FILE));
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

    private function ensureUploadDirectoryExists($uploadDir)
    {
        if (!is_dir($uploadDir)) {
            if (!mkdir($uploadDir, 0755, true)) {
                throw new Exception('No se pudo crear el directorio de carga.');
            }
        }

        if (!is_writable($uploadDir)) {
            throw new Exception('El directorio de carga no tiene permisos de escritura.');
        }
    }

    private function generateUniqueFilename($foto)
    {
        $extension = strtolower(pathinfo($foto['name'], PATHINFO_EXTENSION));
        return uniqid('img_', true) . '.' . $extension;
    }

    private function getUploadErrorMessage($errorCode)
    {
        $errorMessages = [
            UPLOAD_ERR_INI_SIZE   => 'El archivo excede el tamaño máximo permitido por el servidor.',
            UPLOAD_ERR_FORM_SIZE  => 'El archivo excede el tamaño máximo permitido por el formulario.',
            UPLOAD_ERR_PARTIAL    => 'El archivo se subió parcialmente.',
            UPLOAD_ERR_NO_FILE    => 'No se subió ningún archivo.',
            UPLOAD_ERR_NO_TMP_DIR => 'Falta la carpeta temporal.',
            UPLOAD_ERR_CANT_WRITE => 'No se pudo escribir el archivo en el disco.',
            UPLOAD_ERR_EXTENSION  => 'Una extensión de PHP detuvo la carga del archivo.',
        ];

        return $errorMessages[$errorCode] ?? 'Error desconocido en la carga del archivo.';
    }

    private function isValidImage($filePath)
    {
        $info = getimagesize($filePath);
        if ($info === false) {
            return false;
        }

        return in_array($info['mime'], self::ALLOWED_MIME_TYPES);
    }

    private function validateImageIntegrity($filePath)
    {
        if (!$this->isValidImage($filePath)) {
            throw new Exception('El archivo subido no es una imagen válida.');
        }
    }
}
