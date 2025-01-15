<?php
require_once '../Models/UpdateProfileModel.php';
require_once '../Models/SoftDeleteModel.php';

class ProfileController {
    private $updateModel;
    private $deleteModel;

    public function __construct() {
        $this->updateModel = new UpdateProfileModel();
        $this->deleteModel = new SoftDeleteModel();
    }

    public function handleProfileUpdate() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return json_encode(['error' => 'Método no permitido']);
        }

        try {
            // Verificar CSRF token
            if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                throw new Exception('Token CSRF inválido');
            }

            $userId = $_SESSION['usuario_id'];
            
            // Validar datos únicos
            if ($this->updateModel->verificarCorreoExistente($_POST['Gmail'], $userId)) {
                throw new Exception('El correo electrónico ya está en uso');
            }
            if ($this->updateModel->verificarApodoExistente($_POST['Apodo'], $userId)) {
                throw new Exception('El apodo ya está en uso');
            }

            $datosUsuario = [
                'nombres' => $_POST['Nombres'],
                'apellidos' => $_POST['Apellidos'],
                'numero_documento' => $_POST['NumerodeDocumento'],
                'Apodo' => $_POST['Apodo'],
                'Gmail' => $_POST['Gmail'],
                'foto_perfil' => $this->procesarFotoPerfil()
            ];

            // Procesar cambio de contraseña si se proporcionó
            if (!empty($_POST['new_password'])) {
                if (empty($_POST['current_password'])) {
                    throw new Exception('Debe proporcionar la contraseña actual');
                }
                if ($_POST['new_password'] !== $_POST['confirm_new_password']) {
                    throw new Exception('Las contraseñas nuevas no coinciden');
                }
                $datosUsuario['password'] = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
            }

            $resultado = $this->updateModel->actualizarPerfilCompleto($userId, $datosUsuario);
            
            if ($resultado === true) {
                // Actualizar datos de sesión
                $_SESSION['nombre_completo'] = $datosUsuario['nombres'] . ' ' . $datosUsuario['apellidos'];
                $_SESSION['apodo'] = $datosUsuario['Apodo'];
                $_SESSION['foto_perfil'] = $datosUsuario['foto_perfil'];
                
                return json_encode(['success' => true, 'message' => 'Perfil actualizado correctamente']);
            } else {
                throw new Exception($resultado);
            }
        } catch (Exception $e) {
            return json_encode(['error' => $e->getMessage()]);
        }
    }

    public function handleAccountDeletion() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return json_encode(['error' => 'Método no permitido']);
        }

        try {
            // Verificar CSRF token
            if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                throw new Exception('Token CSRF inválido');
            }

            $userId = $_SESSION['usuario_id'];
            
            $datos = [
                'fecha_desactivacion' => date('Y-m-d H:i:s'),
                'motivo' => $_POST['motivo'],
                'motivo_detalle' => $_POST['motivo_detalle']
            ];

            $resultado = $this->deleteModel->desactivarUsuario($userId, $datos);
            
            if ($resultado) {
                // Destruir la sesión
                session_destroy();
                return json_encode(['success' => true, 'message' => 'Cuenta desactivada correctamente']);
            } else {
                throw new Exception('Error al desactivar la cuenta');
            }
        } catch (Exception $e) {
            return json_encode(['error' => $e->getMessage()]);
        }
    }

    private function procesarFotoPerfil() {
        if (!isset($_POST['croppedImageData']) || empty($_POST['croppedImageData'])) {
            return $_SESSION['foto_perfil'] ?? null;
        }

        $imageData = $_POST['croppedImageData'];
        $imageData = str_replace('data:image/png;base64,', '', $imageData);
        $imageData = str_replace(' ', '+', $imageData);
        $imageData = base64_decode($imageData);

        $fileName = uniqid() . '_profile.png';
        $filePath = $_SERVER['DOCUMENT_ROOT'] . '/uploads/' . $fileName;

        if (file_put_contents($filePath, $imageData)) {
            return $fileName;
        }

        return $_SESSION['foto_perfil'] ?? null;
    }
}

// Manejo de las solicitudes AJAX
if (isset($_POST['action'])) {
    $controller = new ProfileController();
    
    switch ($_POST['action']) {
        case 'update':
            echo $controller->handleProfileUpdate();
            break;
        case 'delete':
            echo $controller->handleAccountDeletion();
            break;
        default:
            echo json_encode(['error' => 'Acción no válida']);
    }
}