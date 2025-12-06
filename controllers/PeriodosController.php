<?php

class PeriodosController {
    
    private $conn;
    
    public function __construct() {
        // Solo subdirector y DEP pueden gestionar períodos
        if (!Auth::hasAnyRole([ROLE_SUBDIRECTOR, ROLE_DEP])) {
            header('Location: index.php?c=dashboard&error=access');
            exit;
        }
        
        $db = new Database();
        $this->conn = $db->getConnection();
    }
    
 
    public function index() {
        $sql = "SELECT * FROM periodos_escolares ORDER BY activo DESC, fecha_inicio DESC";
        $stmt = $this->conn->query($sql);
        $periodos = $stmt->fetchAll();
        
        $data = ['periodos' => $periodos];
        $this->loadView('periodos/index', $data);
    }
    
  
    public function crear() {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $this->loadView('periodos/crear');
            return;
        }
        
       
        $datos = [
            'nombre' => $_POST['nombre'] ?? '',
            'fecha_inicio' => $_POST['fecha_inicio'] ?? '',
            'fecha_fin' => $_POST['fecha_fin'] ?? '',
            'activo' => isset($_POST['activo']) ? 1 : 0
        ];
        
     
        if (strtotime($datos['fecha_fin']) <= strtotime($datos['fecha_inicio'])) {
            $_SESSION['error'] = 'La fecha de fin debe ser posterior a la fecha de inicio';
            header('Location: index.php?c=periodos&a=crear');
            exit;
        }
        
        // Si se marca como activo, desactivar los demás
        if ($datos['activo']) {
            $this->conn->exec("UPDATE periodos_escolares SET activo = 0");
        }
        
        $sql = "INSERT INTO periodos_escolares (nombre, fecha_inicio, fecha_fin, activo) 
                VALUES (:nombre, :fecha_inicio, :fecha_fin, :activo)";
        
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($datos);
            
            $id = $this->conn->lastInsertId();
            logAccion(Auth::getCurrentUser(), $_SESSION['rol'], 'CREATE', 'periodos_escolares', $id, 
                     "Período creado: {$datos['nombre']}");
            
            $_SESSION['success'] = 'Período escolar creado exitosamente';
            header('Location: index.php?c=periodos');
        } catch (Exception $e) {
            $_SESSION['error'] = 'Error al crear el período: ' . $e->getMessage();
            header('Location: index.php?c=periodos&a=crear');
        }
        exit;
    }
    
  
    public function editar() {
        $id = $_GET['id'] ?? null;
        
        if (!$id) {
            header('Location: index.php?c=periodos');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $sql = "SELECT * FROM periodos_escolares WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':id' => $id]);
            $periodo = $stmt->fetch();
            
            if (!$periodo) {
                $_SESSION['error'] = 'Período no encontrado';
                header('Location: index.php?c=periodos');
                exit;
            }
            
            $data = ['periodo' => $periodo];
            $this->loadView('periodos/editar', $data);
            return;
        }
        
        
        $datos = [
            'nombre' => $_POST['nombre'] ?? '',
            'fecha_inicio' => $_POST['fecha_inicio'] ?? '',
            'fecha_fin' => $_POST['fecha_fin'] ?? '',
            'activo' => isset($_POST['activo']) ? 1 : 0
        ];
        
       
        if ($datos['activo']) {
            $this->conn->exec("UPDATE periodos_escolares SET activo = 0");
        }
        
        $sql = "UPDATE periodos_escolares SET 
                nombre = :nombre,
                fecha_inicio = :fecha_inicio,
                fecha_fin = :fecha_fin,
                activo = :activo
                WHERE id = :id";
        
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':nombre' => $datos['nombre'],
                ':fecha_inicio' => $datos['fecha_inicio'],
                ':fecha_fin' => $datos['fecha_fin'],
                ':activo' => $datos['activo'],
                ':id' => $id
            ]);
            
            logAccion(Auth::getCurrentUser(), $_SESSION['rol'], 'UPDATE', 'periodos_escolares', $id, 
                     "Período actualizado: {$datos['nombre']}");
            
            $_SESSION['success'] = 'Período escolar actualizado exitosamente';
        } catch (Exception $e) {
            $_SESSION['error'] = 'Error al actualizar el período';
        }
        
        header('Location: index.php?c=periodos');
        exit;
    }
    
  
    public function activar() {
        $id = $_POST['id'] ?? null;
        
        if (!$id) {
            $_SESSION['error'] = 'ID no especificado';
            header('Location: index.php?c=periodos');
            exit;
        }
        
       
        $this->conn->exec("UPDATE periodos_escolares SET activo = 0");
        
       
        $sql = "UPDATE periodos_escolares SET activo = 1 WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        
        logAccion(Auth::getCurrentUser(), $_SESSION['rol'], 'ACTIVATE', 'periodos_escolares', $id, 
                 "Período activado");
        
        $_SESSION['success'] = 'Período escolar activado correctamente';
        header('Location: index.php?c=periodos');
        exit;
    }
  
    public function eliminar() {
        $id = $_POST['id'] ?? null;
        
        if (!$id) {
            $_SESSION['error'] = 'ID no especificado';
            header('Location: index.php?c=periodos');
            exit;
        }
        
       
        $sql = "SELECT COUNT(*) as total FROM horarios WHERE periodo_id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        $result = $stmt->fetch();
        
        if ($result['total'] > 0) {
            $_SESSION['error'] = 'No se puede eliminar el período porque tiene horarios asociados';
            header('Location: index.php?c=periodos');
            exit;
        }
        
        
        $sql = "DELETE FROM periodos_escolares WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        
        if ($stmt->execute([':id' => $id])) {
            logAccion(Auth::getCurrentUser(), $_SESSION['rol'], 'DELETE', 'periodos_escolares', $id, 
                     "Período eliminado");
            $_SESSION['success'] = 'Período eliminado exitosamente';
        } else {
            $_SESSION['error'] = 'Error al eliminar el período';
        }
        
        header('Location: index.php?c=periodos');
        exit;
    }
    
    private function loadView($view, $data = []) {
        extract($data);
        require_once VIEWS_PATH . 'layout/header.php';
        require_once VIEWS_PATH . $view . '.php';
        require_once VIEWS_PATH . 'layout/footer.php';
    }
}

