<?php

class Materia {
    private $conn;
    private $table = 'materias';
    
    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }
    
   
    public function getAll($carrera_id = null, $semestre_id = null) {
        try {
            $sql = "SELECT m.*, c.nombre as carrera_nombre, s.nombre as semestre_nombre 
                    FROM {$this->table} m
                    LEFT JOIN carreras c ON m.carrera_id = c.id
                    LEFT JOIN semestres s ON m.semestre_id = s.id
                    WHERE m.activo = 1";
            
            if ($carrera_id) {
                $sql .= " AND m.carrera_id = :carrera_id";
            }
            
            if ($semestre_id) {
                $sql .= " AND m.semestre_id = :semestre_id";
            }
            
            $sql .= " ORDER BY c.nombre, s.numero, m.nombre";
            
            $stmt = $this->conn->prepare($sql);
            
            if ($carrera_id) {
                $stmt->bindParam(':carrera_id', $carrera_id);
            }
            if ($semestre_id) {
                $stmt->bindParam(':semestre_id', $semestre_id);
            }
            
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Error en Materia::getAll - " . $e->getMessage());
            return [];
        }
    }
    
    public function getById($id) {
        try {
            $sql = "SELECT m.*, c.nombre as carrera_nombre, s.nombre as semestre_nombre 
                    FROM {$this->table} m
                    LEFT JOIN carreras c ON m.carrera_id = c.id
                    LEFT JOIN semestres s ON m.semestre_id = s.id
                    WHERE m.id = :id";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            
            return $stmt->fetch();
        } catch (PDOException $e) {
            error_log("Error en Materia::getById - " . $e->getMessage());
            return null;
        }
    }
    
   
    public function existeClave($clave, $excluir_id = null) {
        try {
            $sql = "SELECT COUNT(*) as total FROM {$this->table} 
                    WHERE clave = :clave 
                    AND activo = 1";
            
            if ($excluir_id) {
                $sql .= " AND id != :excluir_id";
            }
            
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':clave', $clave);
            
            if ($excluir_id) {
                $stmt->bindParam(':excluir_id', $excluir_id);
            }
            
            $stmt->execute();
            $result = $stmt->fetch();
            
            return $result['total'] > 0;
        } catch (PDOException $e) {
            error_log("Error en Materia::existeClave - " . $e->getMessage());
            return true; 
        }
    }
   
    public function carreraExiste($carrera_id) {
        try {
            $sql = "SELECT COUNT(*) as total FROM carreras WHERE id = :id AND activo = 1";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $carrera_id);
            $stmt->execute();
            $result = $stmt->fetch();
            return $result['total'] > 0;
        } catch (PDOException $e) {
            error_log("Error en Materia::carreraExiste - " . $e->getMessage());
            return false;
        }
    }
    
   
    public function semestreExiste($semestre_id) {
        try {
            $sql = "SELECT COUNT(*) as total FROM semestres WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $semestre_id);
            $stmt->execute();
            $result = $stmt->fetch();
            return $result['total'] > 0;
        } catch (PDOException $e) {
            error_log("Error en Materia::semestreExiste - " . $e->getMessage());
            return false;
        }
    }
    
    
    public function create($datos) {
        try {
            
            if (empty($datos['clave']) || empty($datos['nombre'])) {
                return [
                    'success' => false,
                    'message' => 'La clave y nombre son obligatorios'
                ];
            }
            
            if ($datos['creditos'] <= 0 || $datos['creditos'] > 10) {
                return [
                    'success' => false,
                    'message' => 'Los créditos deben estar entre 1 y 10'
                ];
            }
            
            if ($datos['horas_semana'] <= 0 || $datos['horas_semana'] > 20) {
                return [
                    'success' => false,
                    'message' => 'Las horas por semana deben estar entre 1 y 20'
                ];
            }
            
            // Verifica si la carrera existe
            if (!$this->carreraExiste($datos['carrera_id'])) {
                return [
                    'success' => false,
                    'message' => 'La carrera seleccionada no existe o no está activa'
                ];
            }
            
            // Verifica si el semestre existe
            if (!$this->semestreExiste($datos['semestre_id'])) {
                return [
                    'success' => false,
                    'message' => 'El semestre seleccionado no existe'
                ];
            }
            
            // Verifica si ya existe la clave
            if ($this->existeClave($datos['clave'])) {
                return [
                    'success' => false,
                    'message' => 'Ya existe una materia con la clave ' . $datos['clave']
                ];
            }
            
            $sql = "INSERT INTO {$this->table} 
                    (clave, nombre, creditos, horas_semana, semestre_id, carrera_id) 
                    VALUES 
                    (:clave, :nombre, :creditos, :horas_semana, :semestre_id, :carrera_id)";
            
            $stmt = $this->conn->prepare($sql);
            
            $stmt->bindParam(':clave', $datos['clave']);
            $stmt->bindParam(':nombre', $datos['nombre']);
            $stmt->bindParam(':creditos', $datos['creditos']);
            $stmt->bindParam(':horas_semana', $datos['horas_semana']);
            $stmt->bindParam(':semestre_id', $datos['semestre_id']);
            $stmt->bindParam(':carrera_id', $datos['carrera_id']);
            
            if ($stmt->execute()) {
                $id = $this->conn->lastInsertId();
                logAccion(Auth::getCurrentUser(), $_SESSION['rol'], 'CREATE', 'materias', $id, 
                         "Materia creada: {$datos['clave']} - {$datos['nombre']}");
                return ['success' => true, 'id' => $id];
            }
            
            return [
                'success' => false,
                'message' => 'No se pudo crear la materia. Intente nuevamente.'
            ];
            
        } catch (PDOException $e) {
            error_log("Error en Materia::create - " . $e->getMessage());
            
            
            if ($e->getCode() == 23000) { 
                if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
                    if (strpos($e->getMessage(), 'clave') !== false) {
                        return [
                            'success' => false,
                            'message' => 'Ya existe una materia con esa clave'
                        ];
                    }
                    return [
                        'success' => false,
                        'message' => 'Ya existe un registro duplicado'
                    ];
                } elseif (strpos($e->getMessage(), 'foreign key') !== false) {
                    return [
                        'success' => false,
                        'message' => 'Error: La carrera o semestre seleccionado no es válido'
                    ];
                }
            }
            
            return [
                'success' => false,
                'message' => 'Error al crear la materia. Verifique los datos e intente nuevamente.'
            ];
        } catch (Exception $e) {
            error_log("Error general en Materia::create - " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Ocurrió un error inesperado. Contacte al administrador.'
            ];
        }
    }
    
    
    public function update($id, $datos) {
        try {
            
            if (empty($datos['clave']) || empty($datos['nombre'])) {
                return [
                    'success' => false,
                    'message' => 'La clave y nombre son obligatorios'
                ];
            }
            
            if ($datos['creditos'] <= 0 || $datos['creditos'] > 10) {
                return [
                    'success' => false,
                    'message' => 'Los créditos deben estar entre 1 y 10'
                ];
            }
            
            if ($datos['horas_semana'] <= 0 || $datos['horas_semana'] > 20) {
                return [
                    'success' => false,
                    'message' => 'Las horas por semana deben estar entre 1 y 20'
                ];
            }
            
            // Verifica si la materia existe
            $materiaActual = $this->getById($id);
            if (!$materiaActual) {
                return [
                    'success' => false,
                    'message' => 'La materia no existe'
                ];
            }
            
            // Verifica si la carrera existe
            if (!$this->carreraExiste($datos['carrera_id'])) {
                return [
                    'success' => false,
                    'message' => 'La carrera seleccionada no existe o no está activa'
                ];
            }
            
            // Verifica si el semestre existe
            if (!$this->semestreExiste($datos['semestre_id'])) {
                return [
                    'success' => false,
                    'message' => 'El semestre seleccionado no existe'
                ];
            }
            
            // Verifica si ya existe otra materia con la misma clave
            if ($this->existeClave($datos['clave'], $id)) {
                return [
                    'success' => false,
                    'message' => 'Ya existe otra materia con la clave ' . $datos['clave']
                ];
            }
            
            $sql = "UPDATE {$this->table} SET 
                    clave = :clave,
                    nombre = :nombre,
                    creditos = :creditos,
                    horas_semana = :horas_semana,
                    semestre_id = :semestre_id,
                    carrera_id = :carrera_id
                    WHERE id = :id";
            
            $stmt = $this->conn->prepare($sql);
            
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':clave', $datos['clave']);
            $stmt->bindParam(':nombre', $datos['nombre']);
            $stmt->bindParam(':creditos', $datos['creditos']);
            $stmt->bindParam(':horas_semana', $datos['horas_semana']);
            $stmt->bindParam(':semestre_id', $datos['semestre_id']);
            $stmt->bindParam(':carrera_id', $datos['carrera_id']);
            
            if ($stmt->execute()) {
                logAccion(Auth::getCurrentUser(), $_SESSION['rol'], 'UPDATE', 'materias', $id, 
                         "Materia actualizada: {$datos['clave']}");
                return ['success' => true];
            }
            
            return [
                'success' => false,
                'message' => 'No se pudo actualizar la materia'
            ];
            
        } catch (PDOException $e) {
            error_log("Error en Materia::update - " . $e->getMessage());
            
            if ($e->getCode() == 23000) {
                if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
                    return [
                        'success' => false,
                        'message' => 'Ya existe una materia con esa clave'
                    ];
                }
            }
            
            return [
                'success' => false,
                'message' => 'Error al actualizar la materia'
            ];
        } catch (Exception $e) {
            error_log("Error general en Materia::update - " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Ocurrió un error inesperado'
            ];
        }
    }
    
   
    public function delete($id) {
        try {
            // Verifica si la materia existe
            $materia = $this->getById($id);
            if (!$materia) {
                return [
                    'success' => false,
                    'message' => 'La materia no existe'
                ];
            }
            
            // Verifica si la materia tiene grupos asignados
            $sql = "SELECT COUNT(*) as total FROM grupos WHERE materia_id = :materia_id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':materia_id', $id);
            $stmt->execute();
            $result = $stmt->fetch();
            
            if ($result['total'] > 0) {
                return [
                    'success' => false,
                    'message' => 'No se puede eliminar la materia porque tiene grupos asignados. Primero elimine los grupos asociados.'
                ];
            }
            
            
            $sql = "UPDATE {$this->table} SET activo = 0 WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $id);
            
            if ($stmt->execute()) {
                logAccion(Auth::getCurrentUser(), $_SESSION['rol'], 'DELETE', 'materias', $id, 
                         "Materia desactivada");
                return ['success' => true];
            }
            
            return [
                'success' => false,
                'message' => 'No se pudo eliminar la materia'
            ];
            
        } catch (PDOException $e) {
            error_log("Error en Materia::delete - " . $e->getMessage());
            
            if ($e->getCode() == 23000) {
                return [
                    'success' => false,
                    'message' => 'No se puede eliminar la materia porque tiene información relacionada'
                ];
            }
            
            return [
                'success' => false,
                'message' => 'Error al eliminar la materia'
            ];
        } catch (Exception $e) {
            error_log("Error general en Materia::delete - " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Ocurrió un error inesperado'
            ];
        }
    }
    
    
    public function getDiasMaximosPorCreditos($creditos) {
        if ($creditos >= 5) {
            return ['lunes', 'martes', 'miercoles', 'jueves', 'viernes'];
        } elseif ($creditos == 4) {
            return ['lunes', 'martes', 'miercoles', 'jueves'];
        } elseif ($creditos == 3) {
            return ['lunes', 'martes', 'miercoles'];
        } else {
            return ['lunes', 'martes'];
        }
    }
}
?>