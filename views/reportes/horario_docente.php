<?php
// =====================================================
// views/reportes/horario_docente.php
// Reporte de horario por docente - BOTONES CORREGIDOS
// =====================================================

$page_title = 'Reporte: Horario por Docente';
$dias_labels = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes'];
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
        body { font-size: 10pt; }
        table { font-size: 9pt; }
        .page-container { padding: 10px; }
        .bloque-horario-print { border: 1px solid #000 !important; page-break-inside: avoid; }
    }

    /* ------------------------ BREADCRUMB ------------------------ */
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

    /* ------------------------ HEADER ------------------------ */
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
    }

    .btn-toolbar {
        display: flex;
        gap: 10px;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 18px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.9rem;
        border: none;
        cursor: pointer;
        transition: 0.2s ease;
        text-decoration: none;
    }

    .btn-danger {
        background: var(--danger);
        color: #fff;
        box-shadow: 0 5px 14px rgba(239,68,68,0.22);
    }

    .btn-danger:hover {
        background: #dc2626;
        transform: translateY(-2px);
        color: #fff;
    }

    .btn-success {
        background: var(--success);
        color: #fff;
        box-shadow: 0 5px 14px rgba(16,185,129,0.22);
    }

    .btn-success:hover {
        background: #059669;
        transform: translateY(-2px);
        color: #fff;
    }

    .btn-secondary {
        background: #f1f5f9;
        color: var(--muted);
        border: 1px solid var(--border);
    }

    .btn-secondary:hover {
        background: #e2e8f0;
        color: var(--text-main);
    }

    /* ------------------------ CARD ------------------------ */
    .card-box {
        background: var(--bg);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 24px;
        margin-bottom: 22px;
        box-shadow: 0 1px 6px rgba(0,0,0,0.04);
    }

    .card-header-custom {
        background: var(--surface);
        padding: 14px 18px;
        border-radius: var(--radius) var(--radius) 0 0;
        font-weight: 700;
        font-size: 1rem;
        display: flex;
        align-items: center;
        gap: 10px;
        border-bottom: 1px solid var(--border);
        margin: -24px -24px 20px -24px;
    }

    /* ------------------------ REPORT HEADER ------------------------ */
    .report-header {
        display: grid;
        grid-template-columns: 1fr 250px;
        gap: 24px;
        align-items: center;
    }

    .docente-info h3 {
        font-size: 1.3rem;
        font-weight: 800;
        margin: 0 0 8px 0;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .docente-info h4 {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--primary);
        margin: 0 0 16px 0;
    }

    .info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
    }

    .info-item {
        font-size: 0.9rem;
    }

    .info-item strong {
        color: var(--text-main);
        font-weight: 700;
    }

    /* ------------------------ HOURS CARD ------------------------ */
    .hours-card {
        background: linear-gradient(135deg, var(--primary) 0%, var(--info) 100%);
        border-radius: var(--radius);
        padding: 20px;
        text-align: center;
        color: #fff;
    }

    .hours-value {
        font-size: 2.5rem;
        font-weight: 800;
        margin: 0 0 4px 0;
    }

    .hours-label {
        font-size: 0.9rem;
        font-weight: 600;
        opacity: 0.95;
    }

    .hours-detail {
        margin-top: 12px;
        padding-top: 12px;
        border-top: 1px solid rgba(255,255,255,0.3);
        font-size: 0.85rem;
    }

    /* ------------------------ SCHEDULE TABLE ------------------------ */
    .table-responsive {
        border: 1px solid var(--border);
        border-radius: var(--radius);
        overflow: hidden;
        margin-bottom: 22px;
    }

    .schedule-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.85rem;
    }

    .schedule-table thead th {
        background: #0f172a;
        color: #fff;
        padding: 12px 8px;
        text-align: center;
        font-weight: 700;
        font-size: 0.85rem;
        border: 1px solid #1e293b;
    }

    .schedule-table tbody td {
        padding: 0;
        border: 1px solid var(--border);
        min-height: 80px;
        vertical-align: top;
    }

    .schedule-table .hour-cell {
        background: var(--surface);
        font-weight: 700;
        text-align: center;
        padding: 8px;
        width: 70px;
    }

    /* Bloques de horario */
    .schedule-block {
        border-left: 3px solid var(--success);
        padding: 8px;
        background: #d1fae5;
        min-height: 80px;
    }

    .block-subject {
        font-weight: 700;
        color: #065f46;
        font-size: 0.9em;
        margin-bottom: 4px;
    }

    .block-name {
        font-size: 0.75em;
        color: #333;
        margin-bottom: 6px;
        line-height: 1.3;
    }

    .block-info {
        font-size: 0.7em;
        color: #666;
        margin-bottom: 2px;
    }

    .block-info strong {
        color: #333;
    }

    .block-time {
        font-size: 0.65em;
        color: #999;
        border-top: 1px solid #a7f3d0;
        margin-top: 6px;
        padding-top: 4px;
    }

    /* ------------------------ DETAIL TABLE ------------------------ */
    .detail-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.9rem;
    }

    .detail-table thead th {
        background: var(--surface);
        padding: 12px 14px;
        text-align: left;
        font-size: 0.85rem;
        color: var(--muted);
        text-transform: uppercase;
        border-bottom: 2px solid var(--border);
        font-weight: 700;
    }

    .detail-table tbody td {
        padding: 12px 14px;
        border-bottom: 1px solid var(--border);
    }

    .detail-table tbody tr:hover {
        background: var(--surface);
    }

    .detail-subject {
        font-weight: 700;
        color: var(--text-main);
    }

    .detail-subject-name {
        font-size: 0.85rem;
        color: var(--muted);
        display: block;
        margin-top: 2px;
    }

    /* ------------------------ ALERT ------------------------ */
    .alert-box {
        padding: 14px 16px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        gap: 12px;
        font-size: 0.95rem;
    }

    .alert-info {
        background: #dbeafe;
        border: 1px solid #93c5fd;
        color: #1e40af;
    }

    /* ------------------------ FOOTER ------------------------ */
    .report-footer {
        margin-top: 30px;
        padding-top: 20px;
        border-top: 1px solid var(--border);
        text-align: center;
        font-size: 0.85rem;
        color: var(--muted);
    }

    .report-footer p {
        margin-bottom: 4px;
    }

    /* ------------------------ RESPONSIVE ------------------------ */
    @media (max-width: 1200px) {
        .report-header {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .header-actions {
            flex-direction: column;
            align-items: flex-start;
            gap: 12px;
        }

        .btn-toolbar {
            flex-wrap: wrap;
            width: 100%;
        }

        .btn {
            font-size: 0.85rem;
            padding: 8px 14px;
        }

        .info-grid {
            grid-template-columns: 1fr;
        }

        .schedule-table {
            font-size: 0.75rem;
        }
    }
</style>

<div class="page-container">

    <!-- BREADCRUMB -->
    <div class="breadcrumb-wrapper no-print">
        <div class="breadcrumb-clean">
            <span class="breadcrumb-item">
                <a href="<?php echo APP_URL; ?>">Inicio</a>
            </span>
            <span class="breadcrumb-item">
                <a href="<?php echo APP_URL; ?>index.php?c=reportes">Reportes</a>
            </span>
            <span class="breadcrumb-item active">Horario por Docente</span>
        </div>
    </div>

    <!-- HEADER -->
    <div class="header-actions no-print">
        <h1 class="page-title">
            Horario por Docente
        </h1>
        <div class="btn-toolbar">
            <!-- ✅ CORREGIDO: Redirige al controlador con formato=pdf -->
            <a href="<?php echo APP_URL; ?>index.php?c=reportes&a=horarioDocente&periodo=<?php echo $periodo['id']; ?>&docente=<?php echo $docente['id']; ?>&formato=pdf" 
               class="btn btn-danger" target="_blank">
                <i class="fa-solid fa-file-pdf"></i> Descargar PDF
            </a>
            <a href="<?php echo APP_URL; ?>index.php?c=reportes&a=horarioDocente&periodo=<?php echo $periodo['id']; ?>&docente=<?php echo $docente['id']; ?>&formato=excel" 
               class="btn btn-success">
                <i class="fa-solid fa-file-excel"></i> Exportar Excel
            </a>
            <a href="<?php echo APP_URL; ?>index.php?c=reportes" class="btn btn-secondary">
                <i class="fa-solid fa-arrow-left"></i> Volver
            </a>
        </div>
    </div>

    <!-- ENCABEZADO DEL REPORTE -->
    <div class="card-box">
        <div class="report-header">
            <div class="docente-info">
                <h3>HORARIO DEL DOCENTE</h3>
                <h4><?php echo $docente['nombre'] . ' ' . $docente['apellido_paterno'] . ' ' . ($docente['apellido_materno'] ?? ''); ?></h4>
                <div class="info-grid">
                    <div class="info-item">
                        <strong>No. Empleado:</strong> <?php echo $docente['numero_empleado']; ?>
                    </div>
                    <div class="info-item">
                        <strong>Tipo:</strong> <?php echo ucfirst(str_replace('_', ' ', $docente['tipo'])); ?>
                    </div>
                    <div class="info-item">
                        <strong>Período:</strong> <?php echo $periodo['nombre']; ?>
                    </div>
                    <div class="info-item">
                        <strong>Fecha:</strong> <?php echo date('d/m/Y'); ?>
                    </div>
                </div>
            </div>
            
            <div class="hours-card">
                <div class="hours-value"><?php echo round($total_horas, 1); ?></div>
                <div class="hours-label">HORAS SEMANALES</div>
                <div class="hours-detail">
                    Máximo: <?php echo $docente['horas_max_semana']; ?> hrs
                    <br>
                    <?php 
                    $porcentaje = ($docente['horas_max_semana'] > 0) 
                        ? round(($total_horas / $docente['horas_max_semana']) * 100, 1) 
                        : 0;
                    echo $porcentaje . '% de carga';
                    ?>
                </div>
            </div>
        </div>
    </div>

    <!-- TABLA DE HORARIO -->
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
                            <td class="hour-cell">
                                <?php echo $hora; ?>
                            </td>
                            <?php foreach ($matriz_horarios['dias'] as $dia): ?>
                                <td>
                                    <?php 
                                    $horario = $matriz_horarios['matriz'][$dia][$hora];
                                    if ($horario): 
                                    ?>
                                        <div class="schedule-block bloque-horario-print">
                                            <div class="block-subject">
                                                <?php echo $horario['materia_clave']; ?>
                                            </div>
                                            <div class="block-name">
                                                <?php echo substr($horario['materia_nombre'], 0, 30); ?>
                                                <?php if (strlen($horario['materia_nombre']) > 30) echo '...'; ?>
                                            </div>
                                            <div class="block-info">
                                                <strong>Grupo:</strong> <?php echo $horario['grupo_clave']; ?>
                                            </div>
                                            <div class="block-info">
                                                <strong>Aula:</strong> <?php echo $horario['aula'] ?? 'Sin asignar'; ?>
                                            </div>
                                            <div class="block-info">
                                                <strong>Carrera:</strong> <?php echo substr($horario['carrera'], 0, 20); ?>
                                            </div>
                                            <div class="block-time">
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

    <!-- DETALLE DE ASIGNACIONES -->
    <div class="card-box">
        <div class="card-header-custom">
            <i class="fa-solid fa-list"></i>
            Detalle de Materias Asignadas
        </div>
        
        <?php if (empty($horarios)): ?>
            <div class="alert-box alert-info">
                <i class="fa-solid fa-info-circle"></i>
                <span>El docente no tiene horarios asignados en este período.</span>
            </div>
        <?php else: ?>
            <div class="table-responsive" style="border: none;">
                <table class="detail-table">
                    <thead>
                        <tr>
                            <th>Día</th>
                            <th>Horario</th>
                            <th>Materia</th>
                            <th>Grupo</th>
                            <th>Aula</th>
                            <th>Carrera/Semestre</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($horarios as $horario): ?>
                        <tr>
                            <td><?php echo ucfirst($horario['dia']); ?></td>
                            <td>
                                <?php echo substr($horario['hora_inicio'], 0, 5); ?> - 
                                <?php echo substr($horario['hora_fin'], 0, 5); ?>
                            </td>
                            <td>
                                <span class="detail-subject"><?php echo $horario['materia_clave']; ?></span>
                                <span class="detail-subject-name"><?php echo $horario['materia_nombre']; ?></span>
                            </td>
                            <td><?php echo $horario['grupo_clave']; ?></td>
                            <td><?php echo $horario['aula'] ?? '<em>Sin asignar</em>'; ?></td>
                            <td>
                                <span style="font-size: 0.85rem;">
                                    <?php echo $horario['carrera']; ?><br>
                                    <small style="color: var(--muted);"><?php echo $horario['semestre']; ?></small>
                                </span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

    <!-- PIE DE PÁGINA -->
    <div class="report-footer">
        <p>
             Reporte de Horario por Docente
        </p>
        <p>
            Generado el <?php echo date('d/m/Y H:i'); ?> | 
            Total de horas semanales: <?php echo round($total_horas, 1); ?>
        </p>
    </div>

</div>