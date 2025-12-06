<?php

$page_title = 'Gestión de Períodos Escolares';
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    :root {
        --primary: #2563eb;
        --primary-hover: #1d4ed8;
        --success: #10b981;
        --warning: #f59e0b;
        --danger: #ef4444;
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

    .btn-create {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: var(--primary);
        color: #fff;
        padding: 10px 18px;
        border-radius: 10px;
        font-weight: 600;
        box-shadow: 0 5px 14px rgba(37,99,235,0.22);
        transition: 0.25s ease;
        text-decoration: none;
    }
    .btn-create:hover { 
        background: var(--primary-hover); 
        transform: translateY(-2px);
        color: #fff;
    }

    /* ------------------------ ALERTS ------------------------ */
    .alert-box {
        padding: 14px 16px;
        border-radius: 10px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 12px;
        font-size: 0.95rem;
    }

    .alert-success {
        background: #d1fae5;
        border: 1px solid #6ee7b7;
        color: #065f46;
    }

    .alert-danger {
        background: #fee2e2;
        border: 1px solid #fca5a5;
        color: #991b1b;
    }

    .alert-info {
        background: #dbeafe;
        border: 1px solid #93c5fd;
        color: #1e40af;
    }

    .alert-box i {
        font-size: 1.2rem;
    }

    /* ------------------------ CARD ------------------------ */
    .card-box {
        background: var(--bg);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 18px;
        margin-bottom: 22px;
        box-shadow: 0 1px 6px rgba(0,0,0,0.04);
    }

    /* ------------------------ TABLA ------------------------ */
    .table-responsive {
        border: 1px solid var(--border);
        border-radius: 12px;
        overflow: hidden;
    }

    .table-modern {
        width: 100%;
        border-collapse: collapse;
    }

    .table-modern thead th {
        background: #f1f5f9;
        padding: 12px 14px;
        text-align: left;
        font-size: 0.80rem;
        color: var(--muted);
        text-transform: uppercase;
        border-bottom: 1px solid var(--border);
        font-weight: 700;
    }

    .table-modern tbody td {
        padding: 14px;
        border-bottom: 1px solid var(--border);
        font-size: 0.95rem;
        background: #fff;
    }

    .table-modern tbody tr:hover td {
        background: #f8fafc;
    }

    .table-modern tbody tr.row-active td {
        background: #ecfdf5;
    }

    .periodo-nombre {
        font-weight: 700;
        font-size: 1rem;
    }

    .badge-pill {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.78rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .badge-success { 
        background: #d1fae5; 
        color: #065f46; 
    }

    .badge-secondary { 
        background: #f1f5f9; 
        color: #64748b; 
    }

    .badge-info { 
        background: #dbeafe; 
        color: #1e40af; 
    }

    .badge-active {
        background: #10b981;
        color: #fff;
        padding: 5px 14px;
        font-size: 0.75rem;
        margin-left: 10px;
    }

    /* ------------------------ ACCIONES ------------------------ */
    .actions-cell { 
        display: flex; 
        gap: 8px; 
    }

    .btn-icon {
        width: 38px;
        height: 38px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 1px solid transparent;
        cursor: pointer;
        transition: 0.15s ease;
        text-decoration: none;
        font-size: 14px;
    }

    .btn-icon:hover { 
        transform: translateY(-2px); 
    }

    .btn-action {
        padding: 8px 14px;
        border-radius: 8px;
        border: none;
        cursor: pointer;
        font-weight: 600;
        font-size: 0.85rem;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: 0.15s ease;
        text-decoration: none;
    }

    .btn-success { 
        background: #d1fae5; 
        color: #065f46;
        border: 1px solid #6ee7b7;
    }
    .btn-success:hover { 
        background: #a7f3d0; 
    }

    .btn-edit { 
        background: #fff7e6; 
        border: 1px solid #fde0a3; 
    }
    .btn-edit i { 
        color: #b45309; 
    }

    .btn-delete { 
        background: #ffe4e7; 
        border: 1px solid #fecdd3; 
    }
    .btn-delete i { 
        color: #be123c; 
    }

    /* ------------------------ EMPTY STATE ------------------------ */
    .empty-placeholder {
        padding: 50px;
        text-align: center;
        background: var(--surface);
    }

    .empty-text {
        font-size: 1.05rem;
        color: var(--muted);
        margin-top: 12px;
    }

    /* ------------------------ INFO CARDS ------------------------ */
    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 16px;
        margin-top: 20px;
    }

    .info-card {
        background: var(--bg);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 16px;
    }

    .info-card-header {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 10px;
        font-weight: 700;
        font-size: 0.95rem;
    }

    .info-card-header i {
        font-size: 1.2rem;
    }

    .info-card-body {
        font-size: 0.9rem;
        color: var(--muted);
        line-height: 1.6;
    }

    /* ------------------------ RESPONSIVE ------------------------ */
    @media (max-width: 900px) {
        .header-actions { 
            flex-direction: column; 
            gap: 10px; 
            align-items: flex-start; 
        }
        .actions-cell {
            flex-direction: column;
        }
    }

    @media (max-width: 600px) {
        .table-modern {
            font-size: 0.85rem;
        }
        .btn-action {
            font-size: 0.8rem;
            padding: 6px 10px;
        }
    }
</style>

<div class="page-container">

    <!-- BREADCRUMB -->
    <div class="breadcrumb-wrapper">
        <div class="breadcrumb-clean">
            <span class="breadcrumb-item">
                <a href="<?php echo APP_URL; ?>">Inicio</a>
            </span>
            <span class="breadcrumb-item active">Períodos Escolares</span>
        </div>
    </div>

    <!-- HEADER -->
    <div class="header-actions">
        <h1 class="page-title">Gestión de Períodos Escolares</h1>
        <a href="<?php echo APP_URL; ?>index.php?c=periodos&a=crear" class="btn-create">
            <i class="fa-solid fa-plus-circle"></i> Nuevo Período
        </a>
    </div>

    <!-- ALERTS -->
    <?php if (isset($_SESSION['success'])): ?>
    <div class="alert-box alert-success">
        <i class="fa-solid fa-check-circle"></i>
        <span><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></span>
    </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
    <div class="alert-box alert-danger">
        <i class="fa-solid fa-exclamation-circle"></i>
        <span><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></span>
    </div>
    <?php endif; ?>

    <!-- INFO IMPORTANTE -->
    <div class="alert-box alert-info">
        <i class="fa-solid fa-info-circle"></i>
        <span><strong>Importante:</strong> Solo puede haber un período activo a la vez. El período activo es el que se usa por defecto en todo el sistema para la gestión de horarios y grupos.</span>
    </div>

    <!-- TABLA -->
    <div class="card-box" style="padding:0;">
        <div class="table-responsive">
            <table class="table-modern">
                <thead>
                    <tr>
                        <th>Nombre del Período</th>
                        <th>Fecha Inicio</th>
                        <th>Fecha Fin</th>
                        <th>Duración</th>
                        <th style="text-align: center;">Estado</th>
                        <th style="text-align: center;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($periodos)): ?>
                        <tr>
                            <td colspan="6">
                                <div class="empty-placeholder">
                                    <i class="fa-regular fa-calendar" style="font-size:45px;color:#94a3b8;"></i>
                                    <div class="empty-text">No hay períodos escolares registrados</div>
                                    <small style="color: var(--muted);">Comienza creando el primer período escolar</small>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($periodos as $periodo): ?>
                        <tr <?php echo $periodo['activo'] ? 'class="row-active"' : ''; ?>>
                            <td>
                                <span class="periodo-nombre"><?php echo htmlspecialchars($periodo['nombre']); ?></span>
                                <?php if ($periodo['activo']): ?>
                                    <span class="badge-pill badge-active">
                                        <i class="fa-solid fa-check-circle"></i> ACTIVO
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <i class="fa-solid fa-calendar-day" style="color: var(--primary); margin-right: 6px;"></i>
                                <?php echo date('d/m/Y', strtotime($periodo['fecha_inicio'])); ?>
                            </td>
                            <td>
                                <i class="fa-solid fa-calendar-check" style="color: var(--danger); margin-right: 6px;"></i>
                                <?php echo date('d/m/Y', strtotime($periodo['fecha_fin'])); ?>
                            </td>
                            <td>
                                <?php
                                $inicio = new DateTime($periodo['fecha_inicio']);
                                $fin = new DateTime($periodo['fecha_fin']);
                                $diff = $inicio->diff($fin);
                                $meses = $diff->m + ($diff->y * 12);
                                ?>
                                <span class="badge-pill badge-info">
                                    <i class="fa-solid fa-clock"></i>
                                    <?php echo $diff->days . ' días'; ?>
                                    <?php if ($meses > 0): ?>
                                        <small>(~<?php echo $meses; ?> meses)</small>
                                    <?php endif; ?>
                                </span>
                            </td>
                            <td style="text-align: center;">
                                <?php if ($periodo['activo']): ?>
                                    <span class="badge-pill badge-success">
                                        <i class="fa-solid fa-toggle-on"></i> Activo
                                    </span>
                                <?php else: ?>
                                    <span class="badge-pill badge-secondary">
                                        <i class="fa-solid fa-toggle-off"></i> Inactivo
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="actions-cell">
                                    <?php if (!$periodo['activo']): ?>
                                        <form method="POST" 
                                              action="<?php echo APP_URL; ?>index.php?c=periodos&a=activar" 
                                              style="display: inline;"
                                              onsubmit="return confirm('¿Activar este período escolar?\n\nEsto desactivará automáticamente todos los demás períodos.');">
                                            <input type="hidden" name="id" value="<?php echo $periodo['id']; ?>">
                                            <button type="submit" class="btn-action btn-success" title="Activar">
                                                <i class="fa-solid fa-check-circle"></i> Activar
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                    
                                    <a href="<?php echo APP_URL; ?>index.php?c=periodos&a=editar&id=<?php echo $periodo['id']; ?>" 
                                       class="btn-icon btn-edit" 
                                       title="Editar">
                                        <i class="fa-solid fa-pen"></i>
                                    </a>
                                    
                                    <?php if (!$periodo['activo']): ?>
                                        <form method="POST" 
                                              action="<?php echo APP_URL; ?>index.php?c=periodos&a=eliminar" 
                                              style="display: inline;"
                                              onsubmit="return confirm('¿ELIMINAR este período escolar?\n\nEsta acción NO se puede deshacer.\n\nSolo se puede eliminar si no tiene datos asociados.');">
                                            <input type="hidden" name="id" value="<?php echo $periodo['id']; ?>">
                                            <button type="submit" class="btn-icon btn-delete" title="Eliminar">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

   

</div>