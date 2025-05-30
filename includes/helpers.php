<?php
/**
 * helpers.php - Funciones auxiliares para el análisis de sitios web
 */

/**
 * Obtiene el contenido HTML de una URL
 * 
 * @param string $url La URL del sitio web
 * @return string|false Contenido HTML o false en caso de error
 */
function get_html_content($url) {
    // Intentar con file_get_contents si allow_url_fopen está habilitado
    if (ini_get('allow_url_fopen')) {
        try {
            $context = stream_context_create([
                'http' => [
                    'timeout' => 30,
                    'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
                ]
            ]);
            
            $html = file_get_contents($url, false, $context);
            
            if ($html !== false) {
                return $html;
            }
        } catch (Exception $e) {
            log_event("Error con file_get_contents: " . $e->getMessage(), "ERROR");
        }
    }
    
    // Si file_get_contents falla o no está disponible, intentar con cURL
    if (function_exists('curl_init')) {
        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36');
            
            $html = curl_exec($ch);
            $error = curl_error($ch);
            curl_close($ch);
            
            if ($html !== false && empty($error)) {
                return $html;
            } else {
                log_event("Error con cURL: " . $error, "ERROR");
            }
        } catch (Exception $e) {
            log_event("Error con cURL: " . $e->getMessage(), "ERROR");
        }
    }
    
    // Si ambos métodos fallan
    return false;
}

/**
 * Verifica si la URL utiliza HTTPS
 * 
 * @param string $url La URL a verificar
 * @return bool True si usa HTTPS, False en caso contrario
 */
function is_ssl($url) {
    return strpos($url, 'https://') === 0;
}

/**
 * Verifica si la web tiene aviso legal
 * 
 * @param string $html Contenido HTML de la web
 * @return bool True si tiene aviso legal, False en caso contrario
 */
function has_legal_notice($html) {
    return preg_match('/aviso\s*legal|términos\s*legales|legal|términos/i', $html) > 0;
}

/**
 * Verifica si la web tiene política de privacidad
 * 
 * @param string $html Contenido HTML de la web
 * @return bool True si tiene política de privacidad, False en caso contrario
 */
function has_privacy_policy($html) {
    return preg_match('/privacidad|privacy|política\s*de\s*privacidad/i', $html) > 0;
}

/**
 * Verifica si la web tiene política de cookies
 * 
 * @param string $html Contenido HTML de la web
 * @return bool True si tiene política de cookies, False en caso contrario
 */
function has_cookies_policy($html) {
    return preg_match('/cookie|política\s*de\s*cookies|cookies|consent/i', $html) > 0;
}

/**
 * Verifica si la web tiene formularios
 * 
 * @param string $html Contenido HTML de la web
 * @return bool True si tiene formularios, False en caso contrario
 */
function has_form($html) {
    return preg_match('/<form/i', $html) > 0;
}

/**
 * Verifica si la web usa Google Analytics
 * 
 * @param string $html Contenido HTML de la web
 * @return bool True si usa Google Analytics, False en caso contrario
 */
function uses_google_analytics($html) {
    return strpos($html, 'googletagmanager.com') !== false || 
           strpos($html, 'google-analytics.com') !== false || 
           strpos($html, 'gtag.js') !== false || 
           strpos($html, 'ga.js') !== false;
}

/**
 * Verifica si la web usa Facebook Pixel
 * 
 * @param string $html Contenido HTML de la web
 * @return bool True si usa Facebook Pixel, False en caso contrario
 */
function uses_facebook_pixel($html) {
    return strpos($html, 'connect.facebook.net') !== false || 
           strpos($html, 'facebook-pixel') !== false || 
           strpos($html, 'fbq(') !== false;
}

/**
 * Detecta la plataforma de e-commerce usada
 * 
 * @param string $html Contenido HTML de la web
 * @return string|null Nombre de la plataforma o null si no se detecta ninguna
 */
function detect_ecommerce_platform($html) {
    $platforms = [
        'WooCommerce' => ['/woocommerce|wc-api/i', '/wp-content\/plugins\/woocommerce/i'],
        'Shopify' => ['/shopify|cdn.shopify.com/i', '/shopify\.com/i'],
        'PrestaShop' => ['/prestashop|presta-/i', '/var prestashop/i'],
        'Magento' => ['/magento|mage\/|Mage_|Magento\./i'],
        'OpenCart' => ['/opencart/i', '/catalog\/view/i'],
        'WordPress' => ['/wp-content|wp-includes|wordpress/i']
    ];
    
    foreach ($platforms as $name => $patterns) {
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $html) > 0) {
                return $name;
            }
        }
    }
    
    return null;
}

/**
 * Verifica si la web tiene botones de pago o checkout
 * 
 * @param string $html Contenido HTML de la web
 * @return bool True si tiene botones de pago, False en caso contrario
 */
function has_checkout_buttons($html) {
    return preg_match('/checkout|comprar|añadir\s*al\s*carrito|agregar\s*al\s*carrito|add\s*to\s*cart|buy\s*now|comprar\s*ahora|pagar/i', $html) > 0;
}

/**
 * Realiza el análisis completo de una URL
 * 
 * @param string $url La URL a analizar
 * @return array Resultados del análisis
 * @throws Exception Si no se puede obtener el contenido HTML
 */
function run_full_analysis($url) {
    // Obtener el contenido HTML de la URL
    $html = get_html_content($url);
    
    if ($html === false) {
        throw new Exception("No se pudo obtener el contenido HTML de la URL: $url");
    }
    
    // Realizar todos los análisis
    $analysis = [
        'url' => $url,
        'time' => date('Y-m-d H:i:s'),
        'results' => [
            'ssl' => is_ssl($url),
            'legal_notice' => has_legal_notice($html),
            'privacy_policy' => has_privacy_policy($html),
            'cookies_policy' => has_cookies_policy($html),
            'has_forms' => has_form($html),
            'uses_google_analytics' => uses_google_analytics($html),
            'uses_facebook_pixel' => uses_facebook_pixel($html),
            'ecommerce_platform' => detect_ecommerce_platform($html),
            'has_checkout' => has_checkout_buttons($html)
        ]
    ];
    
    // Registrar el análisis
    log_event("Análisis completado para: $url", "INFO");
    
    return $analysis;
}

/**
 * Registra eventos en un archivo de log
 * 
 * @param string $message Mensaje a registrar
 * @param string $type Tipo de mensaje (INFO, ERROR, WARNING)
 * @return bool True si se registró correctamente, False en caso contrario
 */
function log_event($message, $type = "INFO") {
    try {
        $log_file = 'storage/logs.txt';
        
        // Crear directorio si no existe
        if (!is_dir(dirname($log_file))) {
            // Comprobar permisos antes de intentar crear el directorio
            if (is_writable(dirname(dirname($log_file)))) {
                mkdir(dirname($log_file), 0777, true);
            } else {
                // Si no podemos crear el directorio, usar un directorio temporal
                $log_file = sys_get_temp_dir() . '/legal_gen_log.txt';
            }
        }
        
        // Comprobar si el archivo es escribible
        if ((file_exists($log_file) && !is_writable($log_file)) || 
            (!file_exists($log_file) && !is_writable(dirname($log_file)))) {
            // Si no es escribible, usar un directorio temporal
            $log_file = sys_get_temp_dir() . '/legal_gen_log.txt';
        }
        
        // Formatear el mensaje
        $log_message = date('Y-m-d H:i:s') . " [$type] " . $message . PHP_EOL;
        
        // Escribir en el archivo
        return @file_put_contents($log_file, $log_message, FILE_APPEND);
    } catch (Exception $e) {
        // En caso de error, simplemente devolver false
        // No podemos registrar este error ya que la función de registro es la que falla
        return false;
    }
}
