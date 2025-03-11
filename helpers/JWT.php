<?php
// helpers/JWT.php

// Asegurarse de que la configuración esté cargada
if (!defined('JWT_SECRET')) {
    $configPath = __DIR__ . '/../config/config.php';
    if (file_exists($configPath)) {
        require_once $configPath;
    }
}

class JWT {
    private $secretKey;
    private $tokenExpiry; // en segundos
    
    public function __construct() {
        // Cargar clave secreta desde configuración
        $this->secretKey = defined('JWT_SECRET') ? JWT_SECRET : 'tu_clave_secreta_muy_segura_aqui'; // Idealmente esto vendría de config.php
        $this->tokenExpiry = 3600 * 8; // 8 horas por defecto
    }
    
    /**
     * Generar un token JWT para un usuario
     * 
     * @param array $payload Datos a incluir en el token
     * @return string Token JWT
     */
    public function generate($payload) {
        // Header
        $header = json_encode([
            'typ' => 'JWT',
            'alg' => 'HS256'
        ]);
        
        // Añadir tiempo de expiración al payload
        $payload['exp'] = time() + $this->tokenExpiry;
        $payload['iat'] = time(); // Tiempo en que fue emitido
        
        $encodedHeader = $this->base64UrlEncode($header);
        $encodedPayload = $this->base64UrlEncode(json_encode($payload));
        
        // Crear firma
        $signature = hash_hmac('sha256', $encodedHeader . '.' . $encodedPayload, $this->secretKey, true);
        $encodedSignature = $this->base64UrlEncode($signature);
        
        // Crear token
        $jwt = $encodedHeader . '.' . $encodedPayload . '.' . $encodedSignature;
        
        return $jwt;
    }
    
    /**
     * Verificar y decodificar un token JWT
     * 
     * @param string $token Token JWT a verificar
     * @return array|false Payload decodificado si es válido, false si no
     */
    public function verify($token) {
        // Dividir token en partes
        $parts = explode('.', $token);
        
        if (count($parts) !== 3) {
            return false;
        }
        
        list($encodedHeader, $encodedPayload, $encodedSignature) = $parts;
        
        // Verificar firma
        $signature = $this->base64UrlDecode($encodedSignature);
        $expectedSignature = hash_hmac('sha256', $encodedHeader . '.' . $encodedPayload, $this->secretKey, true);
        
        if (!hash_equals($signature, $expectedSignature)) {
            return false;
        }
        
        // Decodificar payload
        $payload = json_decode($this->base64UrlDecode($encodedPayload), true);
        
        // Verificar expiración
        if (isset($payload['exp']) && $payload['exp'] < time()) {
            return false; // Token expirado
        }
        
        return $payload;
    }
    
    /**
     * Codificar en base64 URL-safe
     */
    private function base64UrlEncode($data) {
        $base64 = base64_encode($data);
        $base64Url = strtr($base64, '+/', '-_');
        return rtrim($base64Url, '=');
    }
    
    /**
     * Decodificar de base64 URL-safe
     */
    private function base64UrlDecode($data) {
        $base64 = strtr($data, '-_', '+/');
        $paddedBase64 = str_pad($base64, strlen($data) % 4, '=', STR_PAD_RIGHT);
        return base64_decode($paddedBase64);
    }
    
    /**
     * Extraer token de los headers de la petición
     */
    public function getTokenFromHeader() {
        $headers = apache_request_headers();
        
        if (isset($headers['Authorization'])) {
            $authHeader = $headers['Authorization'];
            
            // Verificar si es Bearer token
            if (preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
                return $matches[1];
            }
        }
        
        return null;
    }
}