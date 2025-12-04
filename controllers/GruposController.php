<?php
// =====================================================
// controllers/GruposController.php
// ✅ VERSIÓN FINAL MEJORADA - Manejo de Excepciones y Lógica Robusta
// =====================================================

require_once MODELS_PATH . 'Grupo.php';
require_once MODELS_PATH . 'Aula.php';

class GruposController {
    
    private $grupo_model;
    private $aula_model;
    
    public function __construct() {
        // 1. Verificar autenticación
        if (!Auth::isLoggedIn()) {
            header('Location: index.php?c=dashboard');
            exit;
        }

        // 2. Verificar permisos (Solo Subdirector y DEP pueden gestionar grupos)
        if (!Auth::hasAnyRole([ROLE_SUBDIRECTOR, ROLE_DEP])) {
            $_SESSION['error'] = 'No tiene permisos para gestionar grupos.';
            header('Location: index.php?c=dashboard');
            exit;
        }
        
        $this->grupo_model = new Grupo();
        $this->aula_model = new Aula();
    }
    
    public function index() {
        $periodo_id = $_GET['periodo'] ?? null;
        $carrera_id = $_GET['carrera'] ?? null;
        $semestre_id = $_GET['semestre'] ?? null;
        
        $grupos = $this->grupo_model->getAll($periodo_id, $carrera_id, $semestre_id);
        
        $data = [
            'grupos' => $grupos,
            'periodo_filtro' => $periodo_id,
            'carrera_filtro' => $carrera_id,
            'semestre_filtro' => $semestre_id
        ];
        
        $this->loadView('grupos/index', $data);
    }
    
    public function crear() {
        // --- GET: Mostrar formulario ---
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $aulas = $this->aula_model->getAll();
            $this->loadView('grupos/crear', ['aulas' => $aulas]);
            return;
        }
        
        // --- POST: Procesar creación ---
        // Detectar si es una petición AJAX
        $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
                  strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
        
        try {
            // 1. Recolección y limpieza de datos
            $clave = strtoupper(trim($_POST['clave'] ?? ''));
            $materia_id = filter_input(INPUT_POST, 'materia_id', FILTER_VALIDATE_INT);
            $periodo_id = filter_input(INPUT_POST, 'periodo_id', FILTER_VALIDATE_INT);
            $carrera_id = filter_input(INPUT_POST, 'carrera_id', FILTER_VALIDATE_INT);
            $semestre_id = filter_input(INPUT_POST, 'semestre_id', FILTER_VALIDATE_INT);
            $cupo_minimo = filter_input(INPUT_POST, 'cupo_minimo', FILTER_VALIDATE_INT) ?: 22;
            $cupo_maximo = filter_input(INPUT_POST, 'cupo_maximo', FILTER_VALIDATE_INT) ?: 40;
            $alumnos_inscritos = filter_input(INPUT_POST, 'alumnos_inscritos', FILTER_VALIDATE_INT) ?: 0;
            
            // Manejo especial para Aula (puede venir vacía si no se asigna aún)
            $aula_id = !empty($_POST['aula_id']) ? (int)$_POST['aula_id'] : null;

            // 2. Validaciones Críticas Detalladas
            if (empty($clave)) {
                throw new Exception("El campo <strong>Clave del Grupo</strong> es obligatorio.");
            }
            if (!$periodo_id) {
                throw new Exception("Debe seleccionar un <strong>Período</strong> escolar.");
            }
            if (!$carrera_id) {
                throw new Exception("Debe seleccionar una <strong>Carrera</strong>.");
            }
            if (!$semestre_id) {
                throw new Exception("Debe seleccionar un <strong>Semestre</strong>.");
            }
            
            // Validación específica de Materia (Flujo Crítico)
            if (!$materia_id) {
                throw new Exception("⚠️ <strong>Falta la Materia:</strong> No se ha seleccionado ninguna materia. Si la lista aparece vacía, verifique que existan materias registradas para la carrera y semestre seleccionados.");
            }

            // 3. Lógica de Negocio: Calcular Estado Automático
            $estado = ($alumnos_inscritos >= $cupo_minimo) ? 'abierto' : 'proyectado';

            $datos = [
                'clave' => $clave,
                'materia_id' => $materia_id,
                'periodo_id' => $periodo_id,
                'carrera_id' => $carrera_id,
                'semestre_id' => $semestre_id,
                'cupo_minimo' => $cupo_minimo,
                'cupo_maximo' => $cupo_maximo,
                'alumnos_inscritos' => $alumnos_inscritos,
                'aula_id' => $aula_id,
                'estado' => $estado
            ];
            
            // 4. Guardar en Base de Datos con manejo de errores SQL
            $result = $this->grupo_model->create($datos);
            
            if ($result['success']) {
                // Registrar log si la función existe
                if (function_exists('logAccion')) {
                    logAccion(Auth::getCurrentUser(), $_SESSION['rol'], 'CREATE', 'grupos', $result['id'], "Grupo creado: $clave");
                }

                $mensajeExito = "Grupo <strong>$clave</strong> creado correctamente. Estado inicial: <strong>" . ucfirst($estado) . "</strong>.";

                // Respuesta diferenciada según el tipo de petición
                if ($isAjax) {
                    header('Content-Type: application/json');
                    echo json_encode([
                        'success' => true,
                        'message' => $mensajeExito
                    ]);
                    exit;
                } else {
                    $_SESSION['success'] = $mensajeExito;
                    header('Location: index.php?c=grupos');
                    exit;
                }
            } else {
                throw new Exception("No se pudo registrar el grupo en la base de datos.");
            }

        } catch (PDOException $e) {
            // Manejo específico de errores de base de datos
            $errorMsg = "Error de base de datos al crear el grupo.";
            
            // Detectar error de clave duplicada (SQLSTATE 23000 o código 1062)
            if ($e->getCode() == 23000 || strpos($e->getMessage(), '1062') !== false) {
                $errorMsg = "⚠️ <strong>Clave Duplicada:</strong> Ya existe un grupo con la clave <strong>" . htmlspecialchars($clave) . "</strong> en este período escolar. Por favor, utilice una clave diferente.";
            } else {
                error_log("DB Error Crear Grupo: " . $e->getMessage());
            }

            // Respuesta diferenciada
            if ($isAjax) {
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => false,
                    'message' => $errorMsg
                ]);
                exit;
            } else {
                $_SESSION['error'] = $errorMsg;
                header('Location: index.php?c=grupos&a=crear');
                exit;
            }

        } catch (Exception $e) {
            // Errores de validación o lógica
            $errorMsg = $e->getMessage();

            if ($isAjax) {
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => false,
                    'message' => $errorMsg
                ]);
                exit;
            } else {
                $_SESSION['error'] = $errorMsg;
                header('Location: index.php?c=grupos&a=crear');
                exit;
            }
        }
    }
    
    public function editar() {
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        
        if (!$id) {
            $_SESSION['error'] = 'ID de grupo no válido.';
            header('Location: index.php?c=grupos');
            exit;
        }
        
        // --- GET: Mostrar formulario de edición ---
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $db = new Database();
            $conn = $db->getConnection();
            
            // Obtener datos del grupo junto con el nombre de la materia para mostrarlo
            $sql = "SELECT g.*, m.nombre as materia_nombre, m.clave as materia_clave, 
                           c.nombre as carrera_nombre, s.nombre as semestre_nombre
                    FROM grupos g 
                    INNER JOIN materias m ON g.materia_id = m.id 
                    INNER JOIN carreras c ON g.carrera_id = c.id
                    INNER JOIN semestres s ON g.semestre_id = s.id
                    WHERE g.id = :id";
            
            $stmt = $conn->prepare($sql);
            $stmt->execute([':id' => $id]);
            $grupo = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$grupo) {
                $_SESSION['error'] = 'El grupo solicitado no existe.';
                header('Location: index.php?c=grupos');
                exit;
            }
            
            $aulas = $this->aula_model->getAll();
            $this->loadView('grupos/editar', ['grupo' => $grupo, 'aulas' => $aulas]);
            return;
        }
        
        // --- POST: Procesar actualización ---
        $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
                  strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
        
        try {
            // Recolección
            $cupo_minimo = filter_input(INPUT_POST, 'cupo_minimo', FILTER_VALIDATE_INT);
            $cupo_maximo = filter_input(INPUT_POST, 'cupo_maximo', FILTER_VALIDATE_INT);
            $alumnos_inscritos = filter_input(INPUT_POST, 'alumnos_inscritos', FILTER_VALIDATE_INT);
            $aula_id = !empty($_POST['aula_id']) ? (int)$_POST['aula_id'] : null;
            $estado_manual = $_POST['estado'] ?? 'proyectado';

            // Validaciones
            if ($cupo_minimo === false || $cupo_maximo === false || $alumnos_inscritos === false) {
                throw new Exception("Los campos numéricos (cupos e inscritos) son inválidos.");
            }

            // Lógica de Estado:
            if ($estado_manual != 'cancelado' && $estado_manual != 'cerrado') {
                $estado_final = ($alumnos_inscritos >= $cupo_minimo) ? 'abierto' : 'proyectado';
            } else {
                $estado_final = $estado_manual;
            }

            // Actualización directa SQL para mayor control
            $db = new Database();
            $conn = $db->getConnection();
            
            $sql = "UPDATE grupos SET
                    cupo_minimo = :cupo_minimo,
                    cupo_maximo = :cupo_maximo,
                    alumnos_inscritos = :alumnos_inscritos,
                    aula_id = :aula_id,
                    estado = :estado,
                    updated_at = NOW()
                    WHERE id = :id";
            
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ':cupo_minimo' => $cupo_minimo,
                ':cupo_maximo' => $cupo_maximo,
                ':alumnos_inscritos' => $alumnos_inscritos,
                ':aula_id' => $aula_id,
                ':estado' => $estado_final,
                ':id' => $id
            ]);
            
            if (function_exists('logAccion')) {
                logAccion(Auth::getCurrentUser(), $_SESSION['rol'], 'UPDATE', 'grupos', $id, 
                          "Actualización grupo ID $id. Nuevo estado: $estado_final");
            }
            
            $mensajeExito = 'Grupo actualizado exitosamente.';

            if ($isAjax) {
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => true,
                    'message' => $mensajeExito
                ]);
                exit;
            } else {
                $_SESSION['success'] = $mensajeExito;
                header('Location: index.php?c=grupos');
                exit;
            }

        } catch (Exception $e) {
            error_log("Error Update Grupo: " . $e->getMessage());
            $errorMsg = 'Error al actualizar: ' . $e->getMessage();

            if ($isAjax) {
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => false,
                    'message' => $errorMsg
                ]);
                exit;
            } else {
                $_SESSION['error'] = $errorMsg;
                header("Location: index.php?c=grupos&a=editar&id=$id");
                exit;
            }
        }
    }
    
    public function eliminar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;
            if ($id) {
                // Verificar si tiene horarios asignados
                $db = new Database();
                $conn = $db->getConnection();
                $check = $conn->prepare("SELECT COUNT(*) FROM horarios WHERE grupo_id = ?");
                $check->execute([$id]);
                
                if ($check->fetchColumn() > 0) {
                    $_SESSION['error'] = 'No se puede eliminar el grupo porque ya tiene horarios asignados. Elimine los horarios primero.';
                } else {
                    if ($this->grupo_model->delete($id)) {
                        if (function_exists('logAccion')) {
                            logAccion(Auth::getCurrentUser(), $_SESSION['rol'], 'DELETE', 'grupos', $id, "Grupo eliminado");
                        }
                        $_SESSION['success'] = 'Grupo eliminado correctamente.';
                    } else {
                        $_SESSION['error'] = 'Error al eliminar el grupo.';
                    }
                }
            }
        }
        header('Location: index.php?c=grupos');
    }

    private function loadView($view, $data = []) {
        extract($data);
        require_once VIEWS_PATH . 'layout/header.php';
        require_once VIEWS_PATH . $view . '.php';
        require_once VIEWS_PATH . 'layout/footer.php';
    }
}
?>