// assets/js/main.js

/**
 * Script principal para la aplicación Litoclean Perú
 */

document.addEventListener('DOMContentLoaded', function() {
    // Inicializar tooltips de Bootstrap
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Configuración para mensajes de alerta con auto cierre
    setTimeout(function() {
        var alerts = document.querySelectorAll('.alert-dismissible');
        alerts.forEach(function(alert) {
            var bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);
    
    // Confirmar acciones peligrosas que no tienen modal específico
    document.querySelectorAll('.confirm-action').forEach(function(element) {
        element.addEventListener('click', function(e) {
            if (!confirm(this.dataset.confirmMessage || '¿Estás seguro de realizar esta acción?')) {
                e.preventDefault();
            }
        });
    });
    
    // Resaltar filas de la tabla al hacer hover
    document.querySelectorAll('.table-hover tr').forEach(function(row) {
        row.addEventListener('mouseenter', function() {
            this.classList.add('highlighted-row');
        });
        row.addEventListener('mouseleave', function() {
            this.classList.remove('highlighted-row');
        });
    });
    
    // Contador de caracteres para las áreas de texto
    document.querySelectorAll('textarea[data-max-length]').forEach(function(textarea) {
        const maxLength = parseInt(textarea.dataset.maxLength);
        const counterElement = document.querySelector(textarea.dataset.counterSelector);
        
        if (counterElement) {
            const updateCounter = function() {
                const remaining = maxLength - textarea.value.length;
                counterElement.textContent = `${remaining} caracteres restantes`;
                
                if (remaining < 0) {
                    counterElement.classList.add('text-danger');
                } else {
                    counterElement.classList.remove('text-danger');
                }
            };
            
            textarea.addEventListener('input', updateCounter);
            // Inicializar contador
            updateCounter();
        }
    });
});


function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

