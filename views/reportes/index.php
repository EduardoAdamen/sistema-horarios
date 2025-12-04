<?php
// =====================================================
// views/reportes/index.php
// Menú principal de reportes - DISEÑO PROFESIONAL MODERNO
// =====================================================

$page_title = 'Reportes';
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
    .header-section {
        margin-bottom: 22px;
    }

    .page-title {
        font-size: 1.45rem;
        font-weight: 800;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .page-title i {
        color: var(--primary);
    }

    /* --------------------------- ALERT --------------------------- */
    .alert-info-modern {
        background: #eff6ff;
        border: 1px solid #bfdbfe;
        border-radius: var(--radius);
        padding: 16px 18px;
        margin-bottom: 22px;
        display: flex;
        align-items: center;
        gap: 12px;
        color: #1e40af;
    }

    .alert-info-modern i {
        font-size: 1.2rem;
    }

    /* --------------------------- CARD --------------------------- */
    .card-box {
        background: var(--bg);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 20px;
        margin-bottom: 22px;
        box-shadow: 0 1px 6px rgba(0,0,0,0.04);
    }

    .card-header-modern {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 1.05rem;
        font-weight: 700;
        margin-bottom: 12px;
    }

    .card-header-primary {
        background: linear-gradient(135deg, #dbeafe, #bfdbfe);
        padding: 14px 18px;
        border-radius: 10px;
        margin: -20px -20px 18px -20px;
        color: #1e40af;
    }

    .card-header-success {
        background: linear-gradient(135deg, #d1fae5, #a7f3d0);
        padding: 14px 18px;
        border-radius: 10px;
        margin: -20px -20px 18px -20px;
        color: #065f46;
    }

    .card-header-info {
        background: linear-gradient(135deg, #dbeafe, #bfdbfe);
        padding: 14px 18px;
        border-radius: 10px;
        margin: -20px -20px 18px -20px;
        color: #1e40af;
    }

    .card-header-warning {
        background: linear-gradient(135deg, #fef3c7, #fde68a);
        padding: 14px 18px;
        border-radius: 10px;
        margin: -20px -20px 18px -20px;
        color: #92400e;
    }

    .card-description {
        color: var(--muted);
        font-size: 0.95rem;
        margin-bottom: 20px;
        line-height: 1.6;
    }

    /* --------------------------- FORM --------------------------- */
    .form-grid {
        display: grid;
        gap: 16px;
        margin-bottom: 20px;
    }

    .form-grid-3 {
        grid-template-columns: repeat(3, 1fr);
    }

    .form-grid-2 {
        grid-template-columns: repeat(2, 1fr);
    }

    .form-grid-2-auto {
        grid-template-columns: 1fr 1fr;
    }

    .form-label-modern {
        font-size: 0.80rem;
        color: var(--muted);
        font-weight: 700;
        margin-bottom: 8px;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        display: block;
    }

    .form-select-modern {
        width: 100%;
        height: 44px;
        padding: 8px 12px;
        border-radius: 8px;
        border: 1px solid #d1d9e5;
        background: #fff;
        font-size: 0.95rem;
        transition: 0.15s ease;
    }

    .form-select-modern:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(37,99,235,0.20);
        outline: none;
    }

    /* --------------------------- BUTTON GROUP --------------------------- */
    .btn-group-modern {
        display: flex;
        gap: 10px;
    }

    .btn-modern {
        flex: 1;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 10px 16px;
        border-radius: 8px;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: 0.25s ease;
        text-decoration: none;
        font-size: 0.90rem;
    }

    .btn-modern:hover {
        transform: translateY(-2px);
    }

    .btn-primary { background: #2563eb; color: #fff; box-shadow: 0 4px 12px rgba(37,99,235,0.3); }
    .btn-primary:hover { background: #1d4ed8; color: #fff; }

    .btn-success { background: #10b981; color: #fff; box-shadow: 0 4px 12px rgba(16,185,129,0.3); }
    .btn-success:hover { background: #059669; color: #fff; }

    .btn-info { background: #3b82f6; color: #fff; box-shadow: 0 4px 12px rgba(59,130,246,0.3); }
    .btn-info:hover { background: #2563eb; color: #fff; }

    .btn-danger { background: #ef4444; color: #fff; box-shadow: 0 4px 12px rgba(239,68,68,0.3); }
    .btn-danger:hover { background: #dc2626; color: #fff; }

    .btn-warning { background: #f59e0b; color: #fff; box-shadow: 0 4px 12px rgba(245,158,11,0.3); }
    .btn-warning:hover { background: #d97706; color: #fff; }

    /* --------------------------- INFO CARD --------------------------- */
    .info-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 20px;
    }

    .info-card h6 {
        font-weight: 700;
        color: var(--text-main);
        margin-bottom: 12px;
        font-size: 1rem;
    }

    .info-card ul {
        padding-left: 20px;
        margin: 12px 0;
    }

    .info-card ul li {
        margin-bottom: 8px;
        color: var(--text-main);
        line-height: 1.6;
    }

    .info-card hr {
        border: 0;
        border-top: 1px solid var(--border);
        margin: 16px 0;
    }

    /* --------------------------- RESPONSIVE --------------------------- */
    @media (max-width: 900px) {
        .form-grid-3 { grid-template-columns: 1fr; }
        .form-grid-2 { grid-template-columns: 1fr; }
        .form-grid-2-auto { grid-template-columns: 1fr; }
        .btn-group-modern { flex-direction: column; }
    }
</style>

<div class="page-container">

    <!-- BREADCRUMB -->
    <div class="breadcrumb-wrapper">
        <div class="breadcrumb-clean">
            <span class="breadcrumb-item">
                <a href="<?php echo APP_URL; ?>">Inicio</a>
            </span>
            <span class="breadcrumb-item active">Reportes</span>
        </div>
    </div>

    <!-- HEADER -->
    <div class="header-section">
        <h1 class="page-title">
             Generación de Reportes
        </h1>
    </div>

    <!-- ALERT INFO -->
    <div class="alert-info-modern">
        <i class="fas fa-info-circle"></i>
        <span>
            <strong>Seleccione el tipo de reporte</strong> que desea generar. 
            Todos los reportes pueden exportarse a PDF o Excel.
        </span>
    </div>

    <!-- REPORTE 1: HORARIO GENERAL -->
    <div class="card-box">
        <div class="card-header-primary">
            <div class="card-header-modern">
                <i class="fas fa-calendar-week"></i>
                <span>Horario General por Carrera y Semestre</span>
            </div>
        </div>

        <p class="card-description">
            Genera el horario completo de una carrera y semestre específico. 
            Incluye todas las materias, docentes y aulas asignadas.
        </p>

        <form method="GET" action="<?php echo APP_URL; ?>index.php">
            <input type="hidden" name="c" value="reportes">
            <input type="hidden" name="a" value="horarioGeneral">

            <div class="form-grid form-grid-3">
                <div>
                    <label class="form-label-modern">Período *</label>
                    <select class="form-select-modern" name="periodo" required>
                        <option value="">Seleccione...</option>
                        <?php foreach ($periodos as $periodo): ?>
                            <option value="<?php echo $periodo['id']; ?>" <?php echo $periodo['activo'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($periodo['nombre']); ?>
                                <?php echo $periodo['activo'] ? '(Activo)' : ''; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label class="form-label-modern">Carrera *</label>
                    <select class="form-select-modern" name="carrera" required>
                        <option value="">Seleccione...</option>
                        <?php foreach ($carreras as $carrera): ?>
                            <option value="<?php echo $carrera['id']; ?>">
                                <?php echo htmlspecialchars($carrera['nombre']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label class="form-label-modern">Semestre *</label>
                    <select class="form-select-modern" name="semestre" required>
                        <option value="">Seleccione...</option>
                        <?php foreach ($semestres as $semestre): ?>
                            <option value="<?php echo $semestre['id']; ?>">
                                <?php echo htmlspecialchars($semestre['nombre']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="btn-group-modern">
                <button type="submit" name="formato" value="html" class="btn-modern btn-primary">
                    <i class="fas fa-eye"></i> Ver en Pantalla
                </button>
                <button type="submit" name="formato" value="pdf" class="btn-modern btn-danger">
                    <i class="fas fa-file-pdf"></i> Generar PDF
                </button>
                <button type="submit" name="formato" value="excel" class="btn-modern btn-success">
                    <i class="fas fa-file-excel"></i> Exportar a Excel
                </button>
            </div>
        </form>
    </div>

    <!-- REPORTE 2: HORARIO POR DOCENTE -->
    <div class="card-box">
        <div class="card-header-success">
            <div class="card-header-modern">
                <i class="fas fa-user-tie"></i>
                <span>Horario por Docente</span>
            </div>
        </div>

        <p class="card-description">
            Genera el horario individual de un docente específico. 
            Incluye todas sus asignaciones y carga horaria total.
        </p>

        <form method="GET" action="<?php echo APP_URL; ?>index.php">
            <input type="hidden" name="c" value="reportes">
            <input type="hidden" name="a" value="horarioDocente">

            <div class="form-grid form-grid-2">
                <div>
                    <label class="form-label-modern">Período *</label>
                    <select class="form-select-modern" name="periodo" required>
                        <option value="">Seleccione...</option>
                        <?php foreach ($periodos as $periodo): ?>
                            <option value="<?php echo $periodo['id']; ?>" <?php echo $periodo['activo'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($periodo['nombre']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label class="form-label-modern">Docente *</label>
                    <select class="form-select-modern" name="docente" required>
                        <option value="">Seleccione...</option>
                        <?php foreach ($docentes as $docente): ?>
                            <option value="<?php echo $docente['id']; ?>">
                                <?php echo htmlspecialchars($docente['numero_empleado']); ?> - 
                                <?php echo htmlspecialchars($docente['nombre'] . ' ' . $docente['apellido_paterno']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="btn-group-modern">
                <button type="submit" name="formato" value="html" class="btn-modern btn-success">
                    <i class="fas fa-eye"></i> Ver en Pantalla
                </button>
                <button type="submit" name="formato" value="pdf" class="btn-modern btn-danger">
                    <i class="fas fa-file-pdf"></i> Generar PDF
                </button>
                <button type="submit" name="formato" value="excel" class="btn-modern btn-success">
                    <i class="fas fa-file-excel"></i> Exportar a Excel
                </button>
            </div>
        </form>
    </div>

    <!-- REPORTE 3: HORARIO POR AULA -->
    <div class="card-box">
        <div class="card-header-info">
            <div class="card-header-modern">
                <i class="fas fa-door-open"></i>
                <span>Horario por Aula</span>
            </div>
        </div>

        <p class="card-description">
            Muestra la ocupación de un aula específica durante la semana. 
            Útil para verificar disponibilidad y porcentaje de uso.
        </p>

        <form method="GET" action="<?php echo APP_URL; ?>index.php">
            <input type="hidden" name="c" value="reportes">
            <input type="hidden" name="a" value="horarioAula">

            <div class="form-grid form-grid-2">
                <div>
                    <label class="form-label-modern">Período *</label>
                    <select class="form-select-modern" name="periodo" required>
                        <option value="">Seleccione...</option>
                        <?php foreach ($periodos as $periodo): ?>
                            <option value="<?php echo $periodo['id']; ?>" <?php echo $periodo['activo'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($periodo['nombre']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label class="form-label-modern">Aula *</label>
                    <select class="form-select-modern" name="aula" required>
                        <option value="">Seleccione...</option>
                        <?php
                        $db = new Database();
                        $conn = $db->getConnection();
                        $aulas = $conn->query("SELECT * FROM aulas WHERE activo = 1 ORDER BY edificio, numero")->fetchAll();
                        foreach ($aulas as $aula):
                        ?>
                            <option value="<?php echo $aula['id']; ?>">
                                <?php echo htmlspecialchars($aula['edificio'] . '-' . $aula['numero']); ?> 
                                (<?php echo ucfirst($aula['tipo']); ?>, Cap: <?php echo $aula['capacidad']; ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="btn-group-modern">
                <button type="submit" name="formato" value="html" class="btn-modern btn-info">
                    <i class="fas fa-eye"></i> Ver en Pantalla
                </button>
                <button type="submit" name="formato" value="pdf" class="btn-modern btn-danger">
                    <i class="fas fa-file-pdf"></i> Generar PDF
                </button>
                <button type="submit" name="formato" value="excel" class="btn-modern btn-success">
                    <i class="fas fa-file-excel"></i> Exportar a Excel
                </button>
            </div>
        </form>
    </div>

    <!-- REPORTE 4: CARGA HORARIA DE DOCENTES -->
    <div class="card-box">
        <div class="card-header-warning">
            <div class="card-header-modern">
                <i class="fas fa-chart-bar"></i>
                <span>Carga Horaria de Todos los Docentes</span>
            </div>
        </div>

        <p class="card-description">
            Reporte consolidado de la carga horaria de todos los docentes. 
            Muestra horas asignadas vs. horas máximas permitidas.
        </p>

        <form method="GET" action="<?php echo APP_URL; ?>index.php">
            <input type="hidden" name="c" value="reportes">
            <input type="hidden" name="a" value="cargaDocentes">

            <div class="form-grid form-grid-2-auto">
                <div>
                    <label class="form-label-modern">Período *</label>
                    <select class="form-select-modern" name="periodo" required>
                        <option value="">Seleccione...</option>
                        <?php foreach ($periodos as $periodo): ?>
                            <option value="<?php echo $periodo['id']; ?>" <?php echo $periodo['activo'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($periodo['nombre']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div style="display: flex; align-items: end;">
                    <div class="btn-group-modern" style="width: 100%;">
                        <button type="submit" name="formato" value="html" class="btn-modern btn-warning">
                            <i class="fas fa-eye"></i> Ver
                        </button>
                        <button type="submit" name="formato" value="pdf" class="btn-modern btn-danger">
                            <i class="fas fa-file-pdf"></i> PDF
                        </button>
                        <button type="submit" name="formato" value="excel" class="btn-modern btn-success">
                            <i class="fas fa-file-excel"></i> Excel
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

   

</div>