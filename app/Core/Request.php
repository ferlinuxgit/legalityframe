<?php

namespace App\Core;

/**
 * Request - Maneja los datos de la solicitud HTTP
 * 
 * @package App\Core
 */
class Request
{
    /**
     * Datos de la solicitud
     * 
     * @var array
     */
    private $data = [];
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->loadData();
    }
    
    /**
     * Cargar los datos de la solicitud
     * 
     * @return void
     */
    private function loadData()
    {
        // Cargar datos GET
        foreach ($_GET as $key => $value) {
            $this->data['get'][$key] = $this->sanitize($value);
        }
        
        // Cargar datos POST
        foreach ($_POST as $key => $value) {
            $this->data['post'][$key] = $this->sanitize($value);
        }
        
        // Cargar datos de archivos
        $this->data['files'] = $_FILES;
        
        // Cargar cookies
        foreach ($_COOKIE as $key => $value) {
            $this->data['cookies'][$key] = $this->sanitize($value);
        }
        
        // Cargar cabeceras
        $headers = [];
        foreach ($_SERVER as $key => $value) {
            if (strpos($key, 'HTTP_') === 0) {
                $name = str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($key, 5)))));
                $headers[$name] = $value;
            }
        }
        $this->data['headers'] = $headers;
        
        // Cargar datos JSON si el tipo de contenido es application/json
        if (isset($_SERVER['CONTENT_TYPE']) && strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false) {
            $json = file_get_contents('php://input');
            if (!empty($json)) {
                $this->data['json'] = json_decode($json, true);
            }
        }
        
        // Cargar datos RAW
        $this->data['raw'] = file_get_contents('php://input');
        
        // Cargar datos del servidor
        $this->data['server'] = $_SERVER;
    }
    
    /**
     * Sanitizar datos de entrada
     * 
     * @param mixed $data Datos a sanitizar
     * @return mixed
     */
    private function sanitize($data)
    {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $data[$key] = $this->sanitize($value);
            }
            return $data;
        }
        
        // Sanitizar strings
        if (is_string($data)) {
            return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
        }
        
        return $data;
    }
    
    /**
     * Obtener un valor de la solicitud GET
     * 
     * @param string $key Clave a buscar
     * @param mixed $default Valor por defecto si no se encuentra
     * @return mixed
     */
    public function get($key, $default = null)
    {
        return $this->data['get'][$key] ?? $default;
    }
    
    /**
     * Obtener un valor de la solicitud POST
     * 
     * @param string $key Clave a buscar
     * @param mixed $default Valor por defecto si no se encuentra
     * @return mixed
     */
    public function post($key, $default = null)
    {
        return $this->data['post'][$key] ?? $default;
    }
    
    /**
     * Obtener un valor de la solicitud (GET o POST)
     * 
     * @param string $key Clave a buscar
     * @param mixed $default Valor por defecto si no se encuentra
     * @return mixed
     */
    public function input($key, $default = null)
    {
        return $this->post($key) ?? $this->get($key) ?? $default;
    }
    
    /**
     * Obtener un archivo de la solicitud
     * 
     * @param string $key Clave a buscar
     * @return array|null
     */
    public function file($key)
    {
        return $this->data['files'][$key] ?? null;
    }
    
    /**
     * Obtener una cookie
     * 
     * @param string $key Clave a buscar
     * @param mixed $default Valor por defecto si no se encuentra
     * @return mixed
     */
    public function cookie($key, $default = null)
    {
        return $this->data['cookies'][$key] ?? $default;
    }
    
    /**
     * Obtener una cabecera
     * 
     * @param string $key Clave a buscar
     * @param mixed $default Valor por defecto si no se encuentra
     * @return mixed
     */
    public function header($key, $default = null)
    {
        return $this->data['headers'][$key] ?? $default;
    }
    
    /**
     * Obtener un valor de la solicitud JSON
     * 
     * @param string $key Clave a buscar
     * @param mixed $default Valor por defecto si no se encuentra
     * @return mixed
     */
    public function json($key = null, $default = null)
    {
        if ($key === null) {
            return $this->data['json'] ?? [];
        }
        
        return $this->data['json'][$key] ?? $default;
    }
    
    /**
     * Obtener el cuerpo raw de la solicitud
     * 
     * @return string
     */
    public function raw()
    {
        return $this->data['raw'];
    }
    
    /**
     * Obtener el método de la solicitud
     * 
     * @return string
     */
    public function method()
    {
        return $_SERVER['REQUEST_METHOD'];
    }
    
    /**
     * Verificar si la solicitud es AJAX
     * 
     * @return bool
     */
    public function isAjax()
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }
    
    /**
     * Obtener la URL de la solicitud
     * 
     * @return string
     */
    public function url()
    {
        return (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://') . 
               $_SERVER['HTTP_HOST'] . 
               $_SERVER['REQUEST_URI'];
    }
    
    /**
     * Obtener el URI de la solicitud
     * 
     * @return string
     */
    public function uri()
    {
        return $_SERVER['REQUEST_URI'];
    }
    
    /**
     * Obtener la URL base
     * 
     * @return string
     */
    public function baseUrl()
    {
        return (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://') . 
               $_SERVER['HTTP_HOST'];
    }
    
    /**
     * Obtener la IP del cliente
     * 
     * @return string
     */
    public function ip()
    {
        // Intentar obtener la IP real detrás de proxies
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        
        return $ip;
    }
    
    /**
     * Verificar si la solicitud es sobre HTTPS
     * 
     * @return bool
     */
    public function isSecure()
    {
        return isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on';
    }
    
    /**
     * Obtener el user agent
     * 
     * @return string
     */
    public function userAgent()
    {
        return $_SERVER['HTTP_USER_AGENT'] ?? '';
    }
    
    /**
     * Verificar si la solicitud acepta un tipo de contenido
     * 
     * @param string $contentType Tipo de contenido a verificar
     * @return bool
     */
    public function accepts($contentType)
    {
        $accepts = $_SERVER['HTTP_ACCEPT'] ?? '';
        return strpos($accepts, $contentType) !== false;
    }
} 