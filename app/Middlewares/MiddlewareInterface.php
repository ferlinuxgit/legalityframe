<?php

namespace App\Middlewares;

/**
 * MiddlewareInterface - Interfaz para todos los middleware
 *
 * @package App\Middlewares
 */
interface MiddlewareInterface
{
    /**
     * Manejar la solicitud
     *
     * @param callable $next Siguiente middleware o controlador
     * @return mixed
     */
    public function handle($next);
} 