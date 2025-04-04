<?php

namespace App\Middlewares;

use App\Middlewares\MiddlewareInterface;
// use App\Core\Request; // No longer needed in handle signature
use App\Core\Response; // Asumir estático
use App\Core\Session; // Asumir estático
// use Closure; // No longer needed in handle signature

/**
 * Middleware para asegurar que el usuario NO esté autenticado
 * 
 * Este middleware es útil para rutas que solo deberían ser accesibles
 * para usuarios no autenticados, como páginas de registro e inicio de sesión.
 * 
 * @package App\Middlewares
 */
class GuestMiddleware implements MiddlewareInterface
{
    /**
     * Manejar la solicitud entrante.
     *
     * Redirige a la página de inicio si el usuario ya está autenticado.
     *
     * @param callable $next   El siguiente middleware en la pila.
     * @return mixed
     */
    // public function handle(Request $request, Closure $next): mixed // Firma incorrecta
    public function handle($next) // Firma correcta según la interfaz
    {
        // Comprobar si el usuario está autenticado usando la sesión
        // REVISAR: La clave de sesión 'user_id' o similar debe ser consistente
        if (Session::has('user_id')) { 
            // Usuario autenticado, redirigir al dashboard o página principal
            // REVISAR: La ruta '/dashboard' debe existir
            return Response::redirect('/dashboard'); 
        }

        // Usuario no autenticado, continuar con la solicitud
        // return $next($request); // Llamada incorrecta
        return $next(); // Llamada correcta
    }
} 