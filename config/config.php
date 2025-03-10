<?php
// config/config.php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

define('BASE_URL', 'http://localhost:8000/');
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'reque_ggpi');

// Colores corporativos de Litoclean Perú
define('PRIMARY_COLOR', '#00796B'); // Verde oscuro
define('SECONDARY_COLOR', '#4CAF50'); // Verde claro
define('ACCENT_COLOR', '#FF5722'); // Naranja

// Configuración de sesión
session_start();

// Función para redireccionar
function redirect($url) {
    header('Location: ' . BASE_URL . $url);
    exit();
}

// Función para verificar si el usuario está logueado
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Función para verificar si el usuario es administrador
function isAdmin() {
    return isset($_SESSION['rol']) && $_SESSION['rol'] === 'administrador';
}

// Función para mostrar mensajes de error o éxito
function setMessage($type, $message) {
    $_SESSION['message'] = [
        'type' => $type,
        'text' => $message
    ];
}

function getMessage() {
    if (isset($_SESSION['message'])) {
        $message = $_SESSION['message'];
        unset($_SESSION['message']);
        return $message;
    }
    return null;
}

// Función para sanitizar entrada
function sanitize($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}
?>

