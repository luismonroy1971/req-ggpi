<?php
// config/config.php - Configuración global de la aplicación

// Configuración de URL base
define('BASE_URL', 'http://localhost:8000/');

// Configuración de la base de datos
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'reque_ggpi');

// Configuración de JWT
define('JWT_SECRET', 'ChangeThisToASecureRandomString!123$%^&*()'); // Cambiar por una cadena segura y aleatoria
define('JWT_EXPIRY', 28800); // 8 horas en segundos

// Zona horaria
date_default_timezone_set('America/Lima');

// Iniciar sesión
session_start();

// Funciones globales
/**
 * Redireccionar a una URL
 * @param string $page Ruta a la que redireccionar
 */
function redirect($page) {
    header('Location: ' . BASE_URL . $page);
    exit;
}

/**
 * Verificar si el usuario está logueado
 * @return bool True si está logueado, false si no
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

/**
 * Verificar si el usuario es administrador
 * @return bool True si es admin, false si no
 */
function isAdmin() {
    // Verificar si hay sesión activa primero
    if (!isset($_SESSION['user_id'])) {
        return false;
    }
    
    // Verificar si el rol es admin
    return isset($_SESSION['rol']) && $_SESSION['rol'] === 'administrador';
}
/**
 * Sanitizar entrada para prevenir XSS
 * @param string $input Cadena a sanitizar
 * @return string Cadena sanitizada
 */
function sanitize($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

/**
 * Establecer mensaje flash para mostrar en la siguiente página
 * @param string $type Tipo de mensaje (success, error, info, warning)
 * @param string $text Texto del mensaje
 */
function setMessage($type, $text) {
    $_SESSION['message'] = [
        'type' => $type,
        'text' => $text
    ];
}

/**
 * Obtener mensaje flash y eliminarlo de la sesión
 * @return array|null Mensaje o null si no hay mensaje
 */
function getMessage() {
    $message = $_SESSION['message'] ?? null;
    unset($_SESSION['message']);
    return $message;
}

/**
 * Verificar si una ruta requiere API KEY
 * @param string $route Ruta a verificar
 * @return bool True si requiere API KEY, false si no
 */
function requiresApiKey($route) {
    return strpos($route, 'api/') === 0;
}

/**
 * Obtener el usuario actual desde JWT
 * @return array|null Datos del usuario o null si no está autenticado
 */
function getCurrentUser() {
    require_once 'controllers/AuthController.php';
    $authController = new AuthController();
    return $authController->verifyToken();
}

// También puedes agregar una función para verificar permisos específicos si es necesario
/**
 * Verificar si el usuario tiene permiso para editar un requerimiento
 *
 * @param int $creado_por ID del usuario que creó el requerimiento
 * @return bool True si tiene permiso, false si no
 */
function puedeEditar($creado_por) {
    return isAdmin() || (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $creado_por);
}