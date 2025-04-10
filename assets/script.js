/**
 * Validación de URL antes del envío del formulario
 * @returns {boolean} True si la URL es válida, False si no
 */
function validateURLBeforeSubmit() {
    // Obtener el valor del campo de URL
    const urlInput = document.getElementById('website-url');
    const url = urlInput.value.trim();
    const errorElement = document.getElementById('url-error');
    
    // Limpiar mensaje de error anterior
    errorElement.textContent = '';
    
    // Expresión regular para validar URL
    // Debe comenzar con http:// o https:// y tener al menos un carácter después
    const urlRegex = /^https?:\/\/.+$/;
    
    if (!url) {
        // URL vacía
        errorElement.textContent = 'Por favor, introduce la URL de tu sitio web.';
        urlInput.focus();
        return false;
    } else if (!urlRegex.test(url)) {
        // URL con formato incorrecto
        errorElement.textContent = 'La URL debe comenzar con http:// o https://';
        urlInput.focus();
        return false;
    }
    
    // Si la validación es correcta, mostrar indicador de carga
    document.querySelector('button[type="submit"]').textContent = 'Analizando...';
    return true;
}

/**
 * Event listeners cuando la página está completamente cargada
 */
document.addEventListener('DOMContentLoaded', function() {
    // Resetear mensaje de error al modificar el campo
    const urlInput = document.getElementById('website-url');
    if (urlInput) {
        urlInput.addEventListener('input', function() {
            document.getElementById('url-error').textContent = '';
        });
    }
});
