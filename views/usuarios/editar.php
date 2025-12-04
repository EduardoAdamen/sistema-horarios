<?php
// =====================================================
// views/usuarios/editar.php
// Editar usuario - DISEÑO PROFESIONAL MODERNO
// =====================================================

$page_title = 'Editar Usuario';
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

    /* --------------------------- ALERT --------------------------- */
    .alert-info {
        background: #dbeafe;
        border: 1px solid #93c5fd;
        border-radius: 10px;
        padding: 14px 16px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
        color: #1e40af;
        font-size: 0.93rem;
    }

    .alert-info i {
        font-size: 1.1rem;
    }

    /* --------------------------- FORMULARIO --------------------------- */
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

    .text-muted {
        font-size: 0.82rem;
        color: var(--muted);
        margin-top: 4px;
        display: block;
    }

    /* --------------------------- CHECKBOX --------------------------- */
    .form-check {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 20px;
    }

    .form-check-input {
        width: 20px;
        height: 20px;
        border: 2px solid var(--border);
        border-radius: 6px;
        cursor: pointer;
        transition: 0.15s ease;
    }

    .form-check-input:checked {
        background-color: var(--primary);
        border-color: var(--primary);
    }

    .form-check-label {
        font-size: 0.93rem;
        font-weight: 500;
        cursor: pointer;
        color: var(--text-main);
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

    /* --------------------------- SIDEBAR CARDS --------------------------- */
    .info-card {
        background: var(--bg);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        overflow: hidden;
        margin-bottom: 18px;
        box-shadow: 0 1px 6px rgba(0,0,0,0.04);
    }

    .info-card-header {
        padding: 12px 16px;
        font-weight: 700;
        font-size: 0.90rem;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .info-card-header.warning {
        background: #fef3c7;
        color: #92400e;
        border-bottom: 1px solid #fde68a;
    }

    .info-card-header.info {
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
        line-height: 1.5;
    }

    .info-card-body p:last-child {
        margin-bottom: 0;
    }

    .info-card-body hr {
        border: none;
        border-top: 1px solid var(--border);
        margin: 14px 0;
    }

    .info-card-body strong {
        color: var(--text-main);
        font-weight: 600;
    }

    /* --------------------------- RESPONSIVE --------------------------- */
    @media (max-width: 600px) {
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
                <a href="<?php echo APP_URL; ?>index.php?c=usuarios">Usuarios</a>
            </span>
            <span class="breadcrumb-item active">Editar Usuario</span>
        </div>
    </div>

    <!-- HEADER -->
    <h1 class="page-title">Editar Usuario</h1>

    <!-- CONTENT GRID -->
    <div class="content-grid">
        
        <!-- FORMULARIO PRINCIPAL -->
        <div>
            <div class="card-box">
                <form method="POST" action="<?php echo APP_URL; ?>index.php?c=usuarios&a=editar&usuario=<?php echo $usuario_data['usuario']; ?>" class="needs-validation" novalidate>
                    
                    <div class="alert-info">
                        <i class="fas fa-info-circle"></i> 
                        <span>Usuario: <strong><?php echo htmlspecialchars($usuario_data['usuario']); ?></strong></span>
                    </div>
                    
                    <div class="form-group">
                        <label for="nombre" class="form-label">Nombre *</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" 
                               required value="<?php echo htmlspecialchars($usuario_data['nombre']); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="apellidos" class="form-label">Apellidos *</label>
                        <input type="text" class="form-control" id="apellidos" name="apellidos" 
                               required value="<?php echo htmlspecialchars($usuario_data['apellidos']); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="email" class="form-label">Email *</label>
                        <input type="email" class="form-control" id="email" name="email" 
                               required value="<?php echo htmlspecialchars($usuario_data['email']); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="password" class="form-label">Nueva Contraseña</label>
                        <input type="password" class="form-control" id="password" name="password" minlength="6">
                        <small class="text-muted">Dejar en blanco para mantener la contraseña actual</small>
                    </div>
                    
                    <?php if ($usuario_data['rol'] === ROLE_JEFE_DEPTO && isset($usuario_data['departamento'])): ?>
                    <div class="form-group">
                        <label for="departamento" class="form-label">Departamento</label>
                        <input type="text" class="form-control" id="departamento" name="departamento" 
                               value="<?php echo htmlspecialchars($usuario_data['departamento']); ?>">
                    </div>
                    <?php endif; ?>
                    
                    <div class="form-group">
                        <label class="form-label">Rol</label>
                        <input type="text" class="form-control" readonly 
                               value="<?php echo ucfirst(str_replace('_', ' ', $usuario_data['rol'])); ?>">
                        <small class="text-muted">El rol no se puede cambiar después de crear el usuario</small>
                    </div>
                    
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="activo" name="activo" 
                               <?php echo $usuario_data['activo'] ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="activo">
                            Usuario Activo
                        </label>
                    </div>
                    
                    <div class="button-group">
                        <a href="<?php echo APP_URL; ?>index.php?c=usuarios" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Actualizar Usuario
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- SIDEBAR -->
        <div>
            <!-- CARD INFORMACIÓN -->
            <div class="info-card">
                <div class="info-card-header warning">
                    <i class="fas fa-exclamation-triangle"></i>
                    <span>Información</span>
                </div>
                <div class="info-card-body">
                    <h6>Cambio de Contraseña</h6>
                    <p>
                        Solo escribe una nueva contraseña si deseas cambiarla. 
                        Si dejas el campo vacío, se mantendrá la contraseña actual.
                    </p>
                    <hr>
                    <h6>Desactivar Usuario</h6>
                    <p>
                        Desmarca "Usuario Activo" para desactivar el acceso sin eliminar el usuario.
                    </p>
                </div>
            </div>
            
            <!-- CARD HISTORIAL -->
            <div class="info-card">
                <div class="info-card-header info">
                    <i class="fas fa-clock"></i>
                    <span>Historial</span>
                </div>
                <div class="info-card-body">
                    <p>
                        <strong>Creado:</strong><br>
                        <?php echo date('d/m/Y H:i', strtotime($usuario_data['created_at'])); ?>
                    </p>
                    <?php if (isset($usuario_data['updated_at'])): ?>
                    <p>
                        <strong>Última modificación:</strong><br>
                        <?php echo date('d/m/Y H:i', strtotime($usuario_data['updated_at'])); ?>
                    </p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

    </div>

</div>