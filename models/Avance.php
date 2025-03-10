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
    
    // Eliminar avance
    public function eliminar($id) {
        try {
            $sql = "DELETE FROM avances WHERE id = ?";
            $stmt = $this->db->query($sql);
            $stmt->bindValue(1, $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Error en eliminar: " . $e->getMessage());
            return false;
        }
    }

    // Obtener avance por ID
    public function obtenerPorId($id) {
        try {
            $sql = "SELECT a.*, u.nombre as usuario_nombre 
                    FROM avances a 
                    JOIN usuarios u ON a.usuario_id = u.id 
                    WHERE a.id = ?";
            $stmt = $this->db->query($sql);
            $stmt->bindValue(1, $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error en obtenerPorId: " . $e->getMessage());
            return false;
        }
    }

    // Actualizar avance
    public function actualizar($data) {
        try {
            $sql = "UPDATE avances SET descripcion = ?, porcentaje = ? WHERE id = ?";
            $stmt = $this->db->query($sql);
            $stmt->bindValue(1, $data['descripcion'], PDO::PARAM_STR);
            $stmt->bindValue(2, $data['porcentaje'], PDO::PARAM_INT);
            $stmt->bindValue(3, $data['id'], PDO::PARAM_INT);
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Error en actualizar: " . $e->getMessage());
            return false;
        }
    }
}
?>

