<?php

class Grupo {
    private $conn;
    private $table = 'grupos';
    
    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }
    
    public function getAll($periodo_id = null, $carrera_id = null, $semestre_id = null) {
        
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
    
   
    public function getById($id) {
        $sql = "SELECT * FROM {$this->table} WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    
    public function create($datos) {
        
        $estado = ($datos['alumnos_inscritos'] >= $datos['cupo_minimo']) ? 'abierto' : 'proyectado';
        
        
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
        
        
        $aula_id = !empty($datos['aula_id']) ? $datos['aula_id'] : null;
        $stmt->bindParam(':aula_id', $aula_id, PDO::PARAM_INT);
        
        $stmt->bindParam(':estado', $estado);
        
       
        $stmt->execute();
        $id = $this->conn->lastInsertId();
        
        
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

    $stmt->execute();

    if (function_exists('logAccion')) {
        logAccion(Auth::getCurrentUser(), $_SESSION['rol'], 'DELETE', 'grupos', $id, "Grupo eliminado");
    }

    return true;
}

}
?>