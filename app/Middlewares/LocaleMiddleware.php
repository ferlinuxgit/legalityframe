<?php

namespace App\Middlewares;

use App\Config\Lang;

/**
 * Middleware para gestionar la localización y el idioma
 * 
 * Se encarga de establecer el idioma de la aplicación según las preferencias 
 * del usuario o los parámetros de la solicitud
 */
class LocaleMiddleware implements MiddlewareInterface
{
    /**
     * Procesa la solicitud y establece el idioma
     * 
     * @param callable $next Siguiente middleware o controlador
     * @return mixed
     */
    public function handle($next)
    {
        // Comprobar si se ha solicitado un cambio de idioma
        if (isset($_GET['lang']) && is_string($_GET['lang'])) {
            $this->setLocale($_GET['lang']);
        }
        
        // Inicializar el servicio de idioma si no está en la solicitud global
        $lang = Lang::getInstance();
        
        // Pasar al siguiente middleware
        return $next();
    }
    
    /**
     * Establece el idioma actual
     * 
     * @param string $locale
     * @return void
     */
    private function setLocale($locale)
    {
        $lang = Lang::getInstance();
        $lang->setLocale($locale);
    }
} 