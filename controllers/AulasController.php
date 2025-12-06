<?php

require_once MODELS_PATH . 'Aula.php';

class AulasController {
    
    private $aula_model;
    
    public function __construct() {
        if (!Auth::hasAnyRole([ROLE_SUBDIRECTOR, ROLE_JEFE_DEPTO])) {
            header('Location: index.php?c=dashboard&error=access');
            exit;
        }
        
        $this->aula_model = new Aula();
    }
    
    public function index() {
        try {
            $tipo = $_GET['tipo'] ?? null;
            $aulas = $this->aula_model->getAll($tipo);
            $data = ['aulas' => $aulas, 'tipo_filtro' => $tipo];
            $this->loadView('aulas/index', $data);
        } catch (Exception $e) {
            error_log("Error en AulasController::index - " . $e->getMessage());
            $_SESSION['error'] = 'Error al cargar las aulas';
            header('Location: index.php?c=dashboard');
            exit;
        }
    }
    
    public function crear() {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $this->loadView('aulas/crear');
            return;
        }
        
        try {
            
            $errores = $this->validarDatosAula($_POST);
            
            if (!empty($errores)) {
                $_SESSION['error'] = implode('<br>', $errores);
                $_SESSION['form_data'] = $_POST;
                header('Location: index.php?c=aulas&a=crear');
                exit;
            }
            
            $datos = [
                'edificio' => strtoupper(trim($_POST['edificio'] ?? '')),
                'numero' => trim($_POST['numero'] ?? ''),
                'capacidad' => (int)($_POST['capacidad'] ?? 0),
                'tipo' => $_POST['tipo'] ?? 'normal',
                'equipamiento' => trim($_POST['equipamiento'] ?? '')
            ];
            
            $result = $this->aula_model->create($datos);
            
            if ($result['success']) {
                $_SESSION['success'] = 'Aula creada exitosamente: ' . $datos['edificio'] . '-' . $datos['numero'];
                unset($_SESSION['form_data']);
                header('Location: index.php?c=aulas');
            } else {
                $_SESSION['error'] = $result['message'] ?? 'Error al crear el aula';
                $_SESSION['form_data'] = $_POST;
                header('Location: index.php?c=aulas&a=crear');
            }
        } catch (Exception $e) {
            error_log("Error en AulasController::crear - " . $e->getMessage());
            $_SESSION['error'] = 'Ocurrió un error inesperado al crear el aula. Por favor, intente nuevamente.';
            $_SESSION['form_data'] = $_POST;
            header('Location: index.php?c=aulas&a=crear');
        }
        exit;
    }
    
    public function editar() {
        $id = $_GET['id'] ?? null;
        
        if (!$id || !is_numeric($id)) {
            $_SESSION['error'] = 'ID de aula no válido';
            header('Location: index.php?c=aulas');
            exit;
        }
        
        try {
            
            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                $aula = $this->aula_model->getById($id);
                
                if (!$aula) {
                    $_SESSION['error'] = 'Aula no encontrada';
                    header('Location: index.php?c=aulas');
                    exit;
                }
                
                $data = ['aula' => $aula];
                $this->loadView('aulas/editar', $data);
                return;
            }
            
           
            $errores = $this->validarDatosAula($_POST);
            
            if (!empty($errores)) {
                $_SESSION['error'] = implode('<br>', $errores);
                $_SESSION['form_data'] = $_POST;
                header('Location: index.php?c=aulas&a=editar&id=' . $id);
                exit;
            }
            
            $datos = [
                'edificio' => strtoupper(trim($_POST['edificio'] ?? '')),
                'numero' => trim($_POST['numero'] ?? ''),
                'capacidad' => (int)($_POST['capacidad'] ?? 0),
                'tipo' => $_POST['tipo'] ?? 'normal',
                'equipamiento' => trim($_POST['equipamiento'] ?? '')
            ];
            
            $result = $this->aula_model->update($id, $datos);
            
            if ($result['success']) {
                $_SESSION['success'] = 'Aula actualizada exitosamente';
                unset($_SESSION['form_data']);
                header('Location: index.php?c=aulas');
            } else {
                $_SESSION['error'] = $result['message'] ?? 'Error al actualizar el aula';
                $_SESSION['form_data'] = $_POST;
                header('Location: index.php?c=aulas&a=editar&id=' . $id);
            }
        } catch (Exception $e) {
            error_log("Error en AulasController::editar - " . $e->getMessage());
            $_SESSION['error'] = 'Ocurrió un error inesperado al actualizar el aula';
            $_SESSION['form_data'] = $_POST;
            header('Location: index.php?c=aulas&a=editar&id=' . $id);
        }
        exit;
    }
    
    public function eliminar() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?c=aulas');
            exit;
        }
        
        $id = $_POST['id'] ?? null;
        
        if (!$id || !is_numeric($id)) {
            $_SESSION['error'] = 'ID de aula no válido';
            header('Location: index.php?c=aulas');
            exit;
        }
        
        try {
            $result = $this->aula_model->delete($id);
            
            if ($result['success']) {
                $_SESSION['success'] = 'Aula eliminada exitosamente';
            } else {
                $_SESSION['error'] = $result['message'] ?? 'Error al eliminar el aula';
            }
        } catch (Exception $e) {
            error_log("Error en AulasController::eliminar - " . $e->getMessage());
            $_SESSION['error'] = 'Ocurrió un error inesperado al eliminar el aula';
        }
        
        header('Location: index.php?c=aulas');
        exit;
    }
    
    
    private function validarDatosAula($datos) {
        $errores = [];
        
        // Valida edificio
        if (empty($datos['edificio'])) {
            $errores[] = 'El edificio es obligatorio';
        } elseif (strlen($datos['edificio']) > 50) {
            $errores[] = 'El edificio no puede tener más de 50 caracteres';
        }
        
        // Valida número
        if (empty($datos['numero'])) {
            $errores[] = 'El número de aula es obligatorio';
        } elseif (strlen($datos['numero']) > 20) {
            $errores[] = 'El número de aula no puede tener más de 20 caracteres';
        }
        
        // Valida capacidad
        $capacidad = (int)($datos['capacidad'] ?? 0);
        if ($capacidad <= 0) {
            $errores[] = 'La capacidad debe ser mayor a 0';
        } elseif ($capacidad > 500) {
            $errores[] = 'La capacidad no puede ser mayor a 500';
        }
        
        // Valida tipo
        $tipos_validos = ['normal', 'laboratorio', 'taller', 'auditorio'];
        if (empty($datos['tipo'])) {
            $errores[] = 'El tipo de aula es obligatorio';
        } elseif (!in_array($datos['tipo'], $tipos_validos)) {
            $errores[] = 'El tipo de aula no es válido';
        }
        
        // Valida equipamiento
        if (!empty($datos['equipamiento']) && strlen($datos['equipamiento']) > 500) {
            $errores[] = 'El equipamiento no puede tener más de 500 caracteres';
        }
        
        return $errores;
    }
    
    private function loadView($view, $data = []) {
        extract($data);
        require_once VIEWS_PATH . 'layout/header.php';
        require_once VIEWS_PATH . $view . '.php';
        require_once VIEWS_PATH . 'layout/footer.php';
    }
}
?>