<?php
// controllers/DashboardController.php
require_once 'models/Requerimiento.php';
require_once 'models/Notificacion.php';

class DashboardController {
    private $requerimientoModel;
    private $notificacionModel;
    
    public function __construct() {
        $this->requerimientoModel = new Requerimiento();
        $this->notificacionModel = new Notificacion();
    }
    
    // Mostrar dashboard
    public function index() {
        // Verificar si el usuario está logueado
        if (!isLoggedIn()) {
            redirect('login');
        }
        
        // Obtener estadísticas según el rol
        if (isAdmin()) {
            $estadisticas = $this->requerimientoModel->obtenerEstadisticas();
            $requerimientosRecientes = $this->requerimientoModel->obtenerRecientes();
        } else {
            $estadisticas = $this->requerimientoModel->obtenerEstadisticas($_SESSION['area_id']);
            $requerimientosRecientes = $this->requerimientoModel->obtenerRecientes($_SESSION['area_id']);
        }
        
        // Obtener notificaciones no leídas
        $notificaciones = $this->notificacionModel->listarNoLeidasPorUsuario($_SESSION['user_id']);
        
        // Mostrar vista
        include 'views/dashboard/index.php';
    }
}
?>

