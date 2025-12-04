<?php
// =====================================================
// views/aulas/index.php
// Listado de aulas - DISEÑO PROFESIONAL MODERNO
// =====================================================

$page_title = 'Gestión de Aulas';
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

    /* Separador "›" automático */
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

    /* --------------------------- CARD / FILTROS --------------------------- */
    .card-box {
        background: var(--bg);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 18px;
        margin-bottom: 22px;
        box-shadow: 0 1px 6px rgba(0,0,0,0.04);
    }

    .filters-grid {
        display: grid;
        grid-template-columns: 1fr auto;
        gap: 16px;
        align-items: end;
    }

    .filter-label {
        font-size: 0.80rem;
        color: var(--muted);
        font-weight: 700;
        margin-bottom: 6px;
        text-transform: uppercase;
        letter-spacing: 0.6px;
    }

    select.form-select-pro,
    input.form-control-pro {
        width: 100%;
        height: 44px;
        padding: 8px 12px;
        border-radius: 8px;
        border: 1px solid #d1d9e5;
        background: #fff;
        font-size: 0.95rem;
        transition: 0.15s ease;
    }

    select.form-select-pro:focus,
    .form-control-pro:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(37,99,235,0.20);
        outline: none;
    }

    /* BOTONES DE FILTRO */
    .filter-buttons-container {
        display: flex;
        gap: 10px;
        justify-content: flex-end;
        align-items: center;
    }

    .btn-filter {
        height: 44px;
        padding: 0 16px;
        border-radius: 8px;
        border: none;
        cursor: pointer;
        font-weight: 600;
        font-size: 0.95rem;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
    }

    .btn-filter i { font-size: 15px; }

    .btn-apply {
        background: var(--primary);
        color: #fff;
    }
    .btn-apply:hover {
        background: var(--primary-hover);
    }

    .btn-clean {
        background: #f1f5f9;
        color: var(--muted);
        border: 1px solid var(--border);
    }
    .btn-clean:hover {
        background: #e5eaf0;
        color: var(--text-main);
    }

    /* --------------------------- TABLA --------------------------- */
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

    .aula-identificador {
        font-weight: 700;
        font-family: monospace;
        font-size: 1rem;
        color: var(--primary);
    }

    .aula-edificio { 
        font-weight: 600; 
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
    
    .badge-normal { background: #f3f4f6; color: #6b7280; }
    .badge-laboratorio { background: #dbeafe; color: #1e40af; }
    .badge-taller { background: #fef3c7; color: #92400e; }
    .badge-auditorio { background: #d1fae5; color: #065f46; }

    .capacity-badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.78rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #e1ecff;
        color: #1d4ed8;
    }

    /* --------------------------- ACCIONES --------------------------- */
    .actions-cell { display: flex; gap: 10px; }

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
    }

    .btn-icon:hover { transform: translateY(-2px); }

    .btn-edit { background: #fff7e6; border-color: #fde0a3; }
    .btn-edit i { color: #b45309; }

    .btn-delete { background: #ffe4e7; border-color: #fecdd3; }
    .btn-delete i { color: #be123c; }

    /* --------------------------- EMPTY STATE --------------------------- */
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

    /* --------------------------- RESPONSIVE --------------------------- */
    @media (max-width: 900px) {
        .filters-grid { grid-template-columns: 1fr; }
        .filter-buttons-container { justify-content: flex-start; }
    }

    @media (max-width: 600px) {
        .filters-grid { grid-template-columns: 1fr; }
        .header-actions { flex-direction: column; gap: 10px; align-items: flex-start; }
    }
</style>

<div class="page-container">

    <!-- BREADCRUMB -->
    <div class="breadcrumb-wrapper">
        <div class="breadcrumb-clean">
            <span class="breadcrumb-item">
                <a href="<?php echo APP_URL; ?>">Inicio</a>
            </span>
            <span class="breadcrumb-item active">Aulas</span>
        </div>
    </div>

    <!-- HEADER -->
    <div class="header-actions">
        <h1 class="page-title">Gestión de Aulas</h1>

        <a href="<?php echo APP_URL; ?>index.php?c=aulas&a=crear" 
            class="btn-create">
            <i class="fa-solid fa-plus-circle"></i> Nueva Aula
        </a>
    </div>

    <!-- FILTROS -->
    <div class="card-box">
        <form method="GET" action="index.php" class="filters-grid">
            <input type="hidden" name="c" value="aulas">

            <div>
                <label class="filter-label">Tipo de Aula</label>
                <select class="form-select-pro" name="tipo">
                    <option value="">Todos los tipos</option>
                    <option value="normal" <?php echo ($tipo_filtro == 'normal') ? 'selected' : ''; ?>>Normal</option>
                    <option value="laboratorio" <?php echo ($tipo_filtro == 'laboratorio') ? 'selected' : ''; ?>>Laboratorio</option>
                    <option value="taller" <?php echo ($tipo_filtro == 'taller') ? 'selected' : ''; ?>>Taller</option>
                    <option value="auditorio" <?php echo ($tipo_filtro == 'auditorio') ? 'selected' : ''; ?>>Auditorio</option>
                </select>
            </div>

            <div class="filter-buttons-container">
                <button type="submit" class="btn-filter btn-apply">
                    <i class="fa-solid fa-magnifying-glass"></i> Filtrar
                </button>

                <a href="<?php echo APP_URL; ?>index.php?c=aulas" class="btn-filter btn-clean">
                    <i class="fa-solid fa-eraser"></i> Limpiar
                </a>
            </div>
        </form>
    </div>

    <!-- TABLA -->
    <div class="card-box" style="padding:0;">
        <div class="table-responsive">
            <table class="table-modern">
                <thead>
                    <tr>
                        <th>Identificador</th>
                        <th>Edificio</th>
                        <th>Número</th>
                        <th>Tipo</th>
                        <th>Capacidad</th>
                        <th>Equipamiento</th>
                        <th>Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if (empty($aulas)): ?>
                    <tr>
                        <td colspan="7">
                            <div class="empty-placeholder">
                                <i class="fa-regular fa-folder-open" style="font-size:45px;color:#94a3b8;"></i>
                                <div class="empty-text">No se encontraron aulas.</div>
                            </div>
                        </td>
                    </tr>
                    <?php else: ?>

                    <?php foreach ($aulas as $aula): ?>
                    <tr>
                        <td class="aula-identificador">
                            <?php echo htmlspecialchars($aula['edificio'] . '-' . $aula['numero']); ?>
                        </td>

                        <td class="aula-edificio">
                            <?php echo htmlspecialchars($aula['edificio']); ?>
                        </td>

                        <td><?php echo htmlspecialchars($aula['numero']); ?></td>

                        <td>
                            <?php
                            $badge_config = [
                                'normal' => ['class' => 'badge-normal', 'icon' => 'fa-chalkboard'],
                                'laboratorio' => ['class' => 'badge-laboratorio', 'icon' => 'fa-flask'],
                                'taller' => ['class' => 'badge-taller', 'icon' => 'fa-tools'],
                                'auditorio' => ['class' => 'badge-auditorio', 'icon' => 'fa-building']
                            ];
                            $config = $badge_config[$aula['tipo']] ?? $badge_config['normal'];
                            ?>
                            <span class="badge-pill <?php echo $config['class']; ?>">
                                <i class="fas <?php echo $config['icon']; ?>"></i>
                                <?php echo ucfirst($aula['tipo']); ?>
                            </span>
                        </td>

                        <td>
                            <span class="capacity-badge">
                                <i class="fas fa-users"></i>
                                <?php echo (int)$aula['capacidad']; ?> personas
                            </span>
                        </td>

                        <td>
                            <?php if (!empty($aula['equipamiento'])): ?>
                                <?php echo htmlspecialchars(substr($aula['equipamiento'], 0, 50)); ?>
                                <?php if (strlen($aula['equipamiento']) > 50) echo '...'; ?>
                            <?php else: ?>
                                <span style="color: #9ca3af; font-style: italic;">Sin especificar</span>
                            <?php endif; ?>
                        </td>

                        <td>
                            <div class="actions-cell">
                                <a href="<?php echo APP_URL; ?>index.php?c=aulas&a=editar&id=<?php echo $aula['id']; ?>" 
                                    class="btn-icon btn-edit"
                                    title="Editar">
                                    <i class="fa-solid fa-pen"></i>
                                </a>

                                <form method="POST" 
                                    action="<?php echo APP_URL; ?>index.php?c=aulas&a=eliminar"
                                    onsubmit="return confirm('¿Eliminar aula <?php echo addslashes($aula['edificio'] . '-' . $aula['numero']); ?>?');"
                                    style="display:inline;">
                                    
                                    <input type="hidden" name="id" value="<?php echo $aula['id']; ?>">

                                    <button type="submit" class="btn-icon btn-delete" title="Eliminar">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
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