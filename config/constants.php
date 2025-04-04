<?php

/**
 * Constantes globales de la aplicación
 */

// Versión de la aplicación
define('APP_VERSION', '1.0.0');

// Estado de escaneo
define('SCAN_STATUS_PENDING', 'pending');
define('SCAN_STATUS_PROCESSING', 'processing');
define('SCAN_STATUS_COMPLETED', 'completed');
define('SCAN_STATUS_FAILED', 'failed');

// Estado de documento
define('DOCUMENT_STATUS_DRAFT', 'draft');
define('DOCUMENT_STATUS_PUBLISHED', 'published');

// Tipos de documento
define('DOCUMENT_TYPE_PRIVACY', 'privacy_policy');
define('DOCUMENT_TYPE_COOKIES', 'cookie_policy');
define('DOCUMENT_TYPE_TERMS', 'terms_conditions');
define('DOCUMENT_TYPE_LEGAL_NOTICE', 'legal_notice');

// Categorías de cookie
define('COOKIE_CATEGORY_ESSENTIAL', 'essential');
define('COOKIE_CATEGORY_PREFERENCE', 'preference');
define('COOKIE_CATEGORY_STATISTICS', 'statistics');
define('COOKIE_CATEGORY_MARKETING', 'marketing');
define('COOKIE_CATEGORY_UNKNOWN', 'unknown');

// Roles de usuario
define('USER_ROLE_ADMIN', 'admin');
define('USER_ROLE_USER', 'user');

// Estados de usuario
define('USER_STATUS_ACTIVE', 'active');
define('USER_STATUS_INACTIVE', 'inactive');
define('USER_STATUS_SUSPENDED', 'suspended');

// Estado de pago
define('PAYMENT_STATUS_PENDING', 'pending');
define('PAYMENT_STATUS_COMPLETED', 'completed');
define('PAYMENT_STATUS_FAILED', 'failed');
define('PAYMENT_STATUS_REFUNDED', 'refunded');

// Niveles de alerta
define('ALERT_LEVEL_INFO', 'info');
define('ALERT_LEVEL_WARNING', 'warning');
define('ALERT_LEVEL_DANGER', 'danger');

// Idiomas disponibles
define('AVAILABLE_LANGUAGES', [
    'es' => 'Español',
    'en' => 'English',
]); 