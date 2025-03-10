<?php
// controllers/AvanceController.php
require_once 'models/Avance.php';
require_once 'models/Requerimiento.php';

class AvanceController {
    private $avanceModel;
    private $requerimientoModel;
    
    public function __construct() {
        $this->avanceModel = new Avance();
        $this->requerimientoModel = new Requerimiento();
    }
    
    // Crear avance
    public function crear($requerimiento_id) {
        // Verificar si el usuario está logueado y es administrador
        if (!isLoggedIn() || !isAdmin()) {
            redirect('login');
        }
        
        // Obtener requerimiento
        $requerimiento = $this->requerimientoModel->obtenerPorId($requerimiento_id);
        
        // Verificar si existe el requerimiento
        if (!$requerimiento) {
            setMessage('error', 'El requerimiento no existe');
            redirect('requerimientos');
        }
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Procesar formulario
            
            // Sanitizar datos
            $descripcion = sanitize($_POST['descripcion']);
            
            // Validar datos
            $errores = [];
            
            if (empty($descripcion)) {
                $errores[] = 'La descripción es obligatoria';
            }
            
            // Si no hay errores, guardar avance
            if (empty($errores)) {
                $data = [
                    'requerimiento_id' => $requerimiento_id,
                    'descripcion' => $descripcion,
                    'usuario_id' => $_SESSION['user_id'],
                    'porcentaje' => isset($_POST['porcentaje']) ? (int)$_POST['porcentaje'] : null
                ];
                
                if ($this->avanceModel->registrar($data)) {
                    // Si el requerimiento está en estado pendiente, pasarlo a en proceso
                    if ($requerimiento['estado'] == 'pendiente') {
                        $this->requerimientoModel->actualizarEstado($requerimiento_id, 'en proceso');
                    }
                    
                    setMessage('success', 'Avance registrado correctamente');
                    redirect('requerimientos/ver/' . $requerimiento_id);
                } else {
                    setMessage('error', 'Ocurrió un error al registrar el avance');
                }
            } else {
                // Si hay errores, mostrar de nuevo el formulario con los errores
                include 'views/avances/crear.php';
            }
        } else {
            // Mostrar formulario
            include 'views/avances/crear.php';
        }
    }
    
    // Eliminar avance
    public function eliminar($id) {
        // Verificar si el usuario está logueado y es administrador
        if (!isLoggedIn() || !isAdmin()) {
            redirect('login');
        }
        
        // Obtener avance para saber a qué requerimiento volver
        $avance = $this->avanceModel->obtenerPorId($id);
        
        if (!$avance) {
            setMessage('error', 'El avance no existe');
            redirect('requerimientos');
        }
        
        $requerimiento_id = $avance['requerimiento_id'];
        
        // Intentar eliminar
        if ($this->avanceModel->eliminar($id)) {
            setMessage('success', 'Avance eliminado correctamente');
        } else {
            setMessage('error', 'Ocurrió un error al eliminar el avance');
        }
        
        redirect('requerimientos/ver/' . $requerimiento_id);
    }
}
?>

