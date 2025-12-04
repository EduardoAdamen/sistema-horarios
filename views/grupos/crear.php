<?php
// =====================================================
// views/grupos/crear.php
// Nuevo Grupo (Con asignación de Aula, Modales y Manejo de Excepciones)
// =====================================================

$page_title = 'Nuevo Grupo';
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    /* Estilos Base */
    :root { --primary: #2563eb; --primary-hover: #1d4ed8; --muted: #64748b; --text-main: #0f172a; --bg: #ffffff; --surface: #f8fafc; --border: #e2e8f0; --radius: 12px; }
    .page-container { font-family: "Open Sans", system-ui, Helvetica; padding: 22px; color: var(--text-main); }
    .breadcrumb-wrapper { margin-bottom: 16px; }
    .breadcrumb-clean { display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; font-size: 0.93rem; font-weight: 500; }
    .breadcrumb-clean .breadcrumb-item a { color: #64748b; text-decoration: none; }
    .breadcrumb-clean .active { font-weight: 700; color: #2563eb; }
    .page-title { font-size: 1.45rem; font-weight: 800; margin: 0 0 22px 0; }
    .card-box { background: var(--bg); border: 1px solid var(--border); border-radius: var(--radius); padding: 24px; margin-bottom: 22px; box-shadow: 0 1px 6px rgba(0,0,0,0.04); }
    .alert-info-custom { background: #dbeafe; border: 1px solid #93c5fd; border-radius: 10px; padding: 14px 16px; margin-bottom: 24px; display: flex; align-items: start; gap: 10px; }
    .alert-info-custom i { color: #1d4ed8; font-size: 18px; margin-top: 2px; }
    .alert-info-custom .alert-content { flex: 1; color: #1e40af; font-size: 0.92rem; line-height: 1.5; }
    .form-group { margin-bottom: 20px; }
    .form-label { font-size: 0.80rem; color: var(--muted); font-weight: 700; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.6px; display: block; }
    .form-control-pro, .form-select-pro { width: 100%; height: 44px; padding: 8px 12px; border-radius: 8px; border: 1px solid #d1d9e5; background: #fff; font-size: 0.95rem; color: var(--text-main); transition: 0.15s; }
    .form-control-pro:focus, .form-select-pro:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(37,99,235,0.20); outline: none; }
    .form-text { font-size: 0.82rem; color: var(--muted); margin-top: 6px; display: block; }
    .form-row { display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; margin-bottom: 20px; }
    .form-row-3 { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 20px; }
    .form-actions { display: flex; justify-content: flex-end; gap: 12px; margin-top: 32px; padding-top: 24px; border-top: 1px solid var(--border); }
    .btn-pro { display: inline-flex; align-items: center; justify-content: center; gap: 8px; padding: 10px 20px; border-radius: 10px; font-weight: 600; cursor: pointer; transition: 0.25s ease; border: none; min-width: 120px; text-decoration: none; font-size: 0.92rem; }
    .btn-primary-pro { background: var(--primary); color: #fff; box-shadow: 0 5px 14px rgba(37,99,235,0.22); }
    .btn-primary-pro:hover:not(:disabled) { background: var(--primary-hover); transform: translateY(-2px); color: #fff; }
    .btn-primary-pro:disabled { opacity: 0.6; cursor: not-allowed; }
    .btn-secondary-pro { background: #f1f5f9; color: #475569; border: 1px solid #cbd5e1; }
    .btn-secondary-pro:hover { background: #e2e8f0; }
    .layout-grid { display: grid; grid-template-columns: 1fr 350px; gap: 22px; }
    .sidebar-card { background: #fef3c7; border: 1px solid #fde68a; border-radius: var(--radius); overflow: hidden; }
    .sidebar-header { background: #fbbf24; color: #78350f; padding: 12px 16px; font-weight: 700; font-size: 0.92rem; display: flex; align-items: center; gap: 8px; }
    .sidebar-body { padding: 16px; color: #78350f; }
    .sidebar-body ul { margin: 0; padding-left: 20px; font-size: 0.88rem; line-height: 1.8; }
    
    /* Estilos del Modal */
    .modal-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); display: none; align-items: center; justify-content: center; z-index: 9999; backdrop-filter: blur(4px); animation: fadeIn 0.2s ease; }
    .modal-overlay.active { display: flex; }
    .modal-container { background: #fff; border-radius: 12px; width: 90%; max-width: 500px; box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3); animation: slideDown 0.3s ease; overflow: hidden; }
    .modal-header { padding: 20px 24px; border-bottom: 1px solid #e2e8f0; display: flex; align-items: center; gap: 12px; }
    .modal-header.success { background: #f0fdf4; border-bottom-color: #bbf7d0; }
    .modal-header.error { background: #fef2f2; border-bottom-color: #fecaca; }
    .modal-header.warning { background: #fef3c7; border-bottom-color: #fde68a; }
    .modal-icon { width: 48px; height: 48px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 24px; flex-shrink: 0; }
    .modal-icon.success { background: #dcfce7; color: #16a34a; }
    .modal-icon.error { background: #fee2e2; color: #dc2626; }
    .modal-icon.warning { background: #fef3c7; color: #ea580c; }
    .modal-title { font-size: 1.15rem; font-weight: 700; color: var(--text-main); margin: 0; }
    .modal-body { padding: 24px; color: #475569; font-size: 0.95rem; line-height: 1.6; }
    .modal-footer { padding: 16px 24px; border-top: 1px solid #e2e8f0; display: flex; justify-content: flex-end; gap: 10px; background: #f8fafc; }
    .modal-btn { padding: 10px 20px; border-radius: 8px; font-weight: 600; cursor: pointer; transition: 0.2s; border: none; font-size: 0.92rem; }
    .modal-btn-primary { background: var(--primary); color: #fff; }
    .modal-btn-primary:hover { background: var(--primary-hover); }
    .modal-btn-secondary { background: #fff; color: #475569; border: 1px solid #cbd5e1; }
    .modal-btn-secondary:hover { background: #f1f5f9; }
    
    @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
    @keyframes slideDown { from { transform: translateY(-30px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
    
    @media (max-width: 900px) { 
        .layout-grid { grid-template-columns: 1fr; } 
        .form-row, .form-row-3 { grid-template-columns: 1fr; }
        .modal-container { width: 95%; }
    }
</style>

<div class="page-container">

    <div class="breadcrumb-wrapper">
        <div class="breadcrumb-clean">
            <span class="breadcrumb-item"><a href="<?php echo APP_URL; ?>">Inicio</a></span>
            <span class="breadcrumb-item"><a href="<?php echo APP_URL; ?>index.php?c=grupos">Grupos</a></span>
            <span class="breadcrumb-item active">Nuevo Grupo</span>
        </div>
    </div>

    <h1 class="page-title">Nuevo Grupo</h1>

    <div class="layout-grid">
        <div class="card-box">
            <form method="POST" action="<?php echo APP_URL; ?>index.php?c=grupos&a=crear" class="needs-validation" novalidate id="formGrupo">
                
                <div class="alert-info-custom">
                    <i class="fas fa-info-circle"></i>
                    <div class="alert-content">
                        <strong>Nuevo Flujo:</strong> Ahora debes asignar el <strong>Aula Física</strong> directamente aquí. El Jefe de Departamento usará esta aula para los horarios.
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="clave" class="form-label">Clave del Grupo *</label>
                    <input type="text" class="form-control-pro" id="clave" name="clave" 
                           required maxlength="20" style="text-transform: uppercase;"
                           placeholder="Ej: ISC-101, IND-201">
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="periodo_id" class="form-label">Período *</label>
                        <select class="form-select-pro" id="periodo_id" name="periodo_id" required>
                            <option value="">Seleccione...</option>
                            <?php
                            $db = new Database(); $conn = $db->getConnection();
                            $periodos = $conn->query("SELECT * FROM periodos_escolares ORDER BY activo DESC")->fetchAll();
                            foreach ($periodos as $periodo): ?>
                                <option value="<?php echo $periodo['id']; ?>" <?php echo $periodo['activo'] ? 'selected' : ''; ?>>
                                    <?php echo $periodo['nombre']; ?> <?php echo $periodo['activo'] ? '(Activo)' : ''; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="carrera_id" class="form-label">Carrera *</label>
                        <select class="form-select-pro" id="carrera_id" name="carrera_id" required onchange="cargarMaterias()">
                            <option value="">Seleccione...</option>
                            <?php
                            $carreras = $conn->query("SELECT * FROM carreras WHERE activo = 1 ORDER BY nombre")->fetchAll();
                            foreach ($carreras as $carrera): ?>
                                <option value="<?php echo $carrera['id']; ?>"><?php echo $carrera['nombre']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="semestre_id" class="form-label">Semestre *</label>
                        <select class="form-select-pro" id="semestre_id" name="semestre_id" required onchange="cargarMaterias()">
                            <option value="">Seleccione...</option>
                            <?php
                            $semestres = $conn->query("SELECT * FROM semestres ORDER BY numero")->fetchAll();
                            foreach ($semestres as $semestre): ?>
                                <option value="<?php echo $semestre['id']; ?>"><?php echo $semestre['nombre']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="materia_id" class="form-label">Materia *</label>
                        <select class="form-select-pro" id="materia_id" name="materia_id" required>
                            <option value="">Primero seleccione carrera y semestre</option>
                        </select>
                    </div>
                </div>

                <div class="form-group" style="background: #f0f9ff; padding: 15px; border-radius: 8px; border: 1px dashed #bae6fd;">
                    <label for="aula_id" class="form-label" style="color: #0369a1;">
                        <i class="fas fa-door-open"></i> Aula Física Asignada
                    </label>
                    <select class="form-select-pro" id="aula_id" name="aula_id">
                        <option value="">-- Sin aula asignada --</option>
                        <?php if(!empty($aulas)): ?>
                            <?php foreach ($aulas as $aula): ?>
                                <option value="<?php echo $aula['id']; ?>">
                                    Edificio <?php echo $aula['edificio']; ?> - Aula <?php echo $aula['numero']; ?> 
                                    (Cap: <?php echo $aula['capacidad']; ?>)
                                </option>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <option value="" disabled>No hay aulas registradas</option>
                        <?php endif; ?>
                    </select>
                    <small class="form-text" style="color: #0c4a6e;">
                        Seleccione el salón donde se impartirá este grupo. Si aún no está definido, déjelo en blanco.
                    </small>
                </div>
                
                <div class="form-row-3">
                    <div class="form-group">
                        <label for="cupo_minimo" class="form-label">Cupo Mínimo</label>
                        <input type="number" class="form-control-pro" id="cupo_minimo" name="cupo_minimo" 
                               value="22" min="1" max="100">
                    </div>
                    
                    <div class="form-group">
                        <label for="cupo_maximo" class="form-label">Cupo Máximo</label>
                        <input type="number" class="form-control-pro" id="cupo_maximo" name="cupo_maximo" 
                               value="40" min="1" max="100">
                    </div>
                    
                    <div class="form-group">
                        <label for="alumnos_inscritos" class="form-label">Inscritos Actuales</label>
                        <input type="number" class="form-control-pro" id="alumnos_inscritos" name="alumnos_inscritos" 
                               required min="0" max="100" value="0">
                    </div>
                </div>
                
                <div class="form-actions">
                    <a href="<?php echo APP_URL; ?>index.php?c=grupos" class="btn-pro btn-secondary-pro">
                        <i class="fas fa-arrow-left"></i> Cancelar
                    </a>
                    <button type="submit" class="btn-pro btn-primary-pro" id="btnSubmit">
                        <i class="fas fa-save"></i> Guardar Grupo
                    </button>
                </div>
            </form>
        </div>
        
        <div>
            <div class="sidebar-card">
                <div class="sidebar-header">
                    <i class="fas fa-exclamation-triangle"></i> Reglas de Estado
                </div>
                <div class="sidebar-body">
                    <ul>
                        <li><strong>Proyectado:</strong> Menos de 22 alumnos</li>
                        <li><strong>Abierto:</strong> 22 o más alumnos</li>
                        <li><strong>Requisito:</strong> Asignar aula para que el Jefe pueda crear horarios.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Notificaciones -->
<div class="modal-overlay" id="modalNotificacion">
    <div class="modal-container">
        <div class="modal-header" id="modalHeader">
            <div class="modal-icon" id="modalIcon">
                <i class="fas fa-check-circle"></i>
            </div>
            <h3 class="modal-title" id="modalTitle">Título del Modal</h3>
        </div>
        <div class="modal-body" id="modalBody">
            Mensaje del modal
        </div>
        <div class="modal-footer">
            <button type="button" class="modal-btn modal-btn-primary" onclick="cerrarModal()">
                <i class="fas fa-check"></i> Entendido
            </button>
        </div>
    </div>
</div>

<script>
// ========================================
// FUNCIÓN: Cargar Materias Dinámicamente
// ========================================
function cargarMaterias() {
    const carreraId = document.getElementById('carrera_id').value;
    const semestreId = document.getElementById('semestre_id').value;
    const materiaSelect = document.getElementById('materia_id');
    
    // Si no hay selección, limpiar y salir
    if (!carreraId || !semestreId) {
        materiaSelect.innerHTML = '<option value="">Primero seleccione carrera y semestre</option>';
        return;
    }
    
    // Mostrar estado de carga
    materiaSelect.innerHTML = '<option value="">Cargando materias...</option>';
    materiaSelect.disabled = true;
    
    // Construir URL
    const url = `<?php echo APP_URL; ?>index.php?c=materias&a=obtenerPorCarreraYSemestre&carrera=${carreraId}&semestre=${semestreId}`;
    
    fetch(url)
        .then(response => {
            if (!response.ok) {
                throw new Error('Error de conexión con el servidor (HTTP ' + response.status + ')');
            }
            return response.text();
        })
        .then(text => {
            try {
                return JSON.parse(text);
            } catch (e) {
                throw new Error('La respuesta del servidor no es un JSON válido');
            }
        })
        .then(data => {
            materiaSelect.innerHTML = '<option value="">Seleccione una materia...</option>';
            
            if (Array.isArray(data) && data.length > 0) {
                data.forEach(materia => {
                    const option = document.createElement('option');
                    option.value = materia.id;
                    option.textContent = `${materia.clave} - ${materia.nombre}`;
                    materiaSelect.appendChild(option);
                });
            } else {
                materiaSelect.innerHTML += '<option value="" disabled>No se encontraron materias para esta selección</option>';
            }
            materiaSelect.disabled = false;
        })
        .catch(error => {
            console.error('Error detallado:', error);
            materiaSelect.innerHTML = '<option value="">Error al cargar materias</option>';
            materiaSelect.disabled = false;
            
            // Mostrar modal de error
            mostrarModal('warning', 'Problema al cargar materias', 
                'No se pudieron cargar las materias disponibles. Verifique su conexión e intente nuevamente.');
        });
}

// ========================================
// FUNCIONES DEL MODAL
// ========================================
function mostrarModal(tipo, titulo, mensaje) {
    const modal = document.getElementById('modalNotificacion');
    const header = document.getElementById('modalHeader');
    const icon = document.getElementById('modalIcon');
    const titleEl = document.getElementById('modalTitle');
    const body = document.getElementById('modalBody');
    
    // Limpiar clases previas
    header.classList.remove('success', 'error', 'warning');
    icon.classList.remove('success', 'error', 'warning');
    
    // Configurar según el tipo
    if (tipo === 'success') {
        header.classList.add('success');
        icon.classList.add('success');
        icon.innerHTML = '<i class="fas fa-check-circle"></i>';
    } else if (tipo === 'error') {
        header.classList.add('error');
        icon.classList.add('error');
        icon.innerHTML = '<i class="fas fa-times-circle"></i>';
    } else if (tipo === 'warning') {
        header.classList.add('warning');
        icon.classList.add('warning');
        icon.innerHTML = '<i class="fas fa-exclamation-triangle"></i>';
    }
    
    titleEl.textContent = titulo;
    body.innerHTML = mensaje; // Usamos innerHTML para soportar HTML en mensajes
    modal.classList.add('active');
}

function cerrarModal() {
    const modal = document.getElementById('modalNotificacion');
    modal.classList.remove('active');
    
    // Si fue éxito, redirigir a la lista
    const header = document.getElementById('modalHeader');
    if (header.classList.contains('success')) {
        window.location.href = '<?php echo APP_URL; ?>index.php?c=grupos';
    }
}

// Cerrar modal al hacer clic fuera
document.getElementById('modalNotificacion').addEventListener('click', function(e) {
    if (e.target === this) {
        cerrarModal();
    }
});

// ========================================
// INTERCEPTAR ENVÍO DEL FORMULARIO (AJAX)
// ========================================
document.getElementById('formGrupo').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Validación HTML5
    if (!this.checkValidity()) {
        this.classList.add('was-validated');
        mostrarModal('warning', 'Campos incompletos', 'Por favor complete todos los campos obligatorios marcados con (*).');
        return;
    }
    
    const formData = new FormData(this);
    const submitBtn = document.getElementById('btnSubmit');
    const originalHTML = submitBtn.innerHTML;
    
    // Deshabilitar botón durante el envío
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Guardando...';
    
    fetch(this.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest' // Identificador para AJAX
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Error de servidor (HTTP ' + response.status + ')');
        }
        return response.json();
    })
    .then(data => {
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalHTML;
        
        if (data.success) {
            mostrarModal('success', 'Grupo creado exitosamente', data.message || 'El grupo ha sido registrado correctamente.');
        } else {
            mostrarModal('error', 'Error al crear grupo', data.message || 'No se pudo crear el grupo. Verifique los datos ingresados.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalHTML;
        mostrarModal('error', 'Error de conexión', 'No se pudo conectar con el servidor. Por favor, intente nuevamente.');
    });
});

// ========================================
// VERIFICAR MENSAJES EN LA URL (FALLBACK)
// ========================================
window.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const mensaje = urlParams.get('mensaje');
    const tipo = urlParams.get('tipo') || 'success';
    
    if (mensaje) {
        const titulo = tipo === 'success' ? 'Operación exitosa' : 
                       tipo === 'error' ? 'Error' : 'Atención';
        mostrarModal(tipo, titulo, decodeURIComponent(mensaje));
        
        // Limpiar URL sin recargar la página
        const cleanUrl = window.location.origin + window.location.pathname + '?c=grupos&a=crear';
        window.history.replaceState({}, document.title, cleanUrl);
    }
});
</script>