<?php

?>
<style>

:root {
  --sidebar-bg: #ffffff;
  --primary-blue: #2979FF;
  --primary-blue-hover: rgba(30, 102, 240, 0.08);
  --gray-hover: #f5f6f3;
  --text-primary: #4b5563;
  --text-secondary: #6b7280;
  --text-on-primary: #FFFFFF;
  --icon-color: #6b7280;
  --border-color: #e5e7eb;
  --sidebar-width: 280px;
  --sidebar-collapsed: 72px;
  --item-height: 40px;
  --padding-horizontal: 12px;
  --item-margin: 12px;
  --section-spacing: 16px;
  --font-size-item: 14px;
  --font-weight-item: 600;
  --border-radius: 8px;
  --transition-speed: 0.18s;
  --shadow-sidebar: 0 1px 3px rgba(0, 0, 0, 0.08), 0 8px 24px -4px rgba(0, 0, 0, 0.07);
}

#sidebar {
  width: var(--sidebar-width);
  background-color: var(--sidebar-bg);
  box-shadow: var(--shadow-sidebar);
  display: flex;
  flex-direction: column;
  transition: width var(--transition-speed) ease;
  z-index: 100;
  height: 100vh;
  position: fixed;
  top: 0;
  left: 0;
  overflow: hidden;
}

#sidebar.collapsed { width: var(--sidebar-collapsed); }
#sidebar .position-sticky { display: flex; flex-direction: column; height: 100%; }

.sidebar-brand { display: flex; align-items: center; padding: 20px 24px; height: 64px; border-bottom: 1px solid var(--border-color); gap: 12px; }
.brand-content { display: flex; flex-direction: column; justify-content: center; gap: 2px; min-width: 0; transition: opacity var(--transition-speed) ease; }
#sidebar.collapsed .brand-content { opacity: 0; pointer-events: none; width: 0; }
.brand-text { font-size: 15px; font-weight: 700; color: var(--text-primary); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.brand-subtitle { font-size: 11px; color: var(--text-secondary); font-weight: 500; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

.sidebar-nav { flex: 1; overflow-y: auto; overflow-x: hidden; padding: 16px 0; }
#sidebar.collapsed .sidebar-nav { padding: 16px 4px; }
.nav-list { list-style: none; padding: 0; margin: 0; }
.nav-list > li { margin-bottom: 2px; }
.nav-section { margin: var(--section-spacing) 0; }
.section-divider { height: 1px; background-color: var(--border-color); margin: 0 var(--item-margin); }

.nav-item { display: flex !important; align-items: center; gap: 14px; height: var(--item-height); padding: 0 var(--padding-horizontal) !important; margin: 0 var(--item-margin); border-radius: var(--border-radius); font-size: var(--font-size-item); font-weight: var(--font-weight-item); color: var(--text-primary); text-decoration: none; cursor: pointer; transition: all var(--transition-speed) ease; }
.nav-item:hover { background-color: var(--gray-hover); color: var(--text-primary); }
.nav-item.active { background-color: var(--primary-blue); color: var(--text-on-primary); }
.nav-item.active .nav-icon { color: var(--text-on-primary); transform: scale(1.15); }
#sidebar.collapsed .nav-item { padding: 0 !important; justify-content: center; height: 44px; margin: 2px 8px; }

.nav-icon { flex-shrink: 0; width: 24px; height: 24px; color: var(--icon-color); display: flex; align-items: center; justify-content: center; font-size: 18px; }
.nav-text { flex: 1; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; opacity: 1; transition: opacity var(--transition-speed) ease; }
#sidebar.collapsed .nav-text { opacity: 0; width: 0; display: none; }

/* Tooltips básicos para colapsado */
#sidebar.collapsed [data-tooltip]:hover::after { content: attr(data-tooltip); position: fixed; left: calc(var(--sidebar-collapsed) + 12px); transform: translateY(-50%); background-color: #1f2937; color: white; padding: 6px 10px; border-radius: 6px; font-size: 12px; z-index: 1002; pointer-events: none; }
</style>

<nav id="sidebar">
    <div class="position-sticky">
        <div class="sidebar-brand">
            <div class="brand-content">
                <div class="brand-text">Sistema Horarios</div>
                <div class="brand-subtitle">Tecnológico de Chilpancingo</div>
            </div>
        </div>

        <div class="sidebar-nav">
            <ul class="nav-list">
                
                <li>
                    <a class="nav-link nav-item <?php echo (!isset($_GET['c']) || $_GET['c'] == 'dashboard') ? 'active' : ''; ?>" 
                       href="<?php echo APP_URL; ?>index.php?c=dashboard"
                       data-tooltip="Inicio">
                        <span class="nav-icon"><i class="fas fa-home"></i></span>
                        <span class="nav-text">Inicio</span>
                    </a>
                </li>
                
                <?php if (Auth::hasRole(ROLE_SUBDIRECTOR)): ?>
                <li>
                    <a class="nav-link nav-item <?php echo (isset($_GET['c']) && $_GET['c'] == 'usuarios') ? 'active' : ''; ?>" 
                       href="<?php echo APP_URL; ?>index.php?c=usuarios"
                       data-tooltip="Usuarios">
                        <span class="nav-icon"><i class="fas fa-users"></i></span>
                        <span class="nav-text">Usuarios</span>
                    </a>
                </li>
                
                <li>
                    <a class="nav-link nav-item <?php echo (isset($_GET['c']) && $_GET['c'] == 'docentes') ? 'active' : ''; ?>" 
                       href="<?php echo APP_URL; ?>index.php?c=docentes"
                       data-tooltip="Docentes">
                        <span class="nav-icon"><i class="fas fa-chalkboard-teacher"></i></span>
                        <span class="nav-text">Docentes</span>
                    </a>
                </li>
                <?php endif; ?>
                
                <?php if (Auth::hasAnyRole([ROLE_SUBDIRECTOR, ROLE_DEP, ROLE_JEFE_DEPTO])): ?>
                <li class="nav-section"><div class="section-divider"></div></li>
                <?php endif; ?>

                <?php if (Auth::hasAnyRole([ROLE_SUBDIRECTOR, ROLE_DEP])): ?>
                <li>
                    <a class="nav-link nav-item <?php echo (isset($_GET['c']) && $_GET['c'] == 'periodos') ? 'active' : ''; ?>" 
                       href="<?php echo APP_URL; ?>index.php?c=periodos"
                       data-tooltip="Períodos">
                        <span class="nav-icon"><i class="fas fa-calendar-alt"></i></span>
                        <span class="nav-text">Períodos Escolares</span>
                    </a>
                </li>
                
                <li>
                    <a class="nav-link nav-item <?php echo (isset($_GET['c']) && $_GET['c'] == 'grupos') ? 'active' : ''; ?>" 
                       href="<?php echo APP_URL; ?>index.php?c=grupos"
                       data-tooltip="Grupos">
                        <span class="nav-icon"><i class="fas fa-layer-group"></i></span>
                        <span class="nav-text">Grupos y Aulas</span>
                    </a>
                </li>
                <?php endif; ?>

                <?php if (Auth::hasAnyRole([ROLE_SUBDIRECTOR, ROLE_JEFE_DEPTO])): ?>
                <li>
                    <a class="nav-link nav-item <?php echo (isset($_GET['c']) && $_GET['c'] == 'materias') ? 'active' : ''; ?>" 
                       href="<?php echo APP_URL; ?>index.php?c=materias"
                       data-tooltip="Materias">
                        <span class="nav-icon"><i class="fas fa-book"></i></span>
                        <span class="nav-text">Materias</span>
                    </a>
                </li>
                
                <li>
                    <a class="nav-link nav-item <?php echo (isset($_GET['c']) && $_GET['c'] == 'aulas') ? 'active' : ''; ?>" 
                       href="<?php echo APP_URL; ?>index.php?c=aulas"
                       data-tooltip="Aulas">
                        <span class="nav-icon"><i class="fas fa-door-open"></i></span>
                        <span class="nav-text">Catálogo Aulas</span>
                    </a>
                </li>
                <?php endif; ?>

                <?php if (Auth::hasAnyRole([ROLE_SUBDIRECTOR, ROLE_JEFE_DEPTO])): ?>
                <li class="nav-section"><div class="section-divider"></div></li>
                <li>
                    <a class="nav-link nav-item <?php echo (isset($_GET['c']) && $_GET['c'] == 'horarios' && (!isset($_GET['a']) || $_GET['a'] == 'asignar')) ? 'active' : ''; ?>" 
                       href="<?php echo APP_URL; ?>index.php?c=horarios"
                       data-tooltip="Gestión de Horarios">
                        <span class="nav-icon"><i class="fas fa-clock"></i></span>
                        <span class="nav-text">Asignar Horarios</span>
                    </a>
                </li>
                <?php endif; ?>

                <?php if (Auth::hasAnyRole([ROLE_SUBDIRECTOR, ROLE_JEFE_DEPTO, ROLE_DEP])): ?>
                <li>
                    <a class="nav-link nav-item <?php echo (isset($_GET['c']) && $_GET['c'] == 'reportes') ? 'active' : ''; ?>" 
                       href="<?php echo APP_URL; ?>index.php?c=reportes"
                       data-tooltip="Reportes">
                        <span class="nav-icon"><i class="fas fa-file-pdf"></i></span>
                        <span class="nav-text">Reportes Generales</span>
                    </a>
                </li>
                <?php endif; ?>

                <?php if (Auth::hasRole(ROLE_DOCENTE)): ?>
                <li class="nav-section"><div class="section-divider"></div></li>
                <li>
                    <a class="nav-link nav-item <?php echo (isset($_GET['c']) && $_GET['c'] == 'reportes' && isset($_GET['a']) && $_GET['a'] == 'miHorario') ? 'active' : ''; ?>" 
                       href="<?php echo APP_URL; ?>index.php?c=reportes&a=miHorario"
                       data-tooltip="Mi Horario">
                        <span class="nav-icon"><i class="fas fa-calendar-day"></i></span>
                        <span class="nav-text">Mi Horario</span>
                    </a>
                </li>
                <?php endif; ?>

            </ul>
        </div>
    </div>
</nav>