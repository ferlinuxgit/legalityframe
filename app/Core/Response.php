<?php

namespace App\Core;

/**
 * Response - Maneja las respuestas HTTP
 * 
 * @package App\Core
 */
class Response
{
    /**
     * Redirigir a otra URL
     * 
     * @param string $url URL a redirigir
     * @param int $statusCode Código de estado HTTP
     * @return void
     */
    public static function redirect($url, $statusCode = 302)
    {
        header('Location: ' . $url, true, $statusCode);
        exit;
    }
    
    /**
     * Enviar una respuesta JSON
     * 
     * @param mixed $data Datos a enviar
     * @param int $statusCode Código de estado HTTP
     * @return string
     */
    public static function json($data, $statusCode = 200)
    {
        header('Content-Type: application/json');
        http_response_code($statusCode);
        return json_encode($data);
    }
    
    /**
     * Enviar una respuesta exitosa
     * 
     * @param mixed $data Datos a enviar
     * @param string $message Mensaje de éxito
     * @param int $statusCode Código de estado HTTP
     * @return string
     */
    public static function success($data = null, $message = 'Success', $statusCode = 200)
    {
        return self::json([
            'success' => true,
            'message' => $message,
            'data' => $data
        ], $statusCode);
    }
    
    /**
     * Enviar una respuesta de error
     * 
     * @param string $message Mensaje de error
     * @param int $statusCode Código de estado HTTP
     * @param mixed $errors Errores adicionales
     * @return string
     */
    public static function error($message = 'Error', $statusCode = 400, $errors = null)
    {
        return self::json([
            'success' => false,
            'message' => $message,
            'errors' => $errors
        ], $statusCode);
    }
    
    /**
     * Enviar una respuesta de texto plano
     * 
     * @param string $text Texto a enviar
     * @param int $statusCode Código de estado HTTP
     * @return string
     */
    public static function text($text, $statusCode = 200)
    {
        header('Content-Type: text/plain');
        http_response_code($statusCode);
        return $text;
    }
    
    /**
     * Enviar una respuesta HTML
     * 
     * @param string $html HTML a enviar
     * @param int $statusCode Código de estado HTTP
     * @return string
     */
    public static function html($html, $statusCode = 200)
    {
        header('Content-Type: text/html');
        http_response_code($statusCode);
        return $html;
    }
    
    /**
     * Enviar una respuesta de archivo
     * 
     * @param string $path Ruta al archivo
     * @param string $filename Nombre del archivo para descargar
     * @param string $contentType Tipo de contenido
     * @return void
     */
    public static function download($path, $filename = null, $contentType = null)
    {
        if (!file_exists($path)) {
            self::error('File not found', 404);
            exit;
        }
        
        $filename = $filename ?? basename($path);
        $contentType = $contentType ?? mime_content_type($path);
        
        header('Content-Type: ' . $contentType);
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Content-Length: ' . filesize($path));
        
        readfile($path);
        exit;
    }
    
    /**
     * Establecer una cabecera HTTP
     * 
     * @param string $name Nombre de la cabecera
     * @param string $value Valor de la cabecera
     * @return void
     */
    public static function setHeader($name, $value)
    {
        header($name . ': ' . $value);
    }
    
    /**
     * Establecer el código de estado HTTP
     * 
     * @param int $code Código de estado
     * @return void
     */
    public static function setStatusCode($code)
    {
        http_response_code($code);
    }
    
    /**
     * Establecer una cookie
     * 
     * @param string $name Nombre de la cookie
     * @param string $value Valor de la cookie
     * @param int $expire Tiempo de expiración
     * @param string $path Ruta
     * @param string $domain Dominio
     * @param bool $secure Solo HTTPS
     * @param bool $httpOnly Solo HTTP
     * @return bool
     */
    public static function setCookie($name, $value, $expire = 0, $path = '/', $domain = '', $secure = false, $httpOnly = false)
    {
        return setcookie($name, $value, $expire, $path, $domain, $secure, $httpOnly);
    }
    
    /**
     * Enviar una respuesta vacía
     * 
     * @param int $statusCode Código de estado HTTP
     * @return string
     */
    public static function noContent($statusCode = 204)
    {
        http_response_code($statusCode);
        return '';
    }
    
    /**
     * Enviar una respuesta no encontrada
     * 
     * @param string $message Mensaje de error
     * @return string
     */
    public static function notFound($message = 'Not Found')
    {
        return self::error($message, 404);
    }
    
    /**
     * Enviar una respuesta no autorizada
     * 
     * @param string $message Mensaje de error
     * @return string
     */
    public static function unauthorized($message = 'Unauthorized')
    {
        return self::error($message, 401);
    }
    
    /**
     * Enviar una respuesta prohibida
     * 
     * @param string $message Mensaje de error
     * @return string
     */
    public static function forbidden($message = 'Forbidden')
    {
        return self::error($message, 403);
    }
    
    /**
     * Enviar una respuesta de error interno
     * 
     * @param string $message Mensaje de error
     * @return string
     */
    public static function serverError($message = 'Internal Server Error')
    {
        return self::error($message, 500);
    }
} 