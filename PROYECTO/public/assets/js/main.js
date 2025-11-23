// Sistema de Gestión de Reclamos - JavaScript
document.addEventListener('DOMContentLoaded', function() {
    
    // Confirmación de eliminación
    const deleteButtons = document.querySelectorAll('[data-confirm]');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            const message = this.getAttribute('data-confirm') || '¿Estás seguro de que quieres eliminar este elemento?';
            if (!confirm(message)) {
                e.preventDefault();
            }
        });
    });

    // Auto-hide para mensajes flash
    const flashMessages = document.querySelectorAll('.alert[data-auto-hide]');
    flashMessages.forEach(alert => {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.5s';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        }, 5000);
    });

    // Validación de formularios
    const forms = document.querySelectorAll('form[data-validate]');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const requiredFields = this.querySelectorAll('[required]');
            let isValid = true;
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    field.style.borderColor = '#dc3545';
                } else {
                    field.style.borderColor = '#ddd';
                }
            });
            
            if (!isValid) {
                e.preventDefault();
                alert('Por favor complete todos los campos requeridos');
            }
        });
    });

    // Búsqueda en tiempo real (debounced)
    const searchInputs = document.querySelectorAll('input[data-live-search]');
    searchInputs.forEach(input => {
        let timeout;
        input.addEventListener('input', function() {
            clearTimeout(timeout);
            timeout = setTimeout(() => {
                this.form.submit();
            }, 500);
        });
    });

    // Tooltips simples
    const tooltipElements = document.querySelectorAll('[data-tooltip]');
    tooltipElements.forEach(el => {
        el.style.position = 'relative';
        el.addEventListener('mouseenter', function() {
            const tooltip = document.createElement('div');
            tooltip.className = 'tooltip-content';
            tooltip.textContent = this.getAttribute('data-tooltip');
            tooltip.style.cssText = `
                position: absolute;
                background: #333;
                color: white;
                padding: 0.5rem;
                border-radius: 5px;
                font-size: 0.85rem;
                white-space: nowrap;
                z-index: 1000;
                bottom: 100%;
                left: 50%;
                transform: translateX(-50%);
                margin-bottom: 5px;
            `;
            this.appendChild(tooltip);
        });
        el.addEventListener('mouseleave', function() {
            const tooltip = this.querySelector('.tooltip-content');
            if (tooltip) tooltip.remove();
        });
    });

    // Formateo de montos de dinero
    const moneyInputs = document.querySelectorAll('input[type="number"][step="0.01"]');
    moneyInputs.forEach(input => {
        input.addEventListener('blur', function() {
            if (this.value) {
                this.value = parseFloat(this.value).toFixed(2);
            }
        });
    });

    console.log('Sistema de Reclamos - Inicializado');
});