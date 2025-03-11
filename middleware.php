<?php
// middleware.php - Middleware para protección de rutas

/**
 * Middleware para autenticación de rutas
 * Verifica si el usuario está autenticado y tiene los permisos necesarios
 * 
 * @param string $routeGroup Grupo de ruta (public, protected, api)
 * @param array $routeInfo Información de la ruta
 * @return bool True si tiene acceso, false si no
 */
function authMiddleware($routeGroup, $routeInfo) {
    // Rutas públicas no requieren autenticación
    if ($routeGroup === 'public') {
        return true;
    }
    
    // Inicializar controlador de autenticación
    require_once 'controllers/AuthController.php';
    $authController = new AuthController();
    
    // Verificar si el token es válido
    $payload = $authController->verifyToken();
    
    // Si el token no es válido
    if (!$payload) {
        if ($routeGroup === 'api') {
            // Para API, devolver error JSON
            header('HTTP/1.1 401 Unauthorized');
            header('Content-Type: application/json');
            echo json_encode(['error' => 'No autorizado']);
            exit;
        } else {
            // Para web, redirigir al login
            setMessage('error', 'Sesión expirada o inválida. Por favor, inicia sesión nuevamente.');
            redirect('login');
            return false;
        }
    }
    
    // Verificar rol si es necesario
    if (isset($routeInfo['role']) && $routeInfo['role'] === 'administrador') {
        if ($payload['rol'] !== 'admin') {
            if ($routeGroup === 'api') {
                // Para API, devolver error JSON
                header('HTTP/1.1 403 Forbidden');
                header('Content-Type: application/json');
                echo json_encode(['error' => 'Acceso denegado. Se requiere rol de administrador.']);
                exit;
            } else {
                // Para web, mostrar error y redirigir
                setMessage('error', 'Acceso denegado. No tienes permisos para acceder a esta sección.');
                redirect('dashboard');
                return false;
            }
        }
    }
    
    // Si llegamos aquí, el usuario tiene acceso
    return true;
}

/**
 * Middleware para los encabezados CORS
 * Necesario para llamadas API desde otros dominios
 */
function corsMiddleware() {
    // Permitir solicitudes desde cualquier origen
    header('Access-Control-Allow-Origin: *');
    
    // Permitir los siguientes métodos HTTP
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
    
    // Permitir los siguientes encabezados
    header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token, Authorization');
    
    // Permitir credenciales
    header('Access-Control-Allow-Credentials: true');
    
    // Manejar solicitudes preflight OPTIONS
    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        header('HTTP/1.1 200 OK');
        exit;
    }
}