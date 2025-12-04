<?php
// =====================================================
// views/horarios/ver_docente.php
// Vista del horario individual de un docente - DISEÑO MODERNO
// =====================================================

$page_title = 'Mi Horario';

// Organizar horarios en matriz
$dias_semana = ['lunes', 'martes', 'miercoles', 'jueves', 'viernes'];
$dias_labels = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes'];

// Generar horas (7:00 a 21:00)
$horas_dia = [];
for ($h = 7; $h <= 20; $h++) {
    $horas_dia[] = sprintf('%02d:00', $h);
}

// Crear matriz vacía
$matriz_horarios = [];
foreach ($dias_semana as $dia) {
    $matriz_horarios[$dia] = [];
    foreach ($horas_dia as $hora) {
        $matriz_horarios[$dia][$hora] = null;
    }
}

// Llenar matriz con horarios del docente
foreach ($horarios as $horario) {
    $hora_inicio = substr($horario['hora_inicio'], 0, 5);
    if (isset($matriz_horarios[$horario['dia']][$hora_inicio])) {
        $matriz_horarios[$horario['dia']][$hora_inicio] = $horario;
    }
}

// Calcular total de horas
$total_horas = 0;
foreach ($horarios as $horario) {
    $inicio = strtotime($horario['hora_inicio']);
    $fin = strtotime($horario['hora_fin']);
    $total_horas += ($fin - $inicio) / 3600;
}
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    :root {
        --primary: #2563eb;
        --primary-hover: #1d4ed8;
        --muted: #64748b;
        --text-main: #0f172a;
        --bg: #ffffff;
        --surface: #f8fafc;
        --border: #e2e8f0;
        --radius: 12px;
    }

    .page-container {
        font-family: "Open Sans", system-ui, Helvetica;
        padding: 22px;
        color: var(--text-main);
    }

    /* --------------------------- BREADCRUMB --------------------------- */
    .breadcrumb-wrapper {
        margin-bottom: 16px;
    }

    .breadcrumb-clean {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        font-size: 0.93rem;
        font-weight: 500;
    }

    .breadcrumb-clean .breadcrumb-item {
        color: #64748b;
        display: inline-flex;
        align-items: center;
    }

    .breadcrumb-clean .breadcrumb-item a {
        color: #64748b;
        text-decoration: none;
        padding: 2px 6px;
        border-radius: 6px;
        transition: 0.15s;
    }

    .breadcrumb-clean .breadcrumb-item a:hover {
        background: rgba(37,99,235,0.07);
        color: #2563eb;
    }

    .breadcrumb-clean .breadcrumb-item + .breadcrumb-item::before {
        content: "›";
        margin-right: 4px;
        color: #cbd5e1;
    }

    .breadcrumb-clean .active {
        font-weight: 700;
        color: #2563eb;
    }

    /* --------------------------- HEADER --------------------------- */
    .header-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 22px;
    }

    .page-title {
        font-size: 1.45rem;
        font-weight: 800;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .btn-print {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #f1f5f9;
        color: var(--muted);
        padding: 10px 18px;
        border-radius: 10px;
        font-weight: 600;
        border: 1px solid var(--border);
        cursor: pointer;
        transition: 0.25s ease;
    }

    .btn-print:hover {
        background: #e2e8f0;
        color: var(--text-main);
        transform: translateY(-2px);
    }

    /* --------------------------- CARD --------------------------- */
    .card-box {
        background: var(--bg);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 24px;
        margin-bottom: 22px;
        box-shadow: 0 1px 6px rgba(0,0,0,0.04);
    }

    /* --------------------------- INFO DOCENTE --------------------------- */
    .docente-info-grid {
        display: grid;
        grid-template-columns: 1fr auto;
        gap: 24px;
        align-items: center;
    }

    .docente-nombre {
        font-size: 1.35rem;
        font-weight: 700;
        margin: 0 0 8px 0;
        color: var(--text-main);
    }

    .docente-detalles {
        color: var(--muted);
        font-size: 0.95rem;
    }

    .docente-detalles strong {
        color: var(--text-main);
        font-weight: 600;
    }

    /* --------------------------- CARGA HORARIA --------------------------- */
    .carga-widget {
        text-align: right;
    }

    .badge-horas {
        display: inline-block;
        background: linear-gradient(135deg, var(--primary), #1e40af);
        color: white;
        padding: 12px 20px;
        border-radius: 10px;
        font-size: 1.1rem;
        font-weight: 700;
        margin-bottom: 12px;
        box-shadow: 0 4px 12px rgba(37,99,235,0.25);
    }

    .progress-bar-custom {
        background: #e2e8f0;
        border-radius: 12px;
        height: 28px;
        overflow: hidden;
        margin-bottom: 6px;
    }

    .progress-fill {
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.88rem;
        color: white;
        transition: width 0.3s ease;
    }

    .progress-success { background: linear-gradient(90deg, #10b981, #059669); }
    .progress-warning { background: linear-gradient(90deg, #f59e0b, #d97706); }
    .progress-danger { background: linear-gradient(90deg, #ef4444, #dc2626); }

    .progress-label {
        font-size: 0.82rem;
        color: var(--muted);
    }

    /* --------------------------- TABLA HORARIO --------------------------- */
    .horario-table-wrapper {
        border: 1px solid var(--border);
        border-radius: 12px;
        overflow: hidden;
    }

    .horario-table {
        width: 100%;
        border-collapse: collapse;
        margin: 0;
    }

    .horario-table thead th {
        background: #0f172a;
        color: white;
        padding: 14px 12px;
        text-align: center;
        font-size: 0.88rem;
        font-weight: 700;
        border: 1px solid #1e293b;
    }

    .horario-table thead th:first-child {
        width: 80px;
    }

    .horario-table tbody td {
        border: 1px solid var(--border);
        padding: 8px;
        vertical-align: top;
        min-height: 80px;
    }

    .hora-label {
        background: #f8fafc;
        font-weight: 700;
        text-align: center;
        color: var(--text-main);
        font-size: 0.90rem;
    }

    .celda-horario {
        background: white;
        min-height: 80px;
    }

    .bloque-horario {
        background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
        border-left: 4px solid var(--primary);
        border-radius: 8px;
        padding: 12px;
        min-height: 70px;
        transition: 0.2s ease;
    }

    .bloque-horario:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(37,99,235,0.15);
    }

    .bloque-materia-clave {
        font-weight: 700;
        color: var(--primary);
        font-size: 0.95rem;
        margin-bottom: 6px;
    }

    .bloque-materia-nombre {
        font-size: 0.85rem;
        color: var(--text-main);
        margin-bottom: 6px;
        line-height: 1.3;
    }

    .bloque-info {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 0.80rem;
        color: var(--muted);
        margin-bottom: 4px;
    }

    .bloque-horario-time {
        font-size: 0.80rem;
        color: var(--muted);
        border-top: 1px solid #cbd5e1;
        padding-top: 6px;
        margin-top: 6px;
        font-weight: 600;
    }

    /* --------------------------- TABLA RESUMEN --------------------------- */
    .table-resumen {
        width: 100%;
        border-collapse: collapse;
    }

    .table-resumen thead th {
        background: #f8fafc;
        padding: 12px 14px;
        text-align: left;
        font-size: 0.85rem;
        color: var(--muted);
        text-transform: uppercase;
        border-bottom: 2px solid var(--border);
        font-weight: 700;
    }

    .table-resumen tbody td {
        padding: 12px 14px;
        border-bottom: 1px solid var(--border);
        font-size: 0.90rem;
    }

    .table-resumen tbody tr:hover {
        background: #f8fafc;
    }

    .table-resumen tfoot td {
        padding: 14px;
        background: #f8fafc;
        font-weight: 700;
        border-top: 2px solid var(--border);
    }

    .badge-horas-small {
        background: var(--primary);
        color: white;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.82rem;
        font-weight: 700;
    }

    .badge-success {
        background: #10b981;
        color: white;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.82rem;
        font-weight: 700;
    }

    /* --------------------------- ALERT INFO --------------------------- */
    .alert-info-card {
        background: #dbeafe;
        border: 1px solid #93c5fd;
        border-radius: 10px;
        padding: 16px;
        display: flex;
        align-items: start;
        gap: 12px;
        color: #1e40af;
    }

    .alert-info-card i {
        font-size: 1.2rem;
        margin-top: 2px;
    }

    .alert-info-card p {
        margin: 0;
        font-size: 0.93rem;
        line-height: 1.5;
    }

    .alert-empty {
        background: #f0f9ff;
        border: 1px solid #bae6fd;
        border-radius: 10px;
        padding: 24px;
        text-align: center;
        color: #0369a1;
    }

    .alert-empty i {
        font-size: 2.5rem;
        margin-bottom: 12px;
        opacity: 0.5;
    }

    /* --------------------------- SECTION HEADER --------------------------- */
    .section-header {
        background: #f8fafc;
        padding: 14px 18px;
        border-radius: 10px 10px 0 0;
        border: 1px solid var(--border);
        border-bottom: none;
        font-weight: 700;
        font-size: 1rem;
        color: var(--text-main);
        display: flex;
        align-items: center;
        gap: 8px;
    }

    /* --------------------------- RESPONSIVE --------------------------- */
    @media (max-width: 968px) {
        .docente-info-grid {
            grid-template-columns: 1fr;
        }

        .carga-widget {
            text-align: left;
        }
    }

    @media (max-width: 600px) {
        .header-actions {
            flex-direction: column;
            gap: 12px;
            align-items: flex-start;
        }
    }

    /* --------------------------- PRINT --------------------------- */
    @media print {
        .no-print, .breadcrumb-wrapper, .btn-print, .header-actions .btn-print {
            display: none !important;
        }

        .page-container {
            padding: 0;
        }

        .card-box {
            border: 1px solid #000 !important;
            box-shadow: none !important;
            page-break-inside: avoid;
            margin-bottom: 20px;
        }

        .bloque-horario {
            border: 1px solid #000 !important;
            background: white !important;
        }

        .horario-table {
            font-size: 10px;
        }
    }
</style>

<div class="page-container">

    <!-- BREADCRUMB -->
    <div class="breadcrumb-wrapper no-print">
        <div class="breadcrumb-clean">
            <span class="breadcrumb-item">
                <a href="<?php echo APP_URL; ?>index.php">Inicio</a>
            </span>
            <?php if (Auth::hasAnyRole([ROLE_SUBDIRECTOR, ROLE_JEFE_DEPTO])): ?>
                <span class="breadcrumb-item">
                    <a href="<?php echo APP_URL; ?>index.php?c=docentes">Docentes</a>
                </span>
            <?php endif; ?>
            <span class="breadcrumb-item active">Horario</span>
        </div>
    </div>

    <!-- HEADER -->
    <div class="header-actions">
        <h1 class="page-title">
           
            <?php echo Auth::hasRole(ROLE_DOCENTE) ? 'Mi Horario' : 'Horario del Docente'; ?>
        </h1>
        <button type="button" class="btn-print no-print" onclick="window.print()">
            <i class="fas fa-print"></i> Imprimir
        </button>
    </div>

    <!-- INFORMACIÓN DEL DOCENTE -->
    <div class="card-box">
        <div class="docente-info-grid">
            <div>
                <h2 class="docente-nombre">
                    <?php echo htmlspecialchars($docente['nombre'] . ' ' . 
                               $docente['apellido_paterno'] . ' ' . 
                               ($docente['apellido_materno'] ?? '')); ?>
                </h2>
                <div class="docente-detalles">
                    <strong>No. Empleado:</strong> <?php echo htmlspecialchars($docente['numero_empleado']); ?> | 
                    <strong>Tipo:</strong> <?php echo ucfirst(str_replace('_', ' ', $docente['tipo'])); ?>
                </div>
            </div>
            
            <div class="carga-widget">
                <div class="badge-horas">
                    <?php echo $total_horas; ?> / <?php echo $docente['horas_max_semana']; ?> hrs
                </div>
                <?php
                $porcentaje = ($docente['horas_max_semana'] > 0) 
                    ? ($total_horas / $docente['horas_max_semana']) * 100 
                    : 0;
                
                if ($porcentaje > 90) {
                    $color_class = 'progress-danger';
                } elseif ($porcentaje > 70) {
                    $color_class = 'progress-warning';
                } else {
                    $color_class = 'progress-success';
                }
                ?>
                <div class="progress-bar-custom">
                    <div class="progress-fill <?php echo $color_class; ?>" 
                         style="width: <?php echo min($porcentaje, 100); ?>%">
                        <?php echo round($porcentaje, 1); ?>%
                    </div>
                </div>
                <div class="progress-label">Carga horaria semanal</div>
            </div>
        </div>
    </div>

    <!-- TABLA DE HORARIO SEMANAL -->
    <div class="card-box" style="padding: 0;">
        <div class="section-header">
            <i class="fas fa-calendar-week"></i>
            <span>Horario Semanal</span>
        </div>
        
        <div class="horario-table-wrapper" style="border-top: none; border-radius: 0 0 12px 12px;">
            <table class="horario-table">
                <thead>
                    <tr>
                        <th>Hora</th>
                        <?php foreach ($dias_labels as $dia_label): ?>
                            <th><?php echo $dia_label; ?></th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($horas_dia as $hora): ?>
                        <tr>
                            <td class="hora-label">
                                <?php echo $hora; ?>
                            </td>
                            <?php foreach ($dias_semana as $dia): ?>
                                <td class="celda-horario">
                                    <?php 
                                    $horario = $matriz_horarios[$dia][$hora];
                                    if ($horario): 
                                    ?>
                                        <div class="bloque-horario">
                                            <div class="bloque-materia-clave">
                                                <?php echo htmlspecialchars($horario['materia_clave']); ?>
                                            </div>
                                            <div class="bloque-materia-nombre">
                                                <?php 
                                                $nombre = $horario['materia_nombre'];
                                                echo htmlspecialchars(strlen($nombre) > 40 ? substr($nombre, 0, 40) . '...' : $nombre);
                                                ?>
                                            </div>
                                            <div class="bloque-info">
                                                <i class="fas fa-users"></i>
                                                <span><?php echo htmlspecialchars($horario['grupo_clave']); ?></span>
                                            </div>
                                            <div class="bloque-info">
                                                <i class="fas fa-door-open"></i>
                                                <span><?php echo htmlspecialchars($horario['aula']); ?></span>
                                            </div>
                                            <div class="bloque-horario-time">
                                                <?php echo substr($horario['hora_inicio'], 0, 5); ?> - 
                                                <?php echo substr($horario['hora_fin'], 0, 5); ?>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- RESUMEN DE MATERIAS -->
    <div class="card-box" style="padding: 0;">
        <div class="section-header">
            <i class="fas fa-list"></i>
            <span>Resumen de Materias Asignadas</span>
        </div>
        
        <div style="padding: 18px;">
            <?php if (empty($horarios)): ?>
                <div class="alert-empty">
                    <i class="fas fa-info-circle"></i>
                    <div style="font-weight: 600; font-size: 1rem;">No hay materias asignadas en este período</div>
                </div>
            <?php else: ?>
                <table class="table-resumen">
                    <thead>
                        <tr>
                            <th>Materia</th>
                            <th>Grupo</th>
                            <th>Carrera</th>
                            <th>Semestre</th>
                            <th style="text-align: center;">Horas/Semana</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Agrupar por materia
                        $materias_agrupadas = [];
                        foreach ($horarios as $horario) {
                            $key = $horario['materia_id'] . '_' . $horario['grupo_clave'];
                            
                            if (!isset($materias_agrupadas[$key])) {
                                $materias_agrupadas[$key] = [
                                    'materia' => $horario['materia_clave'] . ' - ' . $horario['materia_nombre'],
                                    'grupo' => $horario['grupo_clave'],
                                    'carrera' => $horario['carrera'],
                                    'semestre' => $horario['semestre'],
                                    'horas' => 0
                                ];
                            }
                            
                            $inicio = strtotime($horario['hora_inicio']);
                            $fin = strtotime($horario['hora_fin']);
                            $materias_agrupadas[$key]['horas'] += ($fin - $inicio) / 3600;
                        }
                        
                        foreach ($materias_agrupadas as $materia):
                        ?>
                        <tr>
                            <td><?php echo htmlspecialchars($materia['materia']); ?></td>
                            <td><?php echo htmlspecialchars($materia['grupo']); ?></td>
                            <td><?php echo htmlspecialchars($materia['carrera']); ?></td>
                            <td><?php echo htmlspecialchars($materia['semestre']); ?></td>
                            <td style="text-align: center;">
                                <span class="badge-horas-small">
                                    <?php echo $materia['horas']; ?> hrs
                                </span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4" style="text-align: right; font-size: 1rem;">TOTAL:</td>
                            <td style="text-align: center;">
                                <span class="badge-success">
                                    <?php echo $total_horas; ?> hrs
                                </span>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            <?php endif; ?>
        </div>
    </div>

    <!-- INFORMACIÓN -->
    <div class="card-box no-print">
        <div class="alert-info-card">
            <i class="fas fa-info-circle"></i>
            <p>
                Este horario muestra todas las asignaciones conciliadas o publicadas para el período actual.
                Usa el botón "Imprimir" arriba para generar una versión PDF de este horario.
            </p>
        </div>
    </div>

</div>