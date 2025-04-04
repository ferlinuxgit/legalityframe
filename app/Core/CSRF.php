<?php

namespace App\Core;

/**
 * Clase CSRF para generar y validar tokens de protección contra CSRF.
 */
class CSRF
{
    private const TOKEN_NAME = '_token';
    private const SESSION_KEY = 'csrf_token';

    /**
     * Genera un nuevo token CSRF y lo guarda en la sesión.
     *
     * @param bool $regenerate Forzar la regeneración aunque ya exista.
     * @return string El token generado.
     */
    public static function generateToken(bool $regenerate = false): string
    {
        Session::start(); // Asegurar inicio de sesión
        
        if ($regenerate || !Session::has(self::SESSION_KEY)) {
            try {
                $token = bin2hex(random_bytes(32));
                Session::set(self::SESSION_KEY, $token);
            } catch (\Exception $e) {
                // Manejar error de generación de bytes aleatorios
                // En un caso real, loggear el error y lanzar excepción o retornar error
                die('Error generando token CSRF seguro.');
            }
        } else {
            $token = Session::get(self::SESSION_KEY);
        }
        
        return $token;
    }

    /**
     * Valida un token CSRF contra el almacenado en la sesión.
     *
     * @param string|null $token El token recibido (normalmente de $_POST o $_GET).
     * @return bool True si el token es válido, False en caso contrario.
     */
    public static function validateToken(?string $token): bool
    {
        Session::start(); // Asegurar inicio de sesión
        
        if (!$token || !Session::has(self::SESSION_KEY)) {
            return false;
        }
        
        $sessionToken = Session::get(self::SESSION_KEY);
        
        // Comparación segura contra ataques de temporización
        return hash_equals($sessionToken, $token);
    }

    /**
     * Obtiene el nombre esperado para el campo del token en formularios.
     *
     * @return string
     */
    public static function getTokenName(): string
    {
        return self::TOKEN_NAME;
    }

    /**
     * Genera el campo input HTML oculto para el token CSRF.
     *
     * @return string El HTML del campo input.
     */
    public static function csrfField(): string
    {
        $token = self::generateToken();
        $tokenName = self::getTokenName();
        return sprintf('<input type="hidden" name="%s" value="%s">', $tokenName, $token);
    }
} 