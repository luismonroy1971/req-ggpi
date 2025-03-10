<?php
// index.php - Punto de entrada principal
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';

// Cargar controladores
require_once __DIR__ . '/controllers/AuthController.php';
require_once __DIR__ . '/controllers/RequerimientoController.php';
require_once __DIR__ . '/controllers/AvanceController.php';
require_once __DIR__ . '/controllers/DashboardController.php';
require_once __DIR__ . '/controllers/NotificacionController.php';
require_once __DIR__ . '/controllers/AnexoController.php';


// Instanciar controladores
$authController = new AuthController();
$requerimientoController = new RequerimientoController();
$avanceController = new AvanceController();
$dashboardController = new DashboardController();
$notificacionController = new NotificacionController();
$anexoController = new AnexoController();

// Parsear la URL
$request = trim($_SERVER['REQUEST_URI'], '/');

// Si hay parámetros GET, eliminarlos
if (strpos($request, '?') !== false) {
    $request = substr($request, 0, strpos($request, '?'));
}

// Obtener la ruta base de la aplicación para eliminarla de la URL
$base_path = trim(str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']), '/');
$request = str_replace($base_path, '', $request);

// Dividir la URL en segmentos
$segments = explode('/', $request);

// Determinar el controlador y la acción
$controller = !empty($segments[0]) ? $segments[0] : 'dashboard';
$action = !empty($segments[1]) ? $segments[1] : 'index';
$param = !empty($segments[2]) ? $segments[2] : null;

// Enrutar la solicitud
switch ($controller) {
    case 'login':
        $authController->login();
        break;
        
    case 'logout':
        $authController->logout();
        break;
        
    case 'dashboard':
        $dashboardController->index();
        break;
        
    case 'requerimientos':
        switch ($action) {
            case 'index':
                $requerimientoController->index();
                break;
                
            case 'crear':
                $requerimientoController->crear();
                break;
                
            case 'ver':
                if ($param) {
                    $requerimientoController->ver($param);
                } else {
                    redirect('requerimientos');
                }
                break;
                
            case 'editar':
                if ($param) {
                    $requerimientoController->editar($param);
                } else {
                    redirect('requerimientos');
                }
                break;
                
            case 'eliminar':
                if ($param) {
                    $requerimientoController->eliminar($param);
                } else {
                    redirect('requerimientos');
                }
                break;
                
            case 'cambiarEstado':
                if ($param) {
                    $requerimientoController->cambiarEstado($param);
                } else {
                    redirect('requerimientos');
                }
                break;
                
            default:
                redirect('requerimientos');
                break;
        }
        break;
        
    case 'avances':
        switch ($action) {
            case 'crear':
                if ($param) {
                    $avanceController->crear($param);
                } else {
                    redirect('requerimientos');
                }
                break;
                
            case 'editar':
                if ($param) {
                    $avanceController->editar($param);
                } else {
                    redirect('requerimientos');
                }
                break;
                
            case 'eliminar':
                if ($param) {
                    $avanceController->eliminar($param);
                } else {
                    redirect('requerimientos');
                }
                break;
                
            default:
                redirect('requerimientos');
                break;
        }
        break;
        
    case 'notificaciones':
        switch ($action) {
            case 'index':
                $notificacionController->index();
                break;
                
            case 'marcar-leida':
                if ($param) {
                    $notificacionController->marcarLeida($param);
                } else {
                    redirect('notificaciones');
                }
                break;
                
            case 'marcar-todas-leidas':
                $notificacionController->marcarTodasLeidas();
                break;
                
            case 'contar-no-leidas':
                $notificacionController->contarNoLeidas();
                break;
                
            default:
                redirect('notificaciones');
                break;
        }
        break;
    case 'anexos':
        switch ($action) {
            case 'subir':
                if ($param) {
                    $anexoController->subir($param);
                } else {
                    redirect('requerimientos');
                }
                break;
                
            case 'descargar':
                if ($param) {
                    $anexoController->descargar($param);
                } else {
                    redirect('requerimientos');
                }
                break;
                
            case 'eliminar':
                if ($param) {
                    $anexoController->eliminar($param);
                } else {
                    redirect('requerimientos');
                }
                break;
                
            case 'editar':
                if ($param) {
                    $anexoController->editar($param);
                } else {
                    redirect('requerimientos');
                }
                break;
                
            default:
                redirect('requerimientos');
                break;
        }
        break;
        
    default:
        // Si el usuario está logueado, redirigir al dashboard
        if (isLoggedIn()) {
            redirect('dashboard');
        } else {
            redirect('login');
        }
        break;
}
?>