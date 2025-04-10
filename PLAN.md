# 📘 PLAN.md – Generador Legal con IA (PHP + JS)

## 🔷 FASE 1 – Formulario y análisis legal de la URL (20 tareas)

### Objetivo: permitir al usuario ingresar una URL, analizar automáticamente su contenido y detectar necesidades legales.

---

### 1. Crear la estructura base del proyecto
- **Archivos a crear**:  
  `/index.php`, `/analyze.php`, `/result.php`, `/assets/`, `/includes/`, `/templates/`, `/storage/generated/`
- **Objetivo**: organizar el proyecto para que cada módulo tenga su espacio.
- **Código IA a generar**: instrucciones para crear todos los directorios y archivos vacíos necesarios.

---

### 2. Crear el formulario de entrada de URL en `index.php`
- **Archivo**: `index.php`
- **Función esperada**: `displayURLForm()`
- **Objetivo**: formulario HTML básico con input `type="url"` y botón de envío a `analyze.php`.
- **Código IA**: formulario validado con HTML5 y mensajes de ayuda visuales.

---

### 3. Añadir validación JS del campo URL
- **Archivo**: `assets/script.js`
- **Función esperada**: `validateURLBeforeSubmit()`
- **Objetivo**: evitar que el usuario envíe una URL inválida o vacía.
- **Código IA**: validación con expresiones regulares y alerta de error.

---

### 4. Crear controlador de entrada en `analyze.php`
- **Archivo**: `analyze.php`
- **Función esperada**: `getPostedURL()` y validación básica
- **Objetivo**: capturar la URL enviada desde `index.php` y verificar su formato antes de continuar.
- **Código IA**: validación PHP con `filter_var`.

---

### 5. Implementar `get_html_content()` en `helpers.php`
- **Archivo**: `includes/helpers.php`
- **Función esperada**: `get_html_content($url)`
- **Objetivo**: recuperar el HTML de la web usando `file_get_contents()` o `cURL`.
- **Código IA**: función reutilizable con fallback si `allow_url_fopen` está deshabilitado.

---

### 6. Detectar uso de HTTPS en la URL
- **Archivo**: `helpers.php`
- **Función esperada**: `is_ssl($url)`
- **Objetivo**: comprobar si la web tiene cifrado SSL (`https://`).
- **Código IA**: `strpos($url, 'https://') === 0`

---

### 7. Buscar la existencia de un enlace a Aviso Legal
- **Archivo**: `helpers.php`
- **Función esperada**: `has_legal_notice($html)`
- **Objetivo**: comprobar si hay enlaces o contenido relacionado con “aviso legal”.
- **Código IA**: expresión regular buscando `aviso.*legal`.

---

### 8. Buscar Política de Privacidad
- **Archivo**: `helpers.php`
- **Función esperada**: `has_privacy_policy($html)`
- **Objetivo**: detectar contenido relacionado con privacidad.
- **Código IA**: `preg_match('/privacidad|privacy/i', $html)`

---

### 9. Buscar Política de Cookies
- **Archivo**: `helpers.php`
- **Función esperada**: `has_cookies_policy($html)`
- **Objetivo**: ver si la web informa del uso de cookies.
- **Código IA**: búsqueda de palabra “cookie”, “consent”, scripts de banners.

---

### 10. Detectar formularios
- **Archivo**: `helpers.php`
- **Función esperada**: `has_form($html)`
- **Objetivo**: comprobar si hay formularios (`<form>`) en la web.
- **Código IA**: `preg_match('/<form/i', $html)`

---

### 11. Detectar Google Analytics
- **Archivo**: `helpers.php`
- **Función esperada**: `uses_google_analytics($html)`
- **Objetivo**: buscar `gtag.js` o `googletagmanager.com`.
- **Código IA**: `strpos($html, 'googletagmanager.com') !== false`

---

### 12. Detectar Facebook Pixel
- **Archivo**: `helpers.php`
- **Función esperada**: `uses_facebook_pixel($html)`
- **Objetivo**: detectar `connect.facebook.net` o identificadores del pixel.
- **Código IA**: `strpos($html, 'connect.facebook.net') !== false`

---

### 13. Detectar CMS o plataformas ecommerce
- **Archivo**: `helpers.php`
- **Función esperada**: `detect_ecommerce_platform($html)`
- **Objetivo**: comprobar si usa Shopify, WooCommerce, Prestashop, etc.
- **Código IA**: detección por meta etiquetas o rutas típicas.

---

### 14. Detectar botones de pago
- **Archivo**: `helpers.php`
- **Función esperada**: `has_checkout_buttons($html)`
- **Objetivo**: ver si hay botones de tipo “checkout”, “comprar”, etc.
- **Código IA**: `preg_match('/checkout|comprar|añadir al carrito/i', $html)`

---

### 15. Consolidar todos los datos en un array `$analysis`
- **Archivo**: `analyze.php`
- **Función esperada**: `run_full_analysis($url)`
- **Objetivo**: crear un array estructurado con los resultados.
- **Código IA**: ejecuta todas las funciones anteriores y lo guarda en un solo array.

---

### 16. Crear `result.php` para mostrar el análisis
- **Archivo**: `result.php`
- **Función esperada**: `render_analysis($analysis)`
- **Objetivo**: visualización clara con ✅❌ + resumen textual.
- **Código IA**: HTML semántico con buena legibilidad.

---

### 17. Añadir botón de “Generar textos legales”
- **Archivo**: `result.php`
- **Función esperada**: N/A
- **Objetivo**: enviar el análisis a `generate.php`.
- **Código IA**: formulario oculto con `POST` de los resultados.

---

### 18. Añadir CSS limpio para resultados
- **Archivo**: `assets/style.css`
- **Función esperada**: N/A
- **Objetivo**: estilos para lista de resultados, mensajes, botones.
- **Código IA**: diseño claro, accesible, profesional.

---

### 19. Añadir sistema de logging simple
- **Archivo**: `helpers.php`
- **Función esperada**: `log_event($message, $type = "INFO")`
- **Objetivo**: guardar logs de análisis o errores en archivo `.log`.
- **Código IA**: crea o añade líneas con timestamp.

---

### 20. Añadir control de errores y excepciones
- **Archivo**: `analyze.php`, `helpers.php`
- **Función esperada**: try/catch y logs
- **Objetivo**: evitar errores visibles en HTML. Registrar excepciones.
- **Código IA**: try/catch + fallback + logging.
