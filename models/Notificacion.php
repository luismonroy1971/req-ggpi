<?php
// models/Notificacion.php
class Notificacion {
    private $db;
    
    public function __construct() {
        $this->db = new Database();
    }
    
    // Crear notificación
    public function crear($data) {
        $sql = "INSERT INTO notificaciones (usuario_id, mensaje, requerimiento_id) 
                VALUES (:usuario_id, :mensaje, :requerimiento_id)";
        $stmt = $this->db->query($sql);
        
        $params = [
            ':usuario_id' => $data['usuario_id'],
            ':mensaje' => $data['mensaje'],
            ':requerimiento_id' => $data['requerimiento_id']
        ];
        
        if ($this->db->execute($stmt, $params)) {
            return $this->db->lastInsertId();
        } else {
            return false;
        }
    }
    
    // Listar notificaciones por usuario
    public function listarPorUsuario($usuario_id) {
        try {
            $sql = "SELECT * FROM notificaciones 
                    WHERE usuario_id = ? 
                    ORDER BY created_at DESC";
            $stmt = $this->db->query($sql);
            $stmt->bindValue(1, $usuario_id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            // Registrar error pero devolver array vacío para evitar errores
            error_log("Error en listarPorUsuario: " . $e->getMessage());
            return [];
        }
    }
    

    // Listar notificaciones no leídas por usuario
    public function listarNoLeidasPorUsuario($usuario_id) {
        try {
            // Usar consulta con placeholders posicionales
            $sql = "SELECT * FROM notificaciones 
                    WHERE usuario_id = ? AND leido = 0 
                    ORDER BY created_at DESC";
            $stmt = $this->db->query($sql);
            $stmt->bindValue(1, $usuario_id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            // Registrar error pero devolver array vacío para evitar errores
            error_log("Error en listarNoLeidasPorUsuario: " . $e->getMessage());
            return [];
        }
    }
    
    // Marcar notificación como leída
    public function marcarComoLeida($id) {
        $sql = "UPDATE notificaciones SET leido = 1 WHERE id = :id";
        $stmt = $this->db->query($sql);
        $params = [':id' => $id];
        return $this->db->execute($stmt, $params);
    }
    
    // Marcar todas las notificaciones de un usuario como leídas
    public function marcarTodasComoLeidas($usuario_id) {
        $sql = "UPDATE notificaciones SET leido = 1 WHERE usuario_id = :usuario_id AND leido = 0";
        $stmt = $this->db->query($sql);
        $params = [':usuario_id' => $usuario_id];
        return $this->db->execute($stmt, $params);
    }
    
    public function contarNoLeidas($usuario_id) {
        try {
            $sql = "SELECT COUNT(*) as total FROM notificaciones WHERE usuario_id = ? AND leido = 0";
            $stmt = $this->db->query($sql);
            $stmt->bindValue(1, $usuario_id, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'];
        } catch (Exception $e) {
            error_log("Error en contarNoLeidas: " . $e->getMessage());
            return 0;
        }
    }
    
    // Eliminar notificación
    public function eliminar($id) {
        $sql = "DELETE FROM notificaciones WHERE id = :id";
        $stmt = $this->db->query($sql);
        $params = [':id' => $id];
        return $this->db->execute($stmt, $params);
    }
}