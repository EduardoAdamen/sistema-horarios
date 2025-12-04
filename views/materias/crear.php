<?php
// =====================================================
// views/materias/crear.php
// Crear materia - CON MANEJO DE ERRORES Y ALERTAS
// =====================================================

$page_title = 'Nueva Materia';

// Recuperar datos del formulario si hay errores
$form_data = $_SESSION['form_data'] ?? [];
if (!empty($form_data)) {
    unset($_SESSION['form_data']);
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

    /* --------------------------- ALERTAS --------------------------- */
    .alert {
        padding: 14px 18px;
        border-radius: 10px;
        margin-bottom: 20px;
        display: flex;
        align-items: flex-start;
        gap: 12px;
        font-size: 0.92rem;
        line-height: 1.6;
        animation: slideDown 0.3s ease;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .alert-danger {
        background: #fee2e2;
        border: 1px solid #fecaca;
        color: #991b1b;
    }

    .alert-success {
        background: #dcfce7;
        border: 1px solid #bbf7d0;
        color: #166534;
    }

    .alert i {
        font-size: 1.2rem;
        margin-top: 2px;
    }

    .alert-content {
        flex: 1;
    }

    .alert-close {
        background: none;
        border: none;
        font-size: 1.4rem;
        cursor: pointer;
        color: inherit;
        opacity: 0.6;
        transition: 0.2s;
        padding: 0;
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .alert-close:hover {
        opacity: 1;
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
    .page-title {
        font-size: 1.45rem;
        font-weight: 800;
        margin: 0 0 22px 0;
    }

    /* --------------------------- LAYOUT GRID --------------------------- */
    .content-grid {
        display: grid;
        grid-template-columns: 1fr 380px;
        gap: 22px;
        align-items: start;
    }

    @media (max-width: 968px) {
        .content-grid {
            grid-template-columns: 1fr;
        }
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

    /* --------------------------- FORMULARIO --------------------------- */
    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
        margin-bottom: 20px;
    }

    .form-row.single {
        grid-template-columns: 1fr;
    }

    .form-row.special {
        grid-template-columns: 1fr 2fr;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-label {
        display: block;
        font-size: 0.90rem;
        font-weight: 600;
        color: var(--text-main);
        margin-bottom: 8px;
    }

    .form-control, .form-select {
        width: 100%;
        max-width: 100%;
        padding: 10px 14px;
        border: 1px solid var(--border);
        border-radius: 8px;
        font-size: 0.95rem;
        color: var(--text-main);
        background: var(--bg);
        transition: 0.15s ease;
        box-sizing: border-box;
    }

    .form-control:focus, .form-select:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(37,99,235,0.1);
    }

    .text-muted {
        font-size: 0.82rem;
        color: var(--muted);
        margin-top: 4px;
        display: block;
    }

    /* --------------------------- BOTONES --------------------------- */
    .button-group {
        display: flex;
        gap: 12px;
        justify-content: flex-end;
        margin-top: 28px;
        padding-top: 20px;
        border-top: 1px solid var(--border);
    }

    .btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 18px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.95rem;
        cursor: pointer;
        transition: 0.25s ease;
        text-decoration: none;
        border: none;
    }

    .btn-secondary {
        background: #f1f5f9;
        color: var(--muted);
        border: 1px solid var(--border);
    }

    .btn-secondary:hover {
        background: #e2e8f0;
        color: var(--text-main);
        transform: translateY(-2px);
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

    /* --------------------------- INFO CARD --------------------------- */
    .info-card {
        background: var(--bg);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        overflow: hidden;
        box-shadow: 0 1px 6px rgba(0,0,0,0.04);
    }

    .info-card-header {
        padding: 12px 16px;
        font-weight: 700;
        font-size: 0.90rem;
        display: flex;
        align-items: center;
        gap: 8px;
        background: #dbeafe;
        color: #1e40af;
        border-bottom: 1px solid #93c5fd;
    }

    .info-card-body {
        padding: 16px;
    }

    .info-card-body h6 {
        font-size: 0.88rem;
        font-weight: 700;
        margin: 0 0 8px 0;
        color: var(--text-main);
    }

    .info-card-body p {
        font-size: 0.85rem;
        color: var(--muted);
        margin: 0 0 12px 0;
        line-height: 1.6;
    }

    .info-card-body ul {
        margin: 0;
        padding-left: 20px;
    }

    .info-card-body ul li {
        font-size: 0.85rem;
        color: var(--muted);
        margin-bottom: 6px;
        line-height: 1.5;
    }

    .info-card-body ul li strong {
        color: var(--text-main);
        font-weight: 600;
    }

    .info-card-body hr {
        border: none;
        border-top: 1px solid var(--border);
        margin: 14px 0;
    }

    /* --------------------------- RESPONSIVE --------------------------- */
    @media (max-width: 600px) {
        .form-row, .form-row.special {
            grid-template-columns: 1fr;
        }

        .button-group {
            flex-direction: column-reverse;
        }
        
        .btn {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<div class="page-container">

    <!-- ALERTAS DE ERROR Y ÉXITO -->
    <?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger" role="alert">
        <i class="fas fa-exclamation-circle"></i>
        <div class="alert-content"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <button class="alert-close" onclick="this.parentElement.remove()" aria-label="Cerrar">×</button>
    </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success" role="alert">
        <i class="fas fa-check-circle"></i>
        <div class="alert-content"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
        <button class="alert-close" onclick="this.parentElement.remove()" aria-label="Cerrar">×</button>
    </div>
    <?php endif; ?>

    <!-- BREADCRUMB -->
    <div class="breadcrumb-wrapper">
        <div class="breadcrumb-clean">
            <span class="breadcrumb-item">
                <a href="<?php echo APP_URL; ?>index.php">Dashboard</a>
            </span>
            <span class="breadcrumb-item">
                <a href="<?php echo APP_URL; ?>index.php?c=materias">Materias</a>
            </span>
            <span class="breadcrumb-item active">Nueva Materia</span>
        </div>
    </div>

    <!-- HEADER -->
    <h1 class="page-title">Nueva Materia</h1>

    <!-- CONTENT GRID -->
    <div class="content-grid">
        
        <!-- FORMULARIO PRINCIPAL -->
        <div>
            <div class="card-box">
                <form method="POST" action="<?php echo APP_URL; ?>index.php?c=materias&a=crear" id="formMateria" novalidate>
                    
                    <div class="form-row special">
                        <div>
                            <label for="clave" class="form-label">Clave *</label>
                            <input type="text" class="form-control" id="clave" name="clave" 
                                   required maxlength="20" style="text-transform: uppercase;"
                                   placeholder="Ej: AED-1001"
                                   value="<?php echo htmlspecialchars($form_data['clave'] ?? ''); ?>">
                            <small class="text-muted">Código único de la materia</small>
                        </div>
                        
                        <div>
                            <label for="nombre" class="form-label">Nombre de la Materia *</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" 
                                   required maxlength="200"
                                   placeholder="Ej: Álgebra Lineal"
                                   value="<?php echo htmlspecialchars($form_data['nombre'] ?? ''); ?>">
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div>
                            <label for="carrera_id" class="form-label">Carrera *</label>
                            <select class="form-select" id="carrera_id" name="carrera_id" required>
                                <option value="">Seleccione...</option>
                                <?php foreach ($carreras as $carrera): ?>
                                    <option value="<?php echo $carrera['id']; ?>"
                                            <?php echo (isset($form_data['carrera_id']) && $form_data['carrera_id'] == $carrera['id']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($carrera['nombre']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div>
                            <label for="semestre_id" class="form-label">Semestre *</label>
                            <select class="form-select" id="semestre_id" name="semestre_id" required>
                                <option value="">Seleccione...</option>
                                <?php foreach ($semestres as $semestre): ?>
                                    <option value="<?php echo $semestre['id']; ?>"
                                            <?php echo (isset($form_data['semestre_id']) && $form_data['semestre_id'] == $semestre['id']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($semestre['nombre']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div>
                            <label for="creditos" class="form-label">Créditos *</label>
                            <input type="number" class="form-control" id="creditos" name="creditos" 
                                   required min="1" max="10" 
                                   value="<?php echo htmlspecialchars($form_data['creditos'] ?? '5'); ?>">
                            <small class="text-muted">Entre 1 y 10 créditos</small>
                        </div>
                        
                        <div>
                            <label for="horas_semana" class="form-label">Horas por Semana *</label>
                            <input type="number" class="form-control" id="horas_semana" name="horas_semana" 
                                   required min="1" max="20" 
                                   value="<?php echo htmlspecialchars($form_data['horas_semana'] ?? '5'); ?>">
                            <small class="text-muted">Entre 1 y 20 horas</small>
                        </div>
                    </div>
                    
                    <div class="button-group">
                        <a href="<?php echo APP_URL; ?>index.php?c=materias" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Guardar Materia
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- SIDEBAR -->
        <div>
            <div class="info-card">
                <div class="info-card-header">
                    <i class="fas fa-info-circle"></i>
                    <span>Información</span>
                </div>
                <div class="info-card-body">
                    <h6>Créditos y Días de Asignación</h6>
                    <p>Los créditos determinan los días máximos disponibles para horarios:</p>
                    <ul>
                        <li><strong>5 créditos:</strong> Lunes a viernes</li>
                        <li><strong>4 créditos:</strong> Lunes a jueves</li>
                        <li><strong>3 créditos:</strong> Lunes a miércoles</li>
                        <li><strong>2 créditos:</strong> Lunes y martes</li>
                    </ul>
                    <hr>
                    <h6>Importante</h6>
                    <p>
                        • La clave debe ser única<br>
                        • Los campos marcados con (*) son obligatorios<br>
                        • Verifique la información antes de guardar
                    </p>
                </div>
            </div>
        </div>

    </div>

</div>

<script>
// Convertir clave a mayúsculas mientras escribe
document.getElementById('clave').addEventListener('input', function() {
    this.value = this.value.toUpperCase();
});

// Auto-cerrar alertas después de 8 segundos
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.5s ease';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        }, 8000);
    });
});

// Validación del formulario antes de enviar
document.getElementById('formMateria').addEventListener('submit', function(e) {
    const clave = document.getElementById('clave').value.trim();
    const nombre = document.getElementById('nombre').value.trim();
    const creditos = parseInt(document.getElementById('creditos').value);
    const horas = parseInt(document.getElementById('horas_semana').value);
    const carrera = document.getElementById('carrera_id').value;
    const semestre = document.getElementById('semestre_id').value;
    
    let errores = [];
    
    if (!clave) {
        errores.push('La clave es obligatoria');
    }
    
    if (!nombre) {
        errores.push('El nombre de la materia es obligatorio');
    }
    
    if (!carrera) {
        errores.push('Debe seleccionar una carrera');
    }
    
    if (!semestre) {
        errores.push('Debe seleccionar un semestre');
    }
    
    if (isNaN(creditos) || creditos <= 0 || creditos > 10) {
        errores.push('Los créditos deben estar entre 1 y 10');
    }
    
    if (isNaN(horas) || horas <= 0 || horas > 20) {
        errores.push('Las horas por semana deben estar entre 1 y 20');
    }
    
    if (errores.length > 0) {
        e.preventDefault();
        alert('Por favor corrija los siguientes errores:\n\n• ' + errores.join('\n• '));
        return false;
    }
});
</script>