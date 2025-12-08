<?php

class DocenteMateria {
    
    private $db;
    private $conn;
    
    public function __construct() {
        $this->db = new Database();
        $this->conn = $this->db->getConnection();
    }
    
    /**
     * Asignar materias a un docente
     */
    public function asignarMaterias($docente_id, $materias_ids) {
        try {
            $this->conn->beginTransaction();
            
            // Eliminar asignaciones previas
            $sql = "DELETE FROM docente_materias WHERE docente_id = :docente_id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':docente_id' => $docente_id]);
            
            // Insertar nuevas asignaciones
            if (!empty($materias_ids)) {
                $sql = "INSERT INTO docente_materias (docente_id, materia_id) VALUES (:docente_id, :materia_id)";
                $stmt = $this->conn->prepare($sql);
                
                foreach ($materias_ids as $materia_id) {
                    $stmt->execute([
                        ':docente_id' => $docente_id,
                        ':materia_id' => $materia_id
                    ]);
                }
            }
            
            $this->conn->commit();
            return ['success' => true, 'message' => 'Materias asignadas correctamente'];
            
        } catch (Exception $e) {
            $this->conn->rollBack();
            error_log("Error asignar materias: " . $e->getMessage());
            return ['success' => false, 'message' => 'Error al asignar materias'];
        }
    }
    
    /**
     * Obtener materias asignadas a un docente
     */
    public function getMateriasPorDocente($docente_id) {
        $sql = "SELECT m.*, c.nombre as carrera_nombre, s.nombre as semestre_nombre
                FROM docente_materias dm
                INNER JOIN materias m ON dm.materia_id = m.id
                INNER JOIN carreras c ON m.carrera_id = c.id
                INNER JOIN semestres s ON m.semestre_id = s.id
                WHERE dm.docente_id = :docente_id AND dm.activo = 1
                ORDER BY c.nombre, s.numero, m.nombre";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':docente_id' => $docente_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Obtener docentes que pueden dar una materia específica
     */
    public function getDocentesPorMateria($materia_id) {
        $sql = "SELECT DISTINCT d.*, dm.id as asignacion_id
                FROM docentes d
                INNER JOIN docente_materias dm ON d.id = dm.docente_id
                WHERE dm.materia_id = :materia_id 
                AND dm.activo = 1 
                AND d.activo = 1
                ORDER BY d.apellido_paterno, d.nombre";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':materia_id' => $materia_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Verificar si un docente puede dar una materia
     */
    public function puedeImpartir($docente_id, $materia_id) {
        $sql = "SELECT COUNT(*) FROM docente_materias 
                WHERE docente_id = :docente_id 
                AND materia_id = :materia_id 
                AND activo = 1";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':docente_id' => $docente_id,
            ':materia_id' => $materia_id
        ]);
        
        return $stmt->fetchColumn() > 0;
    }
    
    /**
     * Obtener todas las materias disponibles para asignar
     */
    public function getMateriasDisponibles($carrera_id = null) {
        $sql = "SELECT m.*, c.nombre as carrera_nombre, s.nombre as semestre_nombre
                FROM materias m
                INNER JOIN carreras c ON m.carrera_id = c.id
                INNER JOIN semestres s ON m.semestre_id = s.id
                WHERE m.activo = 1";
        
        if ($carrera_id) {
            $sql .= " AND m.carrera_id = :carrera_id";
        }
        
        $sql .= " ORDER BY c.nombre, s.numero, m.nombre";
        
        $stmt = $this->conn->prepare($sql);
        
        if ($carrera_id) {
            $stmt->execute([':carrera_id' => $carrera_id]);
        } else {
            $stmt->execute();
        }
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>