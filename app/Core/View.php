<?php

namespace App\Core;

/**
 * View - Gestiona la renderización de vistas
 * 
 * @package App\Core
 */
class View
{
    /**
     * Datos disponibles para la vista
     * 
     * @var array
     */
    protected $data = [];
    
    /**
     * Ruta base de las vistas
     * 
     * @var string
     */
    protected $viewPath;
    
    /**
     * Constructor
     * 
     * @param string $viewPath Ruta base de las vistas
     */
    public function __construct($viewPath = '')
    {
        $this->viewPath = $viewPath ?: APP_PATH . '/Views';
    }
    
    /**
     * Asignar datos a la vista
     * 
     * @param string|array $key Clave o array asociativo de datos
     * @param mixed $value Valor (si $key es string)
     * @return $this
     */
    public function assign($key, $value = null)
    {
        if (is_array($key)) {
            $this->data = array_merge($this->data, $key);
        } else {
            $this->data[$key] = $value;
        }
        
        return $this;
    }
    
    /**
     * Renderizar una vista
     * 
     * @param string $view Ruta de la vista
     * @param array $data Datos adicionales para la vista
     * @return string
     */
    public function render($view, $data = [])
    {
        // Combinar datos existentes con los nuevos
        $data = array_merge($this->data, $data);
        
        // Extraer datos para hacerlos disponibles en la vista
        extract($data);
        
        // Iniciar buffer de salida
        ob_start();
        
        // Construir ruta completa de la vista
        $viewFile = $this->viewPath . '/' . $view . '.php';
        
        if (!file_exists($viewFile)) {
            throw new \Exception("La vista {$view} no existe en la ruta {$viewFile}");
        }
        
        include $viewFile;
        
        // Obtener contenido del buffer
        return ob_get_clean();
    }
    
    /**
     * Renderizar una vista con un layout
     * 
     * @param string $view Ruta de la vista
     * @param string $layout Nombre del layout
     * @param array $data Datos adicionales para la vista
     * @return string
     */
    public function renderWithLayout($view, $layout = 'default', $data = [])
    {
        // Renderizar la vista primero
        $content = $this->render($view, $data);
        
        // Añadir el contenido a los datos para el layout
        $layoutData = array_merge($data, ['content' => $content]);
        
        // Renderizar el layout con el contenido de la vista
        return $this->render('layouts/' . $layout, $layoutData);
    }
    
    /**
     * Escapar HTML para prevenir XSS
     * 
     * @param string $string Cadena a escapar
     * @return string
     */
    public static function escape($string)
    {
        return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
    }
    
    /**
     * Renderizar un partial (vista parcial)
     * 
     * @param string $partial Ruta del partial
     * @param array $data Datos para el partial
     * @return string
     */
    public function partial($partial, $data = [])
    {
        return $this->render('partials/' . $partial, $data);
    }
    
    /**
     * Incluir una vista parcial inline
     * 
     * @param string $partial Ruta del partial
     * @param array $data Datos para el partial
     * @return void
     */
    public function include($partial, $data = [])
    {
        echo $this->partial($partial, $data);
    }
    
    /**
     * Obtener una instancia estática para uso rápido
     * 
     * @param string $viewPath Ruta base de las vistas
     * @return self
     */
    public static function getInstance($viewPath = '')
    {
        static $instance = null;
        
        if ($instance === null) {
            $instance = new self($viewPath);
        }
        
        return $instance;
    }
    
    /**
     * Método estático para renderizar vista
     * 
     * @param string $view Ruta de la vista
     * @param array $data Datos para la vista
     * @return string
     */
    public static function make($view, $data = [])
    {
        return self::getInstance()->render($view, $data);
    }
    
    /**
     * Método estático para renderizar vista con layout
     * 
     * @param string $view Ruta de la vista
     * @param string $layout Nombre del layout
     * @param array $data Datos para la vista
     * @return string
     */
    public static function makeWithLayout($view, $layout = 'default', $data = [])
    {
        return self::getInstance()->renderWithLayout($view, $layout, $data);
    }
} 