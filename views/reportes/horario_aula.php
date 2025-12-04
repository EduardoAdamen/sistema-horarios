<?php
// =====================================================
// views/reportes/horario_aula.php
// Reporte de ocupación de aula - BOTONES CORREGIDOS
// =====================================================

$page_title = 'Reporte: Ocupación de Aula';
$dias_labels = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes'];
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    /* [TODOS LOS ESTILOS SE MANTIENEN EXACTAMENTE IGUAL - solo copio los originales] */
    :root {
        --primary: #2563eb;
        --primary-hover: #1d4ed8;
        --success: #10b981;
        --warning: #f59e0b;
        --danger: #ef4444;
        --info: #0ea5e9;
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

    @media print {
        .no-print { display: none !important; }
        .card-box { border: 1px solid #000 !important; box-shadow: none !important; }
        body { font-size: 10pt; }
        table { font-size: 9pt; }
        .page-container { padding: 10px; }
    }

    .breadcrumb-wrapper { margin-bottom: 16px; }
    .breadcrumb-clean { display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; font-size: 0.93rem; font-weight: 500; }
    .breadcrumb-clean .breadcrumb-item { color: #64748b; display: inline-flex; align-items: center; }
    .breadcrumb-clean .breadcrumb-item a { color: #64748b; text-decoration: none; padding: 2px 6px; border-radius: 6px; transition: 0.15s; }
    .breadcrumb-clean .breadcrumb-item a:hover { background: rgba(37,99,235,0.07); color: #2563eb; }
    .breadcrumb-clean .breadcrumb-item + .breadcrumb-item::before { content: "›"; margin-right: 4px; color: #cbd5e1; }
    .breadcrumb-clean .active { font-weight: 700; color: #2563eb; }

    .header-actions { display: flex; justify-content: space-between; align-items: center; margin-bottom: 22px; }
    .page-title { font-size: 1.45rem; font-weight: 800; margin: 0; }
    .btn-toolbar { display: flex; gap: 10px; }
    .btn { display: inline-flex; align-items: center; gap: 8px; padding: 10px 18px; border-radius: 10px; font-weight: 600; font-size: 0.9rem; border: none; cursor: pointer; transition: 0.2s ease; text-decoration: none; }
    .btn-danger { background: var(--danger); color: #fff; box-shadow: 0 5px 14px rgba(239,68,68,0.22); }
    .btn-danger:hover { background: #dc2626; transform: translateY(-2px); color: #fff; }
    .btn-success { background: var(--success); color: #fff; box-shadow: 0 5px 14px rgba(16,185,129,0.22); }
    .btn-success:hover { background: #059669; transform: translateY(-2px); color: #fff; }
    .btn-secondary { background: #f1f5f9; color: var(--muted); border: 1px solid var(--border); }
    .btn-secondary:hover { background: #e2e8f0; color: var(--text-main); }

    .card-box { background: var(--bg); border: 1px solid var(--border); border-radius: var(--radius); padding: 24px; margin-bottom: 22px; box-shadow: 0 1px 6px rgba(0,0,0,0.04); }
    .card-header-custom { background: var(--surface); padding: 14px 18px; border-radius: var(--radius) var(--radius) 0 0; font-weight: 700; font-size: 1rem; display: flex; align-items: center; gap: 10px; border-bottom: 1px solid var(--border); margin: -24px -24px 20px -24px; }

    .info-header { display: grid; grid-template-columns: 1fr 300px; gap: 24px; align-items: center; }
    .info-details h3 { font-size: 1.3rem; font-weight: 800; margin: 0 0 8px 0; text-transform: uppercase; letter-spacing: 0.5px; }
    .info-details h4 { font-size: 1.5rem; font-weight: 700; color: var(--primary); margin: 0 0 16px 0; }
    .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
    .info-item { font-size: 0.9rem; }
    .info-item strong { color: var(--text-main); font-weight: 700; }
    .info-item-text { color: var(--muted); font-size: 0.85rem; }

    .occupation-indicator { text-align: center; }
    .occupation-percent { font-size: 2.5rem; font-weight: 800; color: var(--primary); margin: 0 0 4px 0; }
    .occupation-label { font-size: 0.85rem; color: var(--muted); font-weight: 600; margin-bottom: 16px; display: block; }
    .progress-bar-custom { width: 100%; height: 32px; background: var(--surface); border-radius: 20px; overflow: hidden; border: 1px solid var(--border); position: relative; }
    .progress-fill { height: 100%; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.85rem; color: #fff; transition: width 0.3s ease; }
    .progress-fill.bg-success { background: var(--success); }
    .progress-fill.bg-warning { background: var(--warning); }
    .progress-fill.bg-danger { background: var(--danger); }

    .table-responsive { border: 1px solid var(--border); border-radius: var(--radius); overflow: hidden; margin-bottom: 22px; }
    .schedule-table { width: 100%; border-collapse: collapse; font-size: 0.85rem; }
    .schedule-table thead th { background: #0f172a; color: #fff; padding: 12px 8px; text-align: center; font-weight: 700; font-size: 0.85rem; border: 1px solid #1e293b; }
    .schedule-table tbody td { padding: 0; border: 1px solid var(--border); min-height: 80px; vertical-align: top; }
    .schedule-table .hour-cell { background: var(--surface); font-weight: 700; text-align: center; padding: 8px; width: 70px; }

    .schedule-block { min-height: 80px; padding: 8px; }
    .schedule-block.occupied { background: #fef3c7; border-left: 3px solid var(--warning); }
    .schedule-block.free { background: #d1fae5; display: flex; align-items: center; justify-content: center; text-align: center; }
    .schedule-block.free i { color: var(--success); font-size: 1.2rem; margin-bottom: 4px; }
    .schedule-block.free .free-label { font-size: 0.75rem; font-weight: 700; color: var(--success); }

    .block-subject { font-weight: 700; color: #92400e; font-size: 0.9em; margin-bottom: 4px; }
    .block-name { font-size: 0.75em; color: #333; margin-bottom: 6px; line-height: 1.3; }
    .block-info { font-size: 0.7em; color: #666; margin-bottom: 2px; }
    .block-info strong { color: #333; }
    .block-time { font-size: 0.65em; color: #999; border-top: 1px solid #e5e7eb; margin-top: 6px; padding-top: 4px; }

    .stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 22px; }
    .stat-card { background: var(--bg); border: 1px solid var(--border); border-radius: var(--radius); padding: 20px; text-align: center; box-shadow: 0 1px 6px rgba(0,0,0,0.04); }
    .stat-value { font-size: 2rem; font-weight: 800; margin: 0 0 8px 0; }
    .stat-value.text-warning { color: var(--warning); }
    .stat-value.text-success { color: var(--success); }
    .stat-value.text-info { color: var(--info); }
    .stat-value.text-primary { color: var(--primary); }
    .stat-label { font-size: 0.9rem; color: var(--muted); font-weight: 500; margin: 0; }

    .detail-table { width: 100%; border-collapse: collapse; font-size: 0.9rem; }
    .detail-table thead th { background: var(--surface); padding: 12px 14px; text-align: left; font-size: 0.85rem; color: var(--muted); text-transform: uppercase; border-bottom: 2px solid var(--border); font-weight: 700; }
    .detail-table tbody td { padding: 12px 14px; border-bottom: 1px solid var(--border); }
    .detail-table tbody tr:hover { background: var(--surface); }
    .detail-subject { font-weight: 700; color: var(--text-main); }
    .detail-subject-name { font-size: 0.85rem; color: var(--muted); display: block; margin-top: 2px; }

    .alert-box { padding: 14px 16px; border-radius: 10px; display: flex; align-items: center; gap: 12px; font-size: 0.95rem; }
    .alert-info { background: #dbeafe; border: 1px solid #93c5fd; color: #1e40af; }

    .legend-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; }
    .legend-section h6 { font-weight: 700; font-size: 0.95rem; margin-bottom: 12px; color: var(--text-main); }
    .legend-section ul { list-style: none; padding: 0; margin: 0; }
    .legend-section ul li { font-size: 0.9rem; margin-bottom: 8px; display: flex; align-items: center; gap: 8px; }
    .legend-section ul li span { font-size: 1.2rem; }

    .report-footer { margin-top: 30px; padding-top: 20px; border-top: 1px solid var(--border); text-align: center; font-size: 0.85rem; color: var(--muted); }
    .report-footer p { margin-bottom: 4px; }

    @media (max-width: 1200px) {
        .info-header { grid-template-columns: 1fr; }
        .stats-grid { grid-template-columns: repeat(2, 1fr); }
    }

    @media (max-width: 768px) {
        .header-actions { flex-direction: column; align-items: flex-start; gap: 12px; }
        .btn-toolbar { flex-wrap: wrap; width: 100%; }
        .btn { font-size: 0.85rem; padding: 8px 14px; }
        .stats-grid { grid-template-columns: 1fr; }
        .info-grid { grid-template-columns: 1fr; }
        .legend-grid { grid-template-columns: 1fr; }
        .schedule-table { font-size: 0.75rem; }
    }
</style>

<div class="page-container">

    <div class="breadcrumb-wrapper no-print">
        <div class="breadcrumb-clean">
            <span class="breadcrumb-item"><a href="<?php echo APP_URL; ?>">Inicio</a></span>
            <span class="breadcrumb-item"><a href="<?php echo APP_URL; ?>index.php?c=reportes">Reportes</a></span>
            <span class="breadcrumb-item active">Ocupación de Aula</span>
        </div>
    </div>

    <div class="header-actions no-print">
        <h1 class="page-title">Ocupación de Aula</h1>
        <div class="btn-toolbar">
            <!-- ✅ CORREGIDO: Redirige al controlador -->
            <a href="<?php echo APP_URL; ?>index.php?c=reportes&a=horarioAula&periodo=<?php echo $periodo['id']; ?>&aula=<?php echo $aula['id']; ?>&formato=pdf" 
               class="btn btn-danger" target="_blank">
                <i class="fa-solid fa-file-pdf"></i> Descargar PDF
            </a>
            <a href="<?php echo APP_URL; ?>index.php?c=reportes&a=horarioAula&periodo=<?php echo $periodo['id']; ?>&aula=<?php echo $aula['id']; ?>&formato=excel" 
               class="btn btn-success">
                <i class="fa-solid fa-file-excel"></i> Exportar Excel
            </a>
            <a href="<?php echo APP_URL; ?>index.php?c=reportes" class="btn btn-secondary">
                <i class="fa-solid fa-arrow-left"></i> Volver
            </a>
        </div>
    </div>

    <div class="card-box">
        <div class="info-header">
            <div class="info-details">
                <h3>OCUPACIÓN DE AULA</h3>
                <h4><?php echo $aula['edificio'] . '-' . $aula['numero']; ?></h4>
                <div class="info-grid">
                    <div>
                        <div class="info-item"><strong>Período:</strong> <?php echo $periodo['nombre']; ?></div>
                        <div class="info-item"><strong>Tipo:</strong> <?php echo ucfirst($aula['tipo']); ?></div>
                        <div class="info-item"><strong>Capacidad:</strong> <?php echo $aula['capacidad']; ?> personas</div>
                    </div>
                    <div>
                        <?php if (!empty($aula['equipamiento'])): ?>
                            <div class="info-item"><strong>Equipamiento:</strong></div>
                            <div class="info-item-text"><?php echo $aula['equipamiento']; ?></div>
                        <?php endif; ?>
                        <div class="info-item"><strong>Fecha:</strong> <?php echo date('d/m/Y'); ?></div>
                    </div>
                </div>
            </div>
            
            <div class="occupation-indicator">
                <div class="occupation-percent"><?php echo $porcentaje_ocupacion; ?>%</div>
                <span class="occupation-label">OCUPACIÓN</span>
                <div class="progress-bar-custom">
                    <?php
                    $color = 'success';
                    if ($porcentaje_ocupacion >= 80) {
                        $color = 'danger';
                    } elseif ($porcentaje_ocupacion >= 60) {
                        $color = 'warning';
                    }
                    ?>
                    <div class="progress-fill bg-<?php echo $color; ?>" style="width: <?php echo $porcentaje_ocupacion; ?>%">
                        <?php echo $bloques_ocupados; ?> / 70 bloques
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card-box" style="padding: 0;">
        <div class="table-responsive">
            <table class="schedule-table">
                <thead>
                    <tr>
                        <th style="width: 70px;">HORA</th>
                        <?php foreach ($dias_labels as $dia): ?>
                            <th><?php echo strtoupper($dia); ?></th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($matriz_horarios['horas'] as $hora): ?>
                        <tr>
                            <td class="hour-cell"><?php echo $hora; ?></td>
                            <?php foreach ($matriz_horarios['dias'] as $dia): ?>
                                <?php 
                                $horario = $matriz_horarios['matriz'][$dia][$hora];
                                $ocupado = ($horario !== null);
                                ?>
                                <td>
                                    <?php if ($horario): ?>
                                        <div class="schedule-block occupied">
                                            <div class="block-subject"><?php echo $horario['materia_clave']; ?></div>
                                            <div class="block-name">
                                                <?php echo substr($horario['materia_nombre'], 0, 30); ?>
                                                <?php if (strlen($horario['materia_nombre']) > 30) echo '...'; ?>
                                            </div>
                                            <div class="block-info"><strong>Grupo:</strong> <?php echo $horario['grupo_clave']; ?></div>
                                            <div class="block-info"><strong>Docente:</strong> <?php echo substr($horario['docente'], 0, 20); ?></div>
                                            <div class="block-info"><strong>Carrera:</strong> <?php echo substr($horario['carrera'], 0, 20); ?></div>
                                            <div class="block-time">
                                                <?php echo substr($horario['hora_inicio'], 0, 5); ?> - <?php echo substr($horario['hora_fin'], 0, 5); ?>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <div class="schedule-block free">
                                            <div><i class="fa-solid fa-check-circle"></i><div class="free-label">LIBRE</div></div>
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

    <div class="stats-grid no-print">
        <div class="stat-card">
            <h3 class="stat-value text-warning"><?php echo $bloques_ocupados; ?></h3>
            <p class="stat-label">Bloques Ocupados</p>
        </div>
        <div class="stat-card">
            <h3 class="stat-value text-success"><?php echo (70 - $bloques_ocupados); ?></h3>
            <p class="stat-label">Bloques Libres</p>
        </div>
        <div class="stat-card">
            <h3 class="stat-value text-info"><?php echo count(array_unique(array_column($horarios, 'docente'))); ?></h3>
            <p class="stat-label">Docentes Diferentes</p>
        </div>
        <div class="stat-card">
            <h3 class="stat-value text-primary"><?php echo count(array_unique(array_column($horarios, 'materia_id'))); ?></h3>
            <p class="stat-label">Materias Diferentes</p>
        </div>
    </div>

    <div class="card-box">
        <div class="card-header-custom"><i class="fa-solid fa-list"></i> Detalle de Asignaciones</div>
        
        <?php if (empty($horarios)): ?>
            <div class="alert-box alert-info">
                <i class="fa-solid fa-info-circle"></i>
                <span>El aula no tiene asignaciones en este período.</span>
            </div>
        <?php else: ?>
            <div class="table-responsive" style="border: none;">
                <table class="detail-table">
                    <thead>
                        <tr>
                            <th>Día</th><th>Horario</th><th>Materia</th><th>Grupo</th><th>Docente</th><th>Carrera</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($horarios as $horario): ?>
                        <tr>
                            <td><?php echo ucfirst($horario['dia']); ?></td>
                            <td><?php echo substr($horario['hora_inicio'], 0, 5); ?> - <?php echo substr($horario['hora_fin'], 0, 5); ?></td>
                            <td>
                                <span class="detail-subject"><?php echo $horario['materia_clave']; ?></span>
                                <span class="detail-subject-name"><?php echo $horario['materia_nombre']; ?></span>
                            </td>
                            <td><?php echo $horario['grupo_clave']; ?></td>
                            <td><?php echo $horario['docente']; ?></td>
                            <td><span style="font-size: 0.85rem;"><?php echo $horario['carrera']; ?></span></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

    <div class="card-box no-print">
        <div class="card-header-custom"><i class="fa-solid fa-info-circle"></i> Leyenda</div>
        <div class="legend-grid">
            <div class="legend-section">
                <h6>Colores:</h6>
                <ul>
                    <li><span style="color: #10b981;">●</span> <span><strong>Verde:</strong> Bloque libre (disponible)</span></li>
                    <li><span style="color: #f59e0b;">●</span> <span><strong>Amarillo:</strong> Bloque ocupado</span></li>
                </ul>
            </div>
            <div class="legend-section">
                <h6>Nivel de Ocupación:</h6>
                <ul>
                    <li><strong>0% - 59%:</strong> Ocupación baja (verde)</li>
                    <li><strong>60% - 79%:</strong> Ocupación media (amarillo)</li>
                    <li><strong>80% - 100%:</strong> Ocupación alta (rojo)</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="report-footer">
        <p> Reporte de Ocupación de Aula</p>
        <p>Generado el <?php echo date('d/m/Y H:i'); ?> | Total de bloques semanales: 70 (5 días × 14 horas)</p>
    </div>

</div>