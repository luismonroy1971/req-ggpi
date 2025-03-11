<?php
// index.php - Punto de entrada principal
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/routes.php';
require_once __DIR__ . '/middleware.php';

// Cargar controladores (esto es importante si usas referencias a ellos antes de la carga dinámica)
require_once __DIR__ . '/controllers/AuthController.php';
require_once __DIR__ . '/controllers/RequerimientoController.php';
require_once __DIR__ . '/controllers/AvanceController.php';
require_once __DIR__ . '/controllers/DashboardController.php';
require_once __DIR__ . '/controllers/NotificacionController.php';
require_once __DIR__ . '/controllers/AnexoController.php';

// Cargar modelos importantes
require_once __DIR__ . '/models/Anexo.php';

// Iniciar sesión si aún no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Aplicar middleware CORS para API
corsMiddleware();

// Obtener la ruta actual
$currentRoute = getCurrentRoute();

// Si la ruta está vacía, redirigir a la ruta por defecto
if (empty($currentRoute)) {
    // Si el usuario está logueado, redirigir al dashboard
    if (isLoggedIn()) {
        redirect('dashboard');
    } else {
        redirect('login');
    }
    exit;
}

// Verificar si la ruta existe
$routeData = routeExists($currentRoute);

if (!$routeData) {
    // Ruta no encontrada
    header('HTTP/1.1 404 Not Found');
    include 'views/errors/404.php';
    exit;
}

// Aplicar middleware de autenticación
if (!authMiddleware($routeData['group'], $routeData['route'])) {
    // El middleware se encarga de redirigir o enviar respuesta
    exit;
}

// Cargar el controlador necesario
$controllerName = $routeData['route']['controller'];
$actionName = $routeData['route']['action'];

// Comprobar si el archivo del controlador existe
$controllerFile = "controllers/{$controllerName}.php";
if (!file_exists($controllerFile)) {
    // Controlador no encontrado
    header('HTTP/1.1 500 Internal Server Error');
    include 'views/errors/500.php';
    exit;
}

// Cargar el controlador
require_once $controllerFile;

// Instanciar el controlador
$controller = new $controllerName();

// Verificar si el método existe
if (!method_exists($controller, $actionName)) {
    // Método no encontrado
    header('HTTP/1.1 500 Internal Server Error');
    include 'views/errors/500.php';
    exit;
}

// Obtener parámetros si existen
$params = $routeData['params'] ?? [];

// Ejecutar la acción del controlador con los parámetros
call_user_func_array([$controller, $actionName], $params);