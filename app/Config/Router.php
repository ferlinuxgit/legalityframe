<?php

namespace App\Config;

/**
 * Router - Controlador de rutas de la aplicación
 *
 * @package App\Config
 */
class Router
{
    /**
     * @var array Rutas registradas
     */
    protected static $routes = [
        'GET' => [],
        'POST' => [],
        'PUT' => [],
        'DELETE' => [],
    ];
    
    /**
     * @var Router Instancia del router
     */
    protected static $instance;
    
    /**
     * @var array Parámetros de la ruta actual
     */
    protected $routeParams = [];
    
    /**
     * Obtener la instancia del router
     *
     * @return Router
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        
        return self::$instance;
    }
    
    /**
     * Registrar una ruta GET
     *
     * @param string $uri URI a registrar
     * @param string|callable $action Controlador@método o función callback
     * @return Router
     */
    public function get($uri, $action)
    {
        return $this->addRoute('GET', $uri, $action);
    }
    
    /**
     * Registrar una ruta POST
     *
     * @param string $uri URI a registrar
     * @param string|callable $action Controlador@método o función callback
     * @return Router
     */
    public function post($uri, $action)
    {
        return $this->addRoute('POST', $uri, $action);
    }
    
    /**
     * Registrar una ruta PUT
     *
     * @param string $uri URI a registrar
     * @param string|callable $action Controlador@método o función callback
     * @return Router
     */
    public function put($uri, $action)
    {
        return $this->addRoute('PUT', $uri, $action);
    }
    
    /**
     * Registrar una ruta DELETE
     *
     * @param string $uri URI a registrar
     * @param string|callable $action Controlador@método o función callback
     * @return Router
     */
    public function delete($uri, $action)
    {
        return $this->addRoute('DELETE', $uri, $action);
    }
    
    /**
     * Añadir una ruta al array de rutas
     *
     * @param string $method Método HTTP
     * @param string $uri URI a registrar
     * @param string|callable $action Controlador@método o función callback
     * @return Router
     */
    protected function addRoute($method, $uri, $action)
    {
        // Normalizar la URI
        $uri = trim($uri, '/');
        $uri = $uri ?: '/';
        
        // Convertir la URI a una expresión regular
        $pattern = $this->uriToPattern($uri);
        
        // Guardar la ruta
        self::$routes[$method][$pattern] = [
            'uri' => $uri,
            'action' => $action,
        ];
        
        return $this;
    }
    
    /**
     * Convertir una URI a un patrón de expresión regular
     *
     * @param string $uri URI original
     * @return string Patrón
     */
    protected function uriToPattern($uri)
    {
        // Escapar caracteres especiales de regex
        $pattern = preg_quote($uri, '#');
        
        // Convertir {param} a captura nombrada (?P<param>[^/]+)
        $pattern = preg_replace('#\\\{([a-zA-Z0-9_]+)\\\}#', '(?P<$1>[^/]+)', $pattern);
        
        // Finalizar el patrón
        return '#^' . $pattern . '$#';
    }
    
    /**
     * Procesar la solicitud actual
     */
    public function dispatch()
    {
        // Obtener el método y la URI
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = $this->getRequestUri();
        
        // Permitir métodos PUT y DELETE mediante _method en POST
        if ($method === 'POST' && isset($_POST['_method'])) {
            if (in_array($_POST['_method'], ['PUT', 'DELETE'])) {
                $method = $_POST['_method'];
            }
        }
        
        // Buscar la ruta correspondiente
        $route = $this->findRoute($method, $uri);
        
        if ($route) {
            return $this->executeRoute($route);
        }
        
        // Ruta no encontrada
        $this->notFound();
    }
    
    /**
     * Obtener la URI de la solicitud actual
     *
     * @return string
     */
    protected function getRequestUri()
    {
        $uri = $_SERVER['REQUEST_URI'];
        
        // Eliminar la query string
        if (($pos = strpos($uri, '?')) !== false) {
            $uri = substr($uri, 0, $pos);
        }
        
        // Normalizar la URI
        return trim($uri, '/');
    }
    
    /**
     * Buscar una ruta correspondiente a la URI
     *
     * @param string $method Método HTTP
     * @param string $uri URI solicitada
     * @return array|null Ruta encontrada o null
     */
    protected function findRoute($method, $uri)
    {
        if (!isset(self::$routes[$method])) {
            return null;
        }
        
        foreach (self::$routes[$method] as $pattern => $route) {
            if (preg_match($pattern, $uri, $matches)) {
                // Extraer parámetros
                foreach ($matches as $key => $value) {
                    if (is_string($key)) {
                        $this->routeParams[$key] = $value;
                    }
                }
                
                return $route;
            }
        }
        
        return null;
    }
    
    /**
     * Ejecutar una ruta
     *
     * @param array $route Ruta a ejecutar
     */
    protected function executeRoute($route)
    {
        $action = $route['action'];
        
        // Si la acción es una función anónima
        if (is_callable($action)) {
            call_user_func_array($action, array_values($this->routeParams));
            return;
        }
        
        // Si es un string "Controlador@método"
        if (is_string($action)) {
            list($controller, $method) = explode('@', $action);
            
            // Añadir el namespace completo si no está presente
            if (strpos($controller, '\\') === false) {
                $controller = 'App\\Controllers\\' . $controller;
            }
            
            // Crear la instancia del controlador
            $controllerInstance = new $controller();
            
            // Ejecutar el método con los parámetros
            call_user_func_array([$controllerInstance, $method], array_values($this->routeParams));
            return;
        }
        
        // Tipo de acción no soportado
        $this->notFound();
    }
    
    /**
     * Manejar error 404
     */
    protected function notFound()
    {
        header('HTTP/1.0 404 Not Found');
        include_once base_path('app/Views/error/404.php');
        exit;
    }
    
    /**
     * Obtener los parámetros de la ruta
     *
     * @return array
     */
    public function getRouteParams()
    {
        return $this->routeParams;
    }
} 