<?php
// =====================================================
// views/usuarios/index.php
// Listado de usuarios - DISEÑO PROFESIONAL MODERNO
// =====================================================

$page_title = 'Gestión de Usuarios';
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

    /* --------------------------- CARD / TABLA --------------------------- */
    .card-box {
        background: var(--bg);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 18px;
        margin-bottom: 22px;
        box-shadow: 0 1px 6px rgba(0,0,0,0.04);
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

    .usuario-nombre {
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
    
    .badge-subdirector { background: #fef3c7; color: #92400e; }
    .badge-coordinador { background: #dbeafe; color: #1e40af; }
    .badge-docente { background: #d1fae5; color: #065f46; }
    .badge-usuario { background: #f3f4f6; color: #6b7280; }

    .badge-activo { background: #d1fae5; color: #065f46; }
    .badge-inactivo { background: #fee2e2; color: #991b1b; }

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
    @media (max-width: 600px) {
        .header-actions { flex-direction: column; gap: 10px; align-items: flex-start; }
    }
</style>

<div class="page-container">

    <!-- BREADCRUMB -->
    <div class="breadcrumb-wrapper">
        <div class="breadcrumb-clean">
            <span class="breadcrumb-item">
                <a href="<?php echo APP_URL; ?>index.php">Inicio</a>
            </span>
            <span class="breadcrumb-item active">Usuarios</span>
        </div>
    </div>

    <!-- HEADER -->
    <div class="header-actions">
        <h1 class="page-title">Gestión de Usuarios</h1>

        <?php if (Auth::hasRole(ROLE_SUBDIRECTOR)): ?>
        <a href="<?php echo APP_URL; ?>index.php?c=usuarios&a=crear" 
            class="btn-create">
            <i class="fa-solid fa-plus-circle"></i> Nuevo Usuario
        </a>
        <?php endif; ?>
    </div>

    <!-- TABLA -->
    <div class="card-box" style="padding:0;">
        <div class="table-responsive">
            <table class="table-modern">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Usuario</th>
                        <th>Email</th>
                        <th>Rol</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if (empty($usuarios)): ?>
                    <tr>
                        <td colspan="6">
                            <div class="empty-placeholder">
                                <i class="fa-regular fa-folder-open" style="font-size:45px;color:#94a3b8;"></i>
                                <div class="empty-text">No se encontraron usuarios.</div>
                            </div>
                        </td>
                    </tr>
                    <?php else: ?>

                    <?php foreach ($usuarios as $usuario): ?>
                    <tr>
                        <td class="usuario-nombre">
                            <?php echo htmlspecialchars($usuario['nombre'] . ' ' . ($usuario['apellidos'] ?? '')); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($usuario['usuario']); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($usuario['email'] ?? 'Sin email'); ?>
                        </td>

                        <td>
                            <?php
                            $rol_config = [
                                'subdirector' => ['class' => 'badge-subdirector', 'icon' => 'fa-user-shield', 'text' => 'Subdirector'],
                                'coordinador' => ['class' => 'badge-coordinador', 'icon' => 'fa-user-tie', 'text' => 'Coordinador'],
                                'docente' => ['class' => 'badge-docente', 'icon' => 'fa-chalkboard-teacher', 'text' => 'Docente']
                            ];
                            $config = $rol_config[$usuario['rol']] ?? ['class' => 'badge-usuario', 'icon' => 'fa-user', 'text' => ucfirst($usuario['rol'])];
                            ?>
                            <span class="badge-pill <?php echo $config['class']; ?>">
                                <i class="fas <?php echo $config['icon']; ?>"></i>
                                <?php echo $config['text']; ?>
                            </span>
                        </td>

                        <td>
                            <?php if ($usuario['activo']): ?>
                                <span class="badge-pill badge-activo">
                                    <i class="fas fa-check-circle"></i>
                                    Activo
                                </span>
                            <?php else: ?>
                                <span class="badge-pill badge-inactivo">
                                    <i class="fas fa-times-circle"></i>
                                    Inactivo
                                </span>
                            <?php endif; ?>
                        </td>

                        <td>
                            <div class="actions-cell">
                                <?php if (Auth::hasRole(ROLE_SUBDIRECTOR)): ?>
                                <a href="<?php echo APP_URL; ?>index.php?c=usuarios&a=editar&usuario=<?php echo urlencode($usuario['usuario']); ?>" 
                                    class="btn-icon btn-edit"
                                    title="Editar">
                                    <i class="fa-solid fa-pen"></i>
                                </a>

                                <form method="POST" 
                                    action="<?php echo APP_URL; ?>index.php?c=usuarios&a=eliminar"
                                    onsubmit="return confirm('¿Eliminar usuario <?php echo addslashes($usuario['usuario']); ?>?');"
                                    style="display:inline;">
                                    
                                    <input type="hidden" name="usuario" value="<?php echo htmlspecialchars($usuario['usuario']); ?>">

                                    <button type="submit" class="btn-icon btn-delete" title="Eliminar">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                                <?php endif; ?>
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