<?php

?>
        </main>
    </div>

    <script src="<?php echo APP_URL; ?>assets/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo APP_URL; ?>assets/js/jquery-3.6.0.min.js"></script>
    <script src="<?php echo APP_URL; ?>assets/js/main.js"></script>
    
    <?php if (isset($additional_js)): ?>
        <?php foreach ($additional_js as $js): ?>
            <script src="<?php echo APP_URL . $js; ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>
    
    <script>
        // ========================================
        // FUNCIONES DEL MODAL GLOBAL
        // ========================================
        function mostrarModalGlobal(tipo, titulo, mensaje, esConfirmacion = false, callbackConfirmar = null) {
            const modal = document.getElementById('globalModalOverlay');
            const header = document.getElementById('globalModalHeader');
            const icon = document.getElementById('globalModalIcon');
            const titleEl = document.getElementById('globalModalTitle');
            const body = document.getElementById('globalModalBody');
            const footer = document.getElementById('globalModalFooter');
            
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
            
            // Configurar botones según si es confirmación o notificación
            if (esConfirmacion) {
                footer.innerHTML = `
                    <button type="button" class="global-modal-btn global-modal-btn-secondary" onclick="cerrarModalGlobal()">
                        <i class="fas fa-times"></i> Cancelar
                    </button>
                    <button type="button" class="global-modal-btn global-modal-btn-danger" id="globalModalConfirm">
                        <i class="fas fa-check"></i> Confirmar
                    </button>
                `;
                
                // Asignar callback al botón confirmar
                document.getElementById('globalModalConfirm').onclick = function() {
                    if (callbackConfirmar) callbackConfirmar();
                    cerrarModalGlobal();
                };
            } else {
                footer.innerHTML = `
                    <button type="button" class="global-modal-btn global-modal-btn-primary" onclick="cerrarModalGlobal()">
                        <i class="fas fa-check"></i> Aceptar
                    </button>
                `;
            }
            
            modal.classList.add('active');
        }
        
        function cerrarModalGlobal() {
            document.getElementById('globalModalOverlay').classList.remove('active');
        }
        
        // Cerrar modal al hacer clic fuera
        document.getElementById('globalModalOverlay').addEventListener('click', function(e) {
            if (e.target === this) {
                cerrarModalGlobal();
            }
        });

        // ========================================
        // FUNCIONALIDAD DE SIDEBAR Y DROPDOWN
        // ========================================
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const toggleBtn = document.getElementById('toggleSidebar');
            const topHeader = document.getElementById('topHeader');
            const mainContent = document.getElementById('mainContent');
            const userDropdownToggle = document.getElementById('userDropdownToggle');
            const userDropdownMenu = document.getElementById('userDropdownMenu');
            
            // Estado inicial desde localStorage
            const sidebarCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
            if (sidebarCollapsed) {
                sidebar.classList.add('collapsed');
                topHeader.classList.add('sidebar-collapsed');
                mainContent.classList.add('sidebar-collapsed');
            }
            
            if (toggleBtn) {
                toggleBtn.addEventListener('click', function() {
                    sidebar.classList.toggle('collapsed');
                    topHeader.classList.toggle('sidebar-collapsed');
                    mainContent.classList.toggle('sidebar-collapsed');
                    
                    const isCollapsed = sidebar.classList.contains('collapsed');
                    localStorage.setItem('sidebarCollapsed', isCollapsed);
                });
            }
            
            if (userDropdownToggle) {
                userDropdownToggle.addEventListener('click', function(e) {
                    e.stopPropagation();
                    userDropdownMenu.classList.toggle('show');
                });
                
                document.addEventListener('click', function(e) {
                    if (!userDropdownMenu.contains(e.target) && e.target !== userDropdownToggle) {
                        userDropdownMenu.classList.remove('show');
                    }
                });
            }
            
            <?php if (isset($_SESSION['success'])): ?>
                mostrarModalGlobal('success', 'Operación Exitosa', '<?php echo addslashes($_SESSION['success']); ?>');
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['error'])): ?>
                mostrarModalGlobal('error', 'Error', '<?php echo addslashes($_SESSION['error']); ?>');
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>
        });
    </script>
</body>
</html>