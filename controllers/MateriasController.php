<?php

require_once MODELS_PATH . 'Materia.php';

class MateriasController {
    
    private $materia_model;
    
    public function __construct() {
      
        if (!Auth::isLoggedIn()) {
            header('Location: login.php');
            exit;
        }
        
        $this->materia_model = new Materia();
    }
    
    public function index() {
        // Solo Subdirector y Jefe pueden gestionar (crear/editar) el catálogo
        if (!Auth::hasAnyRole([ROLE_SUBDIRECTOR, ROLE_JEFE_DEPTO])) {
            header('Location: index.php?c=dashboard&error=access');
            exit;
        }
        
        try {
            $carrera_id = $_GET['carrera'] ?? null;
            $semestre_id = $_GET['semestre'] ?? null;
            
            $materias = $this->materia_model->getAll($carrera_id, $semestre_id);
            
            $db = new Database();
            $conn = $db->getConnection();
            
            $carreras = $conn->query("SELECT * FROM carreras WHERE activo = 1 ORDER BY nombre")->fetchAll();
            $semestres = $conn->query("SELECT * FROM semestres ORDER BY numero")->fetchAll();
            
            $data = [
                'materias' => $materias,
                'carreras' => $carreras,
                'semestres' => $semestres,
                'carrera_id_filtro' => $carrera_id,
                'semestre_id_filtro' => $semestre_id
            ];
            
            $this->loadView('materias/index', $data);
        } catch (Exception $e) {
            error_log("Error en MateriasController::index - " . $e->getMessage());
            $_SESSION['error'] = 'Error al cargar las materias';
            header('Location: index.php?c=dashboard');
            exit;
        }
    }
    
    public function crear() {
        if (!Auth::hasAnyRole([ROLE_SUBDIRECTOR, ROLE_JEFE_DEPTO])) {
            header('Location: index.php?c=dashboard&error=access'); 
            exit;
        }

      
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            try {
                $db = new Database();
                $conn = $db->getConnection();
                
                $carreras = $conn->query("SELECT * FROM carreras WHERE activo = 1 ORDER BY nombre")->fetchAll();
                $semestres = $conn->query("SELECT * FROM semestres ORDER BY numero")->fetchAll();
                
                $data = [
                    'carreras' => $carreras, 
                    'semestres' => $semestres
                ];
                
                $this->loadView('materias/crear', $data);
            } catch (Exception $e) {
                error_log("Error en MateriasController::crear GET - " . $e->getMessage());
                $_SESSION['error'] = 'Error al cargar el formulario';
                header('Location: index.php?c=materias');
                exit;
            }
            return;
        }
        
        try {
            
            $errores = $this->validarDatosMateria($_POST);
            
            if (!empty($errores)) {
                $_SESSION['error'] = implode('<br>', $errores);
                $_SESSION['form_data'] = $_POST;
                header('Location: index.php?c=materias&a=crear');
                exit;
            }
            
            $datos = [
                'clave' => strtoupper(trim($_POST['clave'] ?? '')),
                'nombre' => trim($_POST['nombre'] ?? ''),
                'creditos' => (int)($_POST['creditos'] ?? 0),
                'horas_semana' => (int)($_POST['horas_semana'] ?? 0),
                'semestre_id' => (int)($_POST['semestre_id'] ?? 0),
                'carrera_id' => (int)($_POST['carrera_id'] ?? 0)
            ];
            
            $result = $this->materia_model->create($datos);
            
            if ($result['success']) {
                $_SESSION['success'] = 'Materia creada exitosamente: ' . $datos['clave'] . ' - ' . $datos['nombre'];
                unset($_SESSION['form_data']);
                header('Location: index.php?c=materias');
            } else {
                $_SESSION['error'] = $result['message'] ?? 'Error al crear la materia';
                $_SESSION['form_data'] = $_POST;
                header('Location: index.php?c=materias&a=crear');
            }
        } catch (Exception $e) {
            error_log("Error en MateriasController::crear - " . $e->getMessage());
            $_SESSION['error'] = 'Ocurrió un error inesperado al crear la materia. Por favor, intente nuevamente.';
            $_SESSION['form_data'] = $_POST;
            header('Location: index.php?c=materias&a=crear');
        }
        exit;
    }
    
    public function editar() {
        if (!Auth::hasAnyRole([ROLE_SUBDIRECTOR, ROLE_JEFE_DEPTO])) {
            header('Location: index.php?c=dashboard&error=access'); 
            exit;
        }

        $id = $_GET['id'] ?? null;
        
        if (!$id || !is_numeric($id)) {
            $_SESSION['error'] = 'ID de materia no válido';
            header('Location: index.php?c=materias');
            exit;
        }
        
        try {
            
            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                $materia = $this->materia_model->getById($id);
                
                if (!$materia) {
                    $_SESSION['error'] = 'Materia no encontrada';
                    header('Location: index.php?c=materias');
                    exit;
                }
                
                $db = new Database();
                $conn = $db->getConnection();
                
                $carreras = $conn->query("SELECT * FROM carreras WHERE activo = 1 ORDER BY nombre")->fetchAll();
                $semestres = $conn->query("SELECT * FROM semestres ORDER BY numero")->fetchAll();
                
                $data = [
                    'materia' => $materia,
                    'carreras' => $carreras,
                    'semestres' => $semestres
                ];
                
                $this->loadView('materias/editar', $data);
                return;
            }
            
            $errores = $this->validarDatosMateria($_POST);
            
            if (!empty($errores)) {
                $_SESSION['error'] = implode('<br>', $errores);
                $_SESSION['form_data'] = $_POST;
                header('Location: index.php?c=materias&a=editar&id=' . $id);
                exit;
            }
            
            $datos = [
                'clave' => strtoupper(trim($_POST['clave'] ?? '')),
                'nombre' => trim($_POST['nombre'] ?? ''),
                'creditos' => (int)($_POST['creditos'] ?? 0),
                'horas_semana' => (int)($_POST['horas_semana'] ?? 0),
                'semestre_id' => (int)($_POST['semestre_id'] ?? 0),
                'carrera_id' => (int)($_POST['carrera_id'] ?? 0)
            ];
            
            $result = $this->materia_model->update($id, $datos);
            
            if ($result['success']) {
                $_SESSION['success'] = 'Materia actualizada exitosamente';
                unset($_SESSION['form_data']);
                header('Location: index.php?c=materias');
            } else {
                $_SESSION['error'] = $result['message'] ?? 'Error al actualizar la materia';
                $_SESSION['form_data'] = $_POST;
                header('Location: index.php?c=materias&a=editar&id=' . $id);
            }
        } catch (Exception $e) {
            error_log("Error en MateriasController::editar - " . $e->getMessage());
            $_SESSION['error'] = 'Ocurrió un error inesperado al actualizar la materia';
            $_SESSION['form_data'] = $_POST;
            header('Location: index.php?c=materias&a=editar&id=' . $id);
        }
        exit;
    }
    
    public function eliminar() {
        if (!Auth::hasAnyRole([ROLE_SUBDIRECTOR, ROLE_JEFE_DEPTO])) {
            header('Location: index.php?c=dashboard&error=access'); 
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?c=materias');
            exit;
        }
        
        $id = $_POST['id'] ?? null;
        
        if (!$id || !is_numeric($id)) {
            $_SESSION['error'] = 'ID de materia no válido';
            header('Location: index.php?c=materias');
            exit;
        }
        
        try {
            $result = $this->materia_model->delete($id);
            
            if ($result['success']) {
                $_SESSION['success'] = 'Materia eliminada exitosamente';
            } else {
                $_SESSION['error'] = $result['message'] ?? 'Error al eliminar la materia';
            }
        } catch (Exception $e) {
            error_log("Error en MateriasController::eliminar - " . $e->getMessage());
            $_SESSION['error'] = 'Ocurrió un error inesperado al eliminar la materia';
        }
        
        header('Location: index.php?c=materias');
        exit;
    }
 
    private function validarDatosMateria($datos) {
        $errores = [];
        
      
        if (empty($datos['clave'])) {
            $errores[] = 'La clave es obligatoria';
        } elseif (strlen($datos['clave']) > 20) {
            $errores[] = 'La clave no puede tener más de 20 caracteres';
        } elseif (!preg_match('/^[A-Z0-9\-]+$/i', $datos['clave'])) {
            $errores[] = 'La clave solo puede contener letras, números y guiones';
        }
        
        
        if (empty($datos['nombre'])) {
            $errores[] = 'El nombre de la materia es obligatorio';
        } elseif (strlen($datos['nombre']) < 3) {
            $errores[] = 'El nombre debe tener al menos 3 caracteres';
        } elseif (strlen($datos['nombre']) > 200) {
            $errores[] = 'El nombre no puede tener más de 200 caracteres';
        }
        
        // Validar créditos
        $creditos = (int)($datos['creditos'] ?? 0);
        if ($creditos <= 0) {
            $errores[] = 'Los créditos deben ser mayor a 0';
        } elseif ($creditos > 10) {
            $errores[] = 'Los créditos no pueden ser mayor a 10';
        }
        
        // Validar horas por semana
        $horas = (int)($datos['horas_semana'] ?? 0);
        if ($horas <= 0) {
            $errores[] = 'Las horas por semana deben ser mayor a 0';
        } elseif ($horas > 20) {
            $errores[] = 'Las horas por semana no pueden ser mayor a 20';
        }
        
        // Validar carrera
        if (empty($datos['carrera_id']) || $datos['carrera_id'] <= 0) {
            $errores[] = 'Debe seleccionar una carrera';
        }
        
        // Validar semestre
        if (empty($datos['semestre_id']) || $datos['semestre_id'] <= 0) {
            $errores[] = 'Debe seleccionar un semestre';
        }
        
        return $errores;
    }
    
    private function loadView($view, $data = []) {
        extract($data);
        require_once VIEWS_PATH . 'layout/header.php';
        require_once VIEWS_PATH . $view . '.php';
        require_once VIEWS_PATH . 'layout/footer.php';
    }

  
    public function obtenerPorCarreraYSemestre() {
       
        if (ob_get_length()) ob_clean();
        header('Content-Type: application/json');
        
        $carrera_id = $_GET['carrera'] ?? null;
        $semestre_id = $_GET['semestre'] ?? null;
        
        if (!$carrera_id || !$semestre_id) {
            echo json_encode([]);
            exit;
        }
        
        try {
            $materias = $this->materia_model->getAll($carrera_id, $semestre_id);
            
           
            $resultado = array_map(function($materia) {
                return [
                    'id' => $materia['id'],
                    'clave' => $materia['clave'],
                    'nombre' => $materia['nombre'],
                    'creditos' => $materia['creditos']
                ];
            }, $materias);
            
            echo json_encode($resultado);
            
        } catch (Exception $e) {
            error_log("Error AJAX Materias: " . $e->getMessage());
            echo json_encode(['error' => $e->getMessage()]);
        }
        exit;
    }
}
?>