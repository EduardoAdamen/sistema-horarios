<?php
// =====================================================
// controllers/DashboardController.php
// Lógica separada por Roles - CORREGIDO (Manejo de conexión nula)
// =====================================================

class DashboardController {
    
    private $conn;

    public function __construct() {
        if (!Auth::isLoggedIn()) {
            header('Location: login.php');
            exit;
        }
        
        $db = new Database();
        $this->conn = $db->getConnection();

        // ✅ CORRECCIÓN CRÍTICA:
        // Verificamos si la conexión falló (es null) antes de continuar.
        if ($this->conn === null) {
            // Esto evita el "Fatal error: Call to a member function query() on null"
            // Puedes personalizar este mensaje o cargar una vista de error
            die("Error crítico: No se pudo establecer conexión con la base de datos. Verifique las variables de entorno (MYSQLPASSWORD, etc) en Railway.");
        }
    }
    
    public function index() {
        // Redirigir según el rol
        if (Auth::hasRole(ROLE_DOCENTE)) {
            $this->docenteDashboard();
        } else {
            $this->adminDashboard();
        }
    }

    /**
     * Dashboard General (Admin, Subdirector, Jefe Depto)
     */
    private function adminDashboard() {
        // Periodo activo
        $sql = "SELECT * FROM periodos_escolares WHERE activo = 1 LIMIT 1";
        
        // Al llegar aquí, $this->conn ya está validado en el constructor
        $stmt = $this->conn->query($sql);
        $periodo_activo = $stmt ? $stmt->fetch() : null;

        // Contar registros
        $stats = [];
        // Usamos try-catch en consultas por seguridad extra
        try {
            $stats['materias'] = $this->conn->query("SELECT COUNT(*) FROM materias WHERE activo = 1")->fetchColumn();
            $stats['docentes'] = $this->conn->query("SELECT COUNT(*) FROM docentes WHERE activo = 1")->fetchColumn();
            $stats['aulas'] = $this->conn->query("SELECT COUNT(*) FROM aulas WHERE activo = 1")->fetchColumn();

            if ($periodo_activo) {
                $stats['grupos'] = $this->conn->query("SELECT COUNT(*) FROM grupos WHERE periodo_id = {$periodo_activo['id']} AND estado != 'cancelado'")->fetchColumn();
                $stats['horarios_borrador'] = $this->conn->query("SELECT COUNT(*) FROM horarios WHERE periodo_id = {$periodo_activo['id']} AND estado = 'borrador'")->fetchColumn();
                $stats['horarios_conciliados'] = $this->conn->query("SELECT COUNT(*) FROM horarios WHERE periodo_id = {$periodo_activo['id']} AND estado = 'conciliado'")->fetchColumn();
                $stats['horarios_publicados'] = $this->conn->query("SELECT COUNT(*) FROM horarios WHERE periodo_id = {$periodo_activo['id']} AND estado = 'publicado'")->fetchColumn();
            } else {
                // Valores por defecto si no hay periodo activo
                $stats['grupos'] = 0;
                $stats['horarios_borrador'] = 0;
                $stats['horarios_conciliados'] = 0;
                $stats['horarios_publicados'] = 0;
            }
        } catch (PDOException $e) {
            error_log("Error cargando estadísticas: " . $e->getMessage());
        }

        $data = [
            'page_title' => '',
            'periodo_activo' => $periodo_activo,
            'stats' => $stats
        ];

        $this->loadView('dashboard/index', $data);
    }

    /**
     * Dashboard Personalizado para el Docente
     */
    private function docenteDashboard() {
        $usuario = $_SESSION['usuario']; // El username es el numero de empleado

        // 1. Obtener datos del Docente
        $sql_docente = "SELECT * FROM docentes WHERE numero_empleado = :usuario LIMIT 1";
        $stmt = $this->conn->prepare($sql_docente);
        $stmt->execute([':usuario' => $usuario]);
        $docente = $stmt->fetch();

        if (!$docente) {
            $_SESSION['error'] = 'No se encontró su perfil de docente asociado.';
            // En caso de error, mostramos una vista básica o redirigimos
            $this->loadView('dashboard/index', ['page_title' => 'Error de Perfil']); 
            return;
        }

        // Guardar ID en sesión por si se necesita luego
        $_SESSION['docente_id'] = $docente['id'];

        // 2. Obtener Periodo Activo
        $sql_periodo = "SELECT * FROM periodos_escolares WHERE activo = 1 LIMIT 1";
        $stmt = $this->conn->query($sql_periodo);
        $periodo = $stmt ? $stmt->fetch() : null;

        $clases_hoy = [];
        $total_grupos = 0;

        if ($periodo) {
            $_SESSION['periodo_actual'] = $periodo['id']; // Guardar para links

            // 3. Obtener Clases de HOY
            $dias_semana = [
                'Monday' => 'lunes', 'Tuesday' => 'martes', 'Wednesday' => 'miercoles',
                'Thursday' => 'jueves', 'Friday' => 'viernes', 'Saturday' => 'sabado', 'Sunday' => 'domingo'
            ];
            $dia_actual = $dias_semana[date('l')] ?? 'lunes';

            // CORRECCIÓN: Usamos 'a.numero' en lugar de 'a.nombre'
            $sql_clases = "SELECT 
                                h.hora_inicio, h.hora_fin, 
                                m.nombre as materia, m.clave as materia_clave,
                                g.clave as grupo,
                                a.numero as aula, a.edificio
                           FROM horarios h
                           INNER JOIN grupos g ON h.grupo_id = g.id
                           INNER JOIN materias m ON h.materia_id = m.id
                           LEFT JOIN aulas a ON h.aula_id = a.id
                           WHERE h.docente_id = :docente_id 
                           AND h.periodo_id = :periodo_id
                           AND h.dia = :dia
                           AND h.estado = 'publicado'
                           ORDER BY h.hora_inicio ASC";
            
            $stmt = $this->conn->prepare($sql_clases);
            $stmt->execute([
                ':docente_id' => $docente['id'],
                ':periodo_id' => $periodo['id'],
                ':dia' => $dia_actual
            ]);
            $clases_hoy = $stmt->fetchAll();

            // 4. Total de grupos asignados en el semestre
            $sql_total = "SELECT COUNT(DISTINCT grupo_id) FROM horarios WHERE docente_id = :docente_id AND periodo_id = :periodo_id";
            $stmt = $this->conn->prepare($sql_total);
            $stmt->execute([':docente_id' => $docente['id'], ':periodo_id' => $periodo['id']]);
            $total_grupos = $stmt->fetchColumn();
        }

        $data = [
            'page_title' => 'Mi Portal Docente',
            'docente' => $docente,
            'periodo' => $periodo,
            'clases_hoy' => $clases_hoy,
            'dia_actual_str' => ucfirst($dia_actual ?? 'Hoy'),
            'total_grupos' => $total_grupos
        ];

        $this->loadView('dashboard/docente', $data);
    }
    
    private function loadView($view, $data = []) {
        extract($data);
        require_once VIEWS_PATH . 'layout/header.php';
        require_once VIEWS_PATH . $view . '.php';
        require_once VIEWS_PATH . 'layout/footer.php';
    }
}
?>