<?php
// verificar-permisos.php - Archivo temporal de diagnóstico
// Coloca este archivo en la carpeta views y agrégalo al inicio de ver.php temporalmente

// Verificar datos de sesión
echo '<div style="margin: 10px; padding: 10px; border: 1px solid #ccc; background: #f8f8f8;">';
echo '<h3>Diagnóstico</h3>';

// Verificar si hay sesión iniciada
echo '<p>¿Hay sesión iniciada? ' . (session_status() === PHP_SESSION_ACTIVE ? 'Sí' : 'No') . '</p>';

// Verificar datos de sesión
echo '<p>Datos de sesión:</p>';
echo '<ul>';
foreach ($_SESSION as $key => $value) {
    if (is_array($value)) {
        echo '<li>' . $key . ': Array</li>';
    } else {
        echo '<li>' . $key . ': ' . $value . '</li>';
    }
}
echo '</ul>';

// Verificar resultado de isLoggedIn() e isAdmin()
echo '<p>¿Usuario logueado (isLoggedIn)? ' . (isLoggedIn() ? 'Sí' : 'No') . '</p>';
echo '<p>¿Es administrador (isAdmin)? ' . (isAdmin() ? 'Sí' : 'No') . '</p>';

// Verificar rutas
echo '<p>Ruta actual: ' . getCurrentRoute() . '</p>';
$routeInfo = routeExists(getCurrentRoute());
echo '<p>¿Ruta encontrada? ' . ($routeInfo ? 'Sí' : 'No') . '</p>';
if ($routeInfo) {
    echo '<p>Grupo: ' . $routeInfo['group'] . '</p>';
    echo '<p>Controlador: ' . $routeInfo['route']['controller'] . '</p>';
    echo '<p>Acción: ' . $routeInfo['route']['action'] . '</p>';
    echo '<p>¿Requiere rol? ' . (isset($routeInfo['route']['role']) ? $routeInfo['route']['role'] : 'No') . '</p>';
}

echo '</div>';

// También verifica la definición de BASE_URL
echo '<div style="margin: 10px; padding: 10px; border: 1px solid #ccc; background: #f8f8f8;">';
echo '<p>BASE_URL: ' . (defined('BASE_URL') ? BASE_URL : 'No definido') . '</p>';
echo '</div>';

// Quita este archivo después de diagnosticar el problema
?>