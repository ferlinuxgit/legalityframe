<?php

namespace App\Core;

use App\Core\Router;
use App\Core\Request;
use App\Core\Response;
use App\Core\Session;
use App\Config\Config;

/**
 * Application - Clase principal de la aplicación
 * 
 * @package App\Core
 */
class Application
{
    /**
     * Instancia única (Singleton)
     * 
     * @var self
     */
    private static $instance = null;
    
    /**
     * Instancia del router
     * 
     * @var Router
     */
    private $router;
    
    /**
     * Instancia de la solicitud actual
     * 
     * @var Request
     */
    private $request;
    
    /**
     * Instancia de la respuesta
     * 
     * @var Response
     */
    private $response;
    
    /**
     * Middlewares registrados
     * 
     * @var array
     */
    private $middlewares = [];
    
    /**
     * Constructor privado (Singleton)
     */
    private function __construct()
    {
        // Inicializar componentes básicos
        $this->request = new Request();
        $this->response = new Response();
        $this->router = new Router();
        
        // Inicializar sesión
        Session::init();
    }
    
    /**
     * Obtener instancia única (Singleton)
     * 
     * @return self
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        
        return self::$instance;
    }
    
    /**
     * Inicializar la aplicación
     * 
     * @return self
     */
    public function init()
    {
        // Definir constantes básicas
        if (!defined('APP_PATH')) {
            define('APP_PATH', dirname(dirname(__FILE__)));
        }
        
        if (!defined('ROOT_PATH')) {
            define('ROOT_PATH', dirname(APP_PATH));
        }
        
        // Establecer zona horaria
        date_default_timezone_set(Config::get('app.timezone', 'Europe/Madrid'));
        
        // Establecer manejo de errores personalizado
        $this->setupErrorHandling();
        
        return $this;
    }
    
    /**
     * Configurar manejo de errores y excepciones
     * 
     * @return void
     */
    private function setupErrorHandling()
    {
        // Establecer manejador de errores
        set_error_handler(function ($severity, $message, $file, $line) {
            if (!(error_reporting() & $severity)) {
                return;
            }
            
            throw new \ErrorException($message, 0, $severity, $file, $line);
        });
        
        // Establecer manejador de excepciones
        set_exception_handler(function (\Throwable $e) {
            $this->handleException($e);
        });
    }
    
    /**
     * Manejar excepciones no capturadas
     * 
     * @param \Throwable $e Excepción
     * @return void
     */
    private function handleException(\Throwable $e)
    {
        // Registrar el error
        error_log($e->getMessage() . ' in ' . $e->getFile() . ' on line ' . $e->getLine());
        
        // En entorno de desarrollo, mostrar detalles completos
        if (Config::get('app.debug', false)) {
            echo '<h1>Error: ' . $e->getMessage() . '</h1>';
            echo '<p>File: ' . $e->getFile() . '</p>';
            echo '<p>Line: ' . $e->getLine() . '</p>';
            echo '<h2>Stack Trace:</h2>';
            echo '<pre>' . $e->getTraceAsString() . '</pre>';
        } else {
            // En producción, mostrar página de error genérica
            http_response_code(500);
            
            if (file_exists(APP_PATH . '/Views/errors/500.php')) {
                include APP_PATH . '/Views/errors/500.php';
            } else {
                echo '<h1>Error interno del servidor</h1>';
                echo '<p>Lo sentimos, ha ocurrido un error inesperado.</p>';
            }
        }
        
        exit(1);
    }
    
    /**
     * Registrar un middleware global
     * 
     * @param object $middleware Instancia del middleware
     * @return self
     */
    public function registerMiddleware($middleware)
    {
        $this->middlewares[] = $middleware;
        return $this;
    }
    
    /**
     * Obtener todos los middlewares registrados
     * 
     * @return array
     */
    public function getMiddlewares()
    {
        return $this->middlewares;
    }
    
    /**
     * Ejecutar middlewares globales
     * 
     * @param Request $request Solicitud
     * @return Request
     */
    private function runMiddlewares(Request $request)
    {
        $request = clone $this->request;
        
        foreach ($this->middlewares as $middleware) {
            if (method_exists($middleware, 'process')) {
                $request = $middleware->process($request, function ($req) {
                    return $req;
                });
            }
        }
        
        return $request;
    }
    
    /**
     * Ejecutar la aplicación
     * 
     * @return void
     */
    public function run()
    {
        try {
            // Procesar middlewares globales
            $this->request = $this->runMiddlewares($this->request);
            
            // Resolver ruta
            $uri = $this->request->getUri();
            $method = $this->request->getMethod();
            
            // Ejecutar controlador correspondiente
            $this->router->resolve($uri, $method);
            
        } catch (\Throwable $e) {
            $this->handleException($e);
        }
    }
    
    /**
     * Obtener instancia del router
     * 
     * @return Router
     */
    public function getRouter()
    {
        return $this->router;
    }
    
    /**
     * Obtener instancia de la solicitud actual
     * 
     * @return Request
     */
    public function getRequest()
    {
        return $this->request;
    }
    
    /**
     * Obtener instancia de la respuesta
     * 
     * @return Response
     */
    public function getResponse()
    {
 