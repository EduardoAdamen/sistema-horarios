<?php
// =====================================================
// models/Grupo.php
// ✅ VERSIÓN CORREGIDA - Permite que las excepciones suban al controlador
// =====================================================
class Grupo {
    private $conn;
    private $table = 'grupos';
    
    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }
    
    public function getAll($periodo_id = null, $carrera_id = null, $semestre_id = null) {
        // Se agrega el JOIN a aulas para saber qué aula tiene el grupo
        $sql = "SELECT g.*, 
                m.nombre as materia_nombre,
                m.clave as materia_clave,
                c.nombre as carrera_nombre,
                s.nombre as semestre_nombre,
                p.nombre as periodo_nombre,
                CONCAT(a.edificio, '-', a.numero) as aula_asignada
                FROM {$this->table} g
                LEFT JOIN materias m ON g.materia_id = m.id
                LEFT JOIN carreras c ON g.carrera_id = c.id
                LEFT JOIN semestres s ON g.semestre_id = s.id
                LEFT JOIN periodos_escolares p ON g.periodo_id = p.id
                LEFT JOIN aulas a ON g.aula_id = a.id
                WHERE 1=1";
        
        if ($periodo_id) {
            $sql .= " AND g.periodo_id = :periodo_id";
        }
        if ($carrera_id) {
            $sql .= " AND g.carrera_id = :carrera_id";
        }
        if ($semestre_id) {
            $sql .= " AND g.semestre_id = :semestre_id";
        }
        
        $sql .= " ORDER BY c.nombre, s.numero, m.nombre";
        
        $stmt = $this->conn->prepare($sql);
        
        $params = [];
        if ($periodo_id) $params[':periodo_id'] = $periodo_id;
        if ($carrera_id) $params[':carrera_id'] = $carrera_id;
        if ($semestre_id) $params[':semestre_id'] = $semestre_id;
        
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
    
    // Método auxiliar para obtener un solo grupo (Útil para el controlador de Horarios)
    public function getById($id) {
        $sql = "SELECT * FROM {$this->table} WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * ✅ MÉTODO CORREGIDO: Ahora permite que las excepciones PDO suban al controlador
     * Esto permite detectar errores específicos como claves duplicadas (error 1062)
     */
    public function create($datos) {
        // Determinar estado según cupo mínimo
        $estado = ($datos['alumnos_inscritos'] >= $datos['cupo_minimo']) ? 'abierto' : 'proyectado';
        
        // MODIFICADO: Se agrega aula_id
        $sql = "INSERT INTO {$this->table} 
                (clave, materia_id, periodo_id, carrera_id, semestre_id, cupo_minimo, cupo_maximo, alumnos_inscritos, aula_id, estado) 
                VALUES 
                (:clave, :materia_id, :periodo_id, :carrera_id, :semestre_id, :cupo_minimo, :cupo_maximo, :alumnos_inscritos, :aula_id, :estado)";
        
        $stmt = $this->conn->prepare($sql);
        
        $stmt->bindParam(':clave', $datos['clave']);
        $stmt->bindParam(':materia_id', $datos['materia_id'], PDO::PARAM_INT);
        $stmt->bindParam(':periodo_id', $datos['periodo_id'], PDO::PARAM_INT);
        $stmt->bindParam(':carrera_id', $datos['carrera_id'], PDO::PARAM_INT);
        $stmt->bindParam(':semestre_id', $datos['semestre_id'], PDO::PARAM_INT);
        $stmt->bindParam(':cupo_minimo', $datos['cupo_minimo'], PDO::PARAM_INT);
        $stmt->bindParam(':cupo_maximo', $datos['cupo_maximo'], PDO::PARAM_INT);
        $stmt->bindParam(':alumnos_inscritos', $datos['alumnos_inscritos'], PDO::PARAM_INT);
        
        // Si no se asigna aula, se guarda como NULL
        $aula_id = !empty($datos['aula_id']) ? $datos['aula_id'] : null;
        $stmt->bindParam(':aula_id', $aula_id, PDO::PARAM_INT);
        
        $stmt->bindParam(':estado', $estado);
        
        // ✅ CAMBIO CRÍTICO: Ya NO capturamos la excepción aquí
        // La dejamos subir al controlador para manejo específico de errores
        $stmt->execute();
        $id = $this->conn->lastInsertId();
        
        // Log de acción (solo si se ejecutó correctamente)
        if (function_exists('logAccion')) {
            logAccion(Auth::getCurrentUser(), $_SESSION['rol'], 'CREATE', 'grupos', $id, 
                     "Grupo creado: {$datos['clave']} - Estado: $estado");
        }
        
        return ['success' => true, 'id' => $id, 'estado' => $estado];
    }
    
    public function validarCupoMinimo($grupo_id) {
        $grupo = $this->getById($grupo_id);
        
        if (!$grupo) {
            return false;
        }
        
        return $grupo['alumnos_inscritos'] >= $grupo['cupo_minimo'];
    }

    public function delete($id) {
    $sql = "DELETE FROM {$this->table} WHERE id = :id";
    $stmt = $this->conn->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);

    // Dejamos que la excepción suba al controlador (igual que en create)
    $stmt->execute();

    // Registrar acción (si la función existe)
    if (function_exists('logAccion')) {
        logAccion(Auth::getCurrentUser(), $_SESSION['rol'], 'DELETE', 'grupos', $id, "Grupo eliminado");
    }

    return true;
}

}
?>