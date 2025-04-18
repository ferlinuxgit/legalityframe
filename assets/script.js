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
 * Muestra un mensaje de error en el contenedor designado
 * @param {string} message - Mensaje de error a mostrar
 */
function showErrorMessage(message) {
    const errorContainer = document.getElementById('error-container');
    if (!errorContainer) return;
    
    // Crear el banner de error con estilos de Tailwind
    const errorBanner = document.createElement('div');
    errorBanner.className = 'bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6 relative';
    
    // Texto del error
    const errorText = document.createElement('span');
    errorText.className = 'block sm:inline';
    errorText.textContent = message;
    
    // Añadir botón para cerrar el error
    const closeButton = document.createElement('button');
    closeButton.innerHTML = '&times;';
    closeButton.className = 'absolute right-0 top-0 px-4 py-3';
    closeButton.setAttribute('type', 'button');
    closeButton.setAttribute('aria-label', 'Cerrar');
    closeButton.addEventListener('click', function() {
        errorBanner.remove();
    });
    
    // Añadir elementos al banner
    errorBanner.appendChild(errorText);
    errorBanner.appendChild(closeButton);
    
    // Limpiar mensajes previos
    errorContainer.innerHTML = '';
    
    // Añadir el nuevo mensaje
    errorContainer.appendChild(errorBanner);
    
    // Desplazarse al inicio de la página para ver el error
    window.scrollTo(0, 0);
}

/**
 * Obtiene parámetros de la URL para detectar errores
 */
function checkForErrors() {
    const urlParams = new URLSearchParams(window.location.search);
    const errorType = urlParams.get('error');
    
    if (!errorType) return;
    
    let errorMessage = '';
    
    switch (errorType) {
        case 'url_invalid':
            errorMessage = 'La URL introducida no es válida. Por favor, asegúrate de que comienza con http:// o https://';
            break;
        case 'analysis_failed':
            errorMessage = 'No se pudo analizar la URL proporcionada. Por favor, inténtalo de nuevo o prueba con otra URL.';
            break;
        case 'no_analysis':
            errorMessage = 'No hay datos de análisis disponibles. Por favor, introduce una URL para analizar.';
            break;
        case 'unknown_error':
            errorMessage = 'Ha ocurrido un error inesperado. Por favor, inténtalo de nuevo.';
            break;
    }
    
    if (errorMessage) {
        showErrorMessage(errorMessage);
    }
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
    
    // Comprobar si hay errores en la URL
    checkForErrors();
});
