<?php
// =====================================================
// views/grupos/index.php
// ✅ VERSIÓN SIMPLE - Usa confirm() nativo
// =====================================================

$page_title = 'Gestión de Grupos';
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    /* Estilos base */
    :root { --primary: #2563eb; --primary-hover: #1d4ed8; --muted: #64748b; --text-main: #0f172a; --bg: #ffffff; --surface: #f8fafc; --border: #e2e8f0; --radius: 12px; }
    .page-container { font-family: "Open Sans", system-ui, Helvetica; padding: 22px; color: var(--text-main); }
    
    /* Breadcrumb */
    .breadcrumb-wrapper { margin-bottom: 16px; }
    .breadcrumb-clean { display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; font-size: 0.93rem; font-weight: 500; }
    .breadcrumb-clean .breadcrumb-item a { color: #64748b; text-decoration: none; transition: 0.15s; }
    .breadcrumb-clean .breadcrumb-item a:hover { color: #2563eb; }
    .breadcrumb-clean .breadcrumb-item + .breadcrumb-item::before { content: "›"; margin-right: 4px; color: #cbd5e1; }
    .breadcrumb-clean .active { font-weight: 700; color: #2563eb; }

    /* Header & Filters */
    .header-actions { display: flex; justify-content: space-between; align-items: center; margin-bottom: 22px; }
    .page-title { font-size: 1.45rem; font-weight: 800; margin: 0; }
    .btn-create { display: inline-flex; align-items: center; gap: 8px; background: var(--primary); color: #fff; padding: 10px 18px; border-radius: 10px; font-weight: 600; box-shadow: 0 5px 14px rgba(37,99,235,0.22); text-decoration: none; transition: 0.25s ease; }
    .btn-create:hover { background: var(--primary-hover); transform: translateY(-2px); color: #fff; }
    
    .card-box { background: var(--bg); border: 1px solid var(--border); border-radius: var(--radius); padding: 18px; margin-bottom: 22px; box-shadow: 0 1px 6px rgba(0,0,0,0.04); }
    .filters-grid { display: grid; grid-template-columns: repeat(3, 1fr) auto; gap: 16px; align-items: end; }
    .filter-label { font-size: 0.80rem; color: var(--muted); font-weight: 700; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.6px; }
    select.form-select-pro { width: 100%; height: 44px; padding: 8px 12px; border-radius: 8px; border: 1px solid #d1d9e5; background: #fff; font-size: 0.95rem; }
    .btn-filter { height: 44px; padding: 0 16px; border-radius: 8px; border: none; cursor: pointer; font-weight: 600; font-size: 0.95rem; display: inline-flex; align-items: center; gap: 8px; background: var(--primary); color: #fff; width: 100%; justify-content: center; }

    /* Table */
    .table-responsive { border: 1px solid var(--border); border-radius: 12px; overflow: hidden; }
    .table-modern { width: 100%; border-collapse: collapse; }
    .table-modern thead th { background: #f1f5f9; padding: 12px 14px; text-align: left; font-size: 0.80rem; color: var(--muted); text-transform: uppercase; border-bottom: 1px solid var(--border); font-weight: 700; }
    .table-modern tbody td { padding: 14px; border-bottom: 1px solid var(--border); font-size: 0.95rem; background: #fff; vertical-align: middle; }
    .table-modern tbody tr:hover td { background: #f8fafc; }
    
    .grupo-clave { font-weight: 700; font-family: monospace; font-size: 1rem; color: var(--primary); }
    .materia-info { display: flex; flex-direction: column; gap: 4px; }
    .materia-clave { font-weight: 600; }
    .materia-nombre { color: var(--muted); font-size: 0.88rem; }
    
    .badge-pill { padding: 6px 12px; border-radius: 20px; font-size: 0.78rem; font-weight: 700; display: inline-flex; align-items: center; gap: 6px; }
    .badge-proyectado { background: #fef3c7; color: #92400e; }
    .badge-abierto { background: #d1fae5; color: #065f46; }
    .badge-cerrado { background: #f3f4f6; color: #6b7280; }
    .badge-cancelado { background: #fee2e2; color: #991b1b; }
    
    .aula-tag { display: inline-flex; align-items: center; gap: 5px; padding: 4px 10px; background: #e0f2fe; color: #0369a1; border-radius: 6px; font-weight: 600; font-size: 0.85rem; }
    .aula-icon { color: #0ea5e9; }

    .actions-cell { display: flex; gap: 8px; }
    .btn-icon { width: 38px; height: 38px; border-radius: 8px; display: inline-flex; align-items: center; justify-content: center; border: 1px solid transparent; cursor: pointer; transition: 0.15s ease; text-decoration: none; }
    .btn-edit { background: #fff7e6; border-color: #fde0a3; }
    .btn-edit i { color: #b45309; }
    .btn-edit:hover { background: #fef3c7; transform: scale(1.05); }
    .btn-delete { background: #fee2e2; border-color: #fecaca; }
    .btn-delete i { color: #dc2626; }
    .btn-delete:hover { background: #fecaca; transform: scale(1.05); }
    
    .empty-placeholder { padding: 50px; text-align: center; background: var(--surface); }
    .empty-text { font-size: 1.05rem; color: var(--muted); margin-top: 12px; }

    @media (max-width: 900px) { 
        .filters-grid { grid-template-columns: 1fr 1fr; } 
    }
    @media (max-width: 600px) { 
        .filters-grid { grid-template-columns: 1fr; } 
        .header-actions { flex-direction: column; gap: 10px; align-items: flex-start; } 
    }
</style>

<div class="page-container">

    <div class="breadcrumb-wrapper">
        <div class="breadcrumb-clean">
            <span class="breadcrumb-item"><a href="<?php echo APP_URL; ?>">Inicio</a></span>
            <span class="breadcrumb-item active">Grupos</span>
        </div>
    </div>

    <div class="header-actions">
        <h1 class="page-title">Gestión de Grupos</h1>
        <a href="<?php echo APP_URL; ?>index.php?c=grupos&a=crear" class="btn-create">
            <i class="fa-solid fa-plus-circle"></i> Nuevo Grupo
        </a>
    </div>

    <div class="card-box">
        <form method="GET" action="index.php" class="filters-grid">
            <input type="hidden" name="c" value="grupos">

            <div>
                <label class="filter-label">Período</label>
                <select class="form-select-pro" name="periodo">
                    <option value="">Todos</option>
                    <?php 
                    $db = new Database(); $conn = $db->getConnection();
                    $periodos = $conn->query("SELECT * FROM periodos_escolares ORDER BY activo DESC, created_at DESC")->fetchAll();
                    foreach ($periodos as $p): ?>
                        <option value="<?php echo $p['id']; ?>" <?php echo (isset($_GET['periodo']) && $_GET['periodo'] == $p['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($p['nombre']); ?> <?php echo $p['activo'] ? '(Activo)' : ''; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label class="filter-label">Carrera</label>
                <select class="form-select-pro" name="carrera">
                    <option value="">Todas</option>
                    <?php 
                    $carreras = $conn->query("SELECT * FROM carreras WHERE activo = 1 ORDER BY nombre")->fetchAll();
                    foreach ($carreras as $c): ?>
                        <option value="<?php echo $c['id']; ?>" <?php echo (isset($_GET['carrera']) && $_GET['carrera'] == $c['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($c['nombre']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label class="filter-label">Semestre</label>
                <select class="form-select-pro" name="semestre">
                    <option value="">Todos</option>
                    <?php 
                    $semestres = $conn->query("SELECT * FROM semestres ORDER BY numero")->fetchAll();
                    foreach ($semestres as $s): ?>
                        <option value="<?php echo $s['id']; ?>" <?php echo (isset($_GET['semestre']) && $_GET['semestre'] == $s['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($s['nombre']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <button type="submit" class="btn-filter"><i class="fa-solid fa-filter"></i> Filtrar</button>
            </div>
        </form>
    </div>

    <div class="card-box" style="padding:0;">
        <div class="table-responsive">
            <table class="table-modern">
                <thead>
                    <tr>
                        <th>Clave</th>
                        <th>Materia</th>
                        <th>Carrera / Semestre</th>
                        <th>Aula Física</th>
                        <th>Inscritos</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($grupos)): ?>
                    <tr>
                        <td colspan="7">
                            <div class="empty-placeholder">
                                <i class="fa-regular fa-folder-open" style="font-size:45px;color:#94a3b8;"></i>
                                <div class="empty-text">No hay grupos registrados con estos filtros.</div>
                            </div>
                        </td>
                    </tr>
                    <?php else: ?>
                    <?php foreach ($grupos as $grupo): ?>
                    <tr>
                        <td class="grupo-clave">
                            <?php echo htmlspecialchars($grupo['clave']); ?>
                        </td>
                        <td>
                            <div class="materia-info">
                                <span class="materia-clave"><?php echo htmlspecialchars($grupo['materia_clave']); ?></span>
                                <span class="materia-nombre"><?php echo htmlspecialchars($grupo['materia_nombre']); ?></span>
                            </div>
                        </td>
                        <td>
                            <div><?php echo htmlspecialchars($grupo['carrera_nombre']); ?></div>
                            <small class="text-muted"><?php echo htmlspecialchars($grupo['semestre_nombre']); ?></small>
                        </td>
                        
                        <td>
                            <?php if (!empty($grupo['aula_asignada'])): ?>
                                <div class="aula-tag">
                                    <i class="fa-solid fa-door-open aula-icon"></i>
                                    <?php echo htmlspecialchars($grupo['aula_asignada']); ?>
                                </div>
                            <?php else: ?>
                                <span style="color:var(--muted); font-style:italic;">Sin asignar</span>
                            <?php endif; ?>
                        </td>

                        <td>
                            <span class="badge-pill <?php echo $grupo['alumnos_inscritos'] >= $grupo['cupo_minimo'] ? 'badge-abierto' : 'badge-proyectado'; ?>">
                                <i class="fa-solid fa-users"></i>
                                <?php echo (int)$grupo['alumnos_inscritos']; ?> / <?php echo (int)$grupo['cupo_maximo']; ?>
                            </span>
                        </td>
                        <td>
                            <?php
                            $estado_config = [
                                'proyectado' => 'badge-proyectado',
                                'abierto' => 'badge-abierto',
                                'cerrado' => 'badge-cerrado',
                                'cancelado' => 'badge-cancelado'
                            ];
                            $badge_class = $estado_config[$grupo['estado']] ?? 'badge-cerrado';
                            ?>
                            <span class="badge-pill <?php echo $badge_class; ?>">
                                <?php echo ucfirst($grupo['estado']); ?>
                            </span>
                        </td>
                        <td>
                            <div class="actions-cell">
                                <a href="<?php echo APP_URL; ?>index.php?c=grupos&a=editar&id=<?php echo $grupo['id']; ?>" 
                                   class="btn-icon btn-edit" title="Editar Grupo">
                                    <i class="fa-solid fa-pen"></i>
                                </a>

                                <!-- Confirm nativo -->
                                <form method="POST" action="<?php echo APP_URL; ?>index.php?c=grupos&a=eliminar" style="display:inline;" 
                                      onsubmit="return confirm('¿Está seguro de eliminar el grupo <?php echo htmlspecialchars($grupo['clave']); ?>?\n\n⚠️ Esta acción no se puede deshacer.');">
                                    <input type="hidden" name="id" value="<?php echo $grupo['id']; ?>">
                                    <button type="submit" class="btn-icon btn-delete" title="Eliminar Grupo">
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
