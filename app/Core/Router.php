<?php

namespace App\Core;

use App\Config\Routes;

/**
 * Router - Maneja las rutas y el enrutamiento de la aplicación
 * 
 * @package App\Core
 */
class Router
{
    /**
     * Rutas registradas
     * 
     * @var array
     */
    private $routes = [];
    
    /**
     * Middleware global
     * 
     * @var array
     */
    private $globalMiddleware = [];
    
    /**
     * Middleware de rutas
     * 
     * @var array
     */
    private $routeMiddleware = [];
    
    /**
     * Constructor - Carga las rutas definidas
     */
    public function __construct()
    {
        $this->routes = Routes::getRoutes();
        $this->globalMiddleware = Routes::getGlobalMiddleware();
        $this->routeMiddleware = Routes::getRouteMiddleware();
    }
    
    /**
     * Resolver la ruta actual y ejecutar el controlador correspondiente
     * 
     * @return mixed
     */
    public function resolve()
    {
        // Obtener método y URI
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        
        // Eliminar slash final si existe
        $uri = rtrim($uri, '/');
        
        // Añadir slash inicial si no existe
        if (empty($uri)) {
            $uri = '/';
        }
        
        // Buscar una ruta que coincida con el método y URI
        $route = $this->findRoute($method, $uri);
        
        if ($route) {
            // Extraer controlador, método y parámetros
            list($controller, $action, $params) = $route;
            
            // Crear instancia del controlador
            $controllerInstance = $this->createControllerInstance($controller);
            
            if (!$controllerInstance) {
                return $this->handleError(404, "Controller {$controller} not found");
            }
            
            if (!method_exists($controllerInstance, $action)) {
                return $this->handleError(404, "Method {$action} not found in {$controller}");
            }
            
            // Crear instancia del request
            $request = new Request();
            
            // Ejecutar middleware global
            foreach ($this->globalMiddleware as $middleware) {
                $middlewareInstance = new $middleware();
                $request = $middlewareInstance->handle($request, function($req) { return $req; });
            }
            
            // Ejecutar middleware de rutas
            foreach ($this->routeMiddleware as $key => $middlewareData) {
                if (Routes::hasMiddleware($uri, $key)) {
                    $middlewareClass = $middlewareData['middleware'];
                    $middlewareInstance = new $middlewareClass();
                    $response = $middlewareInstance->handle($request, function($req) {
                        return null; // Continuar si el middleware lo permite
                    });
                    
                    // Si el middleware devuelve una respuesta, retornarla
                    if ($response !== null) {
                        return $response;
                    }
                }
            }
            
            // Ejecutar la acción del controlador con los parámetros
            return call_user_func_array([$controllerInstance, $action], array_merge([$request], $params));
        }
        
        // Ruta no encontrada
        return $this->handleError(404, "Route not found");
    }
    
    /**
     * Encontrar una ruta que coincida con el método y URI
     * 
     * @param string $method Método HTTP
     * @param string $uri URI solicitada
     * @return array|null [controller, action, params] o null si no se encuentra
     */
    private function findRoute($method, $uri)
    {
        foreach ($this->routes as $route => $handler) {
            // Dividir la ruta en método y patrón
            list($routeMethod, $routePattern) = explode('|', $route, 2);
            
            // Verificar si el método coincide
            if ($routeMethod !== $method && $routeMethod !== 'ANY') {
                continue;
            }
            
            // Comprobar si es una ruta estática (sin parámetros)
            if ($routePattern === $uri) {
                list($controller, $action) = explode('@', $handler);
                return [$controller, $action, []];
            }
            
            // Comprobar si es una ruta con parámetros dinámicos
            $paramNames = [];
            $paramValues = [];
            
            // Convertir patrón de ruta a expresión regular
            $pattern = preg_replace_callback('/{([^\/]+)}/', function($matches) use (&$paramNames) {
                $paramNames[] = $matches[1];
                return '([^\/]+)';
            }, $routePattern);
            
            $pattern = '#^' . $pattern . '$#';
            
            if (preg_match($pattern, $uri, $matches)) {
                array_shift($matches); // Eliminar la coincidencia completa
                
                foreach ($matches as $value) {
                    $paramValues[] = $value;
                }
                
                list($controller, $action) = explode('@', $handler);
                
                return [$controller, $action, $paramValues];
            }
        }
        
        return null;
    }
    
    /**
     * Crear una instancia del controlador
     * 
     * @param string $controller Nombre del controlador
     * @return object|null
     */
    private function createControllerInstance($controller)
    {
        $controllerClass = "App\\Controllers\\{$controller}";
        
        if (class_exists($controllerClass)) {
            return new $controllerClass();
        }
        
        return null;
    }
    
    /**
     * Manejar un error HTTP
     * 
     * @param int $code Código de error
     * @param string $message Mensaje de error
     * @return string
     */
    private function handleError($code, $message)
    {
        http_response_code($code);
        
        // Si es una solicitud AJAX, devolver JSON
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
            header('Content-Type: application/json');
            return json_encode(['error' => $message, 'code' => $code]);
        }
        
        // De lo contrario, mostrar una página de error
        if (file_exists(APP_PATH . "/Views/errors/{$code}.php")) {
            return include APP_PATH . "/Views/errors/{$code}.php";
        }
        
        // Si no hay una página de error específica, mostrar un mensaje genérico
        return "<h1>Error {$code}</h1><p>{$message}</p>";
    }
} 