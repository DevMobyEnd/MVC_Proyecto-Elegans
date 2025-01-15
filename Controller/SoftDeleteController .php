<?php

include_once 'Models/soltDeleteModel.php';
class SoftDeleteController 
{
    private $soltDeleteModel;
    private $logger;
    private $csrfTokenGenerator;

    public function __construct()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $this->soltdeletemodel = new soltDeleteModel();
        $this->logger = new Logger();
        $this->csrfTokenGenerator = new CSRFTokenGenerator();
    }

    public function desactivarCuenta()
    {
        header('Content-Type: application/json');
        ob_start();

        try {
            $this->validateRequest();
            $this->validateCSRFToken();
            
            if (!isset($_SESSION['usuario_id'])) {
                throw new Exception('Usuario no autenticado');
            }

            $userId = $_SESSION['usuario_id'];
            
            // Verificar contraseña
            if (!$this->verificarContraseña($userId, $_POST['password'])) {
                throw new Exception('Contraseña incorrecta');
            }

            $this->modelo->iniciarTransaccion();

            // Actualizar estado del usuario y registrar fecha de desactivación
            $desactivado = $this->modelo->desactivarUsuario(
                $userId,
                [
                    'estado' => 'inactivo',
                    'fecha_desactivacion' => date('Y-m-d H:i:s'),
                    'motivo_desactivacion' => htmlspecialchars($_POST['motivo'] ?? '', ENT_QUOTES, 'UTF-8')
                ]
            );

            if ($desactivado) {
                $this->modelo->finalizarTransaccion();
                session_destroy();

                $output = ob_get_clean();
                return [
                    'status' => 'success',
                    'message' => 'Cuenta desactivada exitosamente',
                    'redirect' => '/login',
                    'debug' => $output
                ];
            } else {
                throw new Exception('Error al desactivar la cuenta');
            }
        } catch (Exception $e) {
            $this->modelo->revertirTransaccion();
            $this->logger->error('Error en la desactivación: ' . $e->getMessage());
            $output = ob_get_clean();
            return [
                'status' => 'error',
                'message' => $e->getMessage(),
                'debug' => $output
            ];
        }
    }

    private function verificarContraseña($userId, $password)
    {
        $usuario = $this->modelo->obtenerUsuarioPorId($userId);
        return password_verify($password, $usuario['password']);
    }

    // Programar tarea de eliminación periódica
    public function programarEliminacionPeriodica()
    {
        try {
            // Obtener usuarios inactivos con más de X tiempo
            $tiempoInactividad = config('TIEMPO_INACTIVIDAD_ELIMINAR', '30 days');
            $usuariosInactivos = $this->modelo->obtenerUsuariosInactivosPorTiempo($tiempoInactividad);

            foreach ($usuariosInactivos as $usuario) {
                // Anonimizar datos personales
                $datosAnonimizados = $this->anonimizarDatos($usuario);
                
                // Actualizar registro con datos anonimizados
                $this->modelo->actualizarUsuario($usuario['id'], $datosAnonimizados);
                
                $this->logger->info('Usuario ' . $usuario['id'] . ' anonimizado después de ' . $tiempoInactividad);
            }

            return [
                'status' => 'success',
                'message' => 'Proceso de eliminación periódica completado',
                'usuarios_procesados' => count($usuariosInactivos)
            ];
        } catch (Exception $e) {
            $this->logger->error('Error en eliminación periódica: ' . $e->getMessage());
            return [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }
    }

    private function anonimizarDatos($usuario)
    {
        return [
            'nombres' => 'Usuario',
            'apellidos' => 'Anónimo',
            'correoElectronico' => 'anonimo_' . $usuario['id'] . '@example.com',
            'apodo' => 'usuario_' . $usuario['id'],
            'foto_perfil' => 'default_profile.jpg',
            'datos_anonimizados' => true,
            'fecha_anonimizacion' => date('Y-m-d H:i:s')
        ];
    }

    // Otros métodos heredados (validateRequest, validateCSRFToken)
}