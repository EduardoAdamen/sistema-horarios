<?php
// =====================================================
// models/Horario.php
// Modelo principal para gestión de horarios
// =====================================================

class Horario {
    private $conn;
    private $table = 'horarios';
    
    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }
    
    /**
     * Obtener horarios con filtros
     */
    public function getHorarios($periodo_id, $carrera_id = null, $semestre_id = null, $estado = null) {
        $sql = "SELECT h.*, 
                m.clave as materia_clave, m.nombre as materia_nombre, m.creditos,
                g.clave as grupo_clave,
                CONCAT(d.nombre, ' ', d.apellido_paterno, ' ', COALESCE(d.apellido_materno, '')) as docente_nombre,
                d.numero_empleado,
                CONCAT(a.edificio, '-', a.numero) as aula,
                c.nombre as carrera,
                s.nombre as semestre
                FROM {$this->table} h
                LEFT JOIN materias m ON h.materia_id = m.id
                LEFT JOIN grupos g ON h.grupo_id = g.id
                LEFT JOIN docentes d ON h.docente_id = d.id
                LEFT JOIN aulas a ON h.aula_id = a.id
                LEFT JOIN carreras c ON m.carrera_id = c.id
                LEFT JOIN semestres s ON m.semestre_id = s.id
                WHERE h.periodo_id = :periodo_id";
        
        if ($carrera_id) {
            $sql .= " AND m.carrera_id = :carrera_id";
        }
        if ($semestre_id) {
            $sql .= " AND m.semestre_id = :semestre_id";
        }
        if ($estado) {
            $sql .= " AND h.estado = :estado";
        }
        
        $sql .= " ORDER BY h.dia, h.hora_inicio";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':periodo_id', $periodo_id);
        
        if ($carrera_id) $stmt->bindParam(':carrera_id', $carrera_id);
        if ($semestre_id) $stmt->bindParam(':semestre_id', $semestre_id);
        if ($estado) $stmt->bindParam(':estado', $estado);
        
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * Obtener horario por ID con toda la información
     * ✅ MÉTODO NUEVO AGREGADO
     */
    public function getById($id) {
        $sql = "SELECT h.*, 
                m.clave as materia_clave, m.nombre as materia_nombre,
                g.clave as grupo_clave,
                CONCAT(d.nombre, ' ', d.apellido_paterno, ' ', COALESCE(d.apellido_materno, '')) as docente_nombre,
                CONCAT(a.edificio, '-', a.numero) as aula,
                DATE_FORMAT(h.hora_inicio, '%H:%i') as hora_inicio,
                DATE_FORMAT(h.hora_fin, '%H:%i') as hora_fin
                FROM {$this->table} h
                LEFT JOIN materias m ON h.materia_id = m.id
                LEFT JOIN grupos g ON h.grupo_id = g.id
                LEFT JOIN docentes d ON h.docente_id = d.id
                LEFT JOIN aulas a ON h.aula_id = a.id
                WHERE h.id = :id";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Obtener horarios de un docente específico
     */
    public function getHorariosByDocente($docente_id, $periodo_id) {
        $sql = "SELECT 
                    h.*,
                    m.clave as materia_clave,
                    m.nombre as materia_nombre,
                    m.id as materia_id,
                    g.clave as grupo_clave,
                    CONCAT(a.edificio, '-', a.numero) as aula,
                    c.nombre as carrera,
                    s.nombre as semestre
                FROM {$this->table} h
                INNER JOIN grupos g ON h.grupo_id = g.id
                INNER JOIN materias m ON g.materia_id = m.id
                INNER JOIN aulas a ON h.aula_id = a.id
                INNER JOIN carreras c ON g.carrera_id = c.id
                INNER JOIN semestres s ON g.semestre_id = s.id
                WHERE h.docente_id = :docente_id 
                  AND g.periodo_id = :periodo_id
                  AND h.estado IN ('conciliado', 'publicado')
                ORDER BY 
                    FIELD(h.dia, 'lunes', 'martes', 'miercoles', 'jueves', 'viernes'),
                    h.hora_inicio";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':docente_id', $docente_id, PDO::PARAM_INT);
        $stmt->bindParam(':periodo_id', $periodo_id, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Crear nuevo bloque de horario
     * ✅ MODIFICADO: Ahora devuelve el horario completo
     */
    public function create($datos) {
        // Validar antes de insertar
        $validacion = $this->validarHorario($datos);
        if (!$validacion['success']) {
            return $validacion;
        }
        
        $sql = "INSERT INTO {$this->table} 
                (grupo_id, materia_id, docente_id, aula_id, periodo_id, dia, hora_inicio, hora_fin, estado, created_by) 
                VALUES 
                (:grupo_id, :materia_id, :docente_id, :aula_id, :periodo_id, :dia, :hora_inicio, :hora_fin, :estado, :created_by)";
        
        $stmt = $this->conn->prepare($sql);
        
        $stmt->bindParam(':grupo_id', $datos['grupo_id']);
        $stmt->bindParam(':materia_id', $datos['materia_id']);
        $stmt->bindParam(':docente_id', $datos['docente_id']);
        $stmt->bindParam(':aula_id', $datos['aula_id']);
        $stmt->bindParam(':periodo_id', $datos['periodo_id']);
        $stmt->bindParam(':dia', $datos['dia']);
        $stmt->bindParam(':hora_inicio', $datos['hora_inicio']);
        $stmt->bindParam(':hora_fin', $datos['hora_fin']);
        
        $estado = $datos['estado'] ?? 'borrador';
        $stmt->bindParam(':estado', $estado);
        
        $usuario = Auth::getCurrentUser();
        $stmt->bindParam(':created_by', $usuario);
        
        if ($stmt->execute()) {
            $id = $this->conn->lastInsertId();
            
            // Log de auditoría
            logAccion($usuario, $_SESSION['rol'], 'CREATE', 'horarios', $id, 
                     "Horario creado para {$datos['dia']} {$datos['hora_inicio']}");
            
            // ✅ CAMBIO IMPORTANTE: Obtener y devolver información completa del horario
            $horario_completo = $this->getById($id);
            
            return [
                'success' => true, 
                'id' => $id,
                'horario' => $horario_completo // ← Información completa para actualizar el DOM
            ];
        }
        
        return ['success' => false, 'message' => 'Error al guardar el horario'];
    }
    
    /**
     * Eliminar horario
     * ✅ MÉTODO NUEVO AGREGADO
     */
    public function delete($id) {
        try {
            // Verificar que el horario existe
            $sql = "SELECT id, estado FROM {$this->table} WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $horario = $stmt->fetch();
            
            if (!$horario) {
                return ['success' => false, 'message' => 'Horario no encontrado'];
            }
            
            // Eliminar el horario
            $sql = "DELETE FROM {$this->table} WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            
            if ($stmt->execute()) {
                // Log de auditoría
                $usuario = Auth::getCurrentUser();
                logAccion($usuario, $_SESSION['rol'], 'DELETE', 'horarios', $id, 
                         "Horario eliminado (estado anterior: {$horario['estado']})");
                
                return ['success' => true, 'message' => 'Horario eliminado correctamente'];
            }
            
            return ['success' => false, 'message' => 'Error al eliminar el horario'];
            
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Error: ' . $e->getMessage()];
        }
    }
    
    /**
     * Actualizar horario existente
     * ✅ MÉTODO NUEVO AGREGADO (opcional, para futuras ediciones)
     */
    public function update($id, $datos) {
        // Validar antes de actualizar
        $validacion = $this->validarHorario($datos, $id);
        if (!$validacion['success']) {
            return $validacion;
        }
        
        $sql = "UPDATE {$this->table} 
                SET docente_id = :docente_id,
                    aula_id = :aula_id,
                    dia = :dia,
                    hora_inicio = :hora_inicio,
                    hora_fin = :hora_fin,
                    updated_by = :updated_by
                WHERE id = :id";
        
        $stmt = $this->conn->prepare($sql);
        
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':docente_id', $datos['docente_id']);
        $stmt->bindParam(':aula_id', $datos['aula_id']);
        $stmt->bindParam(':dia', $datos['dia']);
        $stmt->bindParam(':hora_inicio', $datos['hora_inicio']);
        $stmt->bindParam(':hora_fin', $datos['hora_fin']);
        
        $usuario = Auth::getCurrentUser();
        $stmt->bindParam(':updated_by', $usuario);
        
        if ($stmt->execute()) {
            logAccion($usuario, $_SESSION['rol'], 'UPDATE', 'horarios', $id, 
                     "Horario actualizado");
            
            $horario_completo = $this->getById($id);
            
            return [
                'success' => true, 
                'id' => $id,
                'horario' => $horario_completo
            ];
        }
        
        return ['success' => false, 'message' => 'Error al actualizar el horario'];
    }
    
    /**
     * Validar horario antes de guardar
     */
    private function validarHorario($datos, $id_excluir = null) {
        // 1. Validar que la hora de fin sea mayor a la hora de inicio
        if (strtotime($datos['hora_inicio']) >= strtotime($datos['hora_fin'])) {
            return ['success' => false, 'message' => 'La hora de fin debe ser mayor a la hora de inicio'];
        }
        
        // 2. Validar solapamiento de docente
        if ($datos['docente_id']) {
            if ($this->verificarSolapamientoDocente($datos, $id_excluir)) {
                return ['success' => false, 'message' => 'El docente ya tiene asignación en este horario'];
            }
        }
        
        // 3. Validar solapamiento de aula
        if ($datos['aula_id']) {
            if ($this->verificarSolapamientoAula($datos, $id_excluir)) {
                return ['success' => false, 'message' => 'El aula ya está ocupada en este horario'];
            }
        }
        
        // 4. Validar créditos vs días (si existe la clase Materia)
        if (class_exists('Materia')) {
            $materia = new Materia();
            $info_materia = $materia->getById($datos['materia_id']);
            
            if ($info_materia && method_exists($materia, 'getDiasMaximosPorCreditos')) {
                $dias_permitidos = $materia->getDiasMaximosPorCreditos($info_materia['creditos']);
                
                if (!in_array($datos['dia'], $dias_permitidos)) {
                    return ['success' => false, 'message' => 'Esta materia no puede asignarse en ' . ucfirst($datos['dia']) . ' según sus créditos'];
                }
            }
        }
        
        return ['success' => true];
    }
    
    /**
     * Verificar solapamiento de docente
     * ✅ CORREGIDO: Condición de solapamiento mejorada
     */
    private function verificarSolapamientoDocente($datos, $id_excluir = null) {
        $sql = "SELECT COUNT(*) as total FROM {$this->table} 
                WHERE docente_id = :docente_id 
                AND periodo_id = :periodo_id
                AND dia = :dia
                AND (
                    (:hora_inicio < hora_fin AND :hora_fin > hora_inicio)
                )";
        
        if ($id_excluir) {
            $sql .= " AND id != :id_excluir";
        }
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':docente_id', $datos['docente_id']);
        $stmt->bindParam(':periodo_id', $datos['periodo_id']);
        $stmt->bindParam(':dia', $datos['dia']);
        $stmt->bindParam(':hora_inicio', $datos['hora_inicio']);
        $stmt->bindParam(':hora_fin', $datos['hora_fin']);
        
        if ($id_excluir) {
            $stmt->bindParam(':id_excluir', $id_excluir);
        }
        
        $stmt->execute();
        $result = $stmt->fetch();
        
        return $result['total'] > 0;
    }
    
    /**
     * Verificar solapamiento de aula
     * ✅ CORREGIDO: Condición de solapamiento mejorada
     */
    private function verificarSolapamientoAula($datos, $id_excluir = null) {
        $sql = "SELECT COUNT(*) as total FROM {$this->table} 
                WHERE aula_id = :aula_id 
                AND periodo_id = :periodo_id
                AND dia = :dia
                AND (
                    (:hora_inicio < hora_fin AND :hora_fin > hora_inicio)
                )";
        
        if ($id_excluir) {
            $sql .= " AND id != :id_excluir";
        }
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':aula_id', $datos['aula_id']);
        $stmt->bindParam(':periodo_id', $datos['periodo_id']);
        $stmt->bindParam(':dia', $datos['dia']);
        $stmt->bindParam(':hora_inicio', $datos['hora_inicio']);
        $stmt->bindParam(':hora_fin', $datos['hora_fin']);
        
        if ($id_excluir) {
            $stmt->bindParam(':id_excluir', $id_excluir);
        }
        
        $stmt->execute();
        $result = $stmt->fetch();
        
        return $result['total'] > 0;
    }
    
    /**
     * Marcar horarios como conciliados y sincronizar con Firebase
     */
    public function marcarComoConciliado($periodo_id, $carrera_id, $semestre_id) {
        try {
            $this->conn->beginTransaction();
            
            // Actualizar estado de horarios
            $sql = "UPDATE {$this->table} h
                    INNER JOIN materias m ON h.materia_id = m.id
                    SET h.estado = 'conciliado', h.updated_by = :usuario
                    WHERE h.periodo_id = :periodo_id
                    AND m.carrera_id = :carrera_id
                    AND m.semestre_id = :semestre_id
                    AND h.estado = 'borrador'";
            
            $stmt = $this->conn->prepare($sql);
            $usuario = Auth::getCurrentUser();
            $stmt->bindParam(':usuario', $usuario);
            $stmt->bindParam(':periodo_id', $periodo_id);
            $stmt->bindParam(':carrera_id', $carrera_id);
            $stmt->bindParam(':semestre_id', $semestre_id);
            $stmt->execute();
            
            $affected_rows = $stmt->rowCount();
            
            // Sincronizar con Firebase (si el archivo existe)
            if (file_exists(INCLUDES_PATH . 'firebase-sync.php')) {
                require_once INCLUDES_PATH . 'firebase-sync.php';
                $sync_result = sincronizarHorariosFirebase($periodo_id, $carrera_id, $semestre_id);
                
                if ($sync_result['success']) {
                    $this->conn->commit();
                    logAccion($usuario, $_SESSION['rol'], 'CONCILIAR_HORARIOS', 'horarios', null, 
                             "Horarios conciliados y sincronizados para periodo $periodo_id, carrera $carrera_id, semestre $semestre_id ($affected_rows registros)");
                    return ['success' => true, 'message' => "Horarios conciliados y sincronizados con Firebase ($affected_rows registros)"];
                } else {
                    $this->conn->rollback();
                    return ['success' => false, 'message' => 'Error al sincronizar con Firebase: ' . $sync_result['message']];
                }
            } else {
                // Si no existe Firebase, solo marcar como conciliado
                $this->conn->commit();
                logAccion($usuario, $_SESSION['rol'], 'CONCILIAR_HORARIOS', 'horarios', null, 
                         "Horarios conciliados para periodo $periodo_id, carrera $carrera_id, semestre $semestre_id ($affected_rows registros)");
                return ['success' => true, 'message' => "Horarios conciliados exitosamente ($affected_rows registros)"];
            }
            
        } catch (Exception $e) {
            $this->conn->rollback();
            return ['success' => false, 'message' => 'Error: ' . $e->getMessage()];
        }
    }
    
    /**
     * Obtener estadísticas de horarios por periodo
     * ✅ MÉTODO ADICIONAL ÚTIL
     */
    public function getEstadisticas($periodo_id) {
        $sql = "SELECT 
                    COUNT(*) as total_horarios,
                    COUNT(CASE WHEN estado = 'borrador' THEN 1 END) as borradores,
                    COUNT(CASE WHEN estado = 'conciliado' THEN 1 END) as conciliados,
                    COUNT(CASE WHEN estado = 'publicado' THEN 1 END) as publicados,
                    COUNT(DISTINCT docente_id) as docentes_asignados,
                    COUNT(DISTINCT aula_id) as aulas_utilizadas
                FROM {$this->table}
                WHERE periodo_id = :periodo_id";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':periodo_id', $periodo_id);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Verificar disponibilidad de un docente en un día/hora específicos
     * ✅ MÉTODO ADICIONAL ÚTIL
     */
    public function verificarDisponibilidadDocente($docente_id, $periodo_id, $dia, $hora_inicio, $hora_fin, $excluir_id = null) {
        $sql = "SELECT h.*, 
                m.nombre as materia_nombre,
                g.clave as grupo_clave
                FROM {$this->table} h
                LEFT JOIN materias m ON h.materia_id = m.id
                LEFT JOIN grupos g ON h.grupo_id = g.id
                WHERE h.docente_id = :docente_id 
                AND h.periodo_id = :periodo_id
                AND h.dia = :dia
                AND (
                    (:hora_inicio < h.hora_fin AND :hora_fin > h.hora_inicio)
                )";
        
        if ($excluir_id) {
            $sql .= " AND h.id != :excluir_id";
        }
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':docente_id', $docente_id);
        $stmt->bindParam(':periodo_id', $periodo_id);
        $stmt->bindParam(':dia', $dia);
        $stmt->bindParam(':hora_inicio', $hora_inicio);
        $stmt->bindParam(':hora_fin', $hora_fin);
        
        if ($excluir_id) {
            $stmt->bindParam(':excluir_id', $excluir_id);
        }
        
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>