<?php
// models/Avance.php
class Avance {
    private $db;
    
    public function __construct() {
        $this->db = new Database();
    }
    
    // Registrar avance
    public function registrar($data) {
        $sql = "INSERT INTO avances (requerimiento_id, descripcion, usuario_id, porcentaje) 
                VALUES (:requerimiento_id, :descripcion, :usuario_id, :porcentaje)";
        $stmt = $this->db->query($sql);
        
        $params = [
            ':requerimiento_id' => $data['requerimiento_id'],
            ':descripcion' => $data['descripcion'],
            ':usuario_id' => $data['usuario_id'],
            ':porcentaje' => isset($data['porcentaje']) ? $data['porcentaje'] : null
        ];
        
        if ($this->db->execute($stmt, $params)) {
            return $this->db->lastInsertId();
        } else {
            return false;
        }
    }
    
    // Listar avances por requerimiento
    public function listarPorRequerimiento($requerimiento_id) {
        try {
            $sql = "SELECT a.*, u.nombre as nombre_usuario 
                    FROM avances a
                    JOIN usuarios u ON a.usuario_id = u.id
                    WHERE a.requerimiento_id = ?
                    ORDER BY a.created_at DESC";
            $stmt = $this->db->query($sql);
            $stmt->bindValue(1, $requerimiento_id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error en listarPorRequerimiento: " . $e->getMessage());
            return [];
        }
    }
    
    // Obtener avance por ID
    public function obtenerPorId($id) {
        $sql = "SELECT a.*, u.nombre as usuario_nombre 
                FROM avances a 
                JOIN usuarios u ON a.usuario_id = u.id 
                WHERE a.id = :id";
        $stmt = $this->db->query($sql);
        $params = [':id' => $id];
        return $this->db->single($stmt, $params);
    }
    
    // Eliminar avance
    public function eliminar($id) {
        $sql = "DELETE FROM avances WHERE id = :id";
        $stmt = $this->db->query($sql);
        $params = [':id' => $id];
        return $this->db->execute($stmt, $params);
    }
}
?>

