<?php

$page_title = 'Gestión de Horarios';
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    :root { --primary: #2563eb; --primary-hover: #1d4ed8; --muted: #64748b; --text-main: #0f172a; --bg: #ffffff; --surface: #f8fafc; --border: #e2e8f0; --radius: 12px; }
    .page-container { font-family: "Open Sans", system-ui, Helvetica; padding: 22px; color: var(--text-main); }
    
    /* Breadcrumb */
    .breadcrumb-wrapper { margin-bottom: 16px; }
    .breadcrumb-clean { display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; font-size: 0.93rem; font-weight: 500; }
    .breadcrumb-clean .breadcrumb-item a { color: #64748b; text-decoration: none; transition: 0.15s; }
    .breadcrumb-clean .breadcrumb-item a:hover { color: #2563eb; }
    .breadcrumb-clean .breadcrumb-item + .breadcrumb-item::before { content: "›"; margin-right: 4px; color: #cbd5e1; }
    .breadcrumb-clean .active { font-weight: 700; color: #2563eb; }

    /* Header */
    .header-section { margin-bottom: 22px; }
    .page-title { font-size: 1.45rem; font-weight: 800; margin: 0; }

    /* Alerts */
    .alert-modern { background: var(--bg); border: 1px solid var(--border); border-radius: var(--radius); padding: 18px; margin-bottom: 22px; display: flex; align-items: center; gap: 16px; }
    .alert-success { background: linear-gradient(135deg, #d1fae5, #a7f3d0); border-color: #6ee7b7; }
    .alert-warning { background: linear-gradient(135deg, #fef3c7, #fde68a); border-color: #fcd34d; }
    .alert-content h5 { margin: 0 0 8px 0; font-size: 1.1rem; font-weight: 700; color: #065f46; }
    .alert-warning .alert-content h5, .alert-warning .alert-content p { color: #92400e; }
    .alert-content p { margin: 0; color: #065f46; font-size: 0.95rem; }
    .alert-badge { background: #10b981; color: white; padding: 10px 20px; border-radius: 8px; font-weight: 700; font-size: 1rem; }

    /* Card & Form */
    .card-box { background: var(--bg); border: 1px solid var(--border); border-radius: var(--radius); padding: 20px; margin-bottom: 22px; box-shadow: 0 1px 6px rgba(0,0,0,0.04); }
    .card-header-primary { background: linear-gradient(135deg, #dbeafe, #bfdbfe); padding: 12px 16px; border-radius: 8px; margin: -20px -20px 20px -20px; }
    .card-header-modern { display: flex; align-items: center; gap: 10px; font-size: 1.05rem; font-weight: 700; color: #1e40af; }
    
    .form-grid { display: grid; grid-template-columns: 2fr 2fr 1fr; gap: 16px; align-items: end; }
    .form-label-modern { font-size: 0.80rem; color: var(--muted); font-weight: 700; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.6px; display: block; }
    .form-select-modern { width: 100%; height: 48px; padding: 8px 12px; border-radius: 8px; border: 1px solid #d1d9e5; background: #fff; font-size: 0.95rem; transition: 0.15s ease; }
    .form-select-modern:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(37,99,235,0.20); outline: none; }
    
    .btn-primary-modern { display: inline-flex; align-items: center; justify-content: center; gap: 8px; background: var(--primary); color: #fff; padding: 12px 20px; border-radius: 8px; font-weight: 600; border: none; cursor: pointer; box-shadow: 0 4px 12px rgba(37,99,235,0.22); transition: 0.25s ease; text-decoration: none; font-size: 0.95rem; width: 100%; height: 48px; }
    .btn-primary-modern:hover:not(:disabled) { background: var(--primary-hover); transform: translateY(-2px); color: #fff; }
    .btn-primary-modern:disabled { opacity: 0.5; cursor: not-allowed; }

    @media (max-width: 900px) { .form-grid { grid-template-columns: 1fr; } }
</style>

<div class="page-container">

    <div class="breadcrumb-wrapper">
        <div class="breadcrumb-clean">
            <span class="breadcrumb-item"><a href="<?php echo APP_URL; ?>">Inicio</a></span>
            <span class="breadcrumb-item active">Horarios</span>
        </div>
    </div>

    <div class="header-section">
        <h1 class="page-title">Gestión de Horarios</h1>
    </div>

    <?php if ($periodo_activo): ?>
    <div class="alert-modern alert-success">
        <div class="alert-content">
            <h5><i class="fas fa-calendar-check"></i> Período Activo: <?php echo htmlspecialchars($periodo_activo['nombre']); ?></h5>
            <p><strong>Inicio:</strong> <?php echo date('d/m/Y', strtotime($periodo_activo['fecha_inicio'])); ?> | <strong>Fin:</strong> <?php echo date('d/m/Y', strtotime($periodo_activo['fecha_fin'])); ?></p>
        </div>
        <div class="alert-badge">ACTIVO</div>
    </div>
    <?php else: ?>
    <div class="alert-modern alert-warning">
        <div class="alert-content">
            <h5><i class="fas fa-exclamation-triangle"></i> <strong>No hay un período escolar activo.</strong></h5>
            <p>Configure un período en la base de datos antes de continuar.</p>
        </div>
    </div>
    <?php endif; ?>

    <div class="card-box">
        <div class="card-header-primary">
            <div class="card-header-modern">
                <i class="fas fa-filter"></i>
                <span>Seleccionar Horario a Gestionar</span>
            </div>
        </div>

        <form method="GET" action="<?php echo APP_URL; ?>index.php">
            <input type="hidden" name="c" value="horarios">
            <input type="hidden" name="a" value="asignar">
            
            <?php if ($periodo_activo): ?>
                <input type="hidden" name="periodo" value="<?php echo $periodo_activo['id']; ?>">
            <?php endif; ?>

            <div class="form-grid">
                <div>
                    <label class="form-label-modern">Carrera *</label>
                    <select class="form-select-modern" name="carrera" required>
                        <option value="">Seleccione una carrera...</option>
                        <?php foreach ($carreras as $carrera): ?>
                            <option value="<?php echo $carrera['id']; ?>">
                                <?php echo htmlspecialchars($carrera['clave']); ?> - <?php echo htmlspecialchars($carrera['nombre']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label class="form-label-modern">Semestre *</label>
                    <select class="form-select-modern" name="semestre" required>
                        <option value="">Seleccione un semestre...</option>
                        <?php foreach ($semestres as $semestre): ?>
                            <option value="<?php echo $semestre['id']; ?>">
                                <?php echo htmlspecialchars($semestre['nombre']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <button type="submit" class="btn-primary-modern" <?php echo !$periodo_activo ? 'disabled' : ''; ?>>
                        <i class="fas fa-arrow-right"></i> Continuar
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>