<?php
// models/Anexo.php
class Anexo {
    private $db;
    
    public function __construct() {
        $this->db = new Database();
    }
    
    // Crear un nuevo anexo
    public function crear($data) {
        try {
            $sql = "INSERT INTO documentos_anexos (requerimiento_id, titulo, nombre_archivo, ruta_archivo, 
                   tipo_archivo, tamanio_archivo, usuario_id) 
                   VALUES (?, ?, ?, ?, ?, ?, ?)";
            
            $stmt = $this->db->query($sql);
            $stmt->bindValue(1, $data['requerimiento_id'], PDO::PARAM_INT);
            $stmt->bindValue(2, $data['titulo'], PDO::PARAM_STR);
            $stmt->bindValue(3, $data['nombre_archivo'], PDO::PARAM_STR);
            $stmt->bindValue(4, $data['ruta_archivo'], PDO::PARAM_STR);
            $stmt->bindValue(5, $data['tipo_archivo'], PDO::PARAM_STR);
            $stmt->bindValue(6, $data['tamanio_archivo'], PDO::PARAM_INT);
            $stmt->bindValue(7, $data['usuario_id'], PDO::PARAM_INT);
            
            if($stmt->execute()) {
                return $this->db->lastInsertId();
            } else {
                return false;
            }
        } catch (Exception $e) {
            error_log("Error al crear anexo: " . $e->getMessage());
            return false;
        }
    }
    
    // Obtener todos los anexos de un requerimiento
    public function obtenerPorRequerimiento($requerimiento_id) {
        try {
            $sql = "SELECT a.*, u.nombre as usuario_nombre 
                    FROM documentos_anexos a 
                    JOIN usuarios u ON a.usuario_id = u.id 
                    WHERE a.requerimiento_id = ? 
                    ORDER BY a.created_at DESC";
            
            $stmt = $this->db->query($sql);
            $stmt->bindValue(1, $requerimiento_id, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error al obtener anexos: " . $e->getMessage());
            return [];
        }
    }
    
    // Obtener un anexo por su ID
    public function obtenerPorId($id) {
        try {
            $sql = "SELECT a.*, u.nombre as usuario_nombre 
                    FROM documentos_anexos a 
                    JOIN usuarios u ON a.usuario_id = u.id 
                    WHERE a.id = ?";
            
            $stmt = $this->db->query($sql);
            $stmt->bindValue(1, $id, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error al obtener anexo: " . $e->getMessage());
            return false;
        }
    }
    
    // Actualizar información de un anexo
    public function actualizar($data) {
        try {
            $sql = "UPDATE documentos_anexos SET titulo = ? WHERE id = ?";
            
            $stmt = $this->db->query($sql);
            $stmt->bindValue(1, $data['titulo'], PDO::PARAM_STR);
            $stmt->bindValue(2, $data['id'], PDO::PARAM_INT);
            
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Error al actualizar anexo: " . $e->getMessage());
            return false;
        }
    }
    
    // Eliminar un anexo
    public function eliminar($id) {
        try {
            // Primero obtenemos la información del anexo para eliminar el archivo físico
            $anexo = $this->obtenerPorId($id);
            
            if ($anexo) {
                // Eliminar el registro de la base de datos
                $sql = "DELETE FROM documentos_anexos WHERE id = ?";
                $stmt = $this->db->query($sql);
                $stmt->bindValue(1, $id, PDO::PARAM_INT);
                
                if ($stmt->execute()) {
                    // Si se eliminó el registro, eliminar el archivo físico
                    if (file_exists($anexo['ruta_archivo'])) {
                        unlink($anexo['ruta_archivo']);
                    }
                    return true;
                }
            }
            
            return false;
        } catch (Exception $e) {
            error_log("Error al eliminar anexo: " . $e->getMessage());
            return false;
        }
    }
    
    // Contar anexos por requerimiento
    public function contarPorRequerimiento($requerimiento_id) {
        try {
            $sql = "SELECT COUNT(*) as total FROM documentos_anexos WHERE requerimiento_id = ?";
            
            $stmt = $this->db->query($sql);
            $stmt->bindValue(1, $requerimiento_id, PDO::PARAM_INT);
            $stmt->execute();
            
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'];
        } catch (Exception $e) {
            error_log("Error al contar anexos: " . $e->getMessage());
            return 0;
        }
    }
}
?>