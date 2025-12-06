<?php


$page_title = 'Reporte: Carga Horaria de Docentes';
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    
    :root {
        --primary: #2563eb; --primary-hover: #1d4ed8; --success: #10b981; --warning: #f59e0b; --danger: #ef4444;
        --info: #0ea5e9; --muted: #64748b; --text-main: #0f172a; --bg: #ffffff; --surface: #f8fafc;
        --border: #e2e8f0; --radius: 12px;
    }

    .page-container { font-family: "Open Sans", system-ui, Helvetica; padding: 22px; color: var(--text-main); }

    @media print {
        .no-print { display: none !important; }
        .card-box { border: 1px solid #000 !important; box-shadow: none !important; page-break-inside: avoid; }
        body { font-size: 10pt; }
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

    .report-header { text-align: center; }
    .report-header h3 { font-size: 1.3rem; font-weight: 800; margin: 0 0 8px 0; text-transform: uppercase; letter-spacing: 0.5px; }
    .report-header h5 { font-size: 1.2rem; font-weight: 700; color: var(--primary); margin: 0 0 12px 0; }
    .report-date { font-size: 0.95rem; color: var(--muted); }
    .report-date strong { color: var(--text-main); }

    .table-responsive { border: 1px solid var(--border); border-radius: var(--radius); overflow: hidden; }
    .carga-table { width: 100%; border-collapse: collapse; font-size: 0.9rem; }
    .carga-table thead th { background: #0f172a; color: #fff; padding: 12px 14px; text-align: left; font-weight: 700; font-size: 0.85rem; border: 1px solid #1e293b; }
    .carga-table thead th.text-center { text-align: center; }
    .carga-table tbody td { padding: 12px 14px; border: 1px solid var(--border); background: #fff; }
    .carga-table tbody tr:hover td { background: var(--surface); }
    .carga-table tfoot td { background: var(--surface); font-weight: 700; padding: 12px 14px; border: 1px solid var(--border); }

    .badge-type { padding: 4px 10px; border-radius: 6px; font-size: 0.75rem; font-weight: 700; background: #f1f5f9; color: var(--muted); display: inline-block; }

    .progress-custom { height: 28px; background: #f1f5f9; border-radius: 8px; overflow: hidden; position: relative; border: 1px solid var(--border); }
    .progress-bar-custom { height: 100%; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.8rem; color: #fff; transition: width 0.3s ease; }
    .progress-bar-custom.bg-secondary { background: #94a3b8; }
    .progress-bar-custom.bg-info { background: var(--info); }
    .progress-bar-custom.bg-success { background: var(--success); }
    .progress-bar-custom.bg-warning { background: var(--warning); }
    .progress-bar-custom.bg-danger { background: var(--danger); }

    .badge-estado { padding: 5px 12px; border-radius: 6px; font-size: 0.75rem; font-weight: 700; display: inline-block; }
    .badge-estado.bg-secondary { background: #94a3b8; color: #fff; }
    .badge-estado.bg-info { background: #dbeafe; color: #1e40af; }
    .badge-estado.bg-success { background: #d1fae5; color: #065f46; }
    .badge-estado.bg-warning { background: #fef3c7; color: #92400e; }
    .badge-estado.bg-danger { background: #fee2e2; color: #991b1b; }

    .stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 22px; }
    .stat-card { background: var(--bg); border: 1px solid var(--border); border-radius: var(--radius); padding: 20px; text-align: center; box-shadow: 0 1px 6px rgba(0,0,0,0.04); }
    .stat-value { font-size: 2rem; font-weight: 800; margin: 0 0 8px 0; }
    .stat-value.text-primary { color: var(--primary); }
    .stat-value.text-success { color: var(--success); }
    .stat-value.text-warning { color: var(--warning); }
    .stat-value.text-info { color: var(--info); }
    .stat-label { font-size: 0.9rem; color: var(--muted); font-weight: 500; margin: 0; }

    .legend-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; }
    .legend-section h6 { font-weight: 700; font-size: 0.95rem; margin-bottom: 12px; color: var(--text-main); }
    .legend-section ul { list-style: none; padding: 0; margin: 0; }
    .legend-section ul li { font-size: 0.9rem; margin-bottom: 8px; display: flex; align-items: center; gap: 8px; }

    @media (max-width: 1200px) { .stats-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 768px) {
        .header-actions { flex-direction: column; align-items: flex-start; gap: 12px; }
        .btn-toolbar { flex-wrap: wrap; width: 100%; }
        .btn { font-size: 0.85rem; padding: 8px 14px; }
        .stats-grid { grid-template-columns: 1fr; }
        .legend-grid { grid-template-columns: 1fr; }
        .carga-table { font-size: 0.8rem; }
        .progress-custom { height: 24px; }
    }
</style>

<div class="page-container">

    <div class="breadcrumb-wrapper no-print">
        <div class="breadcrumb-clean">
            <span class="breadcrumb-item"><a href="<?php echo APP_URL; ?>">Inicio</a></span>
            <span class="breadcrumb-item"><a href="<?php echo APP_URL; ?>index.php?c=reportes">Reportes</a></span>
            <span class="breadcrumb-item active">Carga Horaria</span>
        </div>
    </div>

    <div class="header-actions no-print">
        <h1 class="page-title">Carga Horaria de Docentes</h1>
        <div class="btn-toolbar">
            <!-- ✅ CORREGIDO: Redirige al controlador -->
            <a href="<?php echo APP_URL; ?>index.php?c=reportes&a=cargaDocentes&periodo=<?php echo $periodo['id']; ?>&formato=pdf" 
               class="btn btn-danger" target="_blank">
                <i class="fa-solid fa-file-pdf"></i> Descargar PDF
            </a>
            <a href="<?php echo APP_URL; ?>index.php?c=reportes&a=cargaDocentes&periodo=<?php echo $periodo['id']; ?>&formato=excel" 
               class="btn btn-success">
                <i class="fa-solid fa-file-excel"></i> Exportar Excel
            </a>
            <a href="<?php echo APP_URL; ?>index.php?c=reportes" class="btn btn-secondary">
                <i class="fa-solid fa-arrow-left"></i> Volver
            </a>
        </div>
    </div>

    <div class="card-box">
        <div class="report-header">
            <h3>REPORTE DE CARGA HORARIA DE DOCENTES</h3>
            <h5><?php echo $periodo['nombre']; ?></h5>
            <p class="report-date"><strong>Fecha de generación:</strong> <?php echo date('d/m/Y H:i'); ?></p>
        </div>
    </div>

    <div class="card-box" style="padding: 0;">
        <div class="table-responsive">
            <table class="carga-table">
                <thead>
                    <tr>
                        <th>No. Empleado</th><th>Docente</th><th>Tipo</th>
                        <th class="text-center">Horas Máx</th><th class="text-center">Horas Asignadas</th>
                        <th class="text-center">Bloques</th><th class="text-center">Materias</th>
                        <th class="text-center">% Carga</th><th class="text-center">Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $total_docentes = 0;
                    $total_horas_asignadas = 0;
                    $total_horas_maximas = 0;
                    
                    foreach ($docentes as $docente): 
                        $horas_asignadas = $docente['horas_asignadas'] ?? 0;
                        $porcentaje = ($docente['horas_max_semana'] > 0) ? ($horas_asignadas / $docente['horas_max_semana']) * 100 : 0;
                        
                        $total_docentes++;
                        $total_horas_asignadas += $horas_asignadas;
                        $total_horas_maximas += $docente['horas_max_semana'];
                        
                        $color_barra = 'secondary'; $estado_texto = 'Sin asignar';
                        if ($porcentaje >= 90) { $color_barra = 'danger'; $estado_texto = 'Sobrecargado'; }
                        elseif ($porcentaje >= 70) { $color_barra = 'warning'; $estado_texto = 'Carga alta'; }
                        elseif ($porcentaje >= 40) { $color_barra = 'success'; $estado_texto = 'Carga normal'; }
                        elseif ($porcentaje > 0) { $color_barra = 'info'; $estado_texto = 'Carga baja'; }
                    ?>
                    <tr>
                        <td><strong><?php echo $docente['numero_empleado']; ?></strong></td>
                        <td><?php echo $docente['docente']; ?></td>
                        <td><span class="badge-type"><?php echo ucfirst(str_replace('_', ' ', $docente['tipo'])); ?></span></td>
                        <td class="text-center"><?php echo $docente['horas_max_semana']; ?></td>
                        <td class="text-center"><strong><?php echo $horas_asignadas; ?></strong></td>
                        <td class="text-center"><?php echo $docente['num_bloques']; ?></td>
                        <td class="text-center"><?php echo $docente['num_materias']; ?></td>
                        <td class="text-center">
                            <div class="progress-custom">
                                <div class="progress-bar-custom bg-<?php echo $color_barra; ?>" style="width: <?php echo min($porcentaje, 100); ?>%">
                                    <?php echo round($porcentaje, 1); ?>%
                                </div>
                            </div>
                        </td>
                        <td class="text-center">
                            <span class="badge-estado bg-<?php echo $color_barra; ?>"><?php echo $estado_texto; ?></span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" style="text-align: right;"><strong>TOTALES:</strong></td>
                        <td class="text-center"><strong><?php echo $total_horas_maximas; ?></strong></td>
                        <td class="text-center"><strong><?php echo $total_horas_asignadas; ?></strong></td>
                        <td colspan="4"></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <div class="stats-grid no-print">
        <div class="stat-card">
            <h3 class="stat-value text-primary"><?php echo $total_docentes; ?></h3>
            <p class="stat-label">Total Docentes</p>
        </div>
        <div class="stat-card">
            <h3 class="stat-value text-success"><?php echo $total_horas_asignadas; ?></h3>
            <p class="stat-label">Horas Asignadas</p>
        </div>
        <div class="stat-card">
            <h3 class="stat-value text-warning"><?php echo $total_horas_maximas; ?></h3>
            <p class="stat-label">Capacidad Total</p>
        </div>
        <div class="stat-card">
            <?php 
            $porcentaje_global = ($total_horas_maximas > 0) ? round(($total_horas_asignadas / $total_horas_maximas) * 100, 1) : 0;
            ?>
            <h3 class="stat-value text-info"><?php echo $porcentaje_global; ?>%</h3>
            <p class="stat-label">Ocupación Global</p>
        </div>
    </div>

    <div class="card-box no-print">
        <div class="card-header-custom"><i class="fa-solid fa-info-circle"></i> Leyenda</div>
        <div class="legend-grid">
            <div class="legend-section">
                <h6>Estados de Carga:</h6>
                <ul>
                    <li><span class="badge-estado bg-secondary">Sin asignar</span> <span>- 0% de carga</span></li>
                    <li><span class="badge-estado bg-info">Carga baja</span> <span>- Menos del 40%</span></li>
                    <li><span class="badge-estado bg-success">Carga normal</span> <span>- 40% a 69%</span></li>
                    <li><span class="badge-estado bg-warning">Carga alta</span> <span>- 70% a 89%</span></li>
                    <li><span class="badge-estado bg-danger">Sobrecargado</span> <span>- 90% o más</span></li>
                </ul>
            </div>
            <div class="legend-section">
                <h6>Tipos de Contratación:</h6>
                <ul>
                    <li><strong>Tiempo Completo:</strong> 40 horas semanales</li>
                    <li><strong>Medio Tiempo:</strong> 20 horas semanales</li>
                    <li><strong>Asignatura:</strong> Horas variables</li>
                </ul>
            </div>
        </div>
    </div>

</div>