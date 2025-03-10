<?php
// controllers/NotificacionController.php
require_once 'models/Notificacion.php';

class NotificacionController {
    private $notificacionModel;
    
    public function __construct() {
        $this->notificacionModel = new Notificacion();
    }
    
    // Listar notificaciones del usuario
    public function index() {
        // Verificar si el usuario está logueado
        if (!isLoggedIn()) {
            redirect('login');
        }
        
        // Obtener notificaciones
        $notificaciones = $this->notificacionModel->listarPorUsuario($_SESSION['user_id']);
        
        // Mostrar vista
        include 'views/notificaciones/index.php';
    }
    
    // Marcar notificación como leída
    public function marcarLeida($id) {
        // Verificar si el usuario está logueado
        if (!isLoggedIn()) {
            redirect('login');
        }
        
        // Marcar como leída
        $this->notificacionModel->marcarComoLeida($id);
        
        // Redirigir a la lista de notificaciones
        redirect('notificaciones');
    }
    
    // Marcar todas las notificaciones como leídas
    public function marcarTodasLeidas() {
        // Verificar si el usuario está logueado
        if (!isLoggedIn()) {
            redirect('login');
        }
        
        // Marcar todas como leídas
        $this->notificacionModel->marcarTodasComoLeidas($_SESSION['user_id']);
        
        // Redirigir a la lista de notificaciones
        redirect('notificaciones');
    }
    
    // Obtener contador de notificaciones no leídas (para AJAX)
    public function contarNoLeidas() {
        // Verificar si el usuario está logueado
        if (!isLoggedIn()) {
            echo json_encode(['count' => 0]);
            return;
        }
        
        // Contar notificaciones no leídas
        $count = $this->notificacionModel->contarNoLeidas($_SESSION['user_id']);
        
        // Devolver en formato JSON
        header('Content-Type: application/json');
        echo json_encode(['count' => $count]);
    }
}
?>