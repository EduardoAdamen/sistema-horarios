<?php

// Sincronización de horarios con Firebase


require_once __DIR__ . '/../config/firebase.php';
require_once __DIR__ . '/../config/database.php';

function sincronizarHorariosFirebase($periodo_id, $carrera_id, $semestre_id) {
    try {
        // Instanciar configuración de Firebase existente
        $firebase = FirebaseConfig::getInstance();
        
        if (!$firebase->isEnabled()) {
            throw new Exception("Firebase no está habilitado en la configuración.");
        }

        $db = new Database();
        $conn = $db->getConnection();
        
        // Obtener información del contexto 
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
        
        $stmt_info = $conn->prepare($sql_info);
        $stmt_info->execute([
            ':periodo_id' => $periodo_id,
            ':carrera_id' => $carrera_id,
            ':semestre_id' => $semestre_id
        ]);
        $info = $stmt_info->fetch(PDO::FETCH_ASSOC);

        if (!$info) {
            throw new Exception("No se encontró información del periodo/carrera/semestre.");
        }
        
        // Obtener los horarios conciliados
      
        $sql = "SELECT 
                    h.id, h.dia, h.hora_inicio, h.hora_fin,
                    m.clave as materia_clave, m.nombre as materia_nombre,
                    g.clave as grupo_clave,
                    CONCAT(d.nombre, ' ', d.apellido_paterno) as docente,
                    CONCAT(a.edificio, '-', a.numero) as aula
                FROM horarios h
                INNER JOIN materias m ON h.materia_id = m.id
                INNER JOIN grupos g ON h.grupo_id = g.id
                LEFT JOIN docentes d ON h.docente_id = d.id
                LEFT JOIN aulas a ON g.aula_id = a.id  
                WHERE h.periodo_id = :periodo_id
                AND m.carrera_id = :carrera_id
                AND m.semestre_id = :semestre_id
                AND h.estado IN ('conciliado', 'publicado')
                ORDER BY h.dia, h.hora_inicio";
        
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':periodo_id' => $periodo_id,
            ':carrera_id' => $carrera_id,
            ':semestre_id' => $semestre_id
        ]);
        
        $horarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Organizar estructura JSON (Dia -> Lista de Clases)
        $horarios_organizados = [
            'lunes' => [], 'martes' => [], 'miercoles' => [], 'jueves' => [], 'viernes' => []
        ];

        foreach ($horarios as $horario) {
            $dia = strtolower($horario['dia']);
            
           
            $item = [
                'materia' => $horario['materia_nombre'],
                'clave' => $horario['materia_clave'],
                'grupo' => $horario['grupo_clave'],
                'docente' => $horario['docente'] ?? 'Sin asignar',
                'aula' => $horario['aula'] ?? 'Sin asignar', 
                'hora_inicio' => substr($horario['hora_inicio'], 0, 5),
                'hora_fin' => substr($horario['hora_fin'], 0, 5)
            ];

            if (isset($horarios_organizados[$dia])) {
                $horarios_organizados[$dia][] = $item;
            }
        }
        
        // Datos finales a enviar
        $datos_firebase = [
            'info' => [
                'periodo' => $info['periodo_nombre'],
                'carrera' => $info['carrera_nombre'],
                'semestre' => $info['semestre_nombre'],
                'generado_el' => date('d/m/Y H:i')
            ],
            'horarios' => $horarios_organizados,
            'updated_at' => time()
        ];
        
        
        $path = "horarios/periodo_{$periodo_id}/carrera_{$carrera_id}/semestre_{$semestre_id}";
        
        $result = $firebase->sendData($path, $datos_firebase);
        
        if ($result === false || isset($result['error'])) {
            $errorMsg = isset($result['error']) ? $result['error'] : "Error de conexión cURL";
            throw new Exception("Firebase rechazó los datos: " . $errorMsg);
        }
      
            
        return [
            'success' => true,
            'message' => 'Sincronización exitosa',
            'details' => $result
        ];
        
    } catch (Exception $e) {
        error_log("Error Sync Firebase: " . $e->getMessage());
        return [
            'success' => false,
            'message' => $e->getMessage()
        ];
    }
}
?>