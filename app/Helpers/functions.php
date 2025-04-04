<?php

/**
 * Funciones auxiliares globales
 */

use App\Config\Lang;

if (!function_exists('base_path')) {
    /**
     * Obtener la ruta base de la aplicación
     * 
     * @param string $path Ruta relativa
     * @return string
     */
    function base_path($path = '')
    {
        return BASE_PATH . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }
}

if (!function_exists('config')) {
    /**
     * Obtener un valor de configuración
     * 
     * @param string $key Clave de configuración
     * @param mixed $default Valor por defecto
     * @return mixed
     */
    function config($key, $default = null)
    {
        $config = $GLOBALS['config'] ?? [];
        
        $parts = explode('.', $key);
        $value = $config;
        
        foreach ($parts as $part) {
            if (!isset($value[$part])) {
                return $default;
            }
            $value = $value[$part];
        }
        
        return $value;
    }
}

if (!function_exists('__')) {
    /**
     * Traducir un texto
     * 
     * @param string $key Clave de traducción
     * @param array $params Parámetros de sustitución
     * @return string
     */
    function __($key, $params = [])
    {
        return Lang::getInstance()->get($key, $params);
    }
}

if (!function_exists('url')) {
    /**
     * Generar una URL completa
     * 
     * @param string $path Ruta relativa
     * @return string
     */
    function url($path = '')
    {
        $baseUrl = rtrim(config('app.url'), '/');
        $path = ltrim($path, '/');
        
        return $path ? $baseUrl . '/' . $path : $baseUrl;
    }
}

if (!function_exists('asset')) {
    /**
     * Generar una URL para un activo
     * 
     * @param string $path Ruta relativa al directorio public
     * @return string
     */
    function asset($path)
    {
        return url($path);
    }
}

if (!function_exists('redirect')) {
    /**
     * Redireccionar a una URL
     * 
     * @param string $url URL a redireccionar
     * @param int $status Código de estado HTTP
     */
    function redirect($url, $status = 302)
    {
        header('Location: ' . $url, true, $status);
        exit;
    }
}

/**
 * Mostrar un valor con escape HTML
 *
 * @param string $value Valor a mostrar
 * @return string
 */
function e($value)
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8', false);
}

/**
 * Obtener un valor de la sesión
 *
 * @param string $key Clave de la sesión
 * @param mixed $default Valor por defecto si no existe
 * @return mixed
 */
function session($key, $default = null)
{
    return $_SESSION[$key] ?? $default;
}

/**
 * Generar un token CSRF
 *
 * @return string
 */
function csrf_token()
{
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    
    return $_SESSION['csrf_token'];
}

/**
 * Generar un campo de formulario con token CSRF
 *
 * @return string
 */
function csrf_field()
{
    return '<input type="hidden" name="csrf_token" value="' . csrf_token() . '">';
}

/**
 * Verificar si una ruta coincide con la URL actual
 *
 * @param string $path Ruta a comparar
 * @return bool
 */
function is_current_route($path)
{
    $currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    return $currentPath === $path;
}

/**
 * Formatear una fecha
 *
 * @param string $date Fecha en formato Y-m-d H:i:s
 * @param string $format Formato de salida
 * @return string
 */
function format_date($date, $format = 'd/m/Y H:i')
{
    if (empty($date)) {
        return '';
    }
    
    $datetime = new DateTime($date);
    return $datetime->format($format);
}

/**
 * Limitar un texto a un número de caracteres
 *
 * @param string $text Texto a limitar
 * @param int $limit Número máximo de caracteres
 * @param string $end Texto a añadir al final si se ha truncado
 * @return string
 */
function str_limit($text, $limit = 100, $end = '...')
{
    if (mb_strlen($text) <= $limit) {
        return $text;
    }
    
    return mb_substr($text, 0, $limit) . $end;
}

/**
 * Obtener la clase CSS para una alerta
 *
 * @param string $level Nivel de alerta (info, warning, danger)
 * @return string
 */
function alert_class($level)
{
    $classes = [
        ALERT_LEVEL_INFO => 'info',
        ALERT_LEVEL_WARNING => 'warning',
        ALERT_LEVEL_DANGER => 'danger',
    ];
    
    return $classes[$level] ?? 'info';
} 