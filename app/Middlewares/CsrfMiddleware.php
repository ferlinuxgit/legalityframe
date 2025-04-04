<?php

namespace App\Middlewares;

/**
 * CsrfMiddleware - Middleware para protección CSRF
 *
 * @package App\Middlewares
 */
class CsrfMiddleware implements MiddlewareInterface
{
    /**
     * Manejar la solicitud
     *
     * @param callable $next Siguiente middleware o controlador
     * @return mixed
     */
    public function handle($next)
    {
        // Inicializar token CSRF si no existe
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        
        // Para solicitudes POST, PUT, DELETE (modificadoras)
        if (in_array($_SERVER['REQUEST_METHOD'], ['POST', 'PUT', 'DELETE'])) {
            $token = $_POST['csrf_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? null;
            
            // Verificar token
            if (!$token || $token !== $_SESSION['csrf_token']) {
                // Token inválido, mostrar error 403
                header('HTTP/1.0 403 Forbidden');
                echo 'CSRF token verification failed';
                exit;
            }
        }
        
        // Token válido o solicitud no modificadora, continuar
        return $next();
    }
} 