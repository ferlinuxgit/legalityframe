<?php

namespace App\Controllers;

use App\Config\Lang;

/**
 * Controlador para la gestión de idiomas
 * 
 * Permite cambiar el idioma de la aplicación
 */
class LanguageController extends Controller
{
    /**
     * Cambiar el idioma de la aplicación
     * 
     * @param string $locale Código de idioma (es, en, etc.)
     * @return void
     */
    public function switchLanguage($locale)
    {
        // Obtener la instancia de Lang
        $lang = Lang::getInstance();
        
        // Verificar si el idioma es válido
        if (array_key_exists($locale, $lang->getAvailableLanguages())) {
            // Establecer el nuevo idioma
            $lang->setLocale($locale);
            
            // Mensaje de éxito
            $this->addFlashMessage('success', __('general.language_changed'));
        } else {
            // Mensaje de error
            $this->addFlashMessage('error', __('general.invalid_language'));
        }
        
        // Obtener URL de retorno o usar la página principal
        $returnUrl = $_SERVER['HTTP_REFERER'] ?? '/';
        
        // Redireccionar de vuelta a la página anterior
        redirect($returnUrl);
    }
    
    /**
     * Añadir un mensaje flash para mostrar después de la redirección
     * 
     * @param string $type Tipo de mensaje (success, error, info, warning)
     * @param string $message Contenido del mensaje
     * @return void
     */
    private function addFlashMessage($type, $message)
    {
        if (!isset($_SESSION['flash_messages'])) {
            $_SESSION['flash_messages'] = [];
        }
        
        $_SESSION['flash_messages'][] = [
            'type' => $type,
            'message' => $message
        ];
    }
} 