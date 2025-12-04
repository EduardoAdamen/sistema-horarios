<?php
// =====================================================
// views/materias/editar.php
// Editar materia - DISEÑO PROFESIONAL MODERNO
// =====================================================

$page_title = 'Editar Materia';
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

    /* --------------------------- ALERT CARD --------------------------- */
    .alert-card {
        background: var(--bg);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        overflow: hidden;
        box-shadow: 0 1px 6px rgba(0,0,0,0.04);
    }

    .alert-card-header {
        padding: 12px 16px;
        font-weight: 700;
        font-size: 0.90rem;
        display: flex;
        align-items: center;
        gap: 8px;
        background: #fef3c7;
        color: #92400e;
        border-bottom: 1px solid #fde68a;
    }

    .alert-card-body {
        padding: 16px;
    }

    .alert-card-body h6 {
        font-size: 0.88rem;
        font-weight: 700;
        margin: 0 0 8px 0;
        color: var(--text-main);
    }

    .alert-card-body p {
        font-size: 0.85rem;
        color: var(--muted);
        margin: 0;
        line-height: 1.5;
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

    <!-- BREADCRUMB -->
    <div class="breadcrumb-wrapper">
        <div class="breadcrumb-clean">
            <span class="breadcrumb-item">
                <a href="<?php echo APP_URL; ?>index.php">Dashboard</a>
            </span>
            <span class="breadcrumb-item">
                <a href="<?php echo APP_URL; ?>index.php?c=materias">Materias</a>
            </span>
            <span class="breadcrumb-item active">Editar Materia</span>
        </div>
    </div>

    <!-- HEADER -->
    <h1 class="page-title">Editar Materia</h1>

    <!-- CONTENT GRID -->
    <div class="content-grid">
        
        <!-- FORMULARIO PRINCIPAL -->
        <div>
            <div class="card-box">
                <form method="POST" action="<?php echo APP_URL; ?>index.php?c=materias&a=editar&id=<?php echo $materia['id']; ?>" class="needs-validation" novalidate>
                    
                    <div class="form-row special">
                        <div>
                            <label for="clave" class="form-label">Clave *</label>
                            <input type="text" class="form-control" id="clave" name="clave" 
                                   required maxlength="20" 
                                   value="<?php echo htmlspecialchars($materia['clave']); ?>"
                                   style="text-transform: uppercase;">
                        </div>
                        
                        <div>
                            <label for="nombre" class="form-label">Nombre de la Materia *</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" 
                                   required maxlength="200"
                                   value="<?php echo htmlspecialchars($materia['nombre']); ?>">
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div>
                            <label for="carrera_id" class="form-label">Carrera *</label>
                            <select class="form-select" id="carrera_id" name="carrera_id" required>
                                <option value="">Seleccione una carrera...</option>
                                <?php foreach ($carreras as $carrera): ?>
                                    <option value="<?php echo $carrera['id']; ?>"
                                            <?php echo ($materia['carrera_id'] == $carrera['id']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($carrera['nombre']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div>
                            <label for="semestre_id" class="form-label">Semestre *</label>
                            <select class="form-select" id="semestre_id" name="semestre_id" required>
                                <option value="">Seleccione un semestre...</option>
                                <?php foreach ($semestres as $semestre): ?>
                                    <option value="<?php echo $semestre['id']; ?>"
                                            <?php echo ($materia['semestre_id'] == $semestre['id']) ? 'selected' : ''; ?>>
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
                                   value="<?php echo $materia['creditos']; ?>">
                            <small class="text-muted">5 créditos = viernes | 4 créditos = jueves</small>
                        </div>
                        
                        <div>
                            <label for="horas_semana" class="form-label">Horas por Semana *</label>
                            <input type="number" class="form-control" id="horas_semana" name="horas_semana" 
                                   required min="1" max="20" 
                                   value="<?php echo $materia['horas_semana']; ?>">
                            <small class="text-muted">Total de horas semanales</small>
                        </div>
                    </div>
                    
                    <div class="button-group">
                        <a href="<?php echo APP_URL; ?>index.php?c=materias" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Actualizar Materia
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- SIDEBAR -->
        <div>
            <div class="alert-card">
                <div class="alert-card-header">
                    <i class="fas fa-lightbulb"></i>
                    <span>Editando</span>
                </div>
                <div class="alert-card-body">
                    <h6>Impacto de Cambios</h6>
                    <p>
                        Los cambios afectarán a todos los grupos que ya tengan asignada esta materia. 
                        Si hay horarios conciliados o publicados, considera el impacto antes de guardar.
                    </p>
                </div>
            </div>
        </div>

    </div>

</div>