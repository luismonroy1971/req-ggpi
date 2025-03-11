<?php
// routes.php - Archivo de definición de rutas

// Definir las rutas de la aplicación
$routes = [
    // Rutas públicas (no requieren autenticación)
    'public' => [
        'login' => ['controller' => 'AuthController', 'action' => 'login'],
        'logout' => ['controller' => 'AuthController', 'action' => 'logout'],
        'api/token' => ['controller' => 'AuthController', 'action' => 'generateToken'],
    ],
    
    // Rutas protegidas (requieren autenticación)
    'protected' => [
        // Dashboard
        'dashboard' => ['controller' => 'DashboardController', 'action' => 'index'],
        
        // Requerimientos
        'requerimientos' => ['controller' => 'RequerimientoController', 'action' => 'index'],
        'requerimientos/crear' => ['controller' => 'RequerimientoController', 'action' => 'crear'],
        'requerimientos/ver/{id}' => ['controller' => 'RequerimientoController', 'action' => 'ver'],
        'requerimientos/editar/{id}' => ['controller' => 'RequerimientoController', 'action' => 'editar'],
        'requerimientos/eliminar/{id}' => ['controller' => 'RequerimientoController', 'action' => 'eliminar', 'role' => 'admin'],
        'requerimientos/cambiarEstado/{id}' => ['controller' => 'RequerimientoController', 'action' => 'cambiarEstado', 'role' => 'admin'],
        
        // Avances
        'avances/crear/{id}' => ['controller' => 'AvanceController', 'action' => 'crear', 'role' => 'admin'],
        'avances/editar/{id}' => ['controller' => 'AvanceController', 'action' => 'editar', 'role' => 'admin'],
        'avances/eliminar/{id}' => ['controller' => 'AvanceController', 'action' => 'eliminar', 'role' => 'admin'],
        
        // Notificaciones
        'notificaciones' => ['controller' => 'NotificacionController', 'action' => 'index'],
        'notificaciones/marcar-leida/{id}' => ['controller' => 'NotificacionController', 'action' => 'marcarLeida'],
        'notificaciones/marcar-todas-leidas' => ['controller' => 'NotificacionController', 'action' => 'marcarTodasLeidas'],
        'notificaciones/contar-no-leidas' => ['controller' => 'NotificacionController', 'action' => 'contarNoLeidas'],
        
        // Anexos
        'anexos/subir/{id}' => ['controller' => 'AnexoController', 'action' => 'subir'],
        'anexos/descargar/{id}' => ['controller' => 'AnexoController', 'action' => 'descargar'],
        'anexos/eliminar/{id}' => ['controller' => 'AnexoController', 'action' => 'eliminar'],
        'anexos/editar/{id}' => ['controller' => 'AnexoController', 'action' => 'editar'],
    ],
    
    // API Routes
    'api' => [
        'api/requerimientos' => ['controller' => 'ApiController', 'action' => 'listarRequerimientos'],
        'api/requerimientos/{id}' => ['controller' => 'ApiController', 'action' => 'obtenerRequerimiento'],
        'api/notificaciones/contar' => ['controller' => 'ApiController', 'action' => 'contarNotificaciones'],
    ]
];

// Función para verificar si una ruta existe
function routeExists($route, $routeGroups = ['public', 'protected', 'api']) {
    global $routes;
    
    foreach ($routeGroups as $group) {
        if (isset($routes[$group][$route])) {
            return ['group' => $group, 'route' => $routes[$group][$route]];
        }
        
        // Verificar rutas con parámetros
        foreach ($routes[$group] as $pattern => $routeInfo) {
            // Convertir la ruta de patrón en expresión regular
            $pattern = preg_quote($pattern, '/');
            $pattern = str_replace('\{id\}', '(\d+)', $pattern);
            
            if (preg_match('/^' . $pattern . '$/', $route, $matches)) {
                // Extraer el ID si existe
                $id = isset($matches[1]) ? $matches[1] : null;
                
                return [
                    'group' => $group,
                    'route' => $routeInfo,
                    'params' => [$id]
                ];
            }
        }
    }
    
    return false;
}

// Función para obtener la ruta actual
function getCurrentRoute() {
    $request = trim($_SERVER['REQUEST_URI'], '/');
    
    // Si hay parámetros GET, eliminarlos
    if (strpos($request, '?') !== false) {
        $request = substr($request, 0, strpos($request, '?'));
    }
    
    // Obtener la ruta base de la aplicación para eliminarla de la URL
    $base_path = parse_url(BASE_URL, PHP_URL_PATH);
    $base_path = trim($base_path, '/');
    
    if (!empty($base_path) && strpos($request, $base_path) === 0) {
        $request = substr($request, strlen($base_path));
    }
    
    $request = trim($request, '/');
    
    return $request;
}