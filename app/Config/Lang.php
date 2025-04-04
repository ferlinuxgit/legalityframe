<?php

namespace App\Config;

/**
 * Clase para gestionar la internacionalización de la aplicación
 * 
 * Permite cargar y utilizar traducciones en diferentes idiomas
 */
class Lang
{
    /**
     * Instancia singleton de la clase
     * 
     * @var Lang
     */
    private static $instance;
    
    /**
     * Idioma actual
     * 
     * @var string
     */
    private $currentLocale;
    
    /**
     * Mensajes cargados
     * 
     * @var array
     */
    private $messages = [];
    
    /**
     * Archivos de idioma cargados
     * 
     * @var array
     */
    private $loaded = [];
    
    /**
     * Constructor privado para implementar patrón singleton
     */
    private function __construct()
    {
        $this->currentLocale = $this->getLocale();
    }
    
    /**
     * Obtiene la instancia única de Lang
     * 
     * @return Lang
     */
    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        
        return self::$instance;
    }
    
    /**
     * Establece el idioma actual
     * 
     * @param string $locale
     * @return void
     */
    public function setLocale($locale)
    {
        if (array_key_exists($locale, AVAILABLE_LANGUAGES)) {
            $this->currentLocale = $locale;
            $_SESSION['lang'] = $locale;
        }
    }
    
    /**
     * Obtiene el idioma actual basado en la sesión o el valor predeterminado
     * 
     * @return string
     */
    public function getLocale()
    {
        if (isset($_SESSION['lang']) && array_key_exists($_SESSION['lang'], AVAILABLE_LANGUAGES)) {
            return $_SESSION['lang'];
        }
        
        // Detectar por Accept-Language si estamos en una solicitud HTTP
        if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
            $browserLangs = explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']);
            foreach ($browserLangs as $lang) {
                $lang = substr($lang, 0, 2); // Obtener solo el código de idioma (es, en, etc.)
                if (array_key_exists($lang, AVAILABLE_LANGUAGES)) {
                    $_SESSION['lang'] = $lang;
                    return $lang;
                }
            }
        }
        
        // Usar el valor predeterminado de la configuración
        $defaultLocale = $GLOBALS['config']['app']['locale'] ?? 'es';
        $_SESSION['lang'] = $defaultLocale;
        
        return $defaultLocale;
    }
    
    /**
     * Carga un archivo de idioma
     * 
     * @param string $group Grupo de traducciones (general, auth, etc.)
     * @return void
     */
    public function load($group)
    {
        $locale = $this->currentLocale;
        
        // Evitar cargar el mismo archivo varias veces
        $key = "{$locale}.{$group}";
        if (isset($this->loaded[$key])) {
            return;
        }
        
        $path = BASE_PATH . "/resources/lang/{$locale}/{$group}.php";
        
        if (file_exists($path)) {
            $this->messages[$locale][$group] = require $path;
            $this->loaded[$key] = true;
        } else {
            // Si no existe el archivo, intentar cargar el idioma predeterminado
            $defaultLocale = $GLOBALS['config']['app']['locale'] ?? 'es';
            $defaultPath = BASE_PATH . "/resources/lang/{$defaultLocale}/{$group}.php";
            
            if (file_exists($defaultPath)) {
                $this->messages[$locale][$group] = require $defaultPath;
                $this->loaded[$key] = true;
            } else {
                // Si no existe ni siquiera en el idioma predeterminado, usar un array vacío
                $this->messages[$locale][$group] = [];
                $this->loaded[$key] = true;
            }
        }
    }
    
    /**
     * Obtiene una traducción específica
     * 
     * @param string $key Clave de traducción (formato: 'grupo.clave')
     * @param array $replace Variables para reemplazar en el texto
     * @return string
     */
    public function get($key, $replace = [])
    {
        list($group, $item) = explode('.', $key, 2);
        
        // Cargar el grupo si aún no está cargado
        if (!isset($this->loaded["{$this->currentLocale}.{$group}"])) {
            $this->load($group);
        }
        
        // Obtener el mensaje
        $message = $this->messages[$this->currentLocale][$group][$item] ?? $key;
        
        // Reemplazar variables en el texto
        if (!empty($replace)) {
            foreach ($replace as $key => $value) {
                $message = str_replace(":{$key}", $value, $message);
            }
        }
        
        return $message;
    }
    
    /**
     * Cambia el idioma y redirige a la URL anterior
     * 
     * @param string $locale
     * @return void
     */
    public function switchLocale($locale)
    {
        if (array_key_exists($locale, AVAILABLE_LANGUAGES)) {
            $this->setLocale($locale);
        }
    }
    
    /**
     * Obtiene la lista de idiomas disponibles
     * 
     * @return array
     */
    public function getAvailableLanguages()
    {
        return AVAILABLE_LANGUAGES;
    }
} 