<?php

namespace App\Core;

use App\Core\Session;
use App\Core\Request;
use App\Core\Response;

/**
 * Controller - Clase base para todos los controladores
 * 
 * @package App\Core
 */
abstract class Controller
{
    /**
     * Instancia de Request
     * 
     * @var Request
     */
    protected $request;
    
    /**
     * Instancia de Response
     * 
     * @var Response
     */
    protected $response;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->request = new Request();
        $this->response = new Response();
    }
    
    /**
     * Renderizar una vista
     * 
     * @param string $view Ruta de la vista
     * @param array $data Datos para la vista
     * @param int $statusCode Código de estado HTTP
     * @return string
     */
    protected function render($view, $data = [], $statusCode = 200)
    {
        // Extraer datos para hacerlos disponibles en la vista
        extract($data);
        
        // Iniciar buffer de salida
        ob_start();
        
        // Incluir la vista
        $viewPath = APP_PATH . '/Views/' . $view . '.php';
        
        if (!file_exists($viewPath)) {
            throw new \Exception("La vista {$view} no existe");
        }
        
        include $viewPath;
        
        // Obtener contenido del buffer
        $content = ob_get_clean();
        
        // Establecer código de estado
        http_response_code($statusCode);
        
        return $content;
    }
    
    /**
     * Renderizar una vista con un layout
     * 
     * @param string $view Ruta de la vista
     * @param array $data Datos para la vista
     * @param string $layout Nombre del layout
     * @param int $statusCode Código de estado HTTP
     * @return string
     */
    protected function renderWithLayout($view, $data = [], $layout = 'default', $statusCode = 200)
    {
        // Renderizar la vista primero
        $content = $this->render($view, $data, $statusCode);
        
        // Añadir el contenido a los datos para el layout
        $layoutData = array_merge($data, ['content' => $content]);
        
        // Renderizar el layout con el contenido de la vista
        return $this->render('layouts/' . $layout, $layoutData, $statusCode);
    }
    
    /**
     * Responder con JSON
     * 
     * @param mixed $data Datos a enviar
     * @param int $statusCode Código de estado HTTP
     * @return Response
     */
    protected function json($data, $statusCode = 200)
    {
        return $this->response->json($data, $statusCode);
    }
    
    /**
     * Redireccionar a una URL
     * 
     * @param string $url URL de destino
     * @param int $statusCode Código de estado HTTP
     * @return Response
     */
    protected function redirect($url, $statusCode = 302)
    {
        return $this->response->redirect($url, $statusCode);
    }
    
    /**
     * Establecer un mensaje flash
     * 
     * @param string $key Tipo de mensaje
     * @param string $message Mensaje
     * @return void
     */
    protected function setFlash($key, $message)
    {
        Session::setFlash($key, $message);
    }
    
    /**
     * Obtener un mensaje flash
     * 
     * @param string $key Tipo de mensaje
     * @param mixed $default Valor por defecto
     * @return mixed
     */
    protected function getFlash($key, $default = null)
    {
        return Session::getFlash($key, $default);
    }
    
    /**
     * Validar la solicitud utilizando una clase validadora específica
     * 
     * @param string $validatorClass Nombre de la clase validadora
     * @return mixed
     */
    protected function validate($validatorClass)
    {
        $validator = new $validatorClass($this->request);
        return $validator->validate();
    }
    
    /**
     * Comprobar si el usuario está autenticado
     * 
     * @return bool
     */
    protected function isAuthenticated()
    {
        return Session::isAuthenticated();
    }
    
    /**
     * Obtener el ID del usuario autenticado
     * 
     * @return int|null
     */
    protected function getAuthUserId()
    {
        return Session::getAuthUserId();
    }
    
    /**
     * Responder con un error 404
     * 
     * @param string $message Mensaje de error
     * @return Response
     */
    protected function notFound($message = 'Recurso no encontrado')
    {
        return $this->response->notFound($message);
    }
    
    /**
     * Responder con un error 403
     * 
     * @param string $message Mensaje de error
     * @return Response
     */
    protected function forbidden($message = 'Acceso prohibido')
    {
        return $this->response->forbidden($message);
    }
    
    /**
     * Responder con un error 401
     * 
     * @param string $message Mensaje de error
     * @return Response
     */
    protected function unauthorized($message = 'No autorizado')
    {
        return $this->response->unauthorized($message);
    }
    
    /**
     * Responder con un error 500
     * 
     * @param string $message Mensaje de error
     * @return Response
     */
    protected function serverError($message = 'Error interno del servidor')
    {
        return $this->response->serverError($message);
    }
} 