<?php
// models/Usuario.php
class Usuario {
    private $db;
    
    public function __construct() {
        $this->db = new Database();
    }
    
    // Registrar usuario
    public function registrar($data) {
        $sql = "INSERT INTO usuarios (nombre, correo, password, area_id, rol) VALUES (:nombre, :correo, :password, :area_id, :rol)";
        $stmt = $this->db->query($sql);
        
        // Hash del password
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        
        // Bind values
        $params = [
            ':nombre' => $data['nombre'],
            ':correo' => $data['correo'],
            ':password' => $data['password'],
            ':area_id' => $data['area_id'],
            ':rol' => $data['rol']
        ];
        
        // Execute
        if ($this->db->execute($stmt, $params)) {
            return $this->db->lastInsertId();
        } else {
            return false;
        }
    }
    
    // Login
    public function login($correo, $password) {
        $sql = "SELECT id, nombre, correo, password, area_id, rol FROM usuarios WHERE correo = ?";
        $stmt = $this->db->query($sql);
        $stmt->bindParam(1, $correo);
        $stmt->execute();
        $row = $stmt->fetch();
        
        if ($row && password_verify($password, $row['password'])) {
            return $row;
        }
        
        return false;
    }
    
    // Buscar usuario por ID
    public function obtenerPorId($id) {
        $sql = "SELECT u.*, a.nombre as area_nombre FROM usuarios u 
                JOIN areas a ON u.area_id = a.id 
                WHERE u.id = :id";
        $stmt = $this->db->query($sql);
        $params = [':id' => $id];
        return $this->db->single($stmt, $params);
    }
    
    // Buscar usuario por correo
    public function obtenerPorCorreo($correo) {
        $sql = "SELECT * FROM usuarios WHERE correo = :correo";
        $stmt = $this->db->query($sql);
        $params = [':correo' => $correo];
        return $this->db->single($stmt, $params);
    }
    
    // Listar todos los usuarios
    public function listarTodos() {
        $sql = "SELECT u.*, a.nombre as area_nombre FROM usuarios u 
                JOIN areas a ON u.area_id = a.id 
                ORDER BY u.nombre ASC";
        $stmt = $this->db->query($sql);
        return $this->db->resultSet($stmt);
    }
    
    // Listar usuarios por área
    public function listarPorArea($area_id) {
        $sql = "SELECT u.*, a.nombre as area_nombre FROM usuarios u 
                JOIN areas a ON u.area_id = a.id 
                WHERE u.area_id = :area_id 
                ORDER BY u.nombre ASC";
        $stmt = $this->db->query($sql);
        $params = [':area_id' => $area_id];
        return $this->db->resultSet($stmt, $params);
    }
    
    // Actualizar usuario
    public function actualizar($data) {
        // Si se proporciona una nueva contraseña, hash
        if (!empty($data['password'])) {
            $sql = "UPDATE usuarios SET 
                    nombre = :nombre, 
                    correo = :correo, 
                    password = :password, 
                    area_id = :area_id, 
                    rol = :rol 
                    WHERE id = :id";
            $params = [
                ':nombre' => $data['nombre'],
                ':correo' => $data['correo'],
                ':password' => password_hash($data['password'], PASSWORD_DEFAULT),
                ':area_id' => $data['area_id'],
                ':rol' => $data['rol'],
                ':id' => $data['id']
            ];
        } else {
            $sql = "UPDATE usuarios SET 
                    nombre = :nombre, 
                    correo = :correo, 
                    area_id = :area_id, 
                    rol = :rol 
                    WHERE id = :id";
            $params = [
                ':nombre' => $data['nombre'],
                ':correo' => $data['correo'],
                ':area_id' => $data['area_id'],
                ':rol' => $data['rol'],
                ':id' => $data['id']
            ];
        }
        
        $stmt = $this->db->query($sql);
        return $this->db->execute($stmt, $params);
    }
    
    // Eliminar usuario
    public function eliminar($id) {
        $sql = "DELETE FROM usuarios WHERE id = :id";
        $stmt = $this->db->query($sql);
        $params = [':id' => $id];
        return $this->db->execute($stmt, $params);
    }
    
    // Obtener todas las áreas
    public function obtenerAreas() {
        $sql = "SELECT * FROM areas ORDER BY nombre ASC";
        $stmt = $this->db->query($sql);
        return $this->db->resultSet($stmt);
    }
}
?>

