<?php
// =====================================================
// views/aulas/editar.php
// Editar aula - DISEÑO PROFESIONAL MODERNO
// =====================================================

$page_title = 'Editar Aula';
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
    .page-title {
        font-size: 1.45rem;
        font-weight: 800;
        margin: 0 0 22px 0;
    }

    /* --------------------------- CARD --------------------------- */
    .card-box {
        background: var(--bg);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 24px;
        margin-bottom: 22px;
        box-shadow: 0 1px 6px rgba(0,0,0,0.04);
        max-width: 800px;
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

    <!-- BREADCRUMB -->
    <div class="breadcrumb-wrapper">
        <div class="breadcrumb-clean">
            <span class="breadcrumb-item">
                <a href="<?php echo APP_URL; ?>index.php">Inicio</a>
            </span>
            <span class="breadcrumb-item">
                <a href="<?php echo APP_URL; ?>index.php?c=aulas">Aulas</a>
            </span>
            <span class="breadcrumb-item active">Editar Aula</span>
        </div>
    </div>

    <!-- HEADER -->
    <h1 class="page-title">Editar Aula</h1>

    <!-- FORMULARIO -->
    <div class="card-box">
        <form method="POST" action="<?php echo APP_URL; ?>index.php?c=aulas&a=editar&id=<?php echo $aula['id']; ?>" class="needs-validation" novalidate>
            
            <div class="form-row">
                <div>
                    <label for="edificio" class="form-label">Edificio *</label>
                    <input type="text" class="form-control" id="edificio" name="edificio" 
                           required maxlength="50" style="text-transform: uppercase;"
                           value="<?php echo htmlspecialchars($aula['edificio']); ?>">
                </div>
                
                <div>
                    <label for="numero" class="form-label">Número *</label>
                    <input type="text" class="form-control" id="numero" name="numero" 
                           required maxlength="20"
                           value="<?php echo htmlspecialchars($aula['numero']); ?>">
                </div>
                
                <div>
                    <label class="form-label">Identificador Actual</label>
                    <input type="text" class="form-control" 
                           readonly
                           value="<?php echo htmlspecialchars($aula['edificio'] . '-' . $aula['numero']); ?>">
                </div>
            </div>
            
            <div class="form-row two-cols">
                <div>
                    <label for="tipo" class="form-label">Tipo de Aula *</label>
                    <select class="form-select" id="tipo" name="tipo" required>
                        <option value="normal" <?php echo ($aula['tipo'] == 'normal') ? 'selected' : ''; ?>>Normal</option>
                        <option value="laboratorio" <?php echo ($aula['tipo'] == 'laboratorio') ? 'selected' : ''; ?>>Laboratorio</option>
                        <option value="taller" <?php echo ($aula['tipo'] == 'taller') ? 'selected' : ''; ?>>Taller</option>
                        <option value="auditorio" <?php echo ($aula['tipo'] == 'auditorio') ? 'selected' : ''; ?>>Auditorio</option>
                    </select>
                </div>
                
                <div>
                    <label for="capacidad" class="form-label">Capacidad (personas) *</label>
                    <input type="number" class="form-control" id="capacidad" name="capacidad" 
                           required min="1" max="500" 
                           value="<?php echo $aula['capacidad']; ?>">
                </div>
            </div>
            
            <div class="form-group">
                <label for="equipamiento" class="form-label">Equipamiento</label>
                <textarea class="form-control" id="equipamiento" name="equipamiento" 
                          rows="3" maxlength="500"><?php echo htmlspecialchars($aula['equipamiento'] ?? ''); ?></textarea>
            </div>
            
            <div class="button-group">
                <a href="<?php echo APP_URL; ?>index.php?c=aulas" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Cancelar
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Actualizar Aula
                </button>
            </div>
        </form>
    </div>

</div>