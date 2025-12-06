<?php

$page_title = 'Editar Grupo';
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    
    :root { --primary: #2563eb; --primary-hover: #1d4ed8; --muted: #64748b; --text-main: #0f172a; --bg: #ffffff; --surface: #f8fafc; --border: #e2e8f0; --radius: 12px; }
    .page-container { font-family: "Open Sans", system-ui, Helvetica; padding: 22px; color: var(--text-main); }
    .breadcrumb-wrapper { margin-bottom: 16px; }
    .breadcrumb-clean { display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; font-size: 0.93rem; font-weight: 500; }
    .breadcrumb-clean .breadcrumb-item a { color: #64748b; text-decoration: none; }
    .breadcrumb-clean .breadcrumb-item + .breadcrumb-item::before { content: "›"; margin-right: 4px; color: #cbd5e1; }
    .breadcrumb-clean .active { font-weight: 700; color: #2563eb; }
    .page-title { font-size: 1.45rem; font-weight: 800; margin: 0 0 22px 0; }
    .card-box { background: var(--bg); border: 1px solid var(--border); border-radius: var(--radius); padding: 24px; margin-bottom: 22px; box-shadow: 0 1px 6px rgba(0,0,0,0.04); }
    .section-title { font-size: 1.1rem; font-weight: 700; color: var(--text-main); margin: 24px 0 16px 0; padding-bottom: 10px; border-bottom: 2px solid var(--border); }
    .form-group { margin-bottom: 20px; }
    .form-label { font-size: 0.80rem; color: var(--muted); font-weight: 700; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.6px; display: block; }
    .form-control-pro, .form-select-pro { width: 100%; height: 44px; padding: 8px 12px; border-radius: 8px; border: 1px solid #d1d9e5; background: #fff; font-size: 0.95rem; color: var(--text-main); transition: 0.15s; }
    .form-control-pro:focus, .form-select-pro:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(37,99,235,0.20); outline: none; }
    .form-text { font-size: 0.82rem; color: var(--muted); margin-top: 6px; display: block; }
    .form-row-3 { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 20px; }
    .form-actions { display: flex; justify-content: flex-end; gap: 12px; margin-top: 32px; padding-top: 24px; border-top: 1px solid var(--border); }
    .btn-pro { display: inline-flex; align-items: center; justify-content: center; gap: 8px; padding: 10px 20px; border-radius: 10px; font-weight: 600; cursor: pointer; transition: 0.25s ease; border: none; min-width: 120px; text-decoration: none; font-size: 0.92rem; }
    .btn-primary-pro { background: var(--primary); color: #fff; box-shadow: 0 5px 14px rgba(37,99,235,0.22); }
    .btn-primary-pro:hover:not(:disabled) { background: var(--primary-hover); transform: translateY(-2px); color: #fff; }
    .btn-primary-pro:disabled { opacity: 0.6; cursor: not-allowed; }
    .btn-secondary-pro { background: #f1f5f9; color: #475569; border: 1px solid #cbd5e1; }
    .btn-secondary-pro:hover { background: #e2e8f0; }
    .btn-danger-pro { background: #ef4444; color: #fff; width: 100%; margin-bottom: 12px; }
    .btn-danger-pro:hover { background: #dc2626; transform: translateY(-2px); }
    .layout-grid { display: grid; grid-template-columns: 1fr 350px; gap: 22px; }
    .alert-info-custom { background: #dbeafe; border: 1px solid #93c5fd; border-radius: 10px; padding: 16px; margin-bottom: 24px; }
    .info-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px; color: #1e40af; font-size: 0.90rem; }
    .sidebar-card { background: var(--bg); border: 1px solid var(--border); border-radius: var(--radius); overflow: hidden; margin-bottom: 22px; }
    .sidebar-header { padding: 12px 16px; font-weight: 700; font-size: 0.92rem; display: flex; align-items: center; gap: 8px; }
    .sidebar-header.bg-warning { background: #fbbf24; color: #78350f; }
    .sidebar-body { padding: 16px; }
    
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
    .modal-btn-danger { background: #dc2626; color: #fff; }
    .modal-btn-danger:hover { background: #b91c1c; }
    
    @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
    @keyframes slideDown { from { transform: translateY(-30px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
    
    @media (max-width: 900px) { 
        .layout-grid { grid-template-columns: 1fr; } 
        .form-row-3 { grid-template-columns: 1fr; }
        .modal-container { width: 95%; }
    }
</style>

<div class="page-container">

    <div class="breadcrumb-wrapper">
        <div class="breadcrumb-clean">
            <span class="breadcrumb-item"><a href="<?php echo APP_URL; ?>">Inicio</a></span>
            <span class="breadcrumb-item"><a href="<?php echo APP_URL; ?>index.php?c=grupos">Grupos</a></span>
            <span class="breadcrumb-item active">Editar Grupo</span>
        </div>
    </div>

    <h1 class="page-title">Editar Grupo</h1>

    <div class="layout-grid">
        
        <div>
            <div class="card-box">
                <form method="POST" action="<?php echo APP_URL; ?>index.php?c=grupos&a=editar&id=<?php echo $grupo['id']; ?>" 
                      class="needs-validation" novalidate id="formEditar">
                    
                    <div class="alert-info-custom">
                        <h5 style="margin:0 0 10px 0; color:#1e3a8a; font-weight:700;"><i class="fas fa-info-circle"></i> Información Fija</h5>
                        <div class="info-grid">
                            <div>
                                <strong>Clave:</strong> <?php echo htmlspecialchars($grupo['clave']); ?><br>
                                <strong>Materia:</strong> <?php echo htmlspecialchars($grupo['materia_nombre']); ?>
                            </div>
                            <div>
                                <strong>ID Grupo:</strong> #<?php echo $grupo['id']; ?><br>
                                <strong>Creado:</strong> <?php echo date('d/m/Y', strtotime($grupo['created_at'])); ?>
                            </div>
                        </div>
                    </div>
                    
                    <h5 class="section-title">Ubicación (Aula)</h5>
                    <div class="form-group" style="background: #f0f9ff; padding: 15px; border-radius: 8px; border: 1px dashed #bae6fd;">
                        <label for="aula_id" class="form-label" style="color: #0369a1;">
                            <i class="fas fa-door-open"></i> Aula Física Asignada
                        </label>
                        <select class="form-select-pro" id="aula_id" name="aula_id">
                            <option value="">-- Sin aula asignada --</option>
                            <?php if(!empty($aulas)): ?>
                                <?php foreach ($aulas as $aula): ?>
                                    <option value="<?php echo $aula['id']; ?>" <?php echo ($grupo['aula_id'] == $aula['id']) ? 'selected' : ''; ?>>
                                        Edificio <?php echo htmlspecialchars($aula['edificio']); ?> - Aula <?php echo htmlspecialchars($aula['numero']); ?> 
                                        (Cap: <?php echo (int)$aula['capacidad']; ?>)
                                    </option>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <option value="" disabled>No hay aulas registradas</option>
                            <?php endif; ?>
                        </select>
                        <small class="form-text" style="color: #0c4a6e;">
                            Esta aula será la utilizada para todos los bloques horarios de este grupo.
                        </small>
                    </div>

                    <h5 class="section-title">Configuración de Cupos</h5>
                    <div class="form-row-3">
                        <div class="form-group">
                            <label for="cupo_minimo" class="form-label">Cupo Mínimo *</label>
                            <input type="number" class="form-control-pro" id="cupo_minimo" name="cupo_minimo" 
                                   required min="1" max="100" 
                                   value="<?php echo (int)$grupo['cupo_minimo']; ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="cupo_maximo" class="form-label">Cupo Máximo *</label>
                            <input type="number" class="form-control-pro" id="cupo_maximo" name="cupo_maximo" 
                                   required min="1" max="100" 
                                   value="<?php echo (int)$grupo['cupo_maximo']; ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="alumnos_inscritos" class="form-label">Inscritos *</label>
                            <input type="number" class="form-control-pro" id="alumnos_inscritos" name="alumnos_inscritos" 
                                   required min="0" max="100" 
                                   value="<?php echo (int)$grupo['alumnos_inscritos']; ?>">
                        </div>
                    </div>
                    
                    <h5 class="section-title">Estado Manual</h5>
                    <div class="form-group">
                        <label for="estado" class="form-label">Estado *</label>
                        <select class="form-select-pro" id="estado" name="estado" required>
                            <option value="proyectado" <?php echo ($grupo['estado'] == 'proyectado') ? 'selected' : ''; ?>>Proyectado</option>
                            <option value="abierto" <?php echo ($grupo['estado'] == 'abierto') ? 'selected' : ''; ?>>Abierto</option>
                            <option value="cerrado" <?php echo ($grupo['estado'] == 'cerrado') ? 'selected' : ''; ?>>Cerrado</option>
                            <option value="cancelado" <?php echo ($grupo['estado'] == 'cancelado') ? 'selected' : ''; ?>>Cancelado</option>
                        </select>
                    </div>
                    
                    <div class="form-actions">
                        <a href="<?php echo APP_URL; ?>index.php?c=grupos" class="btn-pro btn-secondary-pro">
                            <i class="fas fa-arrow-left"></i> Cancelar
                        </a>
                        <button type="submit" class="btn-pro btn-primary-pro" id="btnSubmit">
                            <i class="fas fa-save"></i> Actualizar Grupo
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <div>
            <div class="sidebar-card">
                <div class="sidebar-header bg-warning">
                    <i class="fas fa-tools"></i> Acciones
                </div>
                <div class="sidebar-body">
                    <?php if ($grupo['estado'] != 'cancelado'): ?>
                    <button type="button" class="btn-pro btn-danger-pro" onclick="confirmarCancelacion()">
                        <i class="fas fa-ban"></i> Cancelar Grupo
                    </button>
                    <?php endif; ?>
                    
                    <a href="<?php echo APP_URL; ?>index.php?c=horarios&a=asignar&grupo=<?php echo $grupo['id']; ?>" 
                       class="btn-pro btn-primary-pro" style="width: 100%;">
                        <i class="fas fa-calendar"></i> Ver Horarios
                    </a>
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
    body.innerHTML = mensaje;
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
document.getElementById('formEditar').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Validación HTML5
    if (!this.checkValidity()) {
        this.classList.add('was-validated');
        mostrarModal('warning', 'Campos incompletos', 'Por favor complete todos los campos obligatorios.');
        return;
    }
    
    const formData = new FormData(this);
    const submitBtn = document.getElementById('btnSubmit');
    const originalHTML = submitBtn.innerHTML;
    
    // Deshabilitar botón durante el envío
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Actualizando...';
    
    fetch(this.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
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
            mostrarModal('success', 'Grupo actualizado', data.message || 'El grupo ha sido actualizado correctamente.');
        } else {
            mostrarModal('error', 'Error al actualizar', data.message || 'No se pudo actualizar el grupo.');
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
// FUNCIÓN CANCELAR GRUPO
// ========================================
function confirmarCancelacion() {
    mostrarModal('warning', 'Confirmar Cancelación', 
        '¿Está seguro de que desea <strong>CANCELAR</strong> este grupo?<br><br>' +
        '<span style="color:#dc2626;">⚠️ Esta acción cambiará el estado del grupo a "Cancelado".</span>'
    );
    
    
    document.getElementById('estado').value = 'cancelado';
}

// ========================================
// VERIFICAR MENSAJES DE SESIÓN
// ========================================
window.addEventListener('DOMContentLoaded', function() {
    <?php if (isset($_SESSION['success'])): ?>
        mostrarModal('success', 'Operación exitosa', '<?php echo addslashes($_SESSION['success']); ?>');
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['error'])): ?>
        mostrarModal('error', 'Error', '<?php echo addslashes($_SESSION['error']); ?>');
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>
});
</script>