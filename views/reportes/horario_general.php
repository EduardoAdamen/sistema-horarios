<?php

$page_title = 'Reporte: Horario General';


$dias_labels = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes'];


$dias_keys   = ['lunes', 'martes', 'miercoles', 'jueves', 'viernes'];
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
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

    /* ------------------------ PRINT STYLES ------------------------ */
    @media print {
        .no-print { display: none !important; }
        .card-box { border: 1px solid #000 !important; box-shadow: none !important; }
        body { font-size: 10pt; -webkit-print-color-adjust: exact; }
        table { font-size: 9pt; }
        .page-container { padding: 10px; }
        .bloque-horario-print { border: 1px solid #ccc !important; page-break-inside: avoid; }
        .header-actions { display: none; }
        /* Forzar colores de fondo al imprimir */
        .hour-cell { background-color: #f0f0f0 !important; }
        thead th { background-color: #0f172a !important; color: white !important; }
    }

    /* ------------------------ BREADCRUMB ------------------------ */
    .breadcrumb-wrapper { margin-bottom: 16px; }
    .breadcrumb-clean {
        display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px;
        background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px;
        font-size: 0.93rem; font-weight: 500;
    }
    .breadcrumb-clean .breadcrumb-item a { color: #64748b; text-decoration: none; padding: 2px 6px; border-radius: 6px; transition: 0.15s; }
    .breadcrumb-clean .breadcrumb-item a:hover { background: rgba(37,99,235,0.07); color: #2563eb; }
    .breadcrumb-clean .breadcrumb-item + .breadcrumb-item::before { content: "›"; margin-right: 4px; color: #cbd5e1; }
    .breadcrumb-clean .active { font-weight: 700; color: #2563eb; }

    /* ------------------------ HEADER ------------------------ */
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

    /* ------------------------ CARD ------------------------ */
    .card-box { background: var(--bg); border: 1px solid var(--border); border-radius: var(--radius); padding: 24px; margin-bottom: 22px; box-shadow: 0 1px 6px rgba(0,0,0,0.04); }
    .card-header-custom { background: var(--surface); padding: 14px 18px; border-radius: var(--radius) var(--radius) 0 0; font-weight: 700; font-size: 1rem; display: flex; align-items: center; gap: 10px; border-bottom: 1px solid var(--border); margin: -24px -24px 20px -24px; }

    /* ------------------------ REPORT HEADER ------------------------ */
    .report-header { text-align: center; }
    .report-header h3 { font-size: 1.3rem; font-weight: 800; margin: 0 0 8px 0; text-transform: uppercase; letter-spacing: 0.5px; }
    .report-header h4 { font-size: 1.5rem; font-weight: 700; color: var(--primary); margin: 0 0 20px 0; }
    .report-info { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; text-align: center; }
    .report-info-item strong { color: var(--text-main); font-weight: 700; }

    /* ------------------------ SCHEDULE TABLE ------------------------ */
    .table-responsive { border: 1px solid var(--border); border-radius: var(--radius); overflow: hidden; margin-bottom: 22px; }
    .schedule-table { width: 100%; border-collapse: collapse; font-size: 0.85rem; table-layout: fixed; }
    .schedule-table thead th { background: #0f172a; color: #fff; padding: 12px 8px; text-align: center; font-weight: 700; font-size: 0.85rem; border: 1px solid #1e293b; }
    .schedule-table tbody td { padding: 0; border: 1px solid var(--border); height: 80px; vertical-align: top; }
    .schedule-table .hour-cell { background: var(--surface); font-weight: 700; text-align: center; padding: 8px; width: 70px; vertical-align: middle; }

    /* Bloques de horario */
    .schedule-block { border-left: 3px solid var(--primary); padding: 8px; background: #f8fafc; height: 100%; box-sizing: border-box; display: flex; flex-direction: column; justify-content: center; }
    .block-subject { font-weight: 700; color: var(--primary); font-size: 0.9em; margin-bottom: 4px; line-height: 1.2; }
    .block-name { font-size: 0.75em; color: #333; margin-bottom: 6px; line-height: 1.3; }
    .block-info { font-size: 0.7em; color: #666; margin-bottom: 2px; }
    .block-info strong { color: #333; }
    .block-time { font-size: 0.65em; color: #999; border-top: 1px solid #e5e7eb; margin-top: 6px; padding-top: 4px; }

    /* ------------------------ STATS GRID ------------------------ */
    .stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 22px; }
    .stat-card { background: var(--bg); border: 1px solid var(--border); border-radius: var(--radius); padding: 20px; text-align: center; box-shadow: 0 1px 6px rgba(0,0,0,0.04); }
    .stat-value { font-size: 2rem; font-weight: 800; margin: 0 0 8px 0; }
    .text-primary { color: var(--primary); } .text-success { color: var(--success); } .text-info { color: var(--info); } .text-warning { color: var(--warning); }
    .stat-label { font-size: 0.9rem; color: var(--muted); font-weight: 500; margin: 0; }

    /* ------------------------ FOOTER ------------------------ */
    .report-footer { margin-top: 30px; padding-top: 20px; border-top: 1px solid var(--border); text-align: center; font-size: 0.85rem; color: var(--muted); }

    @media (max-width: 768px) {
        .stats-grid, .report-info { grid-template-columns: 1fr; }
        .header-actions { flex-direction: column; align-items: flex-start; }
    }
</style>

<div class="page-container">

    <div class="breadcrumb-wrapper no-print">
        <div class="breadcrumb-clean">
            <span class="breadcrumb-item"><a href="<?php echo APP_URL; ?>">Inicio</a></span>
            <span class="breadcrumb-item"><a href="<?php echo APP_URL; ?>index.php?c=reportes">Reportes</a></span>
            <span class="breadcrumb-item active">Horario General</span>
        </div>
    </div>

    <div class="header-actions no-print">
        <h1 class="page-title">Horario General</h1>
        <div class="btn-toolbar">
            
            <a href="<?php echo APP_URL; ?>index.php?c=reportes&a=horarioGeneral&periodo=<?php echo $periodo_id ?? ''; ?>&carrera=<?php echo $carrera_id ?? ''; ?>&semestre=<?php echo $semestre_id ?? ''; ?>&formato=pdf" class="btn btn-danger" target="_blank">
                <i class="fa-solid fa-file-pdf"></i> Descargar PDF
            </a>
            <a href="<?php echo APP_URL; ?>index.php?c=reportes&a=horarioGeneral&periodo=<?php echo $periodo_id ?? ''; ?>&carrera=<?php echo $carrera_id ?? ''; ?>&semestre=<?php echo $semestre_id ?? ''; ?>&formato=excel" class="btn btn-success">
                <i class="fa-solid fa-file-excel"></i> Exportar Excel
            </a>
            <a href="<?php echo APP_URL; ?>index.php?c=reportes" class="btn btn-secondary">
                <i class="fa-solid fa-arrow-left"></i> Volver
            </a>
        </div>
    </div>

    <div class="card-box">
        <div class="report-header">
            <h3>HORARIO GENERAL</h3>
            <h4><?php echo $info['carrera_nombre'] ?? 'Carrera no definida'; ?></h4>
            <div class="report-info">
                <div class="report-info-item"><strong>Período:</strong> <?php echo $info['periodo_nombre'] ?? ''; ?></div>
                <div class="report-info-item"><strong>Semestre:</strong> <?php echo $info['semestre_nombre'] ?? ''; ?></div>
                <div class="report-info-item"><strong>Fecha:</strong> <?php echo date('d/m/Y'); ?></div>
            </div>
        </div>
    </div>

    <div class="card-box" style="padding: 0;">
        <div class="table-responsive">
            <table class="schedule-table">
                <thead>
                    <tr>
                        <th style="width: 70px;">HORA</th>
                        <?php foreach ($dias_labels as $dia_label): ?>
                            <th><?php echo strtoupper($dia_label); ?></th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    // Verificamos si hay datos en la matriz
                    if (isset($matriz_horarios['horas']) && is_array($matriz_horarios['horas'])):
                        foreach ($matriz_horarios['horas'] as $hora): 
                    ?>
                        <tr>
                            <td class="hour-cell">
                                <?php echo substr($hora, 0, 5); // Mostrar solo HH:MM ?>
                            </td>

                            <?php foreach ($dias_keys as $dia_key): ?>
                                <td>
                                    <?php 
                                    // Intentamos obtener el horario usando la clave correcta (ej: 'lunes') y la hora
                                    $horario = $matriz_horarios['matriz'][$dia_key][$hora] ?? null;

                                    if ($horario): 
                                    ?>
                                        <div class="schedule-block bloque-horario-print">
                                            <div class="block-subject">
                                                <?php echo $horario['materia_clave']; ?>
                                            </div>
                                            <div class="block-name">
                                                <?php echo substr($horario['materia_nombre'], 0, 40); ?>
                                                <?php echo (strlen($horario['materia_nombre']) > 40) ? '...' : ''; ?>
                                            </div>
                                            <div class="block-info">
                                                <strong>Gpo:</strong> <?php echo $horario['grupo_clave']; ?>
                                            </div>
                                            <div class="block-info">
                                                <strong>Prof:</strong> <?php echo substr($horario['docente'], 0, 20); ?>
                                            </div>
                                            <div class="block-info">
                                                <strong>Aula:</strong> <?php echo $horario['aula'] ?? 'S/A'; ?>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </td>
                            <?php endforeach; ?>
                        </tr>
                    <?php 
                        endforeach; 
                    else:
                    ?>
                        <tr>
                            <td colspan="6" style="padding: 20px; text-align: center;">No hay horarios generados para mostrar.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="card-box no-print">
        <div class="card-header-custom">
            <i class="fa-solid fa-chart-bar"></i> Resumen Estadístico
        </div>
        
        <div class="stats-grid">
            <div class="stat-card">
                <h3 class="stat-value text-primary"><?php echo count($horarios ?? []); ?></h3>
                <p class="stat-label">Total de Bloques</p>
            </div>
            
            <div class="stat-card">
                <?php
                $materias_unicas = [];
                if (!empty($horarios)) {
                    foreach ($horarios as $h) {
                        $materias_unicas[$h['materia_id']] = true;
                    }
                }
                ?>
                <h3 class="stat-value text-success"><?php echo count($materias_unicas); ?></h3>
                <p class="stat-label">Materias Diferentes</p>
            </div>
            
            <div class="stat-card">
                <?php
                $docentes_unicos = [];
                if (!empty($horarios)) {
                    foreach ($horarios as $h) {
                        if (!empty($h['docente'])) $docentes_unicos[$h['docente']] = true;
                    }
                }
                ?>
                <h3 class="stat-value text-info"><?php echo count($docentes_unicos); ?></h3>
                <p class="stat-label">Docentes Involucrados</p>
            </div>
            
            <div class="stat-card">
                <?php
                $aulas_unicas = [];
                if (!empty($horarios)) {
                    foreach ($horarios as $h) {
                        if (!empty($h['aula'])) $aulas_unicas[$h['aula']] = true;
                    }
                }
                ?>
                <h3 class="stat-value text-warning"><?php echo count($aulas_unicas); ?></h3>
                <p class="stat-label">Aulas Utilizadas</p>
            </div>
        </div>
    </div>

    <div class="report-footer">
        <p><strong>Generado el <?php echo date('d/m/Y H:i'); ?></strong></p>
        <p>Este horario es oficial y ha sido conciliado por el Jefe de Departamento</p>
    </div>

</div>