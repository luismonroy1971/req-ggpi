<?php
// controllers/AuthController.php
require_once 'models/Usuario.php';

class AuthController {
    private $usuarioModel;
    
    public function __construct() {
        $this->usuarioModel = new Usuario();
    }
    
    // Mostrar formulario de login
    public function login() {
        // Si ya está logueado, redirigir al dashboard
        if (isLoggedIn()) {
            redirect('dashboard');
        }
        
        // Comprobar si se ha enviado el formulario
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Procesar formulario
            
            // Sanitizar datos
            $correo = sanitize($_POST['correo']);
            $password = $_POST['password']; // No sanitizar contraseña para no alterar caracteres
            
            // Validar credenciales
            $usuario = $this->usuarioModel->login($correo, $password);
            
            if ($usuario) {
                // Iniciar sesión
                $_SESSION['user_id'] = $usuario['id'];
                $_SESSION['nombre'] = $usuario['nombre'];
                $_SESSION['correo'] = $usuario['correo'];
                $_SESSION['area_id'] = $usuario['area_id'];
                $_SESSION['rol'] = $usuario['rol'];
                
                setMessage('success', 'Bienvenido/a, ' . $usuario['nombre']);
                redirect('dashboard');
            } else {
                setMessage('error', 'Correo electrónico o contraseña incorrectos');
                // Volver a mostrar el formulario de login
                include 'views/auth/login.php';
            }
        } else {
            // Mostrar formulario de login
            include 'views/auth/login.php';
        }
    }
    
    // Cerrar sesión
    public function logout() {
        // Eliminar todas las variables de sesión
        session_unset();
        
        // Destruir la sesión
        session_destroy();
        
        setMessage('success', 'Sesión cerrada correctamente');
        redirect('login');
    }
}
?>