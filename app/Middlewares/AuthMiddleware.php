<?php

namespace App\Middlewares;

/**
 * AuthMiddleware - Middleware para verificar autenticación
 *
 * @package App\Middlewares
 */
class AuthMiddleware implements MiddlewareInterface
{
    /**
     * Manejar la solicitud
     *
     * @param callable $next Siguiente middleware o controlador
     * @return mixed
     */
    public function handle($next)
    {
        // Verificar si el usuario está autenticado
        if (!isset($_SESSION['user_id'])) {
            // Usuario no autenticado, redireccionar a login
            header('Location: ' . url('/login'));
            exit;
        }
        
        // Usuario autenticado, continuar con la solicitud
        return $next();
    }
} 