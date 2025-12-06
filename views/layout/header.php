<?php

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo APP_NAME; ?> - <?php echo $page_title ?? 'Dashboard'; ?></title>
    
    <link rel="stylesheet" href="<?php echo APP_URL; ?>assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo APP_URL; ?>assets/css/fontawesome.min.css">
    <link rel="stylesheet" href="<?php echo APP_URL; ?>assets/css/style.css">
    
    <?php if (isset($additional_css)): ?>
        <?php foreach ($additional_css as $css): ?>
            <link rel="stylesheet" href="<?php echo APP_URL . $css; ?>">
        <?php endforeach; ?>
    <?php endif; ?>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        /* Estilos base del Layout */
        :root {
            --header-bg: #ffffff;
            --header-height: 64px;
            --primary-blue: #2979FF;
            --text-primary: #4b5563;
            --text-secondary: #6b7280;
            --border-color: #e5e7eb;
            --sidebar-width: 280px;
            --sidebar-collapsed: 72px;
            --shadow-header: 0 1px 2px rgba(0, 0, 0, 0.06);
        }
        
        body { margin: 0; padding: 0; font-family: 'Segoe UI', sans-serif; background-color: #f9fafb; }
        
        .top-header {
            display: flex; align-items: center; justify-content: space-between;
            height: var(--header-height); padding: 0 24px;
            background-color: var(--header-bg); border-bottom: 1px solid var(--border-color);
            box-shadow: var(--shadow-header);
            position: fixed; top: 0; left: var(--sidebar-width); right: 0; z-index: 99;
            transition: left 0.18s ease;
        }
        
        .top-header.sidebar-collapsed { left: var(--sidebar-collapsed); }
        
        .header-left { display: flex; align-items: center; gap: 12px; }
        .toggle-sidebar-btn { border: 1px solid var(--border-color); background: #f5f6f3; border-radius: 8px; width: 40px; height: 40px; cursor: pointer; color: var(--text-primary); display: flex; align-items: center; justify-content: center; }
        .toggle-sidebar-btn:hover { color: var(--primary-blue); border-color: var(--primary-blue); }
        .header-title { font-size: 18px; font-weight: 600; color: var(--text-primary); margin: 0; }
        
        .header-right { display: flex; align-items: center; gap: 12px; }
        
        .user-dropdown { position: relative; }
        .user-dropdown-toggle { display: flex; align-items: center; gap: 10px; padding: 6px 12px; background: #f5f6f3; border: 1px solid var(--border-color); border-radius: 8px; cursor: pointer; }
        .user-dropdown-avatar { width: 32px; height: 32px; border-radius: 50%; background: #4b5563; color: white; display: flex; align-items: center; justify-content: center; }
        .user-dropdown-info { display: flex; flex-direction: column; }
        .user-dropdown-name { font-size: 14px; font-weight: 600; color: var(--text-primary); }
        .user-dropdown-role { font-size: 11px; color: var(--text-secondary); }
        
        .user-dropdown-menu { position: absolute; top: 110%; right: 0; width: 200px; background: white; border: 1px solid var(--border-color); border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); display: none; z-index: 1000; overflow: hidden; }
        .user-dropdown-menu.show { display: block; }
        .dropdown-menu-item { display: flex; align-items: center; gap: 10px; padding: 12px 16px; color: var(--text-primary); text-decoration: none; transition: 0.2s; }
        .dropdown-menu-item:hover { background: #f5f6f3; }
        .dropdown-menu-item.danger { color: #dc2626; }
        
        .main-content-wrapper { margin-left: var(--sidebar-width); margin-top: var(--header-height); transition: margin-left 0.18s ease; min-height: calc(100vh - var(--header-height)); }
        .main-content-wrapper.sidebar-collapsed { margin-left: var(--sidebar-collapsed); }
        .content-inner { padding: 24px; }

        /* ========================================
           MODAL GLOBAL - DISEÑO BONITO
           ======================================== */
        .global-modal-overlay { 
            position: fixed; 
            top: 0; 
            left: 0; 
            width: 100%; 
            height: 100%; 
            background: rgba(0, 0, 0, 0.5); 
            display: none; 
            align-items: center; 
            justify-content: center; 
            z-index: 99999; 
            backdrop-filter: blur(4px); 
            animation: fadeIn 0.2s ease; 
        }
        .global-modal-overlay.active { display: flex; }
        .global-modal-container { 
            background: #fff; 
            border-radius: 12px; 
            width: 90%; 
            max-width: 500px; 
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3); 
            animation: slideDown 0.3s ease; 
            overflow: hidden; 
        }
        .global-modal-header { 
            padding: 20px 24px; 
            border-bottom: 1px solid #e2e8f0; 
            display: flex; 
            align-items: center; 
            gap: 12px; 
        }
        .global-modal-header.success { background: #f0fdf4; border-bottom-color: #bbf7d0; }
        .global-modal-header.error { background: #fef2f2; border-bottom-color: #fecaca; }
        .global-modal-header.warning { background: #fef3c7; border-bottom-color: #fde68a; }
        .global-modal-icon { 
            width: 48px; 
            height: 48px; 
            border-radius: 50%; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            font-size: 24px; 
            flex-shrink: 0; 
        }
        .global-modal-icon.success { background: #dcfce7; color: #16a34a; }
        .global-modal-icon.error { background: #fee2e2; color: #dc2626; }
        .global-modal-icon.warning { background: #fef3c7; color: #ea580c; }
        .global-modal-title { 
            font-size: 1.15rem; 
            font-weight: 700; 
            color: #0f172a; 
            margin: 0; 
        }
        .global-modal-body { 
            padding: 24px; 
            color: #475569; 
            font-size: 0.95rem; 
            line-height: 1.6; 
        }
        .global-modal-footer { 
            padding: 16px 24px; 
            border-top: 1px solid #e2e8f0; 
            display: flex; 
            justify-content: flex-end; 
            gap: 10px; 
            background: #f8fafc; 
        }
        .global-modal-btn { 
            padding: 10px 20px; 
            border-radius: 8px; 
            font-weight: 600; 
            cursor: pointer; 
            transition: 0.2s; 
            border: none; 
            font-size: 0.92rem; 
        }
        .global-modal-btn-primary { background: #2563eb; color: #fff; }
        .global-modal-btn-primary:hover { background: #1d4ed8; }
        .global-modal-btn-secondary { background: #fff; color: #475569; border: 1px solid #cbd5e1; }
        .global-modal-btn-secondary:hover { background: #f1f5f9; }
        .global-modal-btn-danger { background: #dc2626; color: #fff; }
        .global-modal-btn-danger:hover { background: #b91c1c; }
        
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        @keyframes slideDown { from { transform: translateY(-30px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }

        @media (max-width: 600px) { 
            .global-modal-container { width: 95%; }
        }
    </style>
</head>
<body>
    <header class="top-header" id="topHeader">
        <div class="header-left">
            <button class="toggle-sidebar-btn" id="toggleSidebar" title="Menú">
                <i class="fas fa-bars"></i>
            </button>
            <h1 class="header-title"><?php echo $page_title ?? 'Sistema Escolar'; ?></h1>
        </div>
        
        <div class="header-right">
            <div class="user-dropdown">
                <div class="user-dropdown-toggle" id="userDropdownToggle">
                    <div class="user-dropdown-avatar">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="user-dropdown-info">
                        <span class="user-dropdown-name"><?php echo $_SESSION['nombre_completo'] ?? 'Usuario'; ?></span>
                        <span class="user-dropdown-role"><?php echo ucfirst($_SESSION['rol'] ?? 'Invitado'); ?></span>
                    </div>
                    <i class="fas fa-chevron-down" style="font-size: 12px; color: #6b7280;"></i>
                </div>
                
                <div class="user-dropdown-menu" id="userDropdownMenu">
                    <a href="<?php echo APP_URL; ?>logout.php" class="dropdown-menu-item danger">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Cerrar Sesión</span>
                    </a>
                </div>
            </div>
        </div>
    </header>

    <?php require_once VIEWS_PATH . 'layout/sidebar.php'; ?>
    
    <!-- ========================================
         MODAL GLOBAL DE NOTIFICACIONES
         ======================================== -->
    <div class="global-modal-overlay" id="globalModalOverlay">
        <div class="global-modal-container">
            <div class="global-modal-header" id="globalModalHeader">
                <div class="global-modal-icon" id="globalModalIcon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <h3 class="global-modal-title" id="globalModalTitle">Notificación</h3>
            </div>
            <div class="global-modal-body" id="globalModalBody">
                Mensaje
            </div>
            <div class="global-modal-footer" id="globalModalFooter">
                <button type="button" class="global-modal-btn global-modal-btn-primary" onclick="cerrarModalGlobal()">
                    <i class="fas fa-check"></i> Aceptar
                </button>
            </div>
        </div>
    </div>
    
    <div class="main-content-wrapper" id="mainContent">
        <main class="content-inner">