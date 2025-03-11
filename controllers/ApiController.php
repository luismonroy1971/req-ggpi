<?php
// controllers/ApiController.php
require_once 'models/Requerimiento.php';
require_once 'models/Notificacion.php';
require_once 'helpers/JWT.php';

class ApiController {
    private $requerimientoModel;
    private $notificacionModel;
    private $jwt;
    
    public function __construct() {
        $this->requerimientoModel = new Requerimiento();
        $this->notificacionModel = new Notificacion();
        $this->jwt = new JWT();
        
        // Establecer cabeceras para respuestas JSON
        header('Content-Type: application/json');
    }
    
    // Listar requerimientos
    public function listarRequerimientos() {
        // Obtener información del usuario del token JWT
        $usuario = $this->getUserFromToken();
        
        if (!$usuario) {
            $this->sendError('No autorizado', 401);
            return;
        }
        
        // Procesar filtros si existen
        $filtros = [];
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            if (!empty($_GET['estado'])) {
                $filtros['estado'] = sanitize($_GET['estado']);
            }
            if (!empty($_GET['prioridad'])) {
                $filtros['prioridad'] = sanitize($_GET['prioridad']);
            }
            if (!empty($_GET['fecha_desde'])) {
                $filtros['fecha_desde'] = sanitize($_GET['fecha_desde']);
            }
            if (!empty($_GET['fecha_hasta'])) {
                $filtros['fecha_hasta'] = sanitize($_GET['fecha_hasta']);
            }
        }
        
        // Obtener requerimientos según el rol del usuario
        if ($usuario['rol'] === 'admin') {
            $requerimientos = $this->requerimientoModel->listarTodos($filtros);
        } else {
            $requerimientos = $this->requerimientoModel->listarPorArea($usuario['area_id'], $filtros);
        }
        
        // Enviar respuesta
        echo json_encode([
            'success' => true,
            'data' => $requerimientos
        ]);
    }
    
    // Obtener un requerimiento específico
    public function obtenerRequerimiento($id) {
        // Obtener información del usuario del token JWT
        $usuario = $this->getUserFromToken();
        
        if (!$usuario) {
            $this->sendError('No autorizado', 401);
            return;
        }
        
        // Obtener requerimiento
        $requerimiento = $this->requerimientoModel->obtenerPorId($id);
        
        // Verificar si existe el requerimiento
        if (!$requerimiento) {
            $this->sendError('El requerimiento no existe', 404);
            return;
        }
        
        // Verificar permisos (solo puede verlo el admin o usuarios de la misma área)
        if ($usuario['rol'] !== 'admin' && $requerimiento['area_id'] != $usuario['area_id']) {
            $this->sendError('No tienes permisos para ver este requerimiento', 403);
            return;
        }
        
        // Enviar respuesta
        echo json_encode([
            'success' => true,
            'data' => $requerimiento
        ]);
    }
    
    // Contar notificaciones no leídas
    public function contarNotificaciones() {
        // Obtener información del usuario del token JWT
        $usuario = $this->getUserFromToken();
        
        if (!$usuario) {
            $this->sendError('No autorizado', 401);
            return;
        }
        
        // Contar notificaciones no leídas
        $count = $this->notificacionModel->contarNoLeidas($usuario['user_id']);
        
        // Enviar respuesta
        echo json_encode([
            'success' => true,
            'count' => $count
        ]);
    }
    
    // Obtener información del usuario desde el token JWT
    private function getUserFromToken() {
        // Obtener token del header Authorization
        $token = $this->jwt->getTokenFromHeader();
        
        if (!$token) {
            return null;
        }
        
        // Verificar y decodificar el token
        return $this->jwt->verify($token);
    }
    
    // Enviar respuesta de error
    private function sendError($message, $statusCode = 400) {
        http_response_code($statusCode);
        echo json_encode([
            'success' => false,
            'error' => $message
        ]);
    }
}