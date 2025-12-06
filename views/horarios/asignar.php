<?php

$page_title = 'Asignar Horarios';

// Obtener contexto (Carrera y Semestre) ---
$db = new Database();
$conn = $db->getConnection();

// Obtener nombre carrera
$stmt = $conn->prepare("SELECT nombre, clave FROM carreras WHERE id = :id");
$stmt->execute([':id' => $carrera_id]);
$info_carrera = $stmt->fetch();
$nombre_carrera = $info_carrera ? $info_carrera['nombre'] : 'Carrera no encontrada';

// Obtener nombre semestre
$stmt = $conn->prepare("SELECT nombre FROM semestres WHERE id = :id");
$stmt->execute([':id' => $semestre_id]);
$nombre_semestre = $stmt->fetchColumn() ?: 'Semestre no encontrado';

// Generación de horas 
$horas_dia = [];
for ($h = 7; $h <= 20; $h++) { 
    $horas_dia[] = sprintf('%02d:00', $h); 
}
$dias_semana = ['lunes', 'martes', 'miercoles', 'jueves', 'viernes'];
$dias_labels = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes'];

// Organizar horarios existentes en una matriz
$matriz_horarios = [];
foreach ($dias_semana as $dia) {
    foreach ($horas_dia as $hora) {
        $key = $dia . '-' . str_replace(':', '', substr($hora, 0, 5));
        $matriz_horarios[$key] = null; 
    }
}

// Llenar matriz con datos reales
if (!empty($horarios_existentes)) {
    foreach ($horarios_existentes as $h) {
        $dia = strtolower($h['dia']);
        $hora_inicio = substr($h['hora_inicio'], 0, 5);
        $key = $dia . '-' . str_replace(':', '', $hora_inicio);
        $matriz_horarios[$key] = $h;
    }
}
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    /* ==================== ESTILOS BASE ==================== */
    :root { 
        --primary: #2563eb; 
        --success: #10b981;
        --danger: #ef4444;
        --warning: #f59e0b;
        --info: #3b82f6;
        --bg: #ffffff; 
        --surface: #f8fafc; 
        --border: #e2e8f0; 
        --muted: #64748b; 
        --text: #0f172a; 
    }
    
    .page-container { 
        padding: 22px; 
        font-family: "Open Sans", sans-serif; 
        color: var(--text); 
    }
    
    /* ==================== BREADCRUMB & HEADER ==================== */
    .breadcrumb-clean { 
        display: inline-flex; 
        gap: 6px; 
        padding: 6px 12px; 
        background: var(--surface); 
        border: 1px solid var(--border); 
        border-radius: 10px; 
        font-size: 0.9rem; 
        font-weight: 500; 
        margin-bottom: 16px; 
    }
    
    .breadcrumb-clean a { 
        text-decoration: none; 
        color: var(--muted); 
        transition: color 0.2s;
    }
    
    .breadcrumb-clean a:hover {
        color: var(--primary);
    }
    
    .breadcrumb-clean .active { 
        color: var(--primary); 
        font-weight: 700; 
    }
    
    .header-section { 
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
    
    .page-subtitle { 
        font-size: 0.95rem; 
        color: var(--muted); 
        margin-top: 4px; 
        font-weight: 500; 
        display: flex; 
        align-items: center; 
        gap: 10px; 
    }
    
    .page-subtitle i { 
        color: var(--primary); 
        opacity: 0.8; 
    }
    
    /* ==================== CARDS & FORMS ==================== */
    .card-box { 
        background: var(--bg); 
        border: 1px solid var(--border); 
        border-radius: 12px; 
        padding: 20px; 
        margin-bottom: 22px; 
        box-shadow: 0 1px 4px rgba(0,0,0,0.03); 
    }
    
    .card-header { 
        font-weight: 700; 
        color: var(--primary); 
        border-bottom: 2px solid var(--border); 
        padding-bottom: 12px; 
        margin-bottom: 16px; 
        display: flex; 
        gap: 10px; 
        align-items: center; 
    }
    
    .form-grid { 
        display: grid; 
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); 
        gap: 16px; 
    }
    
    .form-group label { 
        display: block; 
        font-size: 0.8rem; 
        font-weight: 700; 
        color: var(--muted); 
        margin-bottom: 6px; 
        text-transform: uppercase; 
    }
    
    .form-control { 
        width: 100%; 
        height: 45px; 
        padding: 8px 12px; 
        border: 1px solid #d1d9e5; 
        border-radius: 8px; 
        background: #fff; 
        transition: all 0.2s;
    }
    
    .form-control:focus { 
        border-color: var(--primary); 
        outline: none; 
        box-shadow: 0 0 0 3px rgba(37,99,235,0.1); 
    }
    
    /* ==================== AULA DISPLAY ==================== */
    .aula-display {
        background: #f0f9ff; 
        border: 1px dashed #bae6fd; 
        border-radius: 8px;
        height: 45px; 
        display: flex; 
        align-items: center; 
        padding: 0 12px;
        font-weight: 600; 
        color: #0369a1; 
        gap: 8px; 
        overflow: hidden; 
        white-space: nowrap; 
        text-overflow: ellipsis;
        transition: all 0.3s;
    }
    
    /* ==================== TABLA HORARIO ==================== */
    .table-container { 
        overflow-x: auto; 
        border: 1px solid var(--border); 
        border-radius: 8px; 
    }
    
    .horario-table { 
        width: 100%; 
        border-collapse: collapse; 
        font-size: 0.85rem; 
        table-layout: fixed; 
    }
    
    .horario-table th { 
        background: #1e293b; 
        color: #fff; 
        padding: 12px; 
        border: 1px solid #334155; 
        text-align: center; 
    }
    
    .horario-table td { 
        padding: 8px; 
        min-height: 100px; 
        border: 1px solid var(--border); 
        vertical-align: top; 
        width: 18%; 
    }
    
    .celda-hora { 
        background: var(--surface); 
        font-weight: 700; 
        width: 10%; 
        text-align: center; 
        vertical-align: middle; 
    }
    
    .celda-horario { 
        background: #fafbfc; 
        transition: 0.2s; 
        height: 100px; 
    }
    
    .celda-horario:hover { 
        background: #f1f5f9; 
    }

    /* ==================== BLOQUES ==================== */
    .bloque-horario { 
        padding: 8px; 
        border-radius: 6px; 
        margin-bottom: 6px; 
        border-left: 4px solid; 
        background: #fff; 
        box-shadow: 0 2px 4px rgba(0,0,0,0.05); 
        font-size: 0.8rem;
        animation: fadeIn 0.3s ease;
    }
    
    .estado-borrador { 
        border-color: #64748b; 
        background: #f8fafc; 
    }
    
    .estado-conciliado { 
        border-color: #2563eb; 
        background: #eff6ff; 
    }
    
    .estado-publicado { 
        border-color: #10b981; 
        background: #f0fdf4; 
    }
    
    .btn-danger-sm { 
        background: #ef4444; 
        color: #fff; 
        padding: 2px 8px; 
        font-size: 0.7rem; 
        width: 100%; 
        margin-top: 5px; 
        border: none; 
        border-radius: 4px; 
        cursor: pointer; 
        transition: background 0.2s;
    }
    
    .btn-danger-sm:hover {
        background: #dc2626;
    }

    /* ==================== BOTONES ==================== */
    .btn { 
        padding: 10px 18px; 
        border-radius: 8px; 
        border: none; 
        cursor: pointer; 
        font-weight: 600; 
        display: inline-flex; 
        align-items: center; 
        gap: 8px; 
        transition: all 0.2s; 
        text-decoration: none; 
    }
    
    .btn-primary { 
        background: var(--primary); 
        color: #fff; 
    }
    
    .btn-primary:hover {
        background: #1d4ed8;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(37,99,235,0.3);
    }
    
    .btn-primary:disabled { 
        opacity: 0.6; 
        cursor: not-allowed; 
        transform: none;
    }
    
    .btn-success { 
        background: var(--success); 
        color: #fff; 
    }
    
    .btn-success:hover {
        background: #059669;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(16,185,129,0.3);
    }
    
    /* ==================== SISTEMA DE NOTIFICACIONES TOAST ==================== */
    #toast-container { 
        position: fixed; 
        top: 20px; 
        right: 20px; 
        z-index: 9999; 
        max-width: 400px; 
        display: flex;
        flex-direction: column;
        gap: 10px;
    }
    
    .toast {
        background: #fff;
        padding: 16px 20px;
        border-radius: 12px;
        box-shadow: 0 8px 24px rgba(0,0,0,0.15);
        border-left: 5px solid;
        display: flex;
        align-items: flex-start;
        gap: 12px;
        animation: slideInRight 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        min-width: 300px;
    }
    
    .toast-icon {
        font-size: 1.5rem;
        flex-shrink: 0;
    }
    
    .toast-content {
        flex: 1;
    }
    
    .toast-title {
        font-weight: 700;
        font-size: 0.95rem;
        margin-bottom: 4px;
    }
    
    .toast-message {
        font-size: 0.85rem;
        color: var(--muted);
        line-height: 1.4;
    }
    
    .toast-close {
        background: none;
        border: none;
        cursor: pointer;
        font-size: 1.2rem;
        color: var(--muted);
        padding: 0;
        margin-left: 8px;
        transition: color 0.2s;
    }
    
    .toast-close:hover {
        color: var(--text);
    }
    
    .toast.success { 
        border-color: var(--success); 
    }
    
    .toast.success .toast-icon { 
        color: var(--success); 
    }
    
    .toast.danger { 
        border-color: var(--danger); 
    }
    
    .toast.danger .toast-icon { 
        color: var(--danger); 
    }
    
    .toast.warning { 
        border-color: var(--warning); 
    }
    
    .toast.warning .toast-icon { 
        color: var(--warning); 
    }
    
    .toast.info { 
        border-color: var(--info); 
    }
    
    .toast.info .toast-icon { 
        color: var(--info); 
    }
    
    @keyframes slideInRight { 
        from { 
            transform: translateX(400px); 
            opacity: 0;
        } 
        to { 
            transform: translateX(0); 
            opacity: 1;
        } 
    }
    
    @keyframes slideOutRight {
        to {
            transform: translateX(400px);
            opacity: 0;
        }
    }
    
    .toast.removing {
        animation: slideOutRight 0.3s ease forwards;
    }
    
    /* ==================== SISTEMA DE MODALES ==================== */
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(4px);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 10000;
        opacity: 0;
        animation: fadeIn 0.3s ease forwards;
    }
    
    .modal-container {
        background: #fff;
        border-radius: 16px;
        max-width: 500px;
        width: 90%;
        box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        transform: scale(0.9);
        animation: scaleIn 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55) forwards;
        overflow: hidden;
    }
    
    .modal-header {
        padding: 24px;
        border-bottom: 1px solid var(--border);
        display: flex;
        align-items: center;
        gap: 12px;
    }
    
    .modal-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        flex-shrink: 0;
    }
    
    .modal-icon.danger {
        background: #fee2e2;
        color: var(--danger);
    }
    
    .modal-icon.success {
        background: #d1fae5;
        color: var(--success);
    }
    
    .modal-icon.warning {
        background: #fef3c7;
        color: var(--warning);
    }
    
    .modal-icon.info {
        background: #dbeafe;
        color: var(--info);
    }
    
    .modal-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--text);
        margin: 0;
    }
    
    .modal-body {
        padding: 24px;
    }
    
    .modal-message {
        font-size: 1rem;
        color: var(--muted);
        line-height: 1.6;
        margin: 0;
    }
    
    .modal-footer {
        padding: 16px 24px;
        background: var(--surface);
        display: flex;
        justify-content: flex-end;
        gap: 12px;
    }
    
    .modal-btn {
        padding: 10px 24px;
        border-radius: 8px;
        border: none;
        cursor: pointer;
        font-weight: 600;
        font-size: 0.95rem;
        transition: all 0.2s;
    }
    
    .modal-btn-cancel {
        background: #fff;
        color: var(--muted);
        border: 1px solid var(--border);
    }
    
    .modal-btn-cancel:hover {
        background: var(--surface);
        color: var(--text);
    }
    
    .modal-btn-confirm {
        background: var(--primary);
        color: #fff;
    }
    
    .modal-btn-confirm:hover {
        background: #1d4ed8;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(37,99,235,0.3);
    }
    
    .modal-btn-danger {
        background: var(--danger);
        color: #fff;
    }
    
    .modal-btn-danger:hover {
        background: #dc2626;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(239,68,68,0.3);
    }
    
    .modal-btn-success {
        background: var(--success);
        color: #fff;
    }
    
    .modal-btn-success:hover {
        background: #059669;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(16,185,129,0.3);
    }
    
    @keyframes fadeIn {
        to { opacity: 1; }
    }
    
    @keyframes scaleIn {
        to { 
            transform: scale(1); 
            opacity: 1;
        }
    }
    
    /* Loading Spinner dentro del botón */
    .btn-loading {
        position: relative;
        pointer-events: none;
    }
    
    .btn-loading::after {
        content: "";
        position: absolute;
        width: 16px;
        height: 16px;
        top: 50%;
        left: 50%;
        margin-left: -8px;
        margin-top: -8px;
        border: 2px solid #fff;
        border-radius: 50%;
        border-top-color: transparent;
        animation: spin 0.6s linear infinite;
    }
    
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
    
    @keyframes fadeIn { 
        from { opacity: 0; transform: translateY(5px); } 
        to { opacity: 1; transform: translateY(0); } 
    }
</style>

<div class="page-container">
    <div class="breadcrumb-clean">
        <a href="<?php echo APP_URL; ?>">Inicio</a> <span class="mx-1">›</span>
        <a href="<?php echo APP_URL; ?>index.php?c=horarios">Horarios</a> <span class="mx-1">›</span>
        <span class="active">Asignar</span>
    </div>

    <div class="header-section">
        <div>
            <h1 class="page-title">Asignación de Horarios</h1>
            <div class="page-subtitle">
                <span><i class="fas fa-graduation-cap"></i> <?php echo htmlspecialchars($nombre_carrera); ?></span>
                <span style="color: #cbd5e1;">|</span>
                <span><i class="fas fa-layer-group"></i> <?php echo htmlspecialchars($nombre_semestre); ?></span>
            </div>
        </div>
        <div class="header-actions">
            <button type="button" class="btn btn-success" onclick="mostrarModalConciliar()">
                <i class="fas fa-check-circle"></i> Conciliar y Sincronizar
            </button>
        </div>
    </div>

    <!-- CONTENEDOR DE NOTIFICACIONES TOAST -->
    <div id="toast-container"></div>

    <div class="card-box">
        <div class="card-header"><i class="fas fa-plus-circle"></i> Agregar Bloque</div>
        
        <form id="form-agregar-horario">
            <input type="hidden" name="periodo_id" value="<?php echo $periodo_id; ?>">
            <input type="hidden" name="carrera_id" value="<?php echo $carrera_id; ?>">
            <input type="hidden" name="semestre_id" value="<?php echo $semestre_id; ?>">
            <input type="hidden" name="materia_id" id="materia_id">
            
            <div class="form-grid">
                <div class="form-group">
                    <label>Grupo *</label>
                    <select class="form-control" name="grupo_id" id="grupo_id" required onchange="cargarInfoMateria()">
                        <option value="">Seleccione...</option>
                        <?php foreach ($grupos as $grupo): ?>
                            <option value="<?php echo $grupo['id']; ?>" 
                                    data-materia="<?php echo $grupo['materia_id']; ?>"
                                    data-clave="<?php echo $grupo['materia_clave']; ?>"
                                    data-aula="<?php echo !empty($grupo['aula_asignada']) ? htmlspecialchars($grupo['aula_asignada']) : ''; ?>">
                                <?php echo $grupo['clave']; ?> - <?php echo $grupo['materia_nombre']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Aula Asignada (Solo lectura)</label>
                    <div id="info-aula-asignada" class="aula-display">
                        <span style="color: #94a3b8;">Seleccione un grupo...</span>
                    </div>
                </div>

                <div class="form-group">
                    <label>Docente *</label>
                    <select class="form-control" name="docente_id" required>
                        <option value="">Seleccione...</option>
                        <?php foreach ($docentes as $d): ?>
                            <option value="<?php echo $d['id']; ?>">
                                <?php echo $d['apellido_paterno'] . ' ' . $d['nombre']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Día *</label>
                    <select class="form-control" name="dia" required>
                        <option value="lunes">Lunes</option>
                        <option value="martes">Martes</option>
                        <option value="miercoles">Miércoles</option>
                        <option value="jueves">Jueves</option>
                        <option value="viernes">Viernes</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Inicio *</label>
                    <select class="form-control" name="hora_inicio" required>
                        <?php foreach ($horas_dia as $h): ?>
                            <option value="<?php echo $h; ?>:00"><?php echo $h; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Fin *</label>
                    <select class="form-control" name="hora_fin" required>
                        <?php foreach ($horas_dia as $h): if($h > '07:00'): ?>
                            <option value="<?php echo $h; ?>:00"><?php echo $h; ?></option>
                        <?php endif; endforeach; ?>
                    </select>
                </div>
            </div>
            
            <div style="margin-top: 16px; text-align: right;">
                <button type="submit" id="btn-submit" class="btn btn-primary" disabled>
                    <i class="fas fa-save"></i> Guardar Bloque
                </button>
            </div>
        </form>
    </div>

    <div class="card-box" style="padding: 0; overflow: hidden;">
        <div class="table-container">
            <table class="horario-table">
                <thead>
                    <tr>
                        <th>Hora</th>
                        <?php foreach ($dias_labels as $d) echo "<th>$d</th>"; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($horas_dia as $hora): ?>
                    <tr>
                        <td class="celda-hora"><?php echo $hora; ?></td>
                        <?php foreach ($dias_semana as $dia): 
                            $hora_id = str_replace(':', '', substr($hora, 0, 5)); 
                            $celda_id = "celda-{$dia}-{$hora_id}";
                            $key = "{$dia}-{$hora_id}";
                            $bloque_existente = $matriz_horarios[$key] ?? null;
                        ?>
                            <td class="celda-horario" id="<?php echo $celda_id; ?>" 
                                data-dia="<?php echo $dia; ?>" 
                                data-hora="<?php echo substr($hora, 0, 5); ?>">
                                
                                <?php if ($bloque_existente): ?>
                                    <div class="bloque-horario estado-<?php echo $bloque_existente['estado']; ?>">
                                        <div style="font-weight:700; color:#2563eb; line-height: 1.2; margin-bottom: 4px;">
                                            <?php echo htmlspecialchars($bloque_existente['materia_clave']); ?>
                                            <div style="font-size:0.85em; color:#1e293b; font-weight:600;">
                                                <?php echo htmlspecialchars($bloque_existente['materia_nombre']); ?>
                                            </div>
                                        </div>
                                        <div style="font-size:0.75rem; color:#64748b;">
                                            <i class="fas fa-user"></i> <?php echo htmlspecialchars($bloque_existente['docente_nombre']); ?><br>
                                            <i class="fas fa-door-open"></i> <?php echo htmlspecialchars($bloque_existente['aula']); ?>
                                        </div>
                                        <div style="font-size:0.75rem; font-weight:700; margin-top:4px; border-top:1px solid #eee; padding-top:4px;">
                                            <?php echo substr($bloque_existente['hora_inicio'], 0, 5); ?> - 
                                            <?php echo substr($bloque_existente['hora_fin'], 0, 5); ?>
                                        </div>
                                        <button class="btn-danger-sm" onclick="mostrarModalEliminar(<?php echo $bloque_existente['id']; ?>)">Eliminar</button>
                                    </div>
                                <?php endif; ?>
                                
                            </td>
                        <?php endforeach; ?>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
// =====================================================
// SISTEMA DE NOTIFICACIONES TOAST
// =====================================================
function mostrarToast(tipo, titulo, mensaje) {
    const container = document.getElementById('toast-container');
    
    const iconos = {
        success: 'fa-check-circle',
        danger: 'fa-exclamation-circle',
        warning: 'fa-exclamation-triangle',
        info: 'fa-info-circle'
    };
    
    const toast = document.createElement('div');
    toast.className = `toast ${tipo}`;
    toast.innerHTML = `
        <div class="toast-icon">
            <i class="fas ${iconos[tipo]}"></i>
        </div>
        <div class="toast-content">
            <div class="toast-title">${titulo}</div>
            <div class="toast-message">${mensaje}</div>
        </div>
        <button class="toast-close" onclick="cerrarToast(this)">
            <i class="fas fa-times"></i>
        </button>
    `;
    
    container.appendChild(toast);
    
    // Auto-cerrar después de 5 segundos
    setTimeout(() => {
        cerrarToast(toast.querySelector('.toast-close'));
    }, 5000);
}

function cerrarToast(btn) {
    const toast = btn.closest('.toast');
    toast.classList.add('removing');
    setTimeout(() => toast.remove(), 300);
}

// =====================================================
// SISTEMA DE MODALES
// =====================================================
function mostrarModal(config) {
    const overlay = document.createElement('div');
    overlay.className = 'modal-overlay';
    
    const iconos = {
        danger: 'fa-exclamation-triangle',
        success: 'fa-check-circle',
        warning: 'fa-exclamation-circle',
        info: 'fa-info-circle'
    };
    
    overlay.innerHTML = `
        <div class="modal-container">
            <div class="modal-header">
                <div class="modal-icon ${config.tipo}">
                    <i class="fas ${iconos[config.tipo]}"></i>
                </div>
                <h3 class="modal-title">${config.titulo}</h3>
            </div>
            <div class="modal-body">
                <p class="modal-message">${config.mensaje}</p>
            </div>
            <div class="modal-footer">
                <button class="modal-btn modal-btn-cancel" onclick="cerrarModal(this)">
                    ${config.textoCancelar || 'Cancelar'}
                </button>
                <button class="modal-btn modal-btn-${config.tipo}" id="modal-confirm-btn">
                    ${config.textoConfirmar || 'Confirmar'}
                </button>
            </div>
        </div>
    `;
    
    document.body.appendChild(overlay);
    
    // Cerrar al hacer clic fuera del modal
    overlay.addEventListener('click', (e) => {
        if (e.target === overlay) {
            cerrarModal(overlay);
        }
    });
    
    // Manejar confirmación
    const confirmBtn = overlay.querySelector('#modal-confirm-btn');
    confirmBtn.addEventListener('click', () => {
        if (config.onConfirm) {
            config.onConfirm();
        }
        cerrarModal(overlay);
    });
    
    return overlay;
}

function cerrarModal(elemento) {
    const overlay = elemento.closest ? elemento.closest('.modal-overlay') : elemento;
    overlay.style.animation = 'fadeOut 0.3s ease';
    setTimeout(() => overlay.remove(), 300);
}

// =====================================================
// LÓGICA ESPECÍFICA DE HORARIOS
// =====================================================
const APP_URL = '<?php echo APP_URL; ?>';
const PERIODO_ID = <?php echo $periodo_id; ?>;
const CARRERA_ID = <?php echo $carrera_id; ?>;
const SEMESTRE_ID = <?php echo $semestre_id; ?>;
let grupoSeleccionado = null;

// Control del formulario y selección de grupo
function cargarInfoMateria() {
    const grupoSelect = document.getElementById('grupo_id');
    const selectedOption = grupoSelect.options[grupoSelect.selectedIndex];
    const infoAula = document.getElementById('info-aula-asignada');
    const btnSubmit = document.getElementById('btn-submit');
    
    if (selectedOption.value) {
        document.getElementById('materia_id').value = selectedOption.dataset.materia;
        grupoSeleccionado = selectedOption.value;
        
        const aulaNombre = selectedOption.dataset.aula;
        
        if (aulaNombre && aulaNombre !== '') {
            infoAula.innerHTML = `<i class="fas fa-door-open"></i> ${aulaNombre}`;
            infoAula.style.color = '#0369a1';
            infoAula.style.backgroundColor = '#f0f9ff';
            infoAula.style.borderColor = '#bae6fd';
            btnSubmit.disabled = false; 
        } else {
            infoAula.innerHTML = `<i class="fas fa-exclamation-triangle"></i> SIN AULA (DEP)`;
            infoAula.style.color = '#991b1b';
            infoAula.style.backgroundColor = '#fee2e2';
            infoAula.style.borderColor = '#fca5a5';
            btnSubmit.disabled = true;
            mostrarToast('warning', 'Sin Aula Asignada', 'El grupo seleccionado no tiene aula asignada por la DEP. Imposible asignar horario.');
        }
    } else {
        grupoSeleccionado = null;
        infoAula.innerHTML = '<span style="color: #94a3b8;">Seleccione un grupo...</span>';
        btnSubmit.disabled = true;
    }
}

// Función para renderizar bloques
function renderizarBloque(h) {
    let dia = h.dia.toLowerCase().trim(); 
    let horaClean = h.hora_inicio.substring(0, 5).replace(':', ''); 
    
    const celdaId = `celda-${dia}-${horaClean}`;
    const celda = document.getElementById(celdaId);

    if (celda) {
        celda.innerHTML = `
            <div class="bloque-horario estado-${h.estado}" data-id="${h.id}">
                <div style="font-weight:700; color:#2563eb; line-height: 1.2; margin-bottom: 4px;">
                    ${h.materia_clave}
                    <div style="font-size:0.85em; color:#1e293b; font-weight:600;">
                        ${h.materia_nombre}
                    </div>
                </div>
                <div style="font-size:0.75rem; color:#64748b;">
                    <i class="fas fa-user"></i> ${h.docente_nombre}<br>
                    <i class="fas fa-door-open"></i> ${h.aula}
                </div>
                <div style="font-size:0.75rem; font-weight:700; margin-top:4px; border-top:1px solid #eee; padding-top:4px;">
                    ${h.hora_inicio.substring(0,5)} - ${h.hora_fin.substring(0,5)}
                </div>
                <button class="btn-danger-sm" onclick="mostrarModalEliminar(${h.id})">Eliminar</button>
            </div>
        `;
    } else {
        console.warn("No se encontró celda para:", celdaId);
    }
}

// Guardar bloque
document.getElementById('form-agregar-horario').addEventListener('submit', function(e) {
    e.preventDefault();
    if (!grupoSeleccionado) return;

    const btn = document.getElementById('btn-submit');
    const originalHTML = btn.innerHTML;
    btn.disabled = true;
    btn.classList.add('btn-loading');
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Guardando...';

    fetch(APP_URL + 'index.php?c=horarios&a=guardar', {
        method: 'POST',
        body: new FormData(this)
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            mostrarToast('success', '¡Éxito!', 'Bloque de horario agregado correctamente');
            
            if(data.horario) {
                renderizarBloque(data.horario);
                this.reset();
                document.getElementById('info-aula-asignada').innerHTML = '<span style="color: #94a3b8;">Seleccione un grupo...</span>';
                grupoSeleccionado = null;
            } else {
                setTimeout(() => location.reload(), 1500);
            }
        } else {
            mostrarToast('danger', 'Error', data.message || 'No se pudo agregar el bloque');
        }
        btn.disabled = false;
        btn.classList.remove('btn-loading');
        btn.innerHTML = originalHTML;
    })
    .catch(err => {
        console.error(err);
        mostrarToast('danger', 'Error de Conexión', 'No se pudo conectar con el servidor');
        btn.disabled = false;
        btn.classList.remove('btn-loading');
        btn.innerHTML = originalHTML;
    });
});

// Modal de eliminación
function mostrarModalEliminar(id) {
    mostrarModal({
        tipo: 'danger',
        titulo: '¿Eliminar Bloque?',
        mensaje: 'Esta acción no se puede deshacer. El bloque será eliminado permanentemente.',
        textoConfirmar: 'Eliminar',
        textoCancelar: 'Cancelar',
        onConfirm: () => eliminarBloque(id)
    });
}

// Eliminar bloque
function eliminarBloque(id) {
    fetch(APP_URL + 'index.php?c=horarios&a=eliminar', {
        method: 'POST',
        headers: {'Content-Type':'application/x-www-form-urlencoded'},
        body: 'id='+id
    })
    .then(res => res.json())
    .then(data => {
        if(data.success) {
            mostrarToast('success', 'Eliminado', 'El bloque ha sido eliminado correctamente');
            setTimeout(() => location.reload(), 1000);
        } else {
            mostrarToast('danger', 'Error', data.message || 'No se pudo eliminar el bloque');
        }
    })
    .catch(err => {
        console.error(err);
        mostrarToast('danger', 'Error de Conexión', 'No se pudo conectar con el servidor');
    });
}

// Modal de conciliación
function mostrarModalConciliar() {
    mostrarModal({
        tipo: 'warning',
        titulo: '¿Conciliar Horarios?',
        mensaje: 'Esta acción sincronizará los horarios con Firebase y los marcará como conciliados.',
        textoConfirmar: 'Conciliar y Sincronizar',
        textoCancelar: 'Cancelar',
        onConfirm: () => conciliarHorarios()
    });
}

// Conciliar horarios
function conciliarHorarios() {
    const formData = new FormData();
    formData.append('periodo_id', PERIODO_ID);
    formData.append('carrera_id', CARRERA_ID);
    formData.append('semestre_id', SEMESTRE_ID);
    
    // Mostrar toast de "procesando"
    mostrarToast('info', 'Procesando...', 'Sincronizando horarios con Firebase...');
    
    fetch(APP_URL + 'index.php?c=horarios&a=conciliar', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if(data.success) {
            mostrarToast('success', '¡Sincronización Exitosa!', data.message || 'Los horarios han sido conciliados y sincronizados correctamente');
            setTimeout(() => location.reload(), 2000);
        } else {
            mostrarToast('danger', 'Error de Sincronización', data.message || 'No se pudieron conciliar los horarios');
        }
    })
    .catch(err => {
        console.error(err);
        mostrarToast('danger', 'Error de Conexión', 'No se pudo conectar con el servidor para sincronizar');
    });
}

// Estilo de animación fadeOut para cerrar modales
const style = document.createElement('style');
style.textContent = `
    @keyframes fadeOut {
        to {
            opacity: 0;
        }
    }
`;
document.head.appendChild(style);
</script>