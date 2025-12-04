<?php
// =====================================================
// views/usuarios/crear.php
// Crear usuario administrativo - SIN ROL DOCENTE
// =====================================================

$page_title = 'Crear Usuario';
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
    .breadcrumb-wrapper { margin-bottom: 16px; }
    .breadcrumb-clean {
        display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px;
        background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px;
        font-size: 0.93rem; font-weight: 500;
    }
    .breadcrumb-clean a { color: #64748b; text-decoration: none; padding: 2px 6px; border-radius: 6px; transition: 0.15s; }
    .breadcrumb-clean a:hover { background: rgba(37,99,235,0.07); color: #2563eb; }
    .breadcrumb-clean .breadcrumb-item + .breadcrumb-item::before { content: "›"; margin-right: 4px; color: #cbd5e1; }
    .breadcrumb-clean .active { font-weight: 700; color: #2563eb; }

    /* --------------------------- CARD & FORM --------------------------- */
    .page-title { font-size: 1.45rem; font-weight: 800; margin: 0 0 22px 0; }
    
    .card-box {
        background: var(--bg); border: 1px solid var(--border); border-radius: var(--radius);
        padding: 24px; margin-bottom: 22px; box-shadow: 0 1px 6px rgba(0,0,0,0.04); max-width: 800px;
    }

    .form-group { margin-bottom: 20px; }
    .form-label { display: block; font-size: 0.90rem; font-weight: 600; color: var(--text-main); margin-bottom: 8px; }
    .form-control, .form-select {
        width: 100%; padding: 10px 14px; border: 1px solid var(--border); border-radius: 8px;
        font-size: 0.95rem; color: var(--text-main); background: var(--bg); transition: 0.15s ease; box-sizing: border-box;
    }
    .form-control:focus, .form-select:focus { outline: none; border-color: var(--primary); box-shadow: 0 0 0 3px rgba(37,99,235,0.1); }
    .text-muted { font-size: 0.82rem; color: var(--muted); margin-top: 4px; display: block; }

    /* --------------------------- ALERTS --------------------------- */
    .alert-tip {
        background-color: #f0f9ff; border-left: 4px solid #0ea5e9; padding: 12px 16px;
        margin-bottom: 20px; border-radius: 6px; font-size: 0.9rem; color: #0c4a6e;
        display: flex; align-items: center; gap: 10px;
    }
    .alert-tip a { color: #0284c7; font-weight: 700; text-decoration: none; }
    .alert-tip a:hover { text-decoration: underline; }

    /* --------------------------- BUTTONS --------------------------- */
    .button-group { display: flex; gap: 12px; justify-content: flex-end; margin-top: 28px; padding-top: 20px; border-top: 1px solid var(--border); }
    .btn { display: inline-flex; align-items: center; gap: 8px; padding: 10px 18px; border-radius: 10px; font-weight: 600; font-size: 0.95rem; cursor: pointer; transition: 0.25s ease; text-decoration: none; border: none; }
    .btn-secondary { background: #f1f5f9; color: var(--muted); border: 1px solid var(--border); }
    .btn-secondary:hover { background: #e2e8f0; color: var(--text-main); transform: translateY(-2px); }
    .btn-primary { background: var(--primary); color: #fff; box-shadow: 0 5px 14px rgba(37,99,235,0.22); }
    .btn-primary:hover { background: var(--primary-hover); transform: translateY(-2px); color: #fff; }

    @media (max-width: 600px) {
        .button-group { flex-direction: column-reverse; }
        .btn { width: 100%; justify-content: center; }
    }
</style>

<div class="page-container">

    <div class="breadcrumb-wrapper">
        <div class="breadcrumb-clean">
            <span class="breadcrumb-item"><a href="<?php echo APP_URL; ?>index.php">Dashboard</a></span>
            <span class="breadcrumb-item"><a href="<?php echo APP_URL; ?>index.php?c=usuarios">Usuarios</a></span>
            <span class="breadcrumb-item active">Crear Usuario</span>
        </div>
    </div>

    <h1 class="page-title">Crear Usuario Administrativo</h1>

    <div class="card-box">
        
        <div class="alert-tip">
            <i class="fas fa-info-circle fa-lg"></i>
            <div>
                ¿Buscas registrar un maestro? 
                <a href="<?php echo APP_URL; ?>index.php?c=docentes&a=crear">Ve al módulo de Docentes aquí</a>.
            </div>
        </div>

        <form method="POST" action="<?php echo APP_URL; ?>index.php?c=usuarios&a=crear" class="needs-validation" novalidate>
            
            <div class="form-group">
                <label for="usuario" class="form-label">Nombre de Usuario *</label>
                <input type="text" class="form-control" id="usuario" name="usuario" required>
                <small class="text-muted">Solo letras, números y guion bajo</small>
            </div>
            
            <div class="form-group">
                <label for="password" class="form-label">Contraseña *</label>
                <input type="password" class="form-control" id="password" name="password" required minlength="6">
                <small class="text-muted">Mínimo 6 caracteres</small>
            </div>
            
            <div class="form-group">
                <label for="nombre" class="form-label">Nombre *</label>
                <input type="text" class="form-control" id="nombre" name="nombre" required>
            </div>
            
            <div class="form-group">
                <label for="apellidos" class="form-label">Apellidos *</label>
                <input type="text" class="form-control" id="apellidos" name="apellidos" required>
            </div>
            
            <div class="form-group">
                <label for="email" class="form-label">Email *</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            
            <div class="form-group">
                <label for="rol" class="form-label">Rol *</label>
                <select class="form-select" id="rol" name="rol" required onchange="mostrarCamposRol()">
                    <option value="">Seleccione...</option>
                    <option value="<?php echo ROLE_JEFE_DEPTO; ?>">Jefe de Departamento</option>
                    <option value="<?php echo ROLE_DEP; ?>">División de Estudios Profesionales</option>
                    </select>
            </div>
            
            <div class="form-group" id="campo_departamento" style="display: none;">
                <label for="departamento" class="form-label">Departamento</label>
                <input type="text" class="form-control" id="departamento" name="departamento">
            </div>
            
            <div class="button-group">
                <a href="<?php echo APP_URL; ?>index.php?c=usuarios" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Cancelar
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Guardar Usuario
                </button>
            </div>
        </form>
    </div>

</div>

<script>
function mostrarCamposRol() {
    const rol = document.getElementById('rol').value;
    
    // Ocultar campo por defecto
    const campoDepto = document.getElementById('campo_departamento');
    campoDepto.style.display = 'none';
    
    // Mostrar solo si es Jefe de Depto
    if (rol === '<?php echo ROLE_JEFE_DEPTO; ?>') {
        campoDepto.style.display = 'block';
    } 
    // La lógica de docente ha sido eliminada
}
</script>