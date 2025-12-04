<?php
// =====================================================
// views/dashboard/index.php
// Vista del Dashboard principal - REDISEÑADO MODERNO
// =====================================================

$page_title = 'Dashboard';

// Obtener estadísticas básicas
$db = new Database();
$conn = $db->getConnection();

// Periodo activo
$sql = "SELECT * FROM periodos_escolares WHERE activo = 1 LIMIT 1";
$stmt = $conn->query($sql);
$periodo_activo = $stmt->fetch();

// Contar registros
$stats = [];
$stats['materias'] = $conn->query("SELECT COUNT(*) FROM materias WHERE activo = 1")->fetchColumn();
$stats['docentes'] = $conn->query("SELECT COUNT(*) FROM docentes WHERE activo = 1")->fetchColumn();
$stats['aulas'] = $conn->query("SELECT COUNT(*) FROM aulas WHERE activo = 1")->fetchColumn();

if ($periodo_activo) {
    $stats['grupos'] = $conn->query("SELECT COUNT(*) FROM grupos WHERE periodo_id = {$periodo_activo['id']} AND estado != 'cancelado'")->fetchColumn();
    $stats['horarios_borrador'] = $conn->query("SELECT COUNT(*) FROM horarios WHERE periodo_id = {$periodo_activo['id']} AND estado = 'borrador'")->fetchColumn();
    $stats['horarios_conciliados'] = $conn->query("SELECT COUNT(*) FROM horarios WHERE periodo_id = {$periodo_activo['id']} AND estado = 'conciliado'")->fetchColumn();
    $stats['horarios_publicados'] = $conn->query("SELECT COUNT(*) FROM horarios WHERE periodo_id = {$periodo_activo['id']} AND estado = 'publicado'")->fetchColumn();
}
?>

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

/* Título de bienvenida */
.welcome-section {
    margin-bottom: 22px;
}

.welcome-title {
    font-size: 1.45rem;
    font-weight: 800;
    color: var(--text-main);
    margin: 0;
}

/* Alertas */
.alert-compact {
    background: #fef3c7;
    border: 1px solid #fde68a;
    border-left: 4px solid #f59e0b;
    border-radius: 10px;
    padding: 14px 16px;
    color: #92400e;
    font-size: 0.92rem;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.alert-compact i {
    font-size: 18px;
}

/* Período Activo */
.period-card {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    border-radius: var(--radius);
    padding: 18px 22px;
    color: white;
    margin-bottom: 24px;
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2);
}

.period-card h6 {
    font-size: 1rem;
    font-weight: 700;
    margin: 0 0 8px 0;
}

.period-dates {
    font-size: 0.88rem;
    opacity: 0.95;
    font-weight: 500;
}

/* Estadísticas */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
    margin-bottom: 24px;
}

.stat-card {
    background: var(--bg);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    padding: 20px;
    transition: all 0.2s ease;
    box-shadow: 0 1px 6px rgba(0,0,0,0.04);
}

.stat-card:hover {
    border-color: var(--primary);
    box-shadow: 0 4px 12px rgba(37, 99, 235, 0.15);
    transform: translateY(-2px);
}

.stat-label {
    font-size: 0.82rem;
    color: var(--muted);
    font-weight: 700;
    margin-bottom: 10px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.stat-value {
    font-size: 2rem;
    font-weight: 800;
    color: var(--text-main);
    line-height: 1;
}

/* Estado de Horarios */
.horarios-card {
    background: var(--bg);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    padding: 24px;
    margin-bottom: 24px;
    box-shadow: 0 1px 6px rgba(0,0,0,0.04);
}

.section-header {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 20px;
    padding-bottom: 14px;
    border-bottom: 2px solid var(--border);
}

.section-header i {
    color: var(--primary);
    font-size: 20px;
}

.section-header h6 {
    font-size: 1.1rem;
    font-weight: 700;
    color: var(--text-main);
    margin: 0;
}

.horarios-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 16px;
}

.horario-item {
    text-align: center;
    padding: 20px;
    background: var(--surface);
    border-radius: 10px;
    border: 1px solid var(--border);
}

.horario-value {
    font-size: 2.2rem;
    font-weight: 800;
    margin-bottom: 8px;
    line-height: 1;
}

.horario-label {
    font-size: 0.85rem;
    color: var(--muted);
    font-weight: 600;
}

/* Acciones Rápidas */
.actions-section {
    margin-bottom: 24px;
}

.actions-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 200px));
    gap: 16px;
    justify-content: center;
}

.action-btn {
    background: var(--bg);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    padding: 18px 16px;
    text-align: center;
    text-decoration: none;
    color: var(--text-main);
    transition: all 0.25s ease;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 10px;
    box-shadow: 0 1px 6px rgba(0,0,0,0.04);
}

.action-btn:hover {
    background: var(--primary);
    border-color: var(--primary);
    color: white;
    transform: translateY(-3px);
    box-shadow: 0 6px 16px rgba(37, 99, 235, 0.25);
}

.action-btn i {
    font-size: 24px;
    transition: transform 0.25s ease;
}

.action-btn:hover i {
    transform: scale(1.1);
}

.action-btn span {
    font-size: 0.88rem;
    font-weight: 700;
}

/* Responsive */
@media (max-width: 1200px) {
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 768px) {
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .horarios-grid {
        grid-template-columns: 1fr;
    }

    .actions-grid {
        grid-template-columns: 1fr;
    }

    .welcome-title {
        font-size: 1.25rem;
    }
}
</style>

<div class="page-container" style="font-family: 'Open Sans', system-ui, Helvetica; padding: 0; color: var(--text-main);">

<!-- Alertas -->
<?php if (isset($_GET['error'])): ?>
<div class="alert-compact">
    <i class="fas fa-exclamation-triangle"></i>
    <span>
    <?php 
    switch ($_GET['error']) {
        case 'access':
            echo 'No tiene permisos para acceder a esa sección';
            break;
        default:
            echo 'Ha ocurrido un error';
    }
    ?>
    </span>
</div>
<?php endif; ?>

<!-- Bienvenida -->
<div class="welcome-section">
    <h1 class="welcome-title">¡Bienvenido, <?php echo $_SESSION['nombre_completo']; ?>!</h1>
</div>

<!-- Período Activo -->
<?php if ($periodo_activo): ?>
<div class="period-card">
    <h6><i class="fas fa-calendar-alt"></i> <?php echo $periodo_activo['nombre']; ?></h6>
    <div class="period-dates">
        <?php echo date('d/m/Y', strtotime($periodo_activo['fecha_inicio'])); ?> 
        - 
        <?php echo date('d/m/Y', strtotime($periodo_activo['fecha_fin'])); ?>
    </div>
</div>
<?php else: ?>
<div class="alert-compact">
    <i class="fas fa-exclamation-triangle"></i>
    <span>No hay ningún período escolar activo configurado.</span>
</div>
<?php endif; ?>

<!-- Acciones Rápidas -->
<div class="actions-section">
    <div class="actions-grid">
        <?php if (Auth::hasAnyRole([ROLE_SUBDIRECTOR, ROLE_JEFE_DEPTO])): ?>
        <a href="<?php echo APP_URL; ?>index.php?c=horarios" class="action-btn">
            <i class="fas fa-calendar"></i>
            <span>Gestionar Horarios</span>
        </a>
        <?php endif; ?>
        
        <!-- Eliminado botón de Cargar Demanda -->
        
        <a href="<?php echo APP_URL; ?>index.php?c=reportes" class="action-btn">
            <i class="fas fa-file-pdf"></i>
            <span>Ver Reportes</span>
        </a>
    </div>
</div>

<!-- Estadísticas -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-label">Materias Activas</div>
        <div class="stat-value"><?php echo $stats['materias']; ?></div>
    </div>
    
    <div class="stat-card">
        <div class="stat-label">Docentes Activos</div>
        <div class="stat-value"><?php echo $stats['docentes']; ?></div>
    </div>
    
    <div class="stat-card">
        <div class="stat-label">Aulas Disponibles</div>
        <div class="stat-value"><?php echo $stats['aulas']; ?></div>
    </div>
    
    <div class="stat-card">
        <div class="stat-label">Grupos Activos</div>
        <div class="stat-value"><?php echo $stats['grupos'] ?? 0; ?></div>
    </div>
</div>

<?php if ($periodo_activo && Auth::hasAnyRole([ROLE_SUBDIRECTOR, ROLE_JEFE_DEPTO])): ?>
<!-- Estado de Horarios -->
<div class="horarios-card">
    <div class="section-header">
        <i class="fas fa-chart-bar"></i>
        <h6>Estado de Horarios</h6>
    </div>
    <div class="horarios-grid">
        <div class="horario-item">
            <div class="horario-value" style="color: #6b7280;">
                <?php echo $stats['horarios_borrador']; ?>
            </div>
            <div class="horario-label">Borradores</div>
        </div>
        <div class="horario-item">
            <div class="horario-value" style="color: #2563eb;">
                <?php echo $stats['horarios_conciliados']; ?>
            </div>
            <div class="horario-label">Conciliados</div>
        </div>
        <div class="horario-item">
            <div class="horario-value" style="color: #10b981;">
                <?php echo $stats['horarios_publicados']; ?>
            </div>
            <div class="horario-label">Publicados</div>
        </div>
    </div>
</div>
<?php endif; ?>

</div>