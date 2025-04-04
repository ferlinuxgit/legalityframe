<?php

namespace App\Config;

/**
 * App - Clase principal de la aplicación
 *
 * @package App\Config
 */
class App
{
    /**
     * @var array Configuración de la aplicación
     */
    protected $config;
    
    /**
     * @var array Middleware global
     */
    protected $middleware = [];
    
    /**
     * Constructor
     *
     * @param array $config Configuración de la aplicación
     */
    public function __construct(array $config)
    {
        $this->config = $config;
        
        // Configurar zona horaria
        date_default_timezone_set($config['app']['timezone']);
        
        // Configurar gestión de errores
        $this->setupErrorHandling();
        
        // Registrar middleware global
        $this->registerMiddleware();
    }
    
    /**
     * Configurar gestión de errores
     */
    protected function setupErrorHandling()
    {
        if ($this->config['app']['debug']) {
            error_reporting(E_ALL);
            ini_set('display_errors', 1);
        } else {
            error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
            ini_set('display_errors', 0);
        }
        
        // Directorio de logs
        ini_set('error_log', $this->config['log']['path'] . '/error.log');
    }
    
    /**
     * Registrar middleware global
     */
    protected function registerMiddleware()
    {
        // Registrar el middleware de localización
        $this->middleware[] = new \App\Middlewares\LocaleMiddleware();
        
        // Registrar el middleware CSRF
        $this->middleware[] = new \App\Middlewares\CsrfMiddleware();
        
        // Registrar otros middleware según sea necesario
    }
    
    /**
     * Ejecutar la aplicación
     */
    public function run()
    {
        // Inicializar el router
        $router = Router::getInstance();
        
        // Cargar rutas
        require_once BASE_PATH . '/app/Config/Routes.php';
        
        // Procesar la solicitud a través de middleware
        $this->processMiddleware(function() use ($router) {
            // Finalmente, procesar la ruta
            $router->dispatch();
        });
    }
    
    /**
     * Procesar la cadena de middleware
     *
     * @param callable $target Función objetivo final
     * @return mixed
     */
    protected function processMiddleware($target)
    {
        // Crear la cadena de middleware
        $pipeline = array_reduce(
            array_reverse($this->middleware),
            function ($next, $middleware) {
                return function () use ($next, $middleware) {
                    return $middleware->handle($next);
                };
            },
            $target
        );
        
        // Ejecutar la cadena
        return $pipeline();
    }
} 