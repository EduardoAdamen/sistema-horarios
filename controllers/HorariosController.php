<?php

require_once MODELS_PATH . 'Horario.php';
require_once MODELS_PATH . 'Materia.php';
require_once MODELS_PATH . 'Grupo.php'; 


require_once __DIR__ . '/../includes/firebase-sync.php'; 

class HorariosController {
    
    private $horario_model;
    private $materia_model;
    
    public function __construct() {
        if (!Auth::isLoggedIn()) {
            header('Location: login.php');
            exit;
        }
        $this->horario_model = new Horario();
        $this->materia_model = new Materia();
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

        // Obtener Grupos 
        $grupoModel = new Grupo();
        $grupos = $grupoModel->getAll($periodo_id, $carrera_id, $semestre_id);
        
        $docentes = $conn->query("SELECT * FROM docentes WHERE activo = 1 ORDER BY apellido_paterno")->fetchAll();
       
        $aulas = $conn->query("SELECT * FROM aulas WHERE activo = 1")->fetchAll(); 
        $horarios_existentes = $this->horario_model->getHorarios($periodo_id, $carrera_id, $semestre_id);

        $data = compact('contexto', 'periodo_id', 'carrera_id', 'semestre_id', 'grupos', 'docentes', 'aulas', 'horarios_existentes');
        $this->loadView('horarios/asignar', $data);
    }
    
   
    public function guardar() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Método no permitido']); exit;
        }
        
        $grupo_id = $_POST['grupo_id'] ?? null;
        
        if (!$grupo_id) {
            echo json_encode(['success' => false, 'message' => 'Grupo no especificado']); exit;
        }

        // Consultar el Grupo para obtener su Aula
        $grupoModel = new Grupo();
        $grupoInfo = $grupoModel->getById($grupo_id);

        if (!$grupoInfo) {
            echo json_encode(['success' => false, 'message' => 'Grupo no encontrado']); exit;
        }

        if (empty($grupoInfo['aula_id'])) {
            echo json_encode([
                'success' => false, 
                'message' => 'ERROR: Este grupo no tiene aula asignada por la DEP. No se puede crear horario.'
            ]); 
            exit;
        }

        $aula_id_asignada = $grupoInfo['aula_id'];

       
        $datos = [
            'grupo_id' => $grupo_id,
            'materia_id' => $_POST['materia_id'] ?? null,
            'docente_id' => $_POST['docente_id'] ?? null,
            'aula_id' => $aula_id_asignada, // <-- Forzado
            'periodo_id' => $_POST['periodo_id'] ?? null,
            'dia' => $_POST['dia'] ?? null,
            'hora_inicio' => $_POST['hora_inicio'] ?? null,
            'hora_fin' => $_POST['hora_fin'] ?? null,
            'estado' => 'borrador'
        ];
        
        $result = $this->horario_model->create($datos);
        echo json_encode($result);
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
        
        // Elimina el horario
        $sql = "DELETE FROM horarios WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        
        if ($stmt->rowCount() > 0) {
            
            if (function_exists('logAccion')) {
                logAccion(
                    Auth::getCurrentUser(), 
                    $_SESSION['rol'], 
                    'DELETE', 
                    'horarios', 
                    $id, 
                    "Bloque de horario eliminado"
                );
            }
            
            echo json_encode([
                'success' => true, 
                'message' => 'Bloque eliminado correctamente'
            ]);
        } else {
            echo json_encode([
                'success' => false, 
                'message' => 'No se pudo eliminar el bloque'
            ]);
        }
        
    } catch (Exception $e) {
        error_log("Error al eliminar horario: " . $e->getMessage());
        echo json_encode([
            'success' => false, 
            'message' => 'Error al eliminar: ' . $e->getMessage()
        ]);
    }
    exit;
}
    
    // Marca estado y sincroniza a Firebase
   
    public function conciliar() {
        if (!Auth::hasRole(ROLE_JEFE_DEPTO)) {
            header('Location: index.php?c=dashboard&error=access'); exit;
        }
        
        $periodo_id = $_POST['periodo_id'] ?? null;
        $carrera_id = $_POST['carrera_id'] ?? null;
        $semestre_id = $_POST['semestre_id'] ?? null;
        
        if (!$periodo_id || !$carrera_id || !$semestre_id) {
            $_SESSION['error'] = 'Parámetros incompletos';
            header('Location: index.php?c=horarios'); exit;
        }
        
        // Marcar como conciliado en MySQL
        $result = $this->horario_model->marcarComoConciliado($periodo_id, $carrera_id, $semestre_id);
        
        if ($result['success']) {
            //  Sincronizar a Firebase
            try {
                if (function_exists('sincronizarHorariosFirebase')) {
                    $syncResult = sincronizarHorariosFirebase($periodo_id, $carrera_id, $semestre_id);
                    
                    if ($syncResult['success']) {
                        $result['message'] .= " y sincronizado con la Nube.";
                    } else {
                        $result['message'] .= " (Advertencia: Falló sync Firebase - " . $syncResult['message'] . ")";
                    }
                }
            } catch (Exception $e) {
                error_log("Error sync: " . $e->getMessage());
                $result['message'] .= " (Error de red al sincronizar)";
            }

            $_SESSION['success'] = $result['message'];
        } else {
            $_SESSION['error'] = $result['message'];
        }
        
        header("Location: index.php?c=horarios&a=asignar&periodo=$periodo_id&carrera=$carrera_id&semestre=$semestre_id");
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
                'aula' => $h['aula']
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

        // Obtener horarios conciliados
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