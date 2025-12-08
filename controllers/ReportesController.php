<?php

class ReportesController {
    
    private $conn;
    
    public function __construct() {
        if (!Auth::isLoggedIn()) {
            header('Location: login.php');
            exit;
        }
        
        $db = new Database();
        $this->conn = $db->getConnection();
    }
    
    public function index() {
        $periodos = $this->conn->query("SELECT * FROM periodos_escolares ORDER BY activo DESC, created_at DESC")->fetchAll();
        $carreras = $this->conn->query("SELECT * FROM carreras WHERE activo = 1 ORDER BY nombre")->fetchAll();
        $semestres = $this->conn->query("SELECT * FROM semestres ORDER BY numero")->fetchAll();
        $docentes = $this->conn->query("SELECT * FROM docentes WHERE activo = 1 ORDER BY apellido_paterno, nombre")->fetchAll();
        
        $data = [
            'periodos' => $periodos,
            'carreras' => $carreras,
            'semestres' => $semestres,
            'docentes' => $docentes
        ];
        
        $this->loadView('reportes/index', $data);
    }
    

    public function horarioGeneral() {
        $periodo_id = $_GET['periodo'] ?? null;
        $carrera_id = $_GET['carrera'] ?? null;
        $semestre_id = $_GET['semestre'] ?? null;
        $formato = $_GET['formato'] ?? 'html';
        
        if (!$periodo_id || !$carrera_id || !$semestre_id) {
            $_SESSION['error'] = 'Debe seleccionar período, carrera y semestre';
            header('Location: index.php?c=reportes');
            exit;
        }
        
      
        $sql_info = "SELECT 
                        p.nombre as periodo_nombre,
                        c.nombre as carrera_nombre,
                        c.clave as carrera_clave,
                        s.nombre as semestre_nombre
                      FROM periodos_escolares p
                      CROSS JOIN carreras c
                      CROSS JOIN semestres s
                      WHERE p.id = :periodo_id 
                      AND c.id = :carrera_id 
                      AND s.id = :semestre_id";
        
        $stmt = $this->conn->prepare($sql_info);
        $stmt->execute([
            ':periodo_id' => $periodo_id,
            ':carrera_id' => $carrera_id,
            ':semestre_id' => $semestre_id
        ]);
        $info = $stmt->fetch();
        
        if (!$info) {
            $_SESSION['error'] = 'No se encontró información para los parámetros seleccionados';
            header('Location: index.php?c=reportes');
            exit;
        }
        
        $sql = "SELECT 
                    h.id, h.dia, h.hora_inicio, h.hora_fin, h.estado, h.observaciones,
                    m.id as materia_id, m.clave as materia_clave, m.nombre as materia_nombre, m.creditos,
                    g.id as grupo_id, g.clave as grupo_clave,
                    CONCAT(COALESCE(d.nombre, ''), ' ', COALESCE(d.apellido_paterno, ''), ' ', COALESCE(d.apellido_materno, '')) as docente,
                    d.numero_empleado,
                    CONCAT(COALESCE(a.edificio, ''), '-', COALESCE(a.numero, '')) as aula,
                    c.nombre as carrera, s.nombre as semestre
                FROM horarios h
                INNER JOIN grupos g ON h.grupo_id = g.id
                INNER JOIN materias m ON g.materia_id = m.id
                LEFT JOIN docentes d ON h.docente_id = d.id
                LEFT JOIN aulas a ON h.aula_id = a.id
                INNER JOIN carreras c ON m.carrera_id = c.id
                INNER JOIN semestres s ON m.semestre_id = s.id
                WHERE h.periodo_id = :periodo_id
                AND m.carrera_id = :carrera_id
                AND m.semestre_id = :semestre_id
                ORDER BY FIELD(h.dia, 'lunes', 'martes', 'miercoles', 'jueves', 'viernes'), h.hora_inicio";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':periodo_id' => $periodo_id,
            ':carrera_id' => $carrera_id,
            ':semestre_id' => $semestre_id
        ]);
        $horarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $matriz_horarios = $this->organizarHorariosMatriz($horarios);
        
        $data = [
            'info' => $info,
            'horarios' => $horarios,
            'matriz_horarios' => $matriz_horarios,
            'periodo_id' => $periodo_id,
            'carrera_id' => $carrera_id,
            'semestre_id' => $semestre_id
        ];
        
        switch ($formato) {
            case 'pdf':
                $this->generarPDF('horario_general', $data);
                break;
            case 'excel':
                $this->exportarExcel('horario_general', $data);
                break;
            default:
                $this->loadView('reportes/horario_general', $data);
        }
    }
    
   
    public function horarioDocente() {
        $periodo_id = $_GET['periodo'] ?? null;
        $docente_id = $_GET['docente'] ?? null;
        $formato = $_GET['formato'] ?? 'html';
        
        if (!$periodo_id || !$docente_id) {
            $_SESSION['error'] = 'Debe seleccionar período y docente';
            header('Location: index.php?c=reportes');
            exit;
        }
        
        $stmt = $this->conn->prepare("SELECT * FROM docentes WHERE id = :docente_id");
        $stmt->execute([':docente_id' => $docente_id]);
        $docente = $stmt->fetch();
        
        if (!$docente) {
            $_SESSION['error'] = 'Docente no encontrado';
            header('Location: index.php?c=reportes');
            exit;
        }
        
        $stmt = $this->conn->prepare("SELECT * FROM periodos_escolares WHERE id = :periodo_id");
        $stmt->execute([':periodo_id' => $periodo_id]);
        $periodo = $stmt->fetch();
        
        if (!$periodo) {
            $_SESSION['error'] = 'Período no encontrado';
            header('Location: index.php?c=reportes');
            exit;
        }
        
        $sql = "SELECT 
                    h.id, h.dia, h.hora_inicio, h.hora_fin, h.estado, h.observaciones,
                    m.id as materia_id, m.clave as materia_clave, m.nombre as materia_nombre, m.creditos,
                    g.id as grupo_id, g.clave as grupo_clave,
                    CONCAT(COALESCE(a.edificio, ''), '-', COALESCE(a.numero, '')) as aula,
                    c.nombre as carrera, s.nombre as semestre
                FROM horarios h
                INNER JOIN grupos g ON h.grupo_id = g.id
                INNER JOIN materias m ON g.materia_id = m.id
                LEFT JOIN aulas a ON h.aula_id = a.id
                INNER JOIN carreras c ON m.carrera_id = c.id
                INNER JOIN semestres s ON m.semestre_id = s.id
                WHERE h.docente_id = :docente_id
                AND h.periodo_id = :periodo_id
                ORDER BY FIELD(h.dia, 'lunes', 'martes', 'miercoles', 'jueves', 'viernes'), h.hora_inicio";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':docente_id' => $docente_id, ':periodo_id' => $periodo_id]);
        $horarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $total_horas = 0;
        foreach ($horarios as $horario) {
            $inicio = strtotime($horario['hora_inicio']);
            $fin = strtotime($horario['hora_fin']);
            $total_horas += ($fin - $inicio) / 3600;
        }
        
        $matriz_horarios = $this->organizarHorariosMatriz($horarios);
        
        $data = [
            'docente' => $docente,
            'periodo' => $periodo,
            'horarios' => $horarios,
            'matriz_horarios' => $matriz_horarios,
            'total_horas' => $total_horas
        ];
        
        switch ($formato) {
            case 'pdf':
                $this->generarPDF('horario_docente', $data);
                break;
            case 'excel':
                $this->exportarExcel('horario_docente', $data);
                break;
            default:
                $this->loadView('reportes/horario_docente', $data);
        }
    }
    
  
    public function horarioAula() {
        $periodo_id = $_GET['periodo'] ?? null;
        $aula_id = $_GET['aula'] ?? null;
        $formato = $_GET['formato'] ?? 'html';
        
        if (!$periodo_id || !$aula_id) {
            $_SESSION['error'] = 'Debe seleccionar período y aula';
            header('Location: index.php?c=reportes');
            exit;
        }
        
        $stmt = $this->conn->prepare("SELECT * FROM aulas WHERE id = :aula_id");
        $stmt->execute([':aula_id' => $aula_id]);
        $aula = $stmt->fetch();
        
        if (!$aula) {
            $_SESSION['error'] = 'Aula no encontrada';
            header('Location: index.php?c=reportes');
            exit;
        }
        
        $stmt = $this->conn->prepare("SELECT * FROM periodos_escolares WHERE id = :periodo_id");
        $stmt->execute([':periodo_id' => $periodo_id]);
        $periodo = $stmt->fetch();
        
        if (!$periodo) {
            $_SESSION['error'] = 'Período no encontrado';
            header('Location: index.php?c=reportes');
            exit;
        }
        
        $sql = "SELECT 
                    h.id, h.dia, h.hora_inicio, h.hora_fin, h.estado, h.observaciones,
                    m.id as materia_id, m.clave as materia_clave, m.nombre as materia_nombre,
                    g.id as grupo_id, g.clave as grupo_clave,
                    CONCAT(COALESCE(d.nombre, ''), ' ', COALESCE(d.apellido_paterno, ''), ' ', COALESCE(d.apellido_materno, '')) as docente,
                    d.numero_empleado,
                    c.nombre as carrera, s.nombre as semestre
                FROM horarios h
                INNER JOIN grupos g ON h.grupo_id = g.id
                INNER JOIN materias m ON g.materia_id = m.id
                LEFT JOIN docentes d ON h.docente_id = d.id
                INNER JOIN carreras c ON m.carrera_id = c.id
                INNER JOIN semestres s ON m.semestre_id = s.id
                WHERE h.aula_id = :aula_id
                AND h.periodo_id = :periodo_id
                ORDER BY FIELD(h.dia, 'lunes', 'martes', 'miercoles', 'jueves', 'viernes'), h.hora_inicio";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':aula_id' => $aula_id, ':periodo_id' => $periodo_id]);
        $horarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $total_bloques_posibles = 5 * 14;
        $bloques_ocupados = count($horarios);
        $porcentaje_ocupacion = ($total_bloques_posibles > 0) 
            ? round(($bloques_ocupados / $total_bloques_posibles) * 100, 1)
            : 0;
        
        $matriz_horarios = $this->organizarHorariosMatriz($horarios);
        
        $data = [
            'aula' => $aula,
            'periodo' => $periodo,
            'horarios' => $horarios,
            'matriz_horarios' => $matriz_horarios,
            'bloques_ocupados' => $bloques_ocupados,
            'porcentaje_ocupacion' => $porcentaje_ocupacion
        ];
        
        switch ($formato) {
            case 'pdf':
                $this->generarPDF('horario_aula', $data);
                break;
            case 'excel':
                $this->exportarExcel('horario_aula', $data);
                break;
            default:
                $this->loadView('reportes/horario_aula', $data);
        }
    }
    
   
    public function cargaDocentes() {
        $periodo_id = $_GET['periodo'] ?? null;
        $formato = $_GET['formato'] ?? 'html';
        
        if (!$periodo_id) {
            $_SESSION['error'] = 'Debe seleccionar un período';
            header('Location: index.php?c=reportes');
            exit;
        }
        
        $stmt = $this->conn->prepare("SELECT * FROM periodos_escolares WHERE id = :periodo_id");
        $stmt->execute([':periodo_id' => $periodo_id]);
        $periodo = $stmt->fetch();
        
        if (!$periodo) {
            $_SESSION['error'] = 'Período no encontrado';
            header('Location: index.php?c=reportes');
            exit;
        }
        
        $sql = "SELECT 
                    d.id, d.numero_empleado,
                    CONCAT(d.nombre, ' ', d.apellido_paterno, ' ', COALESCE(d.apellido_materno, '')) as docente,
                    d.tipo, d.horas_max_semana,
                    COUNT(DISTINCT h.id) as num_bloques,
                    COUNT(DISTINCT h.materia_id) as num_materias,
                    COALESCE(SUM(TIMESTAMPDIFF(HOUR, h.hora_inicio, h.hora_fin)), 0) as horas_asignadas
                FROM docentes d
                LEFT JOIN horarios h ON d.id = h.docente_id AND h.periodo_id = :periodo_id
                WHERE d.activo = 1
                GROUP BY d.id, d.numero_empleado, d.nombre, d.apellido_paterno, d.apellido_materno, d.tipo, d.horas_max_semana
                ORDER BY d.apellido_paterno, d.nombre";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':periodo_id' => $periodo_id]);
        $docentes = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $data = [
            'periodo' => $periodo,
            'docentes' => $docentes
        ];
        
        switch ($formato) {
            case 'pdf':
                $this->generarPDF('carga_docentes', $data);
                break;
            case 'excel':
                $this->exportarExcel('carga_docentes', $data);
                break;
            default:
                $this->loadView('reportes/carga_docentes', $data);
        }
    }
    
    private function organizarHorariosMatriz($horarios) {
        $dias_semana = ['lunes', 'martes', 'miercoles', 'jueves', 'viernes'];
        $horas_dia = [];
        for ($h = 7; $h <= 20; $h++) {
            $horas_dia[] = sprintf('%02d:%02d', $h, 0);
        }
        
        $matriz = [];
        foreach ($dias_semana as $dia) {
            $matriz[$dia] = [];
            foreach ($horas_dia as $hora) {
                $matriz[$dia][$hora] = null;
            }
        }
        
        foreach ($horarios as $h) {
            $dia = strtolower(trim($h['dia']));
            $hora_raw = $h['hora_inicio'];
            $partes = explode(':', $hora_raw);
            $hora_normalizada = sprintf('%02d:%02d', (int)$partes[0], (int)$partes[1]);
            
            if (array_key_exists($dia, $matriz) && in_array($hora_normalizada, array_keys($matriz[$dia]))) {
                $matriz[$dia][$hora_normalizada] = $h;
            }
        }
        
        return [
            'matriz' => $matriz,
            'dias' => $dias_semana,
            'horas' => $horas_dia
        ];
    }

   
    private function generarPDF($tipo, $data) {
        // Extraer datos para la vista
        extract($data);
        
        // Iniciar buffer de salida
        ob_start();
        
       
        require_once VIEWS_PATH . 'reportes/pdf/' . $tipo . '.php';
        
        // Capturar contenido
        $html = ob_get_clean();
        
        // Enviar al navegador
        echo $html;
        exit;
    }
    
  
    private function exportarExcel($tipo, $data) {
        // Configurar headers para UTF-8
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $tipo . '_' . date('Y-m-d_His') . '.csv"');
        
        $output = fopen('php://output', 'w');
        
      
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
        
        switch ($tipo) {
            case 'horario_general':
            case 'horario_docente':
            case 'horario_aula':
                fputcsv($output, ['Día', 'Hora Inicio', 'Hora Fin', 'Materia', 'Grupo', 'Docente', 'Aula', 'Estado']);
                foreach ($data['horarios'] as $horario) {
                    fputcsv($output, [
                        ucfirst($horario['dia']),
                        substr($horario['hora_inicio'], 0, 5),
                        substr($horario['hora_fin'], 0, 5),
                        $horario['materia_clave'] . ' - ' . $horario['materia_nombre'],
                        $horario['grupo_clave'],
                        $horario['docente'] ?? 'Sin asignar',
                        $horario['aula'] ?? 'Sin asignar',
                        ucfirst($horario['estado'])
                    ]);
                }
                break;
                
            case 'carga_docentes':
                fputcsv($output, ['No. Empleado', 'Docente', 'Tipo', 'Horas Máx', 'Horas Asignadas', 'Bloques', 'Materias', 'Porcentaje']);
                foreach ($data['docentes'] as $docente) {
                    $porcentaje = ($docente['horas_max_semana'] > 0) 
                        ? round(($docente['horas_asignadas'] / $docente['horas_max_semana']) * 100, 1) 
                        : 0;
                    fputcsv($output, [
                        $docente['numero_empleado'],
                        $docente['docente'],
                        ucfirst(str_replace('_', ' ', $docente['tipo'])),
                        $docente['horas_max_semana'],
                        $docente['horas_asignadas'],
                        $docente['num_bloques'],
                        $docente['num_materias'],
                        $porcentaje . '%'
                    ]);
                }
                break;
        }
        
        fclose($output);
        exit;
    }
    
    private function loadView($view, $data = []) {
        extract($data);
        require_once VIEWS_PATH . 'layout/header.php';
        require_once VIEWS_PATH . $view . '.php';
        require_once VIEWS_PATH . 'layout/footer.php';
    }

   
    public function miHorario() {
        if (!Auth::hasRole(ROLE_DOCENTE)) {
            $_SESSION['error'] = 'Esta sección es exclusiva para docentes.';
            header('Location: index.php?c=dashboard');
            exit;
        }

        $usuario_login = $_SESSION['usuario']; 
        
        // 1. RECUPERACIÓN INTELIGENTE (Consultar Auth/Archivos)
        $authUser = Auth::getUserByUsername($usuario_login);
        
        // Criterios de búsqueda
        $numero_empleado_real = $authUser['numero_empleado'] ?? $usuario_login;
        $email = $authUser['email'] ?? $_SESSION['email'] ?? '';

        // 2. Buscar en MySQL usando OR para cubrir todos los casos
        $sql = "SELECT id FROM docentes WHERE numero_empleado = :num OR numero_empleado = :user OR email = :email LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':num' => $numero_empleado_real,
            ':user' => $usuario_login,
            ':email' => $email
        ]);
        $docente = $stmt->fetch();

        if (!$docente) {
            $_SESSION['error'] = 'No se encontró su registro de docente. (Revise que el No. Empleado o Email coincida)';
            header('Location: index.php?c=dashboard');
            exit;
        }

        // Obtener ID del periodo activo
        $stmt_p = $this->conn->query("SELECT id FROM periodos_escolares WHERE activo = 1 LIMIT 1");
        $periodo = $stmt_p->fetch();

        if (!$periodo) {
            $_SESSION['error'] = 'No hay periodo activo.';
            header('Location: index.php?c=dashboard');
            exit;
        }

        // Asignar parámetros GET para que la función horarioDocente funcione
        $_GET['docente'] = $docente['id'];
        $_GET['periodo'] = $periodo['id'];

        // Reutilizar la función existente
        $this->horarioDocente();
    }
}
?>