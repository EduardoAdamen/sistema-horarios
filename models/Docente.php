<?php

class Docente {
    private $conn;
    private $table = 'docentes';
    
    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }
    
    public function getAll() {
        $sql = "SELECT * FROM {$this->table} WHERE activo = 1 ORDER BY apellido_paterno, nombre";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll();
    }
    
    public function getById($id) {
        $sql = "SELECT * FROM {$this->table} WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch();
    }
    
    public function create($datos) {
        $sql = "INSERT INTO {$this->table} 
                (numero_empleado, nombre, apellido_paterno, apellido_materno, email, telefono, tipo, horas_max_semana) 
                VALUES 
                (:numero_empleado, :nombre, :apellido_paterno, :apellido_materno, :email, :telefono, :tipo, :horas_max_semana)";
        
        $stmt = $this->conn->prepare($sql);
        
        $stmt->bindParam(':numero_empleado', $datos['numero_empleado']);
        $stmt->bindParam(':nombre', $datos['nombre']);
        $stmt->bindParam(':apellido_paterno', $datos['apellido_paterno']);
        $stmt->bindParam(':apellido_materno', $datos['apellido_materno']);
        $stmt->bindParam(':email', $datos['email']);
        $stmt->bindParam(':telefono', $datos['telefono']);
        $stmt->bindParam(':tipo', $datos['tipo']);
        $stmt->bindParam(':horas_max_semana', $datos['horas_max_semana']);
        
        if ($stmt->execute()) {
            $id = $this->conn->lastInsertId();
            logAccion(Auth::getCurrentUser(), $_SESSION['rol'], 'CREATE', 'docentes', $id, 
                     "Docente creado: {$datos['numero_empleado']}");
            return ['success' => true, 'id' => $id];
        }
        
        return ['success' => false];
    }
    
    public function update($id, $datos) {
        $sql = "UPDATE {$this->table} SET 
                numero_empleado = :numero_empleado,
                nombre = :nombre,
                apellido_paterno = :apellido_paterno,
                apellido_materno = :apellido_materno,
                email = :email,
                telefono = :telefono,
                tipo = :tipo,
                horas_max_semana = :horas_max_semana
                WHERE id = :id";
        
        $stmt = $this->conn->prepare($sql);
        
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':numero_empleado', $datos['numero_empleado']);
        $stmt->bindParam(':nombre', $datos['nombre']);
        $stmt->bindParam(':apellido_paterno', $datos['apellido_paterno']);
        $stmt->bindParam(':apellido_materno', $datos['apellido_materno']);
        $stmt->bindParam(':email', $datos['email']);
        $stmt->bindParam(':telefono', $datos['telefono']);
        $stmt->bindParam(':tipo', $datos['tipo']);
        $stmt->bindParam(':horas_max_semana', $datos['horas_max_semana']);
        
        if ($stmt->execute()) {
            logAccion(Auth::getCurrentUser(), $_SESSION['rol'], 'UPDATE', 'docentes', $id);
            return ['success' => true];
        }
        
        return ['success' => false];
    }
    
    public function delete($id) {
        $sql = "UPDATE {$this->table} SET activo = 0 WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        
        if ($stmt->execute()) {
            logAccion(Auth::getCurrentUser(), $_SESSION['rol'], 'DELETE', 'docentes', $id);
            return ['success' => true];
        }
        
        return ['success' => false];
    }
    
    public function getCargaHoraria($docente_id, $periodo_id) {
        $sql = "SELECT SUM(TIMESTAMPDIFF(HOUR, hora_inicio, hora_fin)) as horas_asignadas
                FROM horarios
                WHERE docente_id = :docente_id AND periodo_id = :periodo_id
                AND estado != 'borrador'";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':docente_id' => $docente_id,
            ':periodo_id' => $periodo_id
        ]);
        
        $result = $stmt->fetch();
        return $result['horas_asignadas'] ?? 0;
    }

      
    public function getByNumeroEmpleado($numero_empleado) {
        $sql = "SELECT * FROM {$this->table} WHERE numero_empleado = :num AND activo = 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':num', $numero_empleado);
        $stmt->execute();
        return $stmt->fetch();
    }
}
?>