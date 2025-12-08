<?php

require_once MODELS_PATH . 'Horario.php';
require_once MODELS_PATH . 'Materia.php';
require_once MODELS_PATH . 'Grupo.php';
require_once MODELS_PATH . 'DocenteMateria.php';
require_once __DIR__ . '/../includes/firebase-sync.php';

class HorariosController {
    
    private $horario_model;
    private $materia_model;
    private $docente_materia_model;
    
    public function __construct() {
        if (!Auth::isLoggedIn()) {
            header('Location: login.php');
            exit;
        }
        $this->horario_model = new Horario();
        $this->materia_model = new Materia();
        $this->docente_materia_model = new DocenteMateria();
    }
    
    public function index() {
        if (!Auth::hasAnyRole([ROLE_SUBDIRECTOR, ROLE_JEFE_DEPTO])) {
            header('Location: index.php?c=dashboard&error=access'); exit;
        }
        $db = new Database(); $conn = $db->getConnection();
        
        $periodo_activo = $conn->query("SELECT * FROM periodos_escolares WHERE activo = 1 LIMIT 1")->fetch();
        $carreras = $conn->query("SELECT * FROM carreras WHERE activo = 1 ORDER BY nombre")->fetchAll();
        $semestres = $conn->query("SELECT * FROM semestres ORDER BY numero")->fetchAll();
        
        $this->loadView('horarios/index', compact('periodo_activo', 'carreras', 'semestres'));
    }

    public function asignar() {
        if (!Auth::hasAnyRole([ROLE_SUBDIRECTOR, ROLE_JEFE_DEPTO])) {
             header('Location: index.php?c=dashboard&error=access'); exit;
        }
        
        $periodo_id = $_GET['periodo'] ?? null;
        $carrera_id = $_GET['carrera'] ?? null;
        $semestre_id = $_GET['semestre'] ?? null;
        
        if (!$periodo_id || !$carrera_id || !$semestre_id) {
            header('Location: index.php?c=horarios&error=params'); exit;
        }

        $db = new Database(); $conn = $db->getConnection();
        
        $sql = "SELECT p.nombre as p_nombre, c.nombre as c_nombre, s.nombre as s_nombre 
                FROM periodos_escolares p, carreras c, semestres s 
                WHERE p.id=:p AND c.id=:c AND s.id=:s";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':p'=>$periodo_id, ':c'=>$carrera_id, ':s'=>$semestre_id]);
        $contexto = $stmt->fetch();

        $grupoModel = new Grupo();
        $grupos = $grupoModel->getAll($periodo_id, $carrera_id, $semestre_id);
        
        // Ya NO cargamos todos los docentes aquí, se cargan por AJAX según materia
        $docentes = []; // Array vacío, se llenará dinámicamente
        $aulas = $conn->query("SELECT * FROM aulas WHERE activo = 1")->fetchAll();
        $horarios_existentes = $this->horario_model->getHorarios($periodo_id, $carrera_id, $semestre_id);

        $data = compact('contexto', 'periodo_id', 'carrera_id', 'semestre_id', 'grupos', 'aulas', 'horarios_existentes');
        $this->loadView('horarios/asignar', $data);
    }
    
    /**
     * NUEVO: Endpoint AJAX para obtener docentes según materia
     */
    public function obtenerDocentesPorMateria() {
        header('Content-Type: application/json');
        
        $materia_id = $_GET['materia_id'] ?? null;
        
        if (!$materia_id) {
            echo json_encode(['success' => false, 'message' => 'Materia no especificada']);
            exit;
        }
        
        $docentes = $this->docente_materia_model->getDocentesPorMateria($materia_id);
        echo json_encode(['success' => true, 'docentes' => $docentes]);
        exit;
    }
    
    /**
     * Guardar horario con distribución automática
     */
    public function guardar() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Método no permitido']); 
            exit;
        }
        
        $grupo_id = $_POST['grupo_id'] ?? null;
        $docente_id = $_POST['docente_id'] ?? null;
        $dia_inicio = $_POST['dia'] ?? null;
        $hora_inicio = $_POST['hora_inicio'] ?? null;
        $hora_fin = $_POST['hora_fin'] ?? null;
        
        if (!$grupo_id || !$docente_id || !$dia_inicio || !$hora_inicio || !$hora_fin) {
            echo json_encode(['success' => false, 'message' => 'Datos incompletos']); 
            exit;
        }

        // Obtener información del grupo
        $grupoModel = new Grupo();
        $grupoInfo = $grupoModel->getById($grupo_id);

        if (!$grupoInfo || empty($grupoInfo['aula_id'])) {
            echo json_encode(['success' => false, 'message' => 'Grupo sin aula asignada']); 
            exit;
        }

        // Validar que el docente puede dar la materia
        if (!$this->docente_materia_model->puedeImpartir($docente_id, $grupoInfo['materia_id'])) {
            echo json_encode(['success' => false, 'message' => 'El docente no está autorizado para impartir esta materia']); 
            exit;
        }

        // Calcular horas del bloque
        $inicio = strtotime($hora_inicio);
        $fin = strtotime($hora_fin);
        $horas_bloque = ($fin - $inicio) / 3600;
        
        // Validar máximo 2 horas continuas
        if ($horas_bloque > 2) {
            echo json_encode(['success' => false, 'message' => 'No se permiten bloques de más de 2 horas continuas']); 
            exit;
        }

        // Obtener créditos de la materia
        $materia = $this->materia_model->getById($grupoInfo['materia_id']);
        $creditos_totales = $materia['creditos'];
        
        // Calcular bloques necesarios
        $bloques_necesarios = ceil($creditos_totales / $horas_bloque);
        
        // Días de la semana
        $dias_semana = ['lunes', 'martes', 'miercoles', 'jueves', 'viernes'];
        $idx_dia_inicio = array_search($dia_inicio, $dias_semana);
        
        $datos_base = [
            'grupo_id' => $grupo_id,
            'materia_id' => $grupoInfo['materia_id'],
            'docente_id' => $docente_id,
            'aula_id' => $grupoInfo['aula_id'],
            'periodo_id' => $_POST['periodo_id'],
            'hora_inicio' => $hora_inicio,
            'hora_fin' => $hora_fin,
            'horas_consecutivas' => $horas_bloque,
            'estado' => 'borrador'
        ];
        
        $horarios_creados = [];
        $bloques_creados = 0;
        
        // Distribuir en días
        for ($i = 0; $i < $bloques_necesarios && $bloques_creados < $bloques_necesarios; $i++) {
            $idx_dia = ($idx_dia_inicio + $i) % count($dias_semana);
            $dia_actual = $dias_semana[$idx_dia];
            
            // Verificar conflictos
            if ($this->verificarConflicto($docente_id, $grupoInfo['aula_id'], $dia_actual, $hora_inicio, $hora_fin, $_POST['periodo_id'])) {
                continue; // Saltar este día si hay conflicto
            }
            
            $datos_bloque = array_merge($datos_base, ['dia' => $dia_actual]);
            $result = $this->horario_model->create($datos_bloque);
            
            if ($result['success']) {
                $horarios_creados[] = $result['horario'];
                $bloques_creados++;
            }
        }
        
        if (empty($horarios_creados)) {
            echo json_encode(['success' => false, 'message' => 'No se pudieron crear bloques de horario']);
            exit;
        }
        
        echo json_encode([
            'success' => true, 
            'message' => "Se crearon $bloques_creados bloques de horario",
            'horarios' => $horarios_creados,
            'bloques_creados' => $bloques_creados,
            'bloques_esperados' => $bloques_necesarios
        ]);
        exit;
    }
    
    /**
     * Verificar conflictos de horario
     */
    private function verificarConflicto($docente_id, $aula_id, $dia, $hora_inicio, $hora_fin, $periodo_id) {
        $db = new Database();
        $conn = $db->getConnection();
        
        // Verificar conflicto de docente
        $sql = "SELECT COUNT(*) FROM horarios 
                WHERE docente_id = :docente_id 
                AND dia = :dia 
                AND periodo_id = :periodo_id
                AND (
                    (hora_inicio < :hora_fin AND hora_fin > :hora_inicio)
                )";
        
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':docente_id' => $docente_id,
            ':dia' => $dia,
            ':periodo_id' => $periodo_id,
            ':hora_inicio' => $hora_inicio,
            ':hora_fin' => $hora_fin
        ]);
        
        if ($stmt->fetchColumn() > 0) {
            return true;
        }
        
        // Verificar conflicto de aula
        $sql = "SELECT COUNT(*) FROM horarios 
                WHERE aula_id = :aula_id 
                AND dia = :dia 
                AND periodo_id = :periodo_id
                AND (
                    (hora_inicio < :hora_fin AND hora_fin > :hora_inicio)
                )";
        
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':aula_id' => $aula_id,
            ':dia' => $dia,
            ':periodo_id' => $periodo_id,
            ':hora_inicio' => $hora_inicio,
            ':hora_fin' => $hora_fin
        ]);
        
        return $stmt->fetchColumn() > 0;
    }

    public function eliminar() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Método no permitido']); 
            exit;
        }
        
        $id = $_POST['id'] ?? null;
        
        if (!$id) {
            echo json_encode(['success' => false, 'message' => 'ID no especificado']); 
            exit;
        }
        
        try {
            $db = new Database();
            $conn = $db->getConnection();
            
            $sql = "DELETE FROM horarios WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->execute([':id' => $id]);
            
            if ($stmt->rowCount() > 0) {
                if (function_exists('logAccion')) {
                    logAccion(Auth::getCurrentUser(), $_SESSION['rol'], 'DELETE', 'horarios', $id, "Bloque eliminado");
                }
                
                echo json_encode(['success' => true, 'message' => 'Bloque eliminado correctamente']);
            } else {
                echo json_encode(['success' => false, 'message' => 'No se pudo eliminar']);
            }
            
        } catch (Exception $e) {
            error_log("Error eliminar: " . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
        exit;
    }
    
    public function conciliar() {
        if (!Auth::hasRole(ROLE_JEFE_DEPTO)) {
            header('Location: index.php?c=dashboard&error=access'); exit;
        }
        
        $periodo_id = $_POST['periodo_id'] ?? null;
        $carrera_id = $_POST['carrera_id'] ?? null;
        $semestre_id = $_POST['semestre_id'] ?? null;
        
        if (!$periodo_id || !$carrera_id || !$semestre_id) {
            echo json_encode(['success' => false, 'message' => 'Parámetros incompletos']);
            exit;
        }
        
        $result = $this->horario_model->marcarComoConciliado($periodo_id, $carrera_id, $semestre_id);
        
        if ($result['success']) {
            try {
                if (function_exists('sincronizarHorariosFirebase')) {
                    $syncResult = sincronizarHorariosFirebase($periodo_id, $carrera_id, $semestre_id);
                    
                    if ($syncResult['success']) {
                        $result['message'] .= " y sincronizado con Firebase";
                    } else {
                        $result['message'] .= " (Advertencia: Falló sync Firebase)";
                    }
                }
            } catch (Exception $e) {
                error_log("Error sync: " . $e->getMessage());
                $result['message'] .= " (Error de red al sincronizar)";
            }
        }
        
        echo json_encode($result);
        exit;
    }

    public function obtenerHorarios() {
        header('Content-Type: application/json');
        $periodo_id = $_GET['periodo'] ?? null;
        $carrera_id = $_GET['carrera'] ?? null;
        $semestre_id = $_GET['semestre'] ?? null;
        
        if (!$periodo_id || !$carrera_id || !$semestre_id) {
            echo json_encode(['success' => false]); exit;
        }
        
        $horarios = $this->horario_model->getHorarios($periodo_id, $carrera_id, $semestre_id);
        
        $data = array_map(function($h) {
            return [
                'id' => $h['id'],
                'grupo_id' => $h['grupo_id'],
                'dia' => $h['dia'],
                'hora_inicio' => substr($h['hora_inicio'], 0, 5),
                'hora_fin' => substr($h['hora_fin'], 0, 5),
                'docente_nombre' => $h['docente_nombre'],
                'aula' => $h['aula'],
                'materia_clave' => $h['materia_clave'],
                'materia_nombre' => $h['materia_nombre']
            ];
        }, $horarios);
        
        echo json_encode(['success' => true, 'horarios' => $data]);
        exit;
    }

    public function verDocente() {
        $docente_id = $_GET['docente'] ?? null;
        $periodo_id = $_GET['periodo'] ?? null;

        if (!$docente_id || !$periodo_id) {
            header('Location: index.php?c=dashboard'); exit;
        }

        $db = new Database(); $conn = $db->getConnection();
        $stmt = $conn->prepare("SELECT * FROM docentes WHERE id=:id");
        $stmt->execute([':id'=>$docente_id]);
        $docente = $stmt->fetch();

        $sql = "SELECT h.*, m.nombre as materia_nombre, g.clave as grupo_clave, 
                CONCAT(a.edificio, '-', a.numero) as aula 
                FROM horarios h 
                JOIN materias m ON h.materia_id = m.id 
                JOIN grupos g ON h.grupo_id = g.id 
                LEFT JOIN aulas a ON h.aula_id = a.id 
                WHERE h.docente_id = :d AND h.periodo_id = :p AND h.estado IN ('conciliado', 'publicado')
                ORDER BY h.dia, h.hora_inicio";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':d'=>$docente_id, ':p'=>$periodo_id]);
        $horarios = $stmt->fetchAll();

        $this->loadView('horarios/ver_docente', compact('docente', 'horarios', 'periodo_id'));
    }

    private function loadView($view, $data = []) {
        extract($data);
        require_once VIEWS_PATH . 'layout/header.php';
        require_once VIEWS_PATH . $view . '.php';
        require_once VIEWS_PATH . 'layout/footer.php';
    }
}
?>