<?php
// controllers/AuthController.php
require_once 'models/Usuario.php';
require_once 'helpers/JWT.php';

class AuthController {
    private $usuarioModel;
    private $jwt;
    
    public function __construct() {
        $this->usuarioModel = new Usuario();
        $this->jwt = new JWT();
    }
    
    // Mostrar formulario de login
    // Método login en AuthController.php

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
                
                // Depuración
                error_log("Usuario logueado: ID={$usuario['id']}, Nombre={$usuario['nombre']}, Rol={$usuario['rol']}");
                
                // Generar token JWT
                $tokenPayload = [
                    'user_id' => $usuario['id'],
                    'nombre' => $usuario['nombre'],
                    'correo' => $usuario['correo'],
                    'area_id' => $usuario['area_id'],
                    'rol' => $usuario['rol']
                ];
                
                $token = $this->jwt->generate($tokenPayload);
                $_SESSION['token'] = $token;
                
                // Si es una solicitud de API, devolver el token en formato JSON
                if (isset($_POST['api_request']) && $_POST['api_request'] === 'true') {
                    header('Content-Type: application/json');
                    echo json_encode([
                        'success' => true,
                        'token' => $token,
                        'user' => [
                            'id' => $usuario['id'],
                            'nombre' => $usuario['nombre'],
                            'correo' => $usuario['correo'],
                            'rol' => $usuario['rol']
                        ]
                    ]);
                    exit;
                }
                
                setMessage('success', 'Bienvenido/a, ' . $usuario['nombre']);
                redirect('dashboard');
            } else {
                if (isset($_POST['api_request']) && $_POST['api_request'] === 'true') {
                    header('Content-Type: application/json');
                    echo json_encode([
                        'success' => false,
                        'message' => 'Credenciales inválidas'
                    ]);
                    exit;
                }
                
                setMessage('error', 'Correo electrónico o contraseña incorrectos');
                // Volver a mostrar el formulario de login
                include 'views/auth/login.php';
            }
        } else {
            // Mostrar formulario de login
            include 'views/auth/login.php';
        }
    }
    
    // Generar token JWT (para uso en API)
    public function generateToken() {
        // Solo aceptar peticiones POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('HTTP/1.1 405 Method Not Allowed');
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Método no permitido']);
            exit;
        }
        
        // Obtener datos del cuerpo de la petición
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (!isset($data['correo']) || !isset($data['password'])) {
            header('HTTP/1.1 400 Bad Request');
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Faltan credenciales']);
            exit;
        }
        
        // Validar credenciales
        $usuario = $this->usuarioModel->login($data['correo'], $data['password']);
        
        if ($usuario) {
            // Generar token JWT
            $tokenPayload = [
                'user_id' => $usuario['id'],
                'nombre' => $usuario['nombre'],
                'correo' => $usuario['correo'],
                'area_id' => $usuario['area_id'],
                'rol' => $usuario['rol']
            ];
            
            $token = $this->jwt->generate($tokenPayload);
            
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'token' => $token,
                'user' => [
                    'id' => $usuario['id'],
                    'nombre' => $usuario['nombre'],
                    'correo' => $usuario['correo'],
                    'rol' => $usuario['rol']
                ]
            ]);
        } else {
            header('HTTP/1.1 401 Unauthorized');
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'error' => 'Credenciales inválidas'
            ]);
        }
        
        exit;
    }
    
    // Verificar token (para middleware)
    public function verifyToken() {
        // Comprobar si el token está en la sesión
        if (isset($_SESSION['token'])) {
            $token = $_SESSION['token'];
        } else {
            // Intentar obtener el token de los headers (para API)
            $token = $this->jwt->getTokenFromHeader();
        }
        
        if (!$token) {
            return false;
        }
        
        // Verificar el token
        $payload = $this->jwt->verify($token);
        
        if (!$payload) {
            // Token inválido o expirado
            return false;
        }
        
        // Si llegamos aquí, el token es válido
        return $payload;
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