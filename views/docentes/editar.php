<?php $page_title = 'Editar Docente'; ?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    :root {
        --primary: #2563eb; --primary-hover: #1d4ed8; --muted: #64748b;
        --text-main: #0f172a; --bg: #ffffff; --surface: #f8fafc;
        --border: #e2e8f0; --radius: 12px;
    }
    .page-container { font-family: "Open Sans", system-ui; padding: 22px; color: var(--text-main); }
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
    .content-grid { display: grid; grid-template-columns: 1fr 380px; gap: 22px; align-items: start; }
    @media (max-width: 968px) { .content-grid { grid-template-columns: 1fr; } }
    .card-box {
        background: var(--bg); border: 1px solid var(--border); border-radius: var(--radius);
        padding: 24px; margin-bottom: 22px; box-shadow: 0 1px 6px rgba(0,0,0,0.04);
    }
    .alert-info, .alert-success, .alert-warning {
        padding: 14px 16px; border-radius: 10px; margin-bottom: 24px;
        display: flex; align-items: center; gap: 10px; font-size: 0.93rem;
    }
    .alert-info { background: #dbeafe; border: 1px solid #93c5fd; color: #1e40af; }
    .alert-success { background: #d1fae5; border: 1px solid #a7f3d0; color: #065f46; }
    .alert-warning { background: #fff7ed; border: 1px solid #fdba74; color: #9a3412; }
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
    
    /* Estilos para selector de materias */
    .materias-selector {
        background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px;
        padding: 16px; max-height: 400px; overflow-y: auto;
    }
    .materia-item {
        padding: 10px 12px; background: white; border: 1px solid #e2e8f0;
        border-radius: 8px; margin-bottom: 8px; cursor: pointer;
        transition: all 0.2s; display: flex; align-items: center; gap: 10px;
    }
    .materia-item:hover { border-color: var(--primary); background: #eff6ff; }
    .materia-item input[type="checkbox"] { width: 18px; height: 18px; cursor: pointer; }
    .materia-info { flex: 1; }
    .materia-clave { font-weight: 700; color: var(--primary); font-size: 0.9rem; }
    .materia-nombre { font-size: 0.85rem; color: var(--text-main); margin-top: 2px; }
    .materia-detalle { font-size: 0.75rem; color: var(--muted); margin-top: 4px; }
    .filtro-materias {
        margin-bottom: 12px; display: flex; gap: 10px;
    }
    .filtro-materias select, .filtro-materias input {
        flex: 1; padding: 8px 12px; border: 1px solid #e2e8f0; border-radius: 6px;
    }
    .contador-materias {
        text-align: center; padding: 8px; background: #dbeafe; border-radius: 6px;
        font-weight: 600; color: #1e40af; font-size: 0.9rem; margin-bottom: 12px;
    }

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
    .btn-danger { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }
    .btn-danger:hover { background: #fecaca; transform: translateY(-2px); }

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
            <span class="breadcrumb-item active">Editar Docente</span>
        </div>
    </div>

    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:22px;">
        <h1 class="page-title" style="margin:0;">Editar Docente: <?php echo htmlspecialchars($docente['nombre']); ?></h1>
        <button type="button" class="btn btn-danger" onclick="confirmarEliminar(<?php echo $docente['id']; ?>)">
            <i class="fas fa-trash"></i> Eliminar
        </button>
    </div>

    <div class="content-grid">
        <div>
            <div class="card-box">
                <form method="POST" action="<?php echo APP_URL; ?>index.php?c=docentes&a=editar&id=<?php echo $docente['id']; ?>" class="needs-validation" novalidate>
                    
                    <h5 class="section-title">Información Laboral</h5>
                    
                    <div class="form-row">
                        <div>
                            <label for="numero_empleado" class="form-label">Número de Empleado *</label>
                            <input type="text" class="form-control" id="numero_empleado" name="numero_empleado" 
                                   required maxlength="20" style="text-transform: uppercase;" 
                                   value="<?php echo htmlspecialchars($docente['numero_empleado']); ?>">
                        </div>
                        <div>
                            <label for="tipo" class="form-label">Tipo de Contratación *</label>
                            <select class="form-select" id="tipo" name="tipo" required onchange="actualizarHorasMax()">
                                <option value="tiempo_completo" <?php echo $docente['tipo'] === 'tiempo_completo' ? 'selected' : ''; ?>>Tiempo Completo</option>
                                <option value="medio_tiempo" <?php echo $docente['tipo'] === 'medio_tiempo' ? 'selected' : ''; ?>>Medio Tiempo</option>
                                <option value="asignatura" <?php echo $docente['tipo'] === 'asignatura' ? 'selected' : ''; ?>>Por Asignatura</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="horas_max_semana" class="form-label">Horas Máximas por Semana *</label>
                        <input type="number" class="form-control" id="horas_max_semana" name="horas_max_semana" 
                               required min="1" max="50" 
                               value="<?php echo htmlspecialchars($docente['horas_max_semana']); ?>">
                    </div>
                    
                    <h5 class="section-title">Información Personal</h5>
                    
                    <div class="form-row three-cols">
                        <div>
                            <label for="nombre" class="form-label">Nombre(s) *</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required maxlength="100"
                                   value="<?php echo htmlspecialchars($docente['nombre']); ?>">
                        </div>
                        <div>
                            <label for="apellido_paterno" class="form-label">Apellido Paterno *</label>
                            <input type="text" class="form-control" id="apellido_paterno" name="apellido_paterno" required maxlength="100"
                                   value="<?php echo htmlspecialchars($docente['apellido_paterno']); ?>">
                        </div>
                        <div>
                            <label for="apellido_materno" class="form-label">Apellido Materno</label>
                            <input type="text" class="form-control" id="apellido_materno" name="apellido_materno" maxlength="100"
                                   value="<?php echo htmlspecialchars($docente['apellido_materno']); ?>">
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div>
                            <label for="email" class="form-label">Email *</label>
                            <input type="email" class="form-control" id="email" name="email" required maxlength="150"
                                   value="<?php echo htmlspecialchars($docente['email']); ?>">
                        </div>
                        <div>
                            <label for="telefono" class="form-label">Teléfono</label>
                            <input type="tel" class="form-control" id="telefono" name="telefono" maxlength="20"
                                   value="<?php echo htmlspecialchars($docente['telefono']); ?>">
                        </div>
                    </div>

                    <h5 class="section-title"><i class="fas fa-book"></i> Materias Asignadas</h5>
                    
                    <div class="contador-materias">
                        <span id="contador-seleccionadas">0</span> materias seleccionadas
                    </div>

                    <div class="filtro-materias">
                        <input type="text" id="buscar-materia" placeholder="🔍 Buscar materia..." onkeyup="filtrarMaterias()">
                        <select id="filtro-carrera" onchange="filtrarMaterias()">
                            <option value="">Todas las carreras</option>
                            <?php
                            $db = new Database();
                            $carreras = $db->getConnection()->query("SELECT * FROM carreras WHERE activo=1 ORDER BY nombre")->fetchAll();
                            foreach($carreras as $c) {
                                echo "<option value='{$c['id']}'>{$c['nombre']}</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="materias-selector" id="materias-container">
                        <?php if(!empty($materias_disponibles)): ?>
                            <?php foreach($materias_disponibles as $mat): ?>
                                <?php 
                                    // Verificar si la materia ya está asignada al docente
                                    $checked = in_array($mat['id'], $materias_asignadas_ids) ? 'checked' : ''; 
                                ?>
                                <label class="materia-item" data-carrera="<?php echo $mat['carrera_id']; ?>" 
                                       data-nombre="<?php echo strtolower($mat['nombre']); ?>"
                                       data-clave="<?php echo strtolower($mat['clave']); ?>">
                                    <input type="checkbox" name="materias[]" value="<?php echo $mat['id']; ?>" <?php echo $checked; ?> onchange="actualizarContador()">
                                    <div class="materia-info">
                                        <div class="materia-clave"><?php echo htmlspecialchars($mat['clave']); ?></div>
                                        <div class="materia-nombre"><?php echo htmlspecialchars($mat['nombre']); ?></div>
                                        <div class="materia-detalle">
                                            <i class="fas fa-graduation-cap"></i> <?php echo htmlspecialchars($mat['carrera_nombre']); ?> - 
                                            <i class="fas fa-layer-group"></i> <?php echo htmlspecialchars($mat['semestre_nombre']); ?>
                                        </div>
                                    </div>
                                </label>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p style="text-align:center; color:#64748b; padding:20px;">No hay materias disponibles para asignar</p>
                        <?php endif; ?>
                    </div>

                    <h5 class="section-title">Acceso al Sistema</h5>
                    
                    <?php if($usuario_asociado): ?>
                        <div class="alert-success">
                            <i class="fas fa-user-check"></i>
                            <div>
                                <strong>Cuenta Activa</strong><br>
                                Usuario actual: <code><?php echo htmlspecialchars($usuario_asociado['usuario']); ?></code>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label style="cursor:pointer; display:flex; align-items:center; gap:10px;">
                                <input type="checkbox" id="modificar_cuenta" name="modificar_cuenta" onchange="toggleCuentaFields(true)" style="width:18px; height:18px;">
                                <strong>Modificar usuario o contraseña</strong>
                            </label>
                        </div>
                        
                        <div id="campos_cuenta" style="display:none; background:#f8fafc; padding:20px; border-radius:10px; border:1px solid #e2e8f0;">
                             <div class="form-row">
                                <div>
                                    <label for="usuario_login" class="form-label">Usuario</label>
                                    <input type="text" class="form-control" id="usuario_login" name="usuario_login" 
                                           value="<?php echo htmlspecialchars($usuario_asociado['usuario']); ?>">
                                </div>
                                <div>
                                    <label for="password" class="form-label">Nueva Contraseña</label>
                                    <input type="password" class="form-control" id="password" name="password" minlength="6" placeholder="Dejar en blanco para mantener">
                                    <small class="text-muted">Solo escribe si deseas cambiarla</small>
                                </div>
                            </div>
                        </div>

                    <?php else: ?>
                        <div class="alert-warning">
                            <i class="fas fa-user-slash"></i> Este docente no tiene cuenta de acceso al sistema.
                        </div>

                        <div class="form-group">
                            <label style="cursor:pointer; display:flex; align-items:center; gap:10px;">
                                <input type="checkbox" id="crear_cuenta" name="crear_cuenta" onchange="toggleCuentaFields(false)" style="width:18px; height:18px;">
                                <strong>Crear cuenta de acceso ahora</strong>
                            </label>
                        </div>

                        <div id="campos_cuenta" style="display:none; background:#f8fafc; padding:20px; border-radius:10px; border:1px solid #e2e8f0;">
                            <div class="form-row">
                                <div>
                                    <label for="usuario_login" class="form-label">Usuario</label>
                                    <input type="text" class="form-control" id="usuario_login" name="usuario_login" 
                                           placeholder="Dejar vacío para usar No. Empleado">
                                </div>
                                <div>
                                    <label for="password" class="form-label">Contraseña *</label>
                                    <input type="password" class="form-control" id="password" name="password" minlength="6">
                                    <small class="text-muted">Mínimo 6 caracteres</small>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="button-group">
                        <a href="<?php echo APP_URL; ?>index.php?c=docentes" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div>
            <div class="info-card">
                <div class="info-card-header">
                    <i class="fas fa-history"></i>
                    <span>Detalles del Registro</span>
                </div>
                <div class="info-card-body">
                    <h6>Fecha de Registro</h6>
                    <p>
                        <?php echo date('d/m/Y H:i', strtotime($docente['created_at'])); ?>
                    </p>
                    
                    <h6>Última Actualización</h6>
                    <p>
                        <?php echo $docente['updated_at'] ? date('d/m/Y H:i', strtotime($docente['updated_at'])) : 'Sin cambios recientes'; ?>
                    </p>
                    
                    <hr>
                    
                    <h6>Advertencia de edición</h6>
                    <p>Si cambias el <strong>Número de Empleado</strong>, asegúrate de notificar al docente, ya que esto podría afectar sus trámites administrativos internos.</p>

                    <h6>Materias</h6>
                    <p>Desmarcar una materia eliminará la asignación actual. Asegúrate de que no haya calificaciones pendientes en esa materia para este periodo.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<form id="form-eliminar" action="<?php echo APP_URL; ?>index.php?c=docentes&a=eliminar" method="POST" style="display:none;">
    <input type="hidden" name="id" value="">
</form>

<script>
function actualizarHorasMax() {
    const tipo = document.getElementById('tipo').value;
    const horasInput = document.getElementById('horas_max_semana');
    
    // Solo actualizamos automáticamente si el usuario no ha puesto un valor personalizado
    // Opcional: forzar siempre el cambio, depende de tu regla de negocio.
    // Aquí dejaremos que cambie el valor sugerido si se cambia el tipo.
    if(tipo === 'tiempo_completo') horasInput.value = 40;
    else if(tipo === 'medio_tiempo') horasInput.value = 20;
    else if(tipo === 'asignatura') horasInput.value = 10;
}

function toggleCuentaFields(isEditMode) {
    const checkId = isEditMode ? 'modificar_cuenta' : 'crear_cuenta';
    const check = document.getElementById(checkId);
    const container = document.getElementById('campos_cuenta');
    const inputPass = document.getElementById('password');
    
    if (check.checked) {
        container.style.display = 'block';
        if (!isEditMode) {
            // Solo requerido si estamos CREANDO cuenta
            inputPass.setAttribute('required', 'required');
        }
    } else {
        container.style.display = 'none';
        inputPass.removeAttribute('required');
        if(!isEditMode) inputPass.value = ''; 
    }
}

function actualizarContador() {
    const checkboxes = document.querySelectorAll('input[name="materias[]"]:checked');
    document.getElementById('contador-seleccionadas').textContent = checkboxes.length;
}

function filtrarMaterias() {
    const busqueda = document.getElementById('buscar-materia').value.toLowerCase();
    const carreraFiltro = document.getElementById('filtro-carrera').value;
    const items = document.querySelectorAll('.materia-item');
    
    items.forEach(item => {
        const nombre = item.dataset.nombre;
        const clave = item.dataset.clave;
        const carrera = item.dataset.carrera;
        
        const coincideBusqueda = nombre.includes(busqueda) || clave.includes(busqueda);
        const coincideCarrera = !carreraFiltro || carrera === carreraFiltro;
        
        item.style.display = (coincideBusqueda && coincideCarrera) ? 'flex' : 'none';
    });
}

function confirmarEliminar(id) {
    if(confirm('¿Está seguro de eliminar este docente? Esta acción no se puede deshacer.')) {
        const form = document.getElementById('form-eliminar');
        form.querySelector('input[name="id"]').value = id;
        form.submit();
    }
}

// Inicializar contador al cargar la página
document.addEventListener('DOMContentLoaded', function() {
    actualizarContador();
});
</script>