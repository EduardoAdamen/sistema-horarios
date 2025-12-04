<?php
// =====================================================
// views/periodos/editar.php
// Formulario de edición de período escolar - DISEÑO MODERNO
// =====================================================

$page_title = 'Editar Período Escolar';
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
    .page-title {
        font-size: 1.45rem;
        font-weight: 800;
        margin-bottom: 22px;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .badge-active {
        background: #10b981;
        color: #fff;
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 700;
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

    .alert-danger {
        background: #fee2e2;
        border: 1px solid #fca5a5;
        color: #991b1b;
    }

    .alert-success {
        background: #d1fae5;
        border: 1px solid #6ee7b7;
        color: #065f46;
    }

    .alert-box i {
        font-size: 1.2rem;
    }

    /* ------------------------ GRID LAYOUT ------------------------ */
    .form-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 20px;
    }

    /* ------------------------ CARD ------------------------ */
    .card-box {
        background: var(--bg);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 24px;
        box-shadow: 0 1px 6px rgba(0,0,0,0.04);
        margin-bottom: 16px;
    }

    .card-header {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 20px;
        padding-bottom: 12px;
        border-bottom: 2px solid var(--border);
    }

    .card-header i {
        font-size: 1.3rem;
    }

    .card-title {
        font-size: 1.1rem;
        font-weight: 700;
        margin: 0;
    }

    .card-warning .card-header {
        border-bottom-color: var(--warning);
    }

    .card-warning .card-header i {
        color: var(--warning);
    }

    /* ------------------------ FORM ------------------------ */
    .form-group {
        margin-bottom: 20px;
    }

    .form-label {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 0.90rem;
        font-weight: 700;
        color: var(--text-main);
        margin-bottom: 8px;
    }

    .form-label i {
        font-size: 1rem;
    }

    .form-control-pro {
        width: 100%;
        height: 48px;
        padding: 10px 14px;
        border-radius: 8px;
        border: 1px solid var(--border);
        background: #fff;
        font-size: 0.95rem;
        transition: 0.15s ease;
        font-family: inherit;
    }

    .form-control-pro:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(37,99,235,0.15);
        outline: none;
    }

    .form-text {
        font-size: 0.85rem;
        color: var(--muted);
        margin-top: 6px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .form-text i {
        font-size: 0.9rem;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
    }

    /* ------------------------ SWITCH ------------------------ */
    .switch-container {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 10px;
        padding: 16px;
        margin-bottom: 20px;
    }

    .form-switch {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .form-switch input[type="checkbox"] {
        width: 50px;
        height: 26px;
        cursor: pointer;
        appearance: none;
        background: #cbd5e1;
        border-radius: 50px;
        position: relative;
        transition: 0.2s;
    }

    .form-switch input[type="checkbox"]:checked {
        background: var(--success);
    }

    .form-switch input[type="checkbox"]::before {
        content: '';
        position: absolute;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background: white;
        top: 3px;
        left: 3px;
        transition: 0.2s;
    }

    .form-switch input[type="checkbox"]:checked::before {
        transform: translateX(24px);
    }

    .switch-label {
        font-weight: 700;
        font-size: 0.95rem;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .switch-help {
        font-size: 0.85rem;
        color: var(--muted);
        margin-top: 8px;
        display: flex;
        align-items: start;
        gap: 6px;
    }

    /* ------------------------ ALERT INFO ------------------------ */
    .info-alert {
        background: #dbeafe;
        border: 1px solid #93c5fd;
        border-left: 4px solid var(--primary);
        border-radius: 8px;
        padding: 14px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .info-alert i {
        color: var(--primary);
        font-size: 1.2rem;
    }

    .info-alert-text {
        font-size: 0.9rem;
        color: #1e40af;
    }

    /* ------------------------ BUTTONS ------------------------ */
    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 12px;
        padding-top: 20px;
        border-top: 1px solid var(--border);
    }

    .btn-action {
        padding: 12px 24px;
        border-radius: 8px;
        border: none;
        cursor: pointer;
        font-weight: 600;
        font-size: 0.95rem;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: 0.15s ease;
        text-decoration: none;
    }

    .btn-warning {
        background: var(--warning);
        color: #fff;
        box-shadow: 0 4px 12px rgba(245,158,11,0.25);
    }

    .btn-warning:hover {
        background: #d97706;
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(245,158,11,0.35);
    }

    .btn-secondary {
        background: #f1f5f9;
        color: var(--muted);
        border: 1px solid var(--border);
    }

    .btn-secondary:hover {
        background: #e5eaf0;
        color: var(--text-main);
    }

    .btn-danger {
        background: var(--danger);
        color: #fff;
        width: 100%;
        justify-content: center;
    }

    .btn-danger:hover {
        background: #dc2626;
        transform: translateY(-1px);
    }

    /* ------------------------ SIDEBAR ------------------------ */
    .status-card {
        background: var(--bg);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 18px;
        margin-bottom: 16px;
        box-shadow: 0 1px 6px rgba(0,0,0,0.04);
    }

    .status-card.active-card {
        border-color: #6ee7b7;
        background: #ecfdf5;
    }

    .status-header {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 14px;
        font-weight: 700;
        font-size: 0.95rem;
    }

    .status-header i {
        font-size: 1.1rem;
    }

    .status-body {
        margin-top: 12px;
    }

    .status-label {
        font-weight: 700;
        font-size: 0.9rem;
        margin-bottom: 8px;
    }

    .badge-pill {
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 0.9rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .badge-success {
        background: #10b981;
        color: #fff;
    }

    .badge-secondary {
        background: #94a3b8;
        color: #fff;
    }

    .temporal-alert {
        padding: 12px;
        border-radius: 8px;
        font-size: 0.88rem;
        margin-top: 12px;
    }

    .temporal-alert strong {
        display: block;
        margin-bottom: 4px;
    }

    .temporal-info {
        background: #dbeafe;
        border: 1px solid #93c5fd;
        color: #1e40af;
    }

    .temporal-success {
        background: #d1fae5;
        border: 1px solid #6ee7b7;
        color: #065f46;
    }

    .temporal-secondary {
        background: #f1f5f9;
        border: 1px solid #cbd5e1;
        color: #475569;
    }

    .help-card {
        background: var(--bg);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 18px;
        margin-bottom: 16px;
        box-shadow: 0 1px 6px rgba(0,0,0,0.04);
    }

    .help-header {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 14px;
        font-weight: 700;
        font-size: 0.95rem;
        color: var(--primary);
    }

    .help-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .help-list li {
        font-size: 0.85rem;
        color: var(--muted);
        padding: 4px 0;
        padding-left: 20px;
        position: relative;
    }

    .help-list li::before {
        content: "•";
        position: absolute;
        left: 8px;
        color: var(--primary);
        font-weight: bold;
    }

    .help-text {
        font-size: 0.85rem;
        color: var(--muted);
        line-height: 1.5;
    }

    .danger-zone {
        background: var(--bg);
        border: 2px solid var(--danger);
        border-radius: var(--radius);
        padding: 18px;
        box-shadow: 0 1px 6px rgba(239,68,68,0.15);
    }

    .danger-header {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 14px;
        font-weight: 700;
        font-size: 0.95rem;
        color: var(--danger);
    }

    .audit-info {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
        padding: 12px;
        background: var(--surface);
        border-radius: 8px;
        font-size: 0.85rem;
        color: var(--muted);
    }

    .audit-item i {
        margin-right: 6px;
        color: var(--primary);
    }

    .audit-item strong {
        display: block;
        color: var(--text-main);
        margin-bottom: 4px;
    }

    /* ------------------------ RESPONSIVE ------------------------ */
    @media (max-width: 900px) {
        .form-grid {
            grid-template-columns: 1fr;
        }
        .form-row {
            grid-template-columns: 1fr;
        }
        .audit-info {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="page-container">

    <!-- BREADCRUMB -->
    <div class="breadcrumb-wrapper">
        <div class="breadcrumb-clean">
            <span class="breadcrumb-item">
                <a href="<?php echo APP_URL; ?>">Dashboard</a>
            </span>
            <span class="breadcrumb-item">
                <a href="<?php echo APP_URL; ?>index.php?c=periodos">Períodos Escolares</a>
            </span>
            <span class="breadcrumb-item active">Editar Período</span>
        </div>
    </div>

    <!-- HEADER -->
    <h1 class="page-title">
        <i class="fa-solid fa-edit" style="color: var(--warning);"></i>
        Editar Período Escolar
        <?php if ($periodo['activo']): ?>
            <span class="badge-active">
                <i class="fa-solid fa-check-circle"></i> ACTIVO
            </span>
        <?php endif; ?>
    </h1>

    <!-- ALERTS -->
    <?php if (isset($_SESSION['error'])): ?>
    <div class="alert-box alert-danger">
        <i class="fa-solid fa-exclamation-circle"></i>
        <span><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></span>
    </div>
    <?php endif; ?>

    <!-- FORM GRID -->
    <div class="form-grid">
        
        <!-- COLUMNA PRINCIPAL -->
        <div>
            <!-- FORMULARIO -->
            <div class="card-box card-warning">
                <div class="card-header">
                    <i class="fa-solid fa-calendar-edit"></i>
                    <h2 class="card-title">Modificar Datos del Período</h2>
                </div>

                <form method="POST" 
                      action="<?php echo APP_URL; ?>index.php?c=periodos&a=editar&id=<?php echo $periodo['id']; ?>" 
                      class="needs-validation" 
                      novalidate 
                      id="formEditarPeriodo">
                    
                    <!-- NOMBRE -->
                    <div class="form-group">
                        <label for="nombre" class="form-label">
                            <i class="fa-solid fa-tag" style="color: var(--primary);"></i>
                            Nombre del Período *
                        </label>
                        <input type="text" 
                               class="form-control-pro" 
                               id="nombre" 
                               name="nombre" 
                               required 
                               maxlength="100"
                               placeholder="Ejemplo: Enero-Junio 2025"
                               value="<?php echo htmlspecialchars($periodo['nombre']); ?>">
                        <div class="form-text">
                            <i class="fa-solid fa-info-circle"></i>
                            Ingresa un nombre descriptivo que identifique claramente el período académico
                        </div>
                    </div>
                    
                    <!-- FECHAS -->
                    <div class="form-row">
                        <div class="form-group">
                            <label for="fecha_inicio" class="form-label">
                                <i class="fa-solid fa-calendar-day" style="color: var(--success);"></i>
                                Fecha de Inicio *
                            </label>
                            <input type="date" 
                                   class="form-control-pro" 
                                   id="fecha_inicio" 
                                   name="fecha_inicio" 
                                   required
                                   value="<?php echo $periodo['fecha_inicio']; ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="fecha_fin" class="form-label">
                                <i class="fa-solid fa-calendar-check" style="color: var(--danger);"></i>
                                Fecha de Fin *
                            </label>
                            <input type="date" 
                                   class="form-control-pro" 
                                   id="fecha_fin" 
                                   name="fecha_fin" 
                                   required
                                   value="<?php echo $periodo['fecha_fin']; ?>">
                        </div>
                    </div>
                    
                    <!-- DURACIÓN CALCULADA -->
                    <div class="info-alert" id="duracionInfo">
                        <i class="fa-solid fa-calculator"></i>
                        <div class="info-alert-text">
                            <strong>Duración calculada:</strong> <span id="duracionTexto"></span>
                        </div>
                    </div>
                    
                    <!-- SWITCH ACTIVO -->
                    <div class="switch-container">
                        <div class="form-switch">
                            <input type="checkbox" 
                                   id="activo" 
                                   name="activo"
                                   <?php echo $periodo['activo'] ? 'checked' : ''; ?>>
                            <label for="activo" class="switch-label">
                                <i class="fa-solid fa-toggle-on" style="color: var(--success);"></i>
                                Marcar como período activo
                            </label>
                        </div>
                        <div class="switch-help">
                            <i class="fa-solid fa-exclamation-triangle" style="color: var(--warning);"></i>
                            <span>Si activas este período, todos los demás períodos se desactivarán automáticamente. Solo puede haber un período activo en el sistema.</span>
                        </div>
                    </div>
                    
                    <?php if ($periodo['activo']): ?>
                    <div class="alert-box alert-success">
                        <i class="fa-solid fa-info-circle"></i>
                        <span><strong>Este es el período activo actual.</strong> Todos los nuevos grupos y horarios se crearán usando este período.</span>
                    </div>
                    <?php endif; ?>
                    
                    <!-- BOTONES -->
                    <div class="form-actions">
                        <a href="<?php echo APP_URL; ?>index.php?c=periodos" class="btn-action btn-secondary">
                            <i class="fa-solid fa-times"></i> Cancelar
                        </a>
                        <button type="submit" class="btn-action btn-warning">
                            <i class="fa-solid fa-save"></i> Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>

            <!-- AUDITORÍA -->
            <div class="card-box">
                <div class="card-header">
                    <i class="fa-solid fa-history" style="color: var(--muted);"></i>
                    <h3 class="card-title">Información de Registro</h3>
                </div>
                <div class="audit-info">
                    <div class="audit-item">
                        <strong><i class="fa-solid fa-calendar-plus"></i> Creado:</strong>
                        <?php echo date('d/m/Y H:i:s', strtotime($periodo['created_at'])); ?>
                    </div>
                    <div class="audit-item">
                        <strong><i class="fa-solid fa-calendar-edit"></i> Última actualización:</strong>
                        <?php echo date('d/m/Y H:i:s', strtotime($periodo['updated_at'])); ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- COLUMNA SIDEBAR -->
        <div>
            <!-- ESTADO DEL PERÍODO -->
            <div class="status-card <?php echo $periodo['activo'] ? 'active-card' : ''; ?>">
                <div class="status-header" style="color: <?php echo $periodo['activo'] ? 'var(--success)' : 'var(--muted)'; ?>;">
                    <i class="fa-solid fa-info-circle"></i>
                    Estado del Período
                </div>
                <div class="status-body">
                    <div class="status-label">Estado Actual:</div>
                    <?php if ($periodo['activo']): ?>
                        <span class="badge-pill badge-success">
                            <i class="fa-solid fa-check-circle"></i> ACTIVO
                        </span>
                    <?php else: ?>
                        <span class="badge-pill badge-secondary">
                            <i class="fa-solid fa-pause-circle"></i> INACTIVO
                        </span>
                    <?php endif; ?>
                    
                    <?php
                    $hoy = new DateTime();
                    $inicio = new DateTime($periodo['fecha_inicio']);
                    $fin = new DateTime($periodo['fecha_fin']);
                    ?>
                    
                    <div class="status-label" style="margin-top: 16px;">Situación Temporal:</div>
                    <?php if ($hoy < $inicio): ?>
                        <div class="temporal-alert temporal-info">
                            <i class="fa-solid fa-hourglass-start"></i>
                            <strong>Por iniciar</strong>
                            Faltan <?php echo $hoy->diff($inicio)->days; ?> días para comenzar
                        </div>
                    <?php elseif ($hoy >= $inicio && $hoy <= $fin): ?>
                        <div class="temporal-alert temporal-success">
                            <i class="fa-solid fa-play-circle"></i>
                            <strong>En curso</strong>
                            Quedan <?php echo $hoy->diff($fin)->days; ?> días
                        </div>
                    <?php else: ?>
                        <div class="temporal-alert temporal-secondary">
                            <i class="fa-solid fa-check-double"></i>
                            <strong>Finalizado</strong>
                            Terminó hace <?php echo $fin->diff($hoy)->days; ?> días
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- CONSEJOS -->
            <div class="help-card">
                <div class="help-header">
                    <i class="fa-solid fa-lightbulb"></i>
                    Consejos
                </div>
                <div class="status-label">
                    <i class="fa-solid fa-exclamation-triangle" style="color: var(--warning);"></i>
                    Precauciones:
                </div>
                <ul class="help-list">
                    <li>Verifica las fechas antes de guardar</li>
                    <li>Cambiar fechas puede afectar horarios existentes</li>
                    <li>Solo puede haber un período activo</li>
                </ul>
                
                <div class="status-label" style="margin-top: 12px;">
                    <i class="fa-solid fa-shield-alt" style="color: var(--success);"></i>
                    Protección:
                </div>
                <p class="help-text">
                    Los períodos con datos asociados (grupos, horarios) están protegidos contra eliminación accidental.
                </p>
            </div>

            <!-- ZONA DE PELIGRO -->
            <?php if (!$periodo['activo']): ?>
            <div class="danger-zone">
                <div class="danger-header">
                    <i class="fa-solid fa-exclamation-triangle"></i>
                    Zona de Peligro
                </div>
                <div class="status-label" style="color: var(--danger);">
                    <i class="fa-solid fa-trash-alt"></i>
                    Eliminar Período
                </div>
                <p class="help-text" style="margin: 8px 0 14px 0;">
                    Esta acción es permanente y no se puede deshacer. Solo se puede eliminar si no tiene datos asociados.
                </p>
                <form method="POST" 
                      action="<?php echo APP_URL; ?>index.php?c=periodos&a=eliminar" 
                      onsubmit="return confirm('⚠️ ADVERTENCIA\n\n¿Estás COMPLETAMENTE SEGURO de que deseas ELIMINAR este período?\n\nEsta acción es IRREVERSIBLE.\n\nSolo se eliminará si no tiene datos asociados.');">
                    <input type="hidden" name="id" value="<?php echo $periodo['id']; ?>">
                    <button type="submit" class="btn-action btn-danger">
                        <i class="fa-solid fa-trash-alt"></i> Eliminar Período
                    </button>
                </form>
            </div>
            <?php endif; ?>
        </div>

    </div>

</div>

<script>
// Validación de Bootstrap
(function() {
    'use strict';
    
    const form = document.getElementById('formEditarPeriodo');
    
    form.addEventListener('submit', function(event) {
        if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
        }
        
        // Validar que fecha fin sea mayor a fecha inicio
        const fechaInicio = document.getElementById('fecha_inicio').value;
        const fechaFin = document.getElementById('fecha_fin').value;
        
        if (fechaInicio && fechaFin && fechaFin <= fechaInicio) {
            event.preventDefault();
            alert('⚠️ Error de validación\n\nLa fecha de fin debe ser posterior a la fecha de inicio.');
            return false;
        }
        
        form.classList.add('was-validated');
    }, false);
})();

// Calcular duración automáticamente
function calcularDuracion() {
    const fechaInicio = document.getElementById('fecha_inicio').value;
    const fechaFin = document.getElementById('fecha_fin').value;
    const duracionInfo = document.getElementById('duracionInfo');
    const duracionTexto = document.getElementById('duracionTexto');
    
    if (fechaInicio && fechaFin) {
        const inicio = new Date(fechaInicio);
        const fin = new Date(fechaFin);
        
        if (fin > inicio) {
            const diffTime = Math.abs(fin - inicio);
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
            const diffMonths = Math.floor(diffDays / 30);
            
            let texto = `${diffDays} días`;
            if (diffMonths > 0) {
                texto += ` (aproximadamente ${diffMonths} ${diffMonths === 1 ? 'mes' : 'meses'})`;
            }
            
            duracionTexto.textContent = texto;
            duracionInfo.style.display = 'flex';
        } else {
            duracionInfo.style.display = 'none';
        }
    }
}

document.getElementById('fecha_inicio').addEventListener('change', calcularDuracion);
document.getElementById('fecha_fin').addEventListener('change', calcularDuracion);

// Calcular al cargar
calcularDuracion();
</script>