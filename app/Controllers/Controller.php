<?php

namespace App\Controllers;

/**
 * Controller - Controlador base para todos los controladores
 *
 * @package App\Controllers
 */
class Controller
{
    /**
     * Datos para las vistas
     *
     * @var array
     */
    protected $viewData = [];
    
    /**
     * Constructor
     */
    public function __construct()
    {
        // Se puede utilizar para inicializar datos comunes
    }
    
    /**
     * Renderizar una vista
     *
     * @param string $view Ruta de la vista
     * @param array $data Datos para la vista
     * @return void
     */
    protected function view($view, $data = [])
    {
        // Combinar datos existentes con nuevos datos
        $this->viewData = array_merge($this->viewData, $data);
        
        // Extraer variables para que estén disponibles en la vista
        extract($this->viewData);
        
        // Ruta completa a la vista
        $viewPath = base_path('app/Views/' . $view . '.php');
        
        // Verificar si la vista existe
        if (!file_exists($viewPath)) {
            throw new \Exception("Vista no encontrada: {$view}");
        }
        
        // Iniciar buffer de salida
        ob_start();
        include $viewPath;
        $content = ob_get_clean();
        
        // Si hay un layout, incluirlo
        if (isset($layout)) {
            $layoutPath = base_path('app/Views/layouts/' . $layout . '.php');
            
            if (!file_exists($layoutPath)) {
                throw new \Exception("Layout no encontrado: {$layout}");
            }
            
            include $layoutPath;
        } else {
            // Si no hay layout, mostrar contenido directamente
            echo $content;
        }
    }
    
    /**
     * Responder con JSON
     *
     * @param mixed $data Datos a convertir a JSON
     * @param int $status Código de estado HTTP
     * @return void
     */
    protected function json($data, $status = 200)
    {
        header('Content-Type: application/json');
        http_response_code($status);
        echo json_encode($data);
        exit;
    }
    
    /**
     * Redireccionar a una URL
     *
     * @param string $url URL a redireccionar
     * @param int $status Código de estado HTTP
     * @return void
     */
    protected function redirect($url, $status = 302)
    {
        header('Location: ' . $url, true, $status);
        exit;
    }
    
    /**
     * Obtener datos del formulario
     *
     * @param string $key Clave a obtener (null para todos)
     * @param mixed $default Valor por defecto
     * @return mixed
     */
    protected function input($key = null, $default = null)
    {
        $data = array_merge($_GET, $_POST);
        
        if ($key === null) {
            return $data;
        }
        
        return isset($data[$key]) ? $data[$key] : $default;
    }
    
    /**
     * Verificar si la solicitud es AJAX
     *
     * @return bool
     */
    protected function isAjax()
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }
} 