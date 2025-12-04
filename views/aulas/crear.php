<?php
// =====================================================
// views/aulas/crear.php
// Crear aula - CON MANEJO DE ERRORES Y ALERTAS
// =====================================================

$page_title = 'Nueva Aula';

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
        grid-template-columns: 1fr 1fr 1fr;
        gap: 16px;
        margin-bottom: 20px;
    }

    .form-row.two-cols {
        grid-template-columns: 1fr 1fr;
    }

    .form-row.single {
        grid-template-columns: 1fr;
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

    .form-control[readonly] {
        background-color: #f1f5f9;
        color: var(--muted);
        cursor: not-allowed;
    }

    textarea.form-control {
        resize: vertical;
        min-height: 80px;
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

    .info-card-body hr {
        border: none;
        border-top: 1px solid var(--border);
        margin: 14px 0;
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

    .info-card-body strong {
        color: var(--text-main);
        font-weight: 600;
    }

    /* --------------------------- RESPONSIVE --------------------------- */
    @media (max-width: 600px) {
        .form-row, .form-row.two-cols {
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
                <a href="<?php echo APP_URL; ?>index.php">Inicio</a>
            </span>
            <span class="breadcrumb-item">
                <a href="<?php echo APP_URL; ?>index.php?c=aulas">Aulas</a>
            </span>
            <span class="breadcrumb-item active">Nueva Aula</span>
        </div>
    </div>

    <!-- HEADER -->
    <h1 class="page-title">Nueva Aula</h1>

    <!-- CONTENT GRID -->
    <div class="content-grid">
        
        <!-- FORMULARIO PRINCIPAL -->
        <div>
            <div class="card-box">
                <form method="POST" action="<?php echo APP_URL; ?>index.php?c=aulas&a=crear" id="formAula" novalidate>
                    
                    <div class="form-row">
                        <div>
                            <label for="edificio" class="form-label">Edificio *</label>
                            <input type="text" class="form-control" id="edificio" name="edificio" 
                                   required maxlength="50" style="text-transform: uppercase;"
                                   placeholder="Ej: A, B, C"
                                   value="<?php echo htmlspecialchars($form_data['edificio'] ?? ''); ?>">
                        </div>
                        
                        <div>
                            <label for="numero" class="form-label">Número *</label>
                            <input type="text" class="form-control" id="numero" name="numero" 
                                   required maxlength="20"
                                   placeholder="Ej: 101, 202"
                                   value="<?php echo htmlspecialchars($form_data['numero'] ?? ''); ?>">
                        </div>
                        
                        <div>
                            <label class="form-label">Identificador</label>
                            <input type="text" class="form-control" id="identificador" 
                                   readonly
                                   placeholder="Auto-generado"
                                   value="<?php 
                                   if (!empty($form_data['edificio']) && !empty($form_data['numero'])) {
                                       echo htmlspecialchars(strtoupper($form_data['edificio']) . '-' . $form_data['numero']);
                                   }
                                   ?>">
                        </div>
                    </div>
                    
                    <div class="form-row two-cols">
                        <div>
                            <label for="tipo" class="form-label">Tipo de Aula *</label>
                            <select class="form-select" id="tipo" name="tipo" required>
                                <option value="">Seleccione...</option>
                                <option value="normal" <?php echo ($form_data['tipo'] ?? 'normal') === 'normal' ? 'selected' : ''; ?>>Normal</option>
                                <option value="laboratorio" <?php echo ($form_data['tipo'] ?? '') === 'laboratorio' ? 'selected' : ''; ?>>Laboratorio</option>
                                <option value="taller" <?php echo ($form_data['tipo'] ?? '') === 'taller' ? 'selected' : ''; ?>>Taller</option>
                                <option value="auditorio" <?php echo ($form_data['tipo'] ?? '') === 'auditorio' ? 'selected' : ''; ?>>Auditorio</option>
                            </select>
                        </div>
                        
                        <div>
                            <label for="capacidad" class="form-label">Capacidad (personas) *</label>
                            <input type="number" class="form-control" id="capacidad" name="capacidad" 
                                   required min="1" max="500" 
                                   value="<?php echo htmlspecialchars($form_data['capacidad'] ?? '35'); ?>">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="equipamiento" class="form-label">Equipamiento</label>
                        <textarea class="form-control" id="equipamiento" name="equipamiento" 
                                  rows="3" maxlength="500"
                                  placeholder="Ej: Proyector, Pizarrón inteligente, Aire acondicionado, Computadoras..."><?php echo htmlspecialchars($form_data['equipamiento'] ?? ''); ?></textarea>
                        <small class="text-muted">Opcional. Describe el equipamiento disponible (máximo 500 caracteres).</small>
                    </div>
                    
                    <div class="button-group">
                        <a href="<?php echo APP_URL; ?>index.php?c=aulas" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Guardar Aula
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- SIDEBAR -->
        <div>
            <div class="info-card">
                <div class="info-card-header">
                    <i class="fas fa-lightbulb"></i>
                    <span>Consejos</span>
                </div>
                <div class="info-card-body">
                    <h6>Nomenclatura</h6>
                    <p>
                        <strong>Edificio:</strong> Usa letras mayúsculas (A, B, C)<br>
                        <strong>Número:</strong> Código del aula (101, 202)<br>
                        <strong>Identificador:</strong> Se genera automáticamente
                    </p>
                    <hr>
                    <h6>Capacidad Sugerida</h6>
                    <ul>
                        <li><strong>Aula normal:</strong> 30-40 personas</li>
                        <li><strong>Laboratorio:</strong> 20-30 personas</li>
                        <li><strong>Taller:</strong> 15-25 personas</li>
                        <li><strong>Auditorio:</strong> 100+ personas</li>
                    </ul>
                    <hr>
                    <h6>Importante</h6>
                    <p>El código del aula (Edificio-Número) debe ser único. No puede haber dos aulas con el mismo código.</p>
                </div>
            </div>
        </div>

    </div>

</div>

<script>
// Auto-generar identificador al escribir
document.getElementById('edificio').addEventListener('input', actualizarIdentificador);
document.getElementById('numero').addEventListener('input', actualizarIdentificador);

function actualizarIdentificador() {
    const edificio = document.getElementById('edificio').value.toUpperCase().trim();
    const numero = document.getElementById('numero').value.trim();
    
    if (edificio && numero) {
        document.getElementById('identificador').value = edificio + '-' + numero;
    } else {
        document.getElementById('identificador').value = '';
    }
}

// Generar identificador al cargar si hay datos previos
window.addEventListener('DOMContentLoaded', actualizarIdentificador);

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
document.getElementById('formAula').addEventListener('submit', function(e) {
    const edificio = document.getElementById('edificio').value.trim();
    const numero = document.getElementById('numero').value.trim();
    const capacidad = parseInt(document.getElementById('capacidad').value);
    const tipo = document.getElementById('tipo').value;
    
    let errores = [];
    
    if (!edificio) {
        errores.push('El edificio es obligatorio');
    }
    
    if (!numero) {
        errores.push('El número es obligatorio');
    }
    
    if (!tipo) {
        errores.push('Debe seleccionar un tipo de aula');
    }
    
    if (isNaN(capacidad) || capacidad <= 0) {
        errores.push('La capacidad debe ser mayor a 0');
    } else if (capacidad > 500) {
        errores.push('La capacidad no puede ser mayor a 500');
    }
    
    if (errores.length > 0) {
        e.preventDefault();
        alert('Por favor corrija los siguientes errores:\n\n• ' + errores.join('\n• '));
        return false;
    }
});

// Convertir edificio a mayúsculas mientras escribe
document.getElementById('edificio').addEventListener('input', function() {
    this.value = this.value.toUpperCase();
});
</script>