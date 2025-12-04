<?php
// =====================================================
// views/docentes/crear.php
// Crear docente + OPCIÓN DE CREAR CUENTA DE USUARIO
// VERSIÓN FINAL Y DEFINITIVA
// =====================================================

$page_title = 'Nuevo Docente';
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

    .breadcrumb-wrapper { margin-bottom: 16px; }
    .breadcrumb-clean {
        display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px;
        background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px;
        font-size: 0.93rem; font-weight: 500;
    }
    .breadcrumb-clean .breadcrumb-item a { color: #64748b; text-decoration: none; padding: 2px 6px; border-radius: 6px; }
    .breadcrumb-clean .breadcrumb-item a:hover { background: rgba(37,99,235,0.07); color: #2563eb; }
    .breadcrumb-clean .breadcrumb-item + .breadcrumb-item::before { content: "›"; margin-right: 4px; color: #cbd5e1; }
    .breadcrumb-clean .active { font-weight: 700; color: #2563eb; }

    .page-title { font-size: 1.45rem; font-weight: 800; margin: 0 0 22px 0; }

    .content-grid {
        display: grid; grid-template-columns: 1fr 380px; gap: 22px; align-items: start;
    }
    @media (max-width: 968px) { .content-grid { grid-template-columns: 1fr; } }

    .card-box {
        background: var(--bg); border: 1px solid var(--border); border-radius: var(--radius);
        padding: 24px; margin-bottom: 22px; box-shadow: 0 1px 6px rgba(0,0,0,0.04);
    }

    .alert-info, .alert-success {
        padding: 14px 16px; border-radius: 10px; margin-bottom: 24px;
        display: flex; align-items: center; gap: 10px; font-size: 0.93rem;
    }
    .alert-info { background: #dbeafe; border: 1px solid #93c5fd; color: #1e40af; }
    .alert-success { background: #d1fae5; border: 1px solid #a7f3d0; color: #065f46; }

    .section-title {
        font-size: 1.1rem; font-weight: 700; color: var(--text-main);
        margin: 28px 0 16px 0; padding-bottom: 8px; border-bottom: 2px solid var(--border);
    }
    .section-title:first-of-type { margin-top: 0; }

    .form-row {
        display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 20px;
    }
    .form-row.three-cols { grid-template-columns: 1fr 1fr 1fr; }
    .form-row.single { grid-template-columns: 1fr; }

    .form-group { margin-bottom: 20px; }
    .form-label {
        display: block; font-size: 0.90rem; font-weight: 600; color: var(--text-main); margin-bottom: 8px;
    }
    .form-control, .form-select {
        width: 100%; padding: 10px 14px; border: 1px solid var(--border); border-radius: 8px;
        font-size: 0.95rem; background: var(--bg); transition: 0.15s ease;
    }
    .form-control:focus, .form-select:focus {
        outline: none; border-color: var(--primary); box-shadow: 0 0 0 3px rgba(37,99,235,0.1);
    }
    .text-muted { font-size: 0.82rem; color: var(--muted); margin-top: 4px; display: block; }

    .button-group {
        display: flex; gap: 12px; justify-content: flex-end; margin-top: 28px;
        padding-top: 20px; border-top: 1px solid var(--border);
    }
    .btn {
        display: inline-flex; align-items: center; gap: 8px; padding: 10px 18px;
        border-radius: 10px; font-weight: 600; font-size: 0.95rem; cursor: pointer;
        transition: 0.25s ease; text-decoration: none; border: none;
    }
    .btn-secondary { background: #f1f5f9; color: var(--muted); border: 1px solid var(--border); }
    .btn-secondary:hover { background: #e2e8f0; color: var(--text-main); transform: translateY(-2px); }
    .btn-primary { background: var(--primary); color: #fff; box-shadow: 0 5px 14px rgba(37,99,235,0.22); }
    .btn-primary:hover { background: var(--primary-hover); transform: translateY(-2px); }

    .info-card { background: var(--bg); border: 1px solid var(--border); border-radius: var(--radius); overflow: hidden; box-shadow: 0 1px 6px rgba(0,0,0,0.04); }
    .info-card-header {
        padding: 12px 16px; font-weight: 700; font-size: 0.90rem; display: flex; align-items: center; gap: 8px;
        background: #dbeafe; color: #1e40af; border-bottom: 1px solid #93c5fd;
    }
    .info-card-body { padding: 16px; }
    .info-card-body h6 { font-size: 0.88rem; font-weight: 700; margin: 0 0 8px 0; color: var(--text-main); }
    .info-card-body p { font-size: 0.85rem; color: var(--muted); margin: 0 0 12px 0; line-height: 1.6; }
    .info-card-body ul { margin: 0; padding-left: 20px; }
    .info-card-body ul li { font-size: 0.85rem; color: var(--muted); margin-bottom: 6px; line-height: 1.5; }

    @media (max-width: 600px) {
        .form-row, .form-row.three-cols { grid-template-columns: 1fr; }
        .button-group { flex-direction: column-reverse; }
        .btn { width: 100%; justify-content: center; }
    }
</style>

<div class="page-container">

    <div class="breadcrumb-wrapper">
        <div class="breadcrumb-clean">
            <span class="breadcrumb-item"><a href="<?php echo APP_URL; ?>index.php">Inicio</a></span>
            <span class="breadcrumb-item"><a href="<?php echo APP_URL; ?>index.php?c=docentes">Docentes</a></span>
            <span class="breadcrumb-item active">Nuevo Docente</span>
        </div>
    </div>

    <h1 class="page-title">Nuevo Docente</h1>

    <div class="content-grid">
        
        <div>
            <div class="card-box">
                <form method="POST" action="<?php echo APP_URL; ?>index.php?c=docentes&a=crear" class="needs-validation" novalidate>
                    
                    <div class="alert-info">
                        <i class="fas fa-info-circle"></i> 
                        <span><strong>Importante:</strong> Los campos marcados con * son obligatorios</span>
                    </div>
                    
                    <h5 class="section-title">Información Laboral</h5>
                    
                    <div class="form-row">
                        <div>
                            <label for="numero_empleado" class="form-label">Número de Empleado *</label>
                            <input type="text" class="form-control" id="numero_empleado" name="numero_empleado" 
                                   required maxlength="20" style="text-transform: uppercase;" placeholder="Ej: D001">
                        </div>
                        <div>
                            <label for="tipo" class="form-label">Tipo de Contratación *</label>
                            <select class="form-select" id="tipo" name="tipo" required onchange="actualizarHorasMax()">
                                <option value="">Seleccione...</option>
                                <option value="tiempo_completo" selected>Tiempo Completo</option>
                                <option value="medio_tiempo">Medio Tiempo</option>
                                <option value="asignatura">Por Asignatura</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="horas_max_semana" class="form-label">Horas Máximas por Semana *</label>
                        <input type="number" class="form-control" id="horas_max_semana" name="horas_max_semana" 
                               required min="1" max="50" value="40">
                        <small class="text-muted">Tiempo completo: 40 hrs | Medio tiempo: 20 hrs | Asignatura: Variable</small>
                    </div>
                    
                    <h5 class="section-title">Información Personal</h5>
                    
                    <div class="form-row three-cols">
                        <div>
                            <label for="nombre" class="form-label">Nombre(s) *</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required maxlength="100">
                        </div>
                        <div>
                            <label for="apellido_paterno" class="form-label">Apellido Paterno *</label>
                            <input type="text" class="form-control" id="apellido_paterno" name="apellido_paterno" required maxlength="100">
                        </div>
                        <div>
                            <label for="apellido_materno" class="form-label">Apellido Materno</label>
                            <input type="text" class="form-control" id="apellido_materno" name="apellido_materno" maxlength="100">
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div>
                            <label for="email" class="form-label">Email *</label>
                            <input type="email" class="form-control" id="email" name="email" required maxlength="150" placeholder="ejemplo@instituto.edu.mx">
                        </div>
                        <div>
                            <label for="telefono" class="form-label">Teléfono</label>
                            <input type="tel" class="form-control" id="telefono" name="telefono" maxlength="20" placeholder="7471234567">
                        </div>
                    </div>

                    <h5 class="section-title">Acceso al Sistema (Opcional)</h5>
                    
                    <div class="form-group">
                        <label style="cursor:pointer; display:flex; align-items:center; gap:10px;">
                            <input type="checkbox" id="crear_cuenta" name="crear_cuenta" onchange="toggleCuentaFields()" style="width:18px; height:18px;">
                            <strong>Crear cuenta de usuario para que el docente pueda entrar al sistema</strong>
                        </label>
                    </div>

                    <div id="campos_cuenta" style="display:none; background:#f8fafc; padding:20px; border-radius:10px; border:1px solid #e2e8f0;">
                        <div class="alert-success">
                            <i class="fas fa-check-circle"></i>
                            <span>El docente podrá iniciar sesión con estos datos</span>
                        </div>

                        <div class="form-row">
                            <div>
                                <label for="usuario_login" class="form-label">Usuario para login</label>
                                <input type="text" class="form-control" id="usuario_login" name="usuario_login" 
                                       placeholder="Dejar vacío para usar el No. Empleado">
                                <small class="text-muted">Si lo dejas vacío, el usuario será el Número de Empleado.</small>
                            </div>
                            <div>
                                <label for="password" class="form-label">Contraseña *</label>
                                <input type="password" class="form-control" id="password" name="password" minlength="6">
                                <small class="text-muted">Mínimo 6 caracteres</small>
                            </div>
                        </div>
                    </div>

                    <div class="button-group">
                        <a href="<?php echo APP_URL; ?>index.php?c=docentes" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Guardar Docente
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div>
            <div class="info-card">
                <div class="info-card-header">
                    <i class="fas fa-lightbulb"></i>
                    <span>Recomendaciones</span>
                </div>
                <div class="info-card-body">
                    <h6>¿Crear cuenta de usuario?</h6>
                    <p>Marcar esta opción si el docente necesita:</p>
                    <ul>
                        <li>Ver su horario personal</li>
                        <li>Recibir notificaciones</li>
                        <li>Acceder al sistema</li>
                    </ul>
                    <hr>
                    <h6>Número de Empleado</h6>
                    <p>Úsalo como usuario de login para que sea fácil de recordar.</p>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
function actualizarHorasMax() {
    const tipo = document.getElementById('tipo').value;
    const horasInput = document.getElementById('horas_max_semana');
    
    switch(tipo) {
        case 'tiempo_completo': horasInput.value = 40; break;
        case 'medio_tiempo': horasInput.value = 20; break;
        case 'asignatura': horasInput.value = 10; break;
        default: horasInput.value = 0;
    }
}

function toggleCuentaFields() {
    const check = document.getElementById('crear_cuenta');
    const container = document.getElementById('campos_cuenta');
    const inputPass = document.getElementById('password');
    const inputUser = document.getElementById('usuario_login');
    
    if (check.checked) {
        // Mostrar y hacer obligatorios
        container.style.display = 'block';
        inputPass.setAttribute('required', 'required');
    } else {
        // Ocultar, quitar required y limpiar
        container.style.display = 'none';
        inputPass.removeAttribute('required');
        inputPass.value = '';
        inputUser.value = '';
    }
}
</script>