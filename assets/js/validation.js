// assets/js/validation.js

/**
 * Validaciones para formularios
 */

document.addEventListener('DOMContentLoaded', function() {
    // Validar formulario de login
    const loginForm = document.querySelector('form[action*="login"]');
    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            let isValid = true;
            const correo = this.querySelector('#correo');
            const password = this.querySelector('#password');
            
            // Limpiar mensajes de error previos
            clearErrors();
            
            // Validar correo
            if (!correo.value.trim()) {
                showError(correo, 'El correo electrónico es obligatorio');
                isValid = false;
            } else if (!isValidEmail(correo.value)) {
                showError(correo, 'Ingrese un correo electrónico válido');
                isValid = false;
            }
            
            // Validar contraseña
            if (!password.value.trim()) {
                showError(password, 'La contraseña es obligatoria');
                isValid = false;
            }
            
            if (!isValid) {
                e.preventDefault();
            }
        });
    }
    
    // Validar formulario de requerimientos
    const requerimientoForm = document.querySelector('form[action*="requerimientos"]');
    if (requerimientoForm) {
        requerimientoForm.addEventListener('submit', function(e) {
            let isValid = true;
            const titulo = this.querySelector('#titulo');
            const descripcion = this.querySelector('#descripcion');
            
            // Limpiar mensajes de error previos
            clearErrors();
            
            // Validar título
            if (!titulo.value.trim()) {
                showError(titulo, 'El título es obligatorio');
                isValid = false;
            } else if (titulo.value.trim().length < 5) {
                showError(titulo, 'El título debe tener al menos 5 caracteres');
                isValid = false;
            }
            
            // Validar descripción
            if (!descripcion.value.trim()) {
                showError(descripcion, 'La descripción es obligatoria');
                isValid = false;
            } else if (descripcion.value.trim().length < 20) {
                showError(descripcion, 'La descripción debe tener al menos 20 caracteres');
                isValid = false;
            }
            
            if (!isValid) {
                e.preventDefault();
            }
        });
    }
    
    // Validar formulario de avances
    const avanceForm = document.querySelector('form[action*="avances"]');
    if (avanceForm) {
        avanceForm.addEventListener('submit', function(e) {
            let isValid = true;
            const descripcion = this.querySelector('#descripcion');
            
            // Limpiar mensajes de error previos
            clearErrors();
            
            // Validar descripción
            if (!descripcion.value.trim()) {
                showError(descripcion, 'La descripción es obligatoria');
                isValid = false;
            } else if (descripcion.value.trim().length < 10) {
                showError(descripcion, 'La descripción debe tener al menos 10 caracteres');
                isValid = false;
            }
            
            if (!isValid) {
                e.preventDefault();
            }
        });
    }
});

/**
 * Funciones auxiliares para validación
 */

function showError(element, message) {
    // Crear elemento de error
    const errorDiv = document.createElement('div');
    errorDiv.className = 'invalid-feedback d-block';
    errorDiv.textContent = message;
    
    // Añadir clase de error al elemento
    element.classList.add('is-invalid');
    
    // Añadir mensaje de error después del elemento
    element.parentNode.insertBefore(errorDiv, element.nextSibling);
}

function clearErrors() {
    // Eliminar todos los mensajes de error
    document.querySelectorAll('.invalid-feedback').forEach(function(errorEl) {
        errorEl.remove();
    });
    
    // Eliminar clases de error
    document.querySelectorAll('.is-invalid').forEach(function(element) {
        element.classList.remove('is-invalid');
    });
}
