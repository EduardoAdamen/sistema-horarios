
document.addEventListener('DOMContentLoaded', function() {
    
    
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
   
    const alerts = document.querySelectorAll('.alert:not(.alert-permanent)');
    alerts.forEach(alert => {
        setTimeout(() => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 5000);
    });
    
    // Validación de formularios
    const forms = document.querySelectorAll('.needs-validation');
    Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });
    
    
    const sidebarToggle = document.getElementById('sidebarToggle');
    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('show');
        });
    }
});

// Funciones globales
function mostrarNotificacion(tipo, mensaje) {
    const toastHTML = `
        <div class="toast align-items-center text-white bg-${tipo} border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    ${mensaje}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    `;
    
    let toastContainer = document.querySelector('.toast-container');
    if (!toastContainer) {
        toastContainer = document.createElement('div');
        toastContainer.className = 'toast-container position-fixed top-0 end-0 p-3';
        document.body.appendChild(toastContainer);
    }
    
    toastContainer.insertAdjacentHTML('beforeend', toastHTML);
    
    const toastElement = toastContainer.lastElementChild;
    const toast = new bootstrap.Toast(toastElement, { delay: 3000 });
    toast.show();
    
    toastElement.addEventListener('hidden.bs.toast', function() {
        toastElement.remove();
    });
}

function mostrarLoading(mensaje = 'Cargando...') {
    const loadingHTML = `
        <div id="loading-overlay" class="position-fixed top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center" 
             style="background-color: rgba(0,0,0,0.5); z-index: 9999;">
            <div class="text-center text-white">
                <div class="spinner-border mb-3" role="status">
                    <span class="visually-hidden">Cargando...</span>
                </div>
                <div>${mensaje}</div>
            </div>
        </div>
    `;
    
    document.body.insertAdjacentHTML('beforeend', loadingHTML);
}


function ocultarLoading() {
    const overlay = document.getElementById('loading-overlay');
    if (overlay) {
        overlay.remove();
    }
}


function formatearFecha(fecha) {
    const d = new Date(fecha);
    const dia = String(d.getDate()).padStart(2, '0');
    const mes = String(d.getMonth() + 1).padStart(2, '0');
    const año = d.getFullYear();
    return `${dia}/${mes}/${año}`;
}


function validarEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
}


function capitalizar(str) {
    return str.charAt(0).toUpperCase() + str.slice(1);
}


function exportarTablaExcel(tableId, filename = 'export.xls') {
    const table = document.getElementById(tableId);
    const html = table.outerHTML;
    const url = 'data:application/vnd.ms-excel,' + encodeURIComponent(html);
    const downloadLink = document.createElement("a");
    document.body.appendChild(downloadLink);
    downloadLink.href = url;
    downloadLink.download = filename;
    downloadLink.click();
    document.body.removeChild(downloadLink);
}


function imprimirElemento(elementId) {
    const printContents = document.getElementById(elementId).innerHTML;
    const originalContents = document.body.innerHTML;
    document.body.innerHTML = printContents;
    window.print();
    document.body.innerHTML = originalContents;
    location.reload();
}


function confirmarAccion(titulo, mensaje, callback) {
    const modalHTML = `
        <div class="modal fade" id="modalConfirmar" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">${titulo}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        ${mensaje}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-primary" id="btnConfirmar">Confirmar</button>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    document.body.insertAdjacentHTML('beforeend', modalHTML);
    
    const modal = new bootstrap.Modal(document.getElementById('modalConfirmar'));
    modal.show();
    
    document.getElementById('btnConfirmar').addEventListener('click', function() {
        modal.hide();
        if (typeof callback === 'function') {
            callback();
        }
    });
    
    document.getElementById('modalConfirmar').addEventListener('hidden.bs.modal', function() {
        this.remove();
    });
}


async function ajaxPost(url, data) {
    try {
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams(data)
        });
        
        if (!response.ok) {
            throw new Error('Error en la petición');
        }
        
        return await response.json();
    } catch (error) {
        console.error('Error AJAX:', error);
        throw error;
    }
}

/**
 * Realizar petición AJAX GET
 */
async function ajaxGet(url) {
    try {
        const response = await fetch(url);
        
        if (!response.ok) {
            throw new Error('Error en la petición');
        }
        
        return await response.json();
    } catch (error) {
        console.error('Error AJAX:', error);
        throw error;
    }
}