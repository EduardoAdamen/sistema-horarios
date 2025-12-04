<?php
// =====================================================
// views/periodos/crear.php
// Formulario de creación de período escolar - DISEÑO MODERNO
// =====================================================

$page_title = 'Crear Período Escolar';
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
        margin: 0 0 22px 0;
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

    .alert-info {
        background: #dbeafe;
        border: 1px solid #93c5fd;
        color: #1e40af;
    }

    .alert-box i {
        font-size: 1.2rem;
    }

    /* ------------------------ LAYOUT ------------------------ */
    .form-layout {
        display: grid;
        grid-template-columns: 1fr 380px;
        gap: 22px;
        align-items: start;
    }

    /* ------------------------ CARD ------------------------ */
    .card-box {
        background: var(--bg);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 0;
        box-shadow: 0 1px 6px rgba(0,0,0,0.04);
    }

    .card-header {
        background: var(--primary);
        color: #fff;
        padding: 16px 18px;
        border-radius: var(--radius) var(--radius) 0 0;
        font-weight: 700;
        font-size: 1rem;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .card-body {
        padding: 24px;
    }

    /* ------------------------ FORM ------------------------ */
    .form-group {
        margin-bottom: 24px;
    }

    .form-label {
        display: block;
        font-weight: 700;
        font-size: 0.95rem;
        margin-bottom: 8px;
        color: var(--text-main);
    }

    .form-label i {
        margin-right: 6px;
    }

    .form-control {
        width: 100%;
        padding: 12px 14px;
        border: 1px solid var(--border);
        border-radius: 10px;
        font-size: 0.95rem;
        transition: 0.15s ease;
        font-family: inherit;
    }

    .form-control:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(37,99,235,0.1);
    }

    .form-control-lg {
        padding: 14px 16px;
        font-size: 1rem;
    }

    .form-text {
        display: block;
        margin-top: 6px;
        font-size: 0.85rem;
        color: var(--muted);
    }

    .form-text i {
        margin-right: 4px;
    }

    .invalid-feedback {
        display: none;
        margin-top: 6px;
        font-size: 0.85rem;
        color: var(--danger);
    }

    .was-validated .form-control:invalid ~ .invalid-feedback {
        display: block;
    }

    .was-validated .form-control:invalid {
        border-color: var(--danger);
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
    }

    /* ------------------------ SWITCH ------------------------ */
    .switch-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 10px;
        padding: 16px;
        margin-bottom: 24px;
    }

    .form-switch {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .switch-input {
        width: 52px;
        height: 28px;
        position: relative;
        display: inline-block;
        cursor: pointer;
    }

    .switch-input input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .switch-slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #cbd5e1;
        transition: 0.3s;
        border-radius: 34px;
    }

    .switch-slider:before {
        position: absolute;
        content: "";
        height: 20px;
        width: 20px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        transition: 0.3s;
        border-radius: 50%;
    }

    .switch-input input:checked + .switch-slider {
        background-color: var(--success);
    }

    .switch-input input:checked + .switch-slider:before {
        transform: translateX(24px);
    }

    .switch-label {
        font-weight: 700;
        font-size: 0.95rem;
        cursor: pointer;
    }

    .switch-label i {
        margin-right: 6px;
    }

    .switch-help {
        margin-top: 10px;
        font-size: 0.85rem;
        color: var(--muted);
        display: flex;
        align-items: flex-start;
        gap: 6px;
    }

    /* ------------------------ DURACION ALERT ------------------------ */
    .duracion-card {
        background: #dbeafe;
        border: 1px solid #93c5fd;
        border-radius: 10px;
        padding: 14px 16px;
        margin-bottom: 24px;
        display: none;
    }

    .duracion-card i {
        color: #0ea5e9;
        margin-right: 8px;
    }

    .duracion-card strong {
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

    .btn {
        padding: 12px 24px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.95rem;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        border: none;
        cursor: pointer;
        transition: 0.2s ease;
        text-decoration: none;
    }

    .btn-primary {
        background: var(--primary);
        color: #fff;
        box-shadow: 0 5px 14px rgba(37,99,235,0.22);
    }

    .btn-primary:hover {
        background: var(--primary-hover);
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

    /* ------------------------ SIDEBAR CARDS ------------------------ */
    .sidebar-card {
        background: var(--bg);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        margin-bottom: 16px;
        box-shadow: 0 1px 6px rgba(0,0,0,0.04);
    }

    .sidebar-card-header {
        background: var(--info);
        color: #fff;
        padding: 12px 16px;
        border-radius: var(--radius) var(--radius) 0 0;
        font-weight: 700;
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .sidebar-card-body {
        padding: 16px;
    }

    .sidebar-card-body h6 {
        font-weight: 700;
        font-size: 0.9rem;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .sidebar-card-body ul {
        margin: 0 0 12px 0;
        padding-left: 20px;
    }

    .sidebar-card-body ul li {
        font-size: 0.85rem;
        margin-bottom: 4px;
        color: var(--muted);
    }

    .sidebar-card-body p {
        font-size: 0.85rem;
        color: var(--muted);
        margin-bottom: 12px;
        line-height: 1.5;
    }

    .sidebar-card-body hr {
        border: none;
        border-top: 1px solid var(--border);
        margin: 14px 0;
    }

    .sidebar-card.border-success {
        border-color: #6ee7b7;
    }

    .sidebar-card.border-success .sidebar-card-header {
        background: var(--success);
    }

    /* ------------------------ RESPONSIVE ------------------------ */
    @media (max-width: 1100px) {
        .form-layout {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 600px) {
        .form-row {
            grid-template-columns: 1fr;
        }

        .form-actions {
            flex-direction: column;
        }

        .btn {
            width: 100%;
            justify-content: center;
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
            <span class="breadcrumb-item active">Nuevo Período</span>
        </div>
    </div>

    <!-- HEADER -->
    <h1 class="page-title"><i class="fa-solid fa-plus-circle"></i> Crear Período Escolar</h1>

    <!-- ALERTS -->
    <?php if (isset($_SESSION['error'])): ?>
    <div class="alert-box alert-danger">
        <i class="fa-solid fa-exclamation-circle"></i>
        <span><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></span>
    </div>
    <?php endif; ?>

    <!-- LAYOUT -->
    <div class="form-layout">
        
        <!-- FORMULARIO PRINCIPAL -->
        <div class="card-box">
            <div class="card-header">
                <i class="fa-solid fa-calendar-plus"></i>
                Datos del Período Escolar
            </div>
            <div class="card-body">
                <form method="POST" action="<?php echo APP_URL; ?>index.php?c=periodos&a=crear" class="needs-validation" novalidate id="formCrearPeriodo">
                    
                    <!-- Nombre -->
                    <div class="form-group">
                        <label for="nombre" class="form-label">
                            <i class="fa-solid fa-tag" style="color: var(--primary);"></i>
                            Nombre del Período *
                        </label>
                        <input type="text" 
                               class="form-control form-control-lg" 
                               id="nombre" 
                               name="nombre" 
                               required 
                               maxlength="100"
                               placeholder="Ejemplo: Enero-Junio 2025"
                               value="<?php echo isset($_POST['nombre']) ? htmlspecialchars($_POST['nombre']) : ''; ?>">
                        <small class="form-text">
                            <i class="fa-solid fa-info-circle"></i>
                            Ingresa un nombre descriptivo que identifique claramente el período académico
                        </small>
                        <div class="invalid-feedback">
                            Por favor ingresa el nombre del período escolar
                        </div>
                    </div>
                    
                    <!-- Fechas -->
                    <div class="form-row">
                        <div class="form-group">
                            <label for="fecha_inicio" class="form-label">
                                <i class="fa-solid fa-calendar-day" style="color: var(--success);"></i>
                                Fecha de Inicio *
                            </label>
                            <input type="date" 
                                   class="form-control form-control-lg" 
                                   id="fecha_inicio" 
                                   name="fecha_inicio" 
                                   required
                                   value="<?php echo isset($_POST['fecha_inicio']) ? $_POST['fecha_inicio'] : ''; ?>">
                            <div class="invalid-feedback">
                                Selecciona la fecha de inicio
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="fecha_fin" class="form-label">
                                <i class="fa-solid fa-calendar-check" style="color: var(--danger);"></i>
                                Fecha de Fin *
                            </label>
                            <input type="date" 
                                   class="form-control form-control-lg" 
                                   id="fecha_fin" 
                                   name="fecha_fin" 
                                   required
                                   value="<?php echo isset($_POST['fecha_fin']) ? $_POST['fecha_fin'] : ''; ?>">
                            <div class="invalid-feedback">
                                Selecciona la fecha de fin
                            </div>
                        </div>
                    </div>
                    
                    <!-- Duración calculada -->
                    <div class="duracion-card" id="duracionInfo">
                        <i class="fa-solid fa-calculator"></i>
                        <strong>Duración calculada:</strong> <span id="duracionTexto"></span>
                    </div>
                    
                    <!-- Switch Activo -->
                    <div class="switch-card">
                        <div class="form-switch">
                            <label class="switch-input">
                                <input type="checkbox" 
                                       id="activo" 
                                       name="activo"
                                       <?php echo (isset($_POST['activo']) && $_POST['activo']) ? 'checked' : ''; ?>>
                                <span class="switch-slider"></span>
                            </label>
                            <label class="switch-label" for="activo">
                                <i class="fa-solid fa-toggle-on" style="color: var(--success);"></i>
                                Marcar como período activo
                            </label>
                        </div>
                        <div class="switch-help">
                            <i class="fa-solid fa-exclamation-triangle" style="color: var(--warning);"></i>
                            <span>Si activas este período, todos los demás períodos se desactivarán automáticamente. Solo puede haber un período activo en el sistema.</span>
                        </div>
                    </div>
                    
                    <!-- Botones -->
                    <div class="form-actions">
                        <a href="<?php echo APP_URL; ?>index.php?c=periodos" class="btn btn-secondary">
                            <i class="fa-solid fa-times"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fa-solid fa-save"></i> Crear Período Escolar
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- SIDEBAR -->
        <div>
            
            
            <!-- Tarjeta de estado -->
            <div class="sidebar-card border-success">
                <div class="sidebar-card-header">
                    <i class="fa-solid fa-check-circle"></i>
                    Estado del Sistema
                </div>
                <div class="sidebar-card-body">
                    <p>
                        <i class="fa-solid fa-info-circle" style="color: var(--primary);"></i>
                        Al crear un nuevo período:
                    </p>
                    <ul style="margin-bottom: 0;">
                        <li>Se agregará a la lista de períodos disponibles</li>
                        <li>Podrás activarlo inmediatamente o después</li>
                        <li>Será usado para crear grupos y horarios</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
// Validación de Bootstrap
(function() {
    'use strict';
    
    const form = document.getElementById('formCrearPeriodo');
    
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
            duracionInfo.style.display = 'block';
        } else {
            duracionInfo.style.display = 'none';
        }
    } else {
        duracionInfo.style.display = 'none';
    }
}

document.getElementById('fecha_inicio').addEventListener('change', calcularDuracion);
document.getElementById('fecha_fin').addEventListener('change', calcularDuracion);

// Calcular al cargar si hay valores
document.addEventListener('DOMContentLoaded', calcularDuracion);
</script>