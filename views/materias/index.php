<?php  

$page_title = 'Gestión de Materias';
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
    .btn-create:hover { background: var(--primary-hover); transform: translateY(-2px); }

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
        grid-template-columns: 1fr 1fr auto;
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

    .materia-clave {
        font-weight: 700;
        font-family: monospace;
        font-size: 1rem;
    }

    .materia-nombre { font-weight: 600; }

    .badge-pill {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.78rem;
        font-weight: 700;
        display: inline-block;
    }
    .badge-blue { background: #e1ecff; color: #1d4ed8; }

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
        .filters-grid { grid-template-columns: 1fr 1fr; }
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
                <a href="#">Inicio</a>
             </span>
        <span class="breadcrumb-item active">Materias</span>
    </div>
    </div>


    <!-- HEADER -->
    <div class="header-actions">
        <h1 class="page-title">Gestión de Materias</h1>

        <a href="<?php echo APP_URL; ?>index.php?c=materias&a=crear" 
            class="btn-create">
            <i class="fa-solid fa-plus-circle"></i> Nueva Materia
        </a>
    </div>

    <!-- FILTROS -->
    <div class="card-box">
        <form method="GET" action="index.php" class="filters-grid">
            <input type="hidden" name="c" value="materias">

            <div>
                <label class="filter-label">Carrera</label>
                <select class="form-select-pro" name="carrera">
                    <option value="">Todas las carreras</option>
                    <?php foreach ($carreras as $carrera): ?>
                    <option value="<?php echo $carrera['id']; ?>" 
                        <?php echo ($carrera_id_filtro == $carrera['id']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($carrera['nombre']); ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label class="filter-label">Semestre</label>
                <select class="form-select-pro" name="semestre">
                    <option value="">Todos los semestres</option>
                    <?php foreach ($semestres as $semestre): ?>
                    <option value="<?php echo $semestre['id']; ?>" 
                        <?php echo ($semestre_id_filtro == $semestre['id']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($semestre['nombre']); ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="filter-buttons-container">
                <button type="submit" class="btn-filter btn-apply">
                    <i class="fa-solid fa-magnifying-glass"></i> Filtrar
                </button>

                <a href="<?php echo APP_URL; ?>index.php?c=materias" class="btn-filter btn-clean">
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
                        <th>Clave</th>
                        <th>Nombre</th>
                        <th>Créditos</th>
                        <th>Horas/Sem.</th>
                        <th>Carrera</th>
                        <th>Semestre</th>
                        <th>Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if (empty($materias)): ?>
                    <tr>
                        <td colspan="7">
                            <div class="empty-placeholder">
                                <i class="fa-regular fa-folder-open" style="font-size:45px;color:#94a3b8;"></i>
                                <div class="empty-text">No se encontraron materias.</div>
                            </div>
                        </td>
                    </tr>
                    <?php else: ?>

                    <?php foreach ($materias as $materia): ?>
                    <tr>
                        <td class="materia-clave"><?php echo htmlspecialchars($materia['clave']); ?></td>

                        <td class="materia-nombre"><?php echo htmlspecialchars($materia['nombre']); ?></td>

                        <td>
                            <span class="badge-pill badge-blue">
                                <?php echo (int)$materia['creditos']; ?> CR
                            </span>
                        </td>

                        <td><?php echo htmlspecialchars($materia['horas_semana']); ?> hrs</td>
                        <td><?php echo htmlspecialchars($materia['carrera_nombre']); ?></td>
                        <td><?php echo htmlspecialchars($materia['semestre_nombre']); ?></td>

                        <td>
                            <div class="actions-cell">

                                <a href="<?php echo APP_URL; ?>index.php?c=materias&a=editar&id=<?php echo $materia['id']; ?>" 
                                    class="btn-icon btn-edit"
                                    title="Editar">
                                    <i class="fa-solid fa-pen"></i>
                                </a>

                                <form method="POST" 
                                    action="<?php echo APP_URL; ?>index.php?c=materias&a=eliminar"
                                    onsubmit="return confirm('¿Eliminar materia <?php echo addslashes($materia['clave']); ?>?');"
                                    style="display:inline;">
                                    
                                    <input type="hidden" name="id" value="<?php echo $materia['id']; ?>">

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
