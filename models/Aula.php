<?php
// =====================================================
// models/Aula.php - CON MANEJO DE EXCEPCIONES
// =====================================================
class Aula {
    private $conn;
    private $table = 'aulas';
    
    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }
    
    public function getAll($tipo = null) {
        try {
            $sql = "SELECT * FROM {$this->table} WHERE activo = 1";
            
            if ($tipo) {
                $sql .= " AND tipo = :tipo";
            }
            
            $sql .= " ORDER BY edificio, numero";
            
            $stmt = $this->conn->prepare($sql);
            
            if ($tipo) {
                $stmt->bindParam(':tipo', $tipo);
            }
            
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Error en Aula::getAll - " . $e->getMessage());
            return [];
        }
    }
    
    public function getById($id) {
        try {
            $sql = "SELECT * FROM {$this->table} WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return $stmt->fetch();
        } catch (PDOException $e) {
            error_log("Error en Aula::getById - " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Verifica si ya existe un aula con el mismo edificio y número
     */
    public function existeAula($edificio, $numero, $excluir_id = null) {
        try {
            $sql = "SELECT COUNT(*) as total FROM {$this->table} 
                    WHERE edificio = :edificio 
                    AND numero = :numero 
                    AND activo = 1";
            
            if ($excluir_id) {
                $sql .= " AND id != :excluir_id";
            }
            
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':edificio', $edificio);
            $stmt->bindParam(':numero', $numero);
            
            if ($excluir_id) {
                $stmt->bindParam(':excluir_id', $excluir_id);
            }
            
            $stmt->execute();
            $result = $stmt->fetch();
            
            return $result['total'] > 0;
        } catch (PDOException $e) {
            error_log("Error en Aula::existeAula - " . $e->getMessage());
            return true; // Por seguridad, asumimos que existe
        }
    }
    
    public function create($datos) {
        try {
            // Validaciones básicas
            if (empty($datos['edificio']) || empty($datos['numero'])) {
                return [
                    'success' => false, 
                    'message' => 'El edificio y número son obligatorios'
                ];
            }
            
            if ($datos['capacidad'] <= 0) {
                return [
                    'success' => false, 
                    'message' => 'La capacidad debe ser mayor a 0'
                ];
            }
            
            // Verificar si ya existe el aula
            if ($this->existeAula($datos['edificio'], $datos['numero'])) {
                return [
                    'success' => false,
                    'message' => 'Ya existe un aula con el código ' . $datos['edificio'] . '-' . $datos['numero']
                ];
            }
            
            $sql = "INSERT INTO {$this->table} 
                    (edificio, numero, capacidad, tipo, equipamiento) 
                    VALUES 
                    (:edificio, :numero, :capacidad, :tipo, :equipamiento)";
            
            $stmt = $this->conn->prepare($sql);
            
            $stmt->bindParam(':edificio', $datos['edificio']);
            $stmt->bindParam(':numero', $datos['numero']);
            $stmt->bindParam(':capacidad', $datos['capacidad']);
            $stmt->bindParam(':tipo', $datos['tipo']);
            $stmt->bindParam(':equipamiento', $datos['equipamiento']);
            
            if ($stmt->execute()) {
                $id = $this->conn->lastInsertId();
                logAccion(Auth::getCurrentUser(), $_SESSION['rol'], 'CREATE', 'aulas', $id, 
                         "Aula creada: {$datos['edificio']}-{$datos['numero']}");
                return ['success' => true, 'id' => $id];
            }
            
            return [
                'success' => false,
                'message' => 'No se pudo crear el aula. Intente nuevamente.'
            ];
            
        } catch (PDOException $e) {
            error_log("Error en Aula::create - " . $e->getMessage());
            
            // Verificar código de error específico
            if ($e->getCode() == 23000) { // Violación de integridad
                if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
                    return [
                        'success' => false,
                        'message' => 'Ya existe un aula con ese código. Por favor, use un código diferente.'
                    ];
                } elseif (strpos($e->getMessage(), 'foreign key') !== false) {
                    return [
                        'success' => false,
                        'message' => 'Error de integridad referencial. Verifique los datos.'
                    ];
                }
            }
            
            return [
                'success' => false,
                'message' => 'Error al crear el aula: ' . $e->getMessage()
            ];
        } catch (Exception $e) {
            error_log("Error general en Aula::create - " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Ocurrió un error inesperado. Contacte al administrador.'
            ];
        }
    }
    
    public function update($id, $datos) {
        try {
            // Validaciones básicas
            if (empty($datos['edificio']) || empty($datos['numero'])) {
                return [
                    'success' => false, 
                    'message' => 'El edificio y número son obligatorios'
                ];
            }
            
            if ($datos['capacidad'] <= 0) {
                return [
                    'success' => false, 
                    'message' => 'La capacidad debe ser mayor a 0'
                ];
            }
            
            // Verificar si el aula existe
            $aulaActual = $this->getById($id);
            if (!$aulaActual) {
                return [
                    'success' => false,
                    'message' => 'El aula no existe'
                ];
            }
            
            // Verificar si ya existe otro aula con el mismo código
            if ($this->existeAula($datos['edificio'], $datos['numero'], $id)) {
                return [
                    'success' => false,
                    'message' => 'Ya existe otra aula con el código ' . $datos['edificio'] . '-' . $datos['numero']
                ];
            }
            
            $sql = "UPDATE {$this->table} SET 
                    edificio = :edificio,
                    numero = :numero,
                    capacidad = :capacidad,
                    tipo = :tipo,
                    equipamiento = :equipamiento
                    WHERE id = :id";
            
            $stmt = $this->conn->prepare($sql);
            
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':edificio', $datos['edificio']);
            $stmt->bindParam(':numero', $datos['numero']);
            $stmt->bindParam(':capacidad', $datos['capacidad']);
            $stmt->bindParam(':tipo', $datos['tipo']);
            $stmt->bindParam(':equipamiento', $datos['equipamiento']);
            
            if ($stmt->execute()) {
                logAccion(Auth::getCurrentUser(), $_SESSION['rol'], 'UPDATE', 'aulas', $id);
                return ['success' => true];
            }
            
            return [
                'success' => false,
                'message' => 'No se pudo actualizar el aula'
            ];
            
        } catch (PDOException $e) {
            error_log("Error en Aula::update - " . $e->getMessage());
            
            if ($e->getCode() == 23000) {
                if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
                    return [
                        'success' => false,
                        'message' => 'Ya existe un aula con ese código'
                    ];
                }
            }
            
            return [
                'success' => false,
                'message' => 'Error al actualizar el aula'
            ];
        } catch (Exception $e) {
            error_log("Error general en Aula::update - " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Ocurrió un error inesperado'
            ];
        }
    }
    
    public function delete($id) {
        try {
            // Verificar si el aula existe
            $aula = $this->getById($id);
            if (!$aula) {
                return [
                    'success' => false,
                    'message' => 'El aula no existe'
                ];
            }
            
            // Verificar si el aula tiene horarios asignados
            $sql = "SELECT COUNT(*) as total FROM horarios 
                    WHERE aula_id = :aula_id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':aula_id', $id);
            $stmt->execute();
            $result = $stmt->fetch();
            
            if ($result['total'] > 0) {
                return [
                    'success' => false,
                    'message' => 'No se puede eliminar el aula porque tiene horarios asignados. Primero elimine los horarios asociados.'
                ];
            }
            
            // Soft delete
            $sql = "UPDATE {$this->table} SET activo = 0 WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $id);
            
            if ($stmt->execute()) {
                logAccion(Auth::getCurrentUser(), $_SESSION['rol'], 'DELETE', 'aulas', $id);
                return ['success' => true];
            }
            
            return [
                'success' => false,
                'message' => 'No se pudo eliminar el aula'
            ];
            
        } catch (PDOException $e) {
            error_log("Error en Aula::delete - " . $e->getMessage());
            
            if ($e->getCode() == 23000) {
                return [
                    'success' => false,
                    'message' => 'No se puede eliminar el aula porque tiene información relacionada'
                ];
            }
            
            return [
                'success' => false,
                'message' => 'Error al eliminar el aula'
            ];
        } catch (Exception $e) {
            error_log("Error general en Aula::delete - " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Ocurrió un error inesperado'
            ];
        }
    }
    
    public function verificarDisponibilidad($aula_id, $dia, $hora_inicio, $hora_fin, $periodo_id, $excluir_horario_id = null) {
        try {
            $sql = "SELECT COUNT(*) as total FROM horarios 
                    WHERE aula_id = :aula_id 
                    AND periodo_id = :periodo_id
                    AND dia = :dia
                    AND (hora_inicio < :hora_fin AND hora_fin > :hora_inicio)";
            
            if ($excluir_horario_id) {
                $sql .= " AND id != :excluir_id";
            }
            
            $stmt = $this->conn->prepare($sql);
            $params = [
                ':aula_id' => $aula_id,
                ':periodo_id' => $periodo_id,
                ':dia' => $dia,
                ':hora_inicio' => $hora_inicio,
                ':hora_fin' => $hora_fin
            ];
            
            if ($excluir_horario_id) {
                $params[':excluir_id'] = $excluir_horario_id;
            }
            
            $stmt->execute($params);
            $result = $stmt->fetch();
            
            return $result['total'] == 0;
        } catch (PDOException $e) {
            error_log("Error en Aula::verificarDisponibilidad - " . $e->getMessage());
            return false; // Por seguridad, asumimos que NO está disponible
        }
    }
}
?>