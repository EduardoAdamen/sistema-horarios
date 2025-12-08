<?php
// =====================================================
// controllers/DashboardController.php
// Versión corregida: Usa Auth (Archivos) para resolver la identidad del docente
// =====================================================

class DashboardController {
    
    private $conn;

    public function __construct() {
        // 1. Verificar autenticación (Igual que en UsuariosController)
        if (!Auth::isLoggedIn()) {
            header('Location: login.php');
            exit;
        }
        
        // 2. Conexión a BD (Necesaria para estadísticas y horarios)
        $db = new Database();
        $this->conn = $db->getConnection();
    }
    
    public function index() {
        if (Auth::hasRole(ROLE_DOCENTE)) {
            $this->docenteDashboard();
        } else {
            $this->adminDashboard();
        }
    }

    private function adminDashboard() {
        // Lógica estándar para administradores
        $sql = "SELECT * FROM periodos_escolares WHERE activo = 1 LIMIT 1";
        $stmt = $this->conn->query($sql);
        $periodo_activo = $stmt ? $stmt->fetch() : null;

        $stats = [];
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
                $stats['grupos'] = 0;
                $stats['horarios_borrador'] = 0;
                $stats['horarios_conciliados'] = 0;
                $stats['horarios_publicados'] = 0;
            }
        } catch (Exception $e) {
            // Silencioso o log
        }

        $data = [
            'page_title' => '',
            'periodo_activo' => $periodo_activo,
            'stats' => $stats
        ];

        $this->loadView('dashboard/index', $data);
    }

    /**
     * Dashboard del Docente
     * CORRECCIÓN: Obtiene el número de empleado desde el archivo de usuarios (Auth)
     */
    private function docenteDashboard() {
        $usuario_login = $_SESSION['usuario']; 
        
        // PASO CLAVE: Consultamos el archivo de usuarios (users.db) mediante Auth
        // para obtener el 'numero_empleado' real asociado a este login.
        $datos_usuario = Auth::getUserByUsername($usuario_login);
        
        // Si el usuario en archivo tiene 'numero_empleado', lo usamos.
        // Si no, usamos el usuario de login como respaldo.
        $numero_empleado_real = $datos_usuario['numero_empleado'] ?? $usuario_login;

        // Ahora buscamos en MySQL usando el número de empleado correcto
        $sql_docente = "SELECT * FROM docentes WHERE numero_empleado = :num LIMIT 1";
        $stmt = $this->conn->prepare($sql_docente);
        $stmt->execute([':num' => $numero_empleado_real]);
        $docente = $stmt->fetch();

        if (!$docente) {
            // Si falla, intentamos por email como última opción (si existe en Auth)
            if (!empty($datos_usuario['email'])) {
                $sql_docente = "SELECT * FROM docentes WHERE email = :email LIMIT 1";
                $stmt = $this->conn->prepare($sql_docente);
                $stmt->execute([':email' => $datos_usuario['email']]);
                $docente = $stmt->fetch();
            }
        }

        if (!$docente) {
            $_SESSION['error'] = 'No se encontró su perfil de docente asociado (Empleado: ' . $numero_empleado_real . ')';
            $this->loadView('dashboard/index', ['page_title' => 'Error de Perfil']); 
            return;
        }

        // Guardamos el ID de base de datos para las consultas de horarios
        $_SESSION['docente_id'] = $docente['id'];

        // Obtener Periodo Activo
        $sql_periodo = "SELECT * FROM periodos_escolares WHERE activo = 1 LIMIT 1";
        $stmt = $this->conn->query($sql_periodo);
        $periodo = $stmt ? $stmt->fetch() : null;

        $clases_hoy = [];
        $total_grupos = 0;

        if ($periodo) {
            $_SESSION['periodo_actual'] = $periodo['id'];

            // Obtener Clases de HOY
            $dias_semana = [
                'Monday' => 'lunes', 'Tuesday' => 'martes', 'Wednesday' => 'miercoles',
                'Thursday' => 'jueves', 'Friday' => 'viernes', 'Saturday' => 'sabado', 'Sunday' => 'domingo'
            ];
            $dia_actual = $dias_semana[date('l')] ?? 'lunes';

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

            // Total de grupos
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