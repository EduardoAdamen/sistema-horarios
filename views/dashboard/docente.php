<?php
// =====================================================
// views/dashboard/docente.php
// Dashboard específico para Docentes
// =====================================================
?>

<style>
    :root {
        --primary: #2563eb;
        --bg-card: #ffffff;
        --text-main: #0f172a;
        --muted: #64748b;
        --radius: 12px;
    }

    .docente-welcome {
        background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
        color: white;
        padding: 24px 30px;
        border-radius: var(--radius);
        margin-bottom: 24px;
        box-shadow: 0 4px 15px rgba(37, 99, 235, 0.2);
    }
    .docente-welcome h1 { 
        margin: 0; 
        font-size: 1.5rem; 
        font-weight: 700; 
    }
    .docente-welcome p { 
        margin: 8px 0 0; 
        opacity: 0.9; 
        font-size: 0.875rem; 
    }

    .quick-stats { 
        display: grid; 
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); 
        gap: 15px; 
        margin-bottom: 24px; 
    }
    .q-stat-card { 
        background: white; 
        padding: 20px; 
        border-radius: var(--radius); 
        border: 1px solid #e2e8f0; 
        text-align: center;
        min-height: 100px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }
    .q-value { 
        font-size: 1.5rem; 
        font-weight: 800; 
        color: var(--text-main); 
        margin-bottom: 8px;
    }
    .q-label { 
        font-size: 0.75rem; 
        color: var(--muted); 
        font-weight: 600; 
        text-transform: uppercase; 
        letter-spacing: 0.5px;
    }

    .today-schedule {
        background: var(--bg-card);
        border: 1px solid #e2e8f0;
        border-radius: var(--radius);
        padding: 24px;
        box-shadow: 0 1px 6px rgba(0,0,0,0.04);
    }
    .section-title { 
        font-size: 1rem; 
        font-weight: 700; 
        color: var(--text-main); 
        margin-bottom: 20px; 
        display: flex; 
        align-items: center; 
        gap: 10px; 
    }
    
    .timeline { 
        position: relative; 
        padding-left: 20px; 
    }
    .timeline-item { 
        position: relative; 
        padding-bottom: 20px; 
        border-left: 2px solid #e2e8f0; 
        padding-left: 20px; 
    }
    .timeline-item:last-child { 
        border-left: 2px solid transparent; 
        padding-bottom: 0;
    }
    .timeline-item::before {
        content: ''; 
        position: absolute; 
        left: -6px; 
        top: 0; 
        width: 10px; 
        height: 10px;
        border-radius: 50%; 
        background: var(--primary); 
        border: 2px solid white;
    }
    
    .class-time { 
        font-size: 0.8rem; 
        font-weight: 700; 
        color: var(--primary); 
        margin-bottom: 6px;
    }
    .class-card { 
        background: #f8fafc; 
        border: 1px solid #f1f5f9; 
        padding: 14px; 
        border-radius: 8px; 
    }
    .class-subject { 
        font-weight: 700; 
        color: var(--text-main); 
        font-size: 0.9rem; 
        margin-bottom: 8px;
    }
    .class-meta { 
        display: flex; 
        gap: 15px; 
        font-size: 0.8rem; 
        color: var(--muted); 
        flex-wrap: wrap;
    }
    .class-meta i { 
        color: #94a3b8; 
        margin-right: 4px; 
    }

    .empty-state {
        text-align: center; 
        padding: 40px 20px; 
        color: #64748b; 
        background: #f8fafc; 
        border-radius: 8px;
    }
    .empty-state i {
        font-size: 2.5rem;
        margin-bottom: 12px;
        display: block;
        opacity: 0.6;
    }
    .empty-state p {
        margin: 0;
        font-size: 0.9rem;
    }
</style>

<div class="page-container">

    <div class="docente-welcome">
        <h1>Hola, <?php echo htmlspecialchars($docente['nombre']); ?></h1>
        <p>
            <i class="fas fa-id-badge"></i> No. <?php echo $docente['numero_empleado']; ?> | 
            <i class="fas fa-university"></i> <?php echo ucfirst(str_replace('_', ' ', $docente['tipo'])); ?>
        </p>
    </div>

    <div class="quick-stats">
        <div class="q-stat-card">
            <div class="q-value"><?php echo $periodo['nombre'] ?? 'Sin Periodo'; ?></div>
            <div class="q-label">Periodo Actual</div>
        </div>
        <div class="q-stat-card">
            <div class="q-value"><?php echo $total_grupos; ?></div>
            <div class="q-label">Grupos Asignados</div>
        </div>
        <a href="index.php?c=reportes&a=miHorario" style="text-decoration:none;">
            <div class="q-stat-card" style="background: #eff6ff; border-color: #bfdbfe; cursor: pointer; transition: all 0.2s;">
                <div class="q-value" style="color: #2563eb;"><i class="fas fa-calendar-alt"></i></div>
                <div class="q-label" style="color: #1e40af;">Ver Mi Horario</div>
            </div>
        </a>
    </div>

    <div class="today-schedule">
        <div class="section-title">
            <i class="fas fa-clock"></i> Tu Agenda para <?php echo $dia_actual_str; ?>
        </div>

        <?php if (empty($clases_hoy)): ?>
            <div class="empty-state">
                <i class="fas fa-mug-hot"></i>
                <p>No tienes clases programadas para hoy</p>
            </div>
        <?php else: ?>
            <div class="timeline">
                <?php foreach ($clases_hoy as $clase): ?>
                <div class="timeline-item">
                    <div class="class-time">
                        <?php echo substr($clase['hora_inicio'], 0, 5); ?> - <?php echo substr($clase['hora_fin'], 0, 5); ?>
                    </div>
                    <div class="class-card">
                        <div class="class-subject">
                            <?php echo htmlspecialchars($clase['materia']); ?>
                        </div>
                        <div class="class-meta">
                            <span><i class="fas fa-users"></i> Gpo: <?php echo $clase['grupo']; ?></span>
                            <span>
                                <i class="fas fa-map-marker-alt"></i> 
                                <?php echo $clase['aula'] ? $clase['aula'] . ($clase['edificio'] ? " ({$clase['edificio']})" : '') : 'Sin Aula'; ?>
                            </span>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

</div>