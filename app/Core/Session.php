<?php

namespace App\Core;

/**
 * Clase Session para gestionar sesiones PHP y mensajes flash.
 */
class Session
{
    private const FLASH_KEY = 'flash_messages';

    /**
     * Inicia la sesión si no está iniciada.
     */
    public static function start(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        // Inicializar contenedor flash si no existe
        if (!isset($_SESSION[self::FLASH_KEY])) {
            $_SESSION[self::FLASH_KEY] = [];
        }
        // Marcar mensajes flash antiguos para eliminar
        foreach ($_SESSION[self::FLASH_KEY] as $key => &$flash_message) {
            if ($flash_message['remove']) {
                // Marcar para eliminar en la siguiente solicitud
                $flash_message['marked_for_deletion'] = true;
            }
             $flash_message['remove'] = true; // Marcar para eliminar en la siguiente carga
        }
        unset($flash_message); // Romper referencia
    }

    /**
     * Establece un valor en la sesión.
     *
     * @param string $key La clave.
     * @param mixed $value El valor.
     */
    public static function set(string $key, $value): void
    {
        self::start(); // Asegurar que la sesión está iniciada
        $_SESSION[$key] = $value;
    }

    /**
     * Obtiene un valor de la sesión.
     *
     * @param string $key La clave.
     * @param mixed $default El valor por defecto si la clave no existe.
     * @return mixed El valor de la sesión o el valor por defecto.
     */
    public static function get(string $key, $default = null)
    {
        self::start();
        return $_SESSION[$key] ?? $default;
    }

    /**
     * Comprueba si una clave existe en la sesión.
     *
     * @param string $key La clave.
     * @return bool True si existe, false si no.
     */
    public static function has(string $key): bool
    {
        self::start();
        return isset($_SESSION[$key]);
    }

    /**
     * Elimina una clave de la sesión.
     *
     * @param string $key La clave a eliminar.
     */
    public static function delete(string $key): void
    {
        self::start();
        unset($_SESSION[$key]);
    }

    /**
     * Establece un mensaje flash (disponible solo para la siguiente solicitud).
     *
     * @param string $key La clave del mensaje flash.
     * @param mixed $message El mensaje.
     */
    public static function setFlash(string $key, $message): void
    {
        self::start();
        $_SESSION[self::FLASH_KEY][$key] = [
            'message' => $message,
            'remove' => false // No eliminar inmediatamente
        ];
    }

    /**
     * Obtiene un mensaje flash y lo marca para eliminar.
     *
     * @param string $key La clave del mensaje flash.
     * @param mixed $default El valor por defecto si no existe.
     * @return mixed El mensaje flash o el valor por defecto.
     */
    public static function getFlash(string $key, $default = null)
    {
        self::start();
        $message = $_SESSION[self::FLASH_KEY][$key]['message'] ?? $default;
        
        // Si existe, marcar para eliminar (ya estaba marcado por start(), pero aseguramos)
        if (isset($_SESSION[self::FLASH_KEY][$key])) {
             $_SESSION[self::FLASH_KEY][$key]['marked_for_deletion'] = true;
             self::cleanupFlashMessages(); // Limpiar inmediatamente al leer si se prefiere
        }
        
        return $message;
    }
    
    /**
     * Comprueba si existe un mensaje flash.
     *
     * @param string $key La clave.
     * @return bool
     */
    public static function hasFlash(string $key): bool
    {
        self::start();
        return isset($_SESSION[self::FLASH_KEY][$key]);
    }

    /**
     * Elimina los mensajes flash marcados para eliminación.
     * Se llama automáticamente al inicio y opcionalmente al obtener un flash.
     */
    private static function cleanupFlashMessages(): void
    {
        if (!isset($_SESSION[self::FLASH_KEY])) return;
        
        foreach ($_SESSION[self::FLASH_KEY] as $key => $flash_message) {
            if (isset($flash_message['marked_for_deletion']) && $flash_message['marked_for_deletion']) {
                unset($_SESSION[self::FLASH_KEY][$key]);
            }
        }
    }
    
    /**
     * Destruye toda la sesión.
     */
    public static function destroy(): void
    {
        self::start();
        session_destroy();
        $_SESSION = []; // Limpiar el array por si acaso
    }
    
    /**
     * Comprueba si el usuario está autenticado.
     * Asume que la autenticación se marca con 'user_id'.
     * 
     * @return bool
     */
    public static function isAuthenticated(): bool
    {
        return self::has('user_id'); // Usar la clave definida en la lógica de login
    }
} 