<?php
// models/Requerimiento.php
class Requerimiento {
    private $db;
    
    public function __construct() {
        $this->db = new Database();
    }
    
    // Crear requerimiento
    public function crear($data) {
        $sql = "INSERT INTO requerimientos (
                titulo, descripcion, prioridad, area_id, creado_por,
                nombre_solicitante, cargo, email, telefono,
                objetivo, resultados, funcionalidades, tecnologia, usuarios,
                recursos, restricciones, impacto, kpi,
                fecha_inicio, fecha_entrega, anexos
            ) VALUES (
                :titulo, :descripcion, :prioridad, :area_id, :creado_por,
                :nombre_solicitante, :cargo, :email, :telefono,
                :objetivo, :resultados, :funcionalidades, :tecnologia, :usuarios,
                :recursos, :restricciones, :impacto, :kpi,
                :fecha_inicio, :fecha_entrega, :anexos
            )";
        $stmt = $this->db->query($sql);
        
        $params = [
            ':titulo' => $data['titulo'],
            ':descripcion' => $data['descripcion'],
            ':prioridad' => $data['prioridad'],
            ':area_id' => $data['area_id'],
            ':creado_por' => $data['creado_por'],
            ':nombre_solicitante' => $data['nombre_solicitante'],
            ':cargo' => $data['cargo'],
            ':email' => $data['email'],
            ':telefono' => $data['telefono'],
            ':objetivo' => $data['objetivo'],
            ':resultados' => $data['resultados'],
            ':funcionalidades' => $data['funcionalidades'],
            ':tecnologia' => $data['tecnologia'],
            ':usuarios' => $data['usuarios'],
            ':recursos' => $data['recursos'],
            ':restricciones' => $data['restricciones'],
            ':impacto' => $data['impacto'],
            ':kpi' => $data['kpi'],
            ':fecha_inicio' => $data['fecha_inicio'] ? $data['fecha_inicio'] : null,
            ':fecha_entrega' => $data['fecha_entrega'] ? $data['fecha_entrega'] : null,
            ':anexos' => $data['anexos']
        ];
        
        if ($this->db->execute($stmt, $params)) {
            return $this->db->lastInsertId();
        } else {
            return false;
        }
    }
    
    // Actualizar requerimiento
    public function actualizar($data) {
        $sql = "UPDATE requerimientos SET 
                titulo = :titulo, 
                descripcion = :descripcion, 
                prioridad = :prioridad,
                nombre_solicitante = :nombre_solicitante,
                cargo = :cargo,
                email = :email,
                telefono = :telefono,
                objetivo = :objetivo,
                resultados = :resultados,
                funcionalidades = :funcionalidades,
                tecnologia = :tecnologia,
                usuarios = :usuarios,
                recursos = :recursos,
                restricciones = :restricciones,
                impacto = :impacto,
                kpi = :kpi,
                fecha_inicio = :fecha_inicio,
                fecha_entrega = :fecha_entrega,
                anexos = :anexos
                WHERE id = :id";
        
        $stmt = $this->db->query($sql);
        
        $params = [
            ':titulo' => $data['titulo'],
            ':descripcion' => $data['descripcion'],
            ':prioridad' => $data['prioridad'],
            ':nombre_solicitante' => $data['nombre_solicitante'],
            ':cargo' => $data['cargo'],
            ':email' => $data['email'],
            ':telefono' => $data['telefono'],
            ':objetivo' => $data['objetivo'],
            ':resultados' => $data['resultados'],
            ':funcionalidades' => $data['funcionalidades'],
            ':tecnologia' => $data['tecnologia'],
            ':usuarios' => $data['usuarios'],
            ':recursos' => $data['recursos'],
            ':restricciones' => $data['restricciones'],
            ':impacto' => $data['impacto'],
            ':kpi' => $data['kpi'],
            ':fecha_inicio' => $data['fecha_inicio'] ? $data['fecha_inicio'] : null,
            ':fecha_entrega' => $data['fecha_entrega'] ? $data['fecha_entrega'] : null,
            ':anexos' => $data['anexos'],
            ':id' => $data['id']
        ];
        
        return $this->db->execute($stmt, $params);
    }
    
    // Obtener requerimiento por ID
    public function obtenerPorId($id) {
        try {
            $sql = "SELECT r.*, a.nombre as area_nombre, u.nombre as creado_por_nombre 
                    FROM requerimientos r 
                    JOIN areas a ON r.area_id = a.id 
                    JOIN usuarios u ON r.creado_por = u.id 
                    WHERE r.id = ?";
            $stmt = $this->db->query($sql);
            $stmt->bindValue(1, $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error en obtenerPorId: " . $e->getMessage());
            return false;
        }
    }
    
    public function listarTodos($filtros = []) {
        try {
            $sql = "SELECT r.*, a.nombre as area_nombre, u.nombre as creado_por_nombre 
                    FROM requerimientos r 
                    JOIN areas a ON r.area_id = a.id 
                    JOIN usuarios u ON r.creado_por = u.id 
                    WHERE 1=1";
            
            $params = [];
            $paramIndex = 1;
            
            // Aplicar filtros si existen
            if (!empty($filtros['estado'])) {
                $sql .= " AND r.estado = ?";
                $params[] = ['index' => $paramIndex++, 'value' => $filtros['estado'], 'type' => PDO::PARAM_STR];
            }
            
            if (!empty($filtros['prioridad'])) {
                $sql .= " AND r.prioridad = ?";
                $params[] = ['index' => $paramIndex++, 'value' => $filtros['prioridad'], 'type' => PDO::PARAM_STR];
            }
            
            if (!empty($filtros['fecha_desde'])) {
                $sql .= " AND r.created_at >= ?";
                $params[] = ['index' => $paramIndex++, 'value' => $filtros['fecha_desde'] . ' 00:00:00', 'type' => PDO::PARAM_STR];
            }
            
            if (!empty($filtros['fecha_hasta'])) {
                $sql .= " AND r.created_at <= ?";
                $params[] = ['index' => $paramIndex++, 'value' => $filtros['fecha_hasta'] . ' 23:59:59', 'type' => PDO::PARAM_STR];
            }
            
            $sql .= " ORDER BY r.created_at DESC";
            
            $stmt = $this->db->query($sql);
            
            // Vincular parámetros
            foreach ($params as $param) {
                $stmt->bindValue($param['index'], $param['value'], $param['type']);
            }
            
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error en listarTodos: " . $e->getMessage());
            return [];
        }
    }
    // Listar requerimientos por área (para usuarios normales)
    public function listarPorArea($area_id, $filtros = []) {
        $sql = "SELECT r.*, a.nombre as area_nombre, u.nombre as creado_por_nombre 
                FROM requerimientos r 
                JOIN areas a ON r.area_id = a.id 
                JOIN usuarios u ON r.creado_por = u.id 
                WHERE r.area_id = :area_id";
        
        $params = [':area_id' => $area_id];
        
        // Aplicar filtros si existen
        if (!empty($filtros['estado'])) {
            $sql .= " AND r.estado = :estado";
            $params[':estado'] = $filtros['estado'];
        }
        
        if (!empty($filtros['prioridad'])) {
            $sql .= " AND r.prioridad = :prioridad";
            $params[':prioridad'] = $filtros['prioridad'];
        }
        
        if (!empty($filtros['fecha_desde'])) {
            $sql .= " AND r.created_at >= :fecha_desde";
            $params[':fecha_desde'] = $filtros['fecha_desde'] . ' 00:00:00';
        }
        
        if (!empty($filtros['fecha_hasta'])) {
            $sql .= " AND r.created_at <= :fecha_hasta";
            $params[':fecha_hasta'] = $filtros['fecha_hasta'] . ' 23:59:59';
        }
        
        $sql .= " ORDER BY r.created_at DESC";
        
        $stmt = $this->db->query($sql);
        return $this->db->resultSet($stmt, $params);
    }
    
    // Actualizar estado del requerimiento
    public function actualizarEstado($id, $estado) {
        $sql = "UPDATE requerimientos SET estado = :estado WHERE id = :id";
        $stmt = $this->db->query($sql);
        $params = [
            ':estado' => $estado,
            ':id' => $id
        ];
        return $this->db->execute($stmt, $params);
    }
    
    // Eliminar requerimiento
    public function eliminar($id) {
        $sql = "DELETE FROM requerimientos WHERE id = :id";
        $stmt = $this->db->query($sql);
        $params = [':id' => $id];
        return $this->db->execute($stmt, $params);
    }
    
    // Obtener estadísticas para dashboard
    public function obtenerEstadisticas($area_id = null) {
        // Si es administrador (area_id = null), ver estadísticas globales
        if ($area_id === null) {
            $sql = "SELECT estado, COUNT(*) as total FROM requerimientos GROUP BY estado";
            $stmt = $this->db->query($sql);
            $resultado = $this->db->resultSet($stmt);
        } else {
            // Para usuarios normales, filtrar por su área
            $sql = "SELECT estado, COUNT(*) as total FROM requerimientos WHERE area_id = :area_id GROUP BY estado";
            $stmt = $this->db->query($sql);
            $params = [':area_id' => $area_id];
            $resultado = $this->db->resultSet($stmt, $params);
        }
        
        // Formatear para uso fácil en gráficos
        $estadisticas = [
            'pendiente' => 0,
            'en proceso' => 0,
            'finalizado' => 0
        ];
        
        foreach ($resultado as $row) {
            $estadisticas[$row['estado']] = (int)$row['total'];
        }
        
        return $estadisticas;
    }
    
    // Obtener requerimientos recientes
    public function obtenerRecientes($area_id = null, $limite = 5) {
        try {
            if ($area_id === null) {
                $sql = "SELECT r.*, a.nombre as area_nombre FROM requerimientos r 
                        JOIN areas a ON r.area_id = a.id 
                        ORDER BY r.created_at DESC LIMIT ?";
                $stmt = $this->db->query($sql);
                $stmt->bindValue(1, $limite, PDO::PARAM_INT);
                return $this->db->resultSet($stmt);
            } else {
                $sql = "SELECT r.*, a.nombre as area_nombre FROM requerimientos r 
                        JOIN areas a ON r.area_id = a.id 
                        WHERE r.area_id = ? 
                        ORDER BY r.created_at DESC LIMIT ?";
                $stmt = $this->db->query($sql);
                $stmt->bindValue(1, $area_id, PDO::PARAM_INT);
                $stmt->bindValue(2, $limite, PDO::PARAM_INT);
                return $this->db->resultSet($stmt);
            }
        } catch (Exception $e) {
            // Registrar el error pero devolver un array vacío para evitar que la aplicación se rompa
            error_log("Error en obtenerRecientes: " . $e->getMessage());
            return [];
        }
    }
}
?>