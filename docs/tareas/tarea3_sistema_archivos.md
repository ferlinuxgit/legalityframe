# Tarea 3: Configuración del sistema de archivos

## Objetivo
Crear la estructura de directorios según lo definido en `estructura.md`, configurar el sistema de autoloading y establecer los permisos adecuados.

## Pasos detallados

### 1. Acceso al servidor

- Acceder al servidor VPS vía SSH:
  ```bash
  ssh usuario@ip_del_servidor
  ```
- Navegar al directorio del dominio:
  ```bash
  cd /var/www/vhosts/legalityframe.com/
  ```

### 2. Creación de la estructura de directorios principal

- Crear la estructura de directorios base:
  ```bash
  mkdir -p httpdocs/{public,app,resources,config,database,tests,logs,storage,tools,vendor}
  ```

- Crear subdirectorios dentro de public:
  ```bash
  mkdir -p httpdocs/public/{assets/css,assets/js,assets/images,assets/fonts,assets/icons,uploads}
  ```

- Crear subdirectorios para la aplicación:
  ```bash
  mkdir -p httpdocs/app/{Config,Controllers,Models,Views,Helpers,Libraries,Services,Middlewares,Exceptions}
  ```

- Crear subdirectorios para controladores específicos:
  ```bash
  mkdir -p httpdocs/app/Controllers/{API,Auth}
  ```

- Crear subdirectorios para vistas:
  ```bash
  mkdir -p httpdocs/app/Views/{layouts,partials,home,scan,report,documents,user,auth,error}
  ```

- Crear subdirectorios para librerías:
  ```bash
  mkdir -p httpdocs/app/Libraries/{Scanner,LegalAnalysis,AIServices,PDF,Payment}
  ```

- Crear subdirectorios para recursos:
  ```bash
  mkdir -p httpdocs/resources/{css/components,js/components,lang}
  ```

- Crear subdirectorios para idiomas:
  ```bash
  mkdir -p httpdocs/resources/lang/{es,en,fr,de,it,pt,ca,eu,gl,nl,sv,pl}
  ```

- Crear subdirectorios para base de datos:
  ```bash
  mkdir -p httpdocs/database/{migrations,seeds}
  ```

- Crear subdirectorios para almacenamiento:
  ```bash
  mkdir -p httpdocs/storage/{app,cache,logs}
  ```

### 3. Creación de archivos iniciales

#### Archivo de entrada principal (public/index.php)

Crear el archivo `httpdocs/public/index.php`:

```php
<?php
/**
 * LegalityFrame - Entry Point
 * 
 * @package LegalityFrame
 */

// Define la ruta base de la aplicación
define('BASE_PATH', dirname(__DIR__));

// Cargar el autoloader de Composer
require_once BASE_PATH . '/vendor/autoload.php';

// Cargar las funciones de ayuda
require_once BASE_PATH . '/app/Helpers/functions.php';

// Cargar el archivo de configuración
$config = require_once BASE_PATH . '/config/app.php';

// Iniciar sesión
session_start();

// Inicializar la aplicación
$app = new \App\Config\App($config);

// Procesar la solicitud
$app->run();
```

#### Configuración de la aplicación (config/app.php)

Crear el archivo `httpdocs/config/app.php`:

```php
<?php

return [
    // Configuración general de la aplicación
    'app' => [
        'name' => 'LegalityFrame',
        'version' => '1.0.0',
        'environment' => 'development', // development, production
        'debug' => true,
        'timezone' => 'Europe/Madrid',
        'url' => 'https://legalityframe.com',
        'locale' => 'es',
        'available_locales' => ['es', 'en', 'fr', 'de', 'it', 'pt', 'ca', 'eu', 'gl'],
    ],
    
    // Configuración de base de datos
    'database' => [
        'driver' => 'mysql',
        'host' => 'localhost',
        'database' => 'legalityframe',
        'username' => 'lfuser',
        'password' => 'password', // Reemplazar con la contraseña real
        'charset' => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci',
    ],
    
    // Configuración de servicios externos
    'services' => [
        'openai' => [
            'api_key' => 'sk_', // Reemplazar con la clave real
            'model' => 'gpt-4-turbo',
        ],
        'stripe' => [
            'public_key' => 'pk_',
            'secret_key' => 'sk_',
            'webhook_secret' => 'whsec_',
        ],
    ],
    
    // Configuración de correo electrónico
    'mail' => [
        'driver' => 'smtp',
        'host' => 'smtp.example.com',
        'port' => 587,
        'username' => 'noreply@legalityframe.com',
        'password' => 'password',
        'encryption' => 'tls',
        'from_address' => 'noreply@legalityframe.com',
        'from_name' => 'LegalityFrame',
    ],
    
    // Configuración de caché
    'cache' => [
        'driver' => 'file',
        'path' => BASE_PATH . '/storage/cache',
    ],
    
    // Configuración de sesión
    'session' => [
        'driver' => 'file',
        'lifetime' => 120,
        'path' => BASE_PATH . '/storage/sessions',
    ],
    
    // Configuración de logs
    'log' => [
        'path' => BASE_PATH . '/logs',
        'level' => 'debug', // debug, info, warning, error, critical
    ],
];
```

#### Archivo .htaccess (public/.htaccess)

Crear el archivo `httpdocs/public/.htaccess`:

```
<IfModule mod_rewrite.c>
    # Activar el motor de reescritura
    RewriteEngine On
    
    # Redirigir de HTTP a HTTPS
    RewriteCond %{HTTPS} off
    RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
    
    # Si no es un directorio o archivo existente, redirigir a index.php
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>

# Compresión GZIP
<IfModule mod_deflate.c>
    AddOutputFilterByType DEFLATE text/html text/plain text/xml text/css application/javascript application/json
</IfModule>

# Cache control headers
<IfModule mod_expires.c>
    ExpiresActive On
    ExpiresByType image/jpg "access plus 1 year"
    ExpiresByType image/jpeg "access plus 1 year"
    ExpiresByType image/gif "access plus 1 year"
    ExpiresByType image/png "access plus 1 year"
    ExpiresByType text/css "access plus 1 month"
    ExpiresByType application/pdf "access plus 1 month"
    ExpiresByType text/javascript "access plus 1 month"
    ExpiresByType application/javascript "access plus 1 month"
    ExpiresByType application/x-javascript "access plus 1 month"
    ExpiresByType application/x-shockwave-flash "access plus 1 month"
    ExpiresByType image/x-icon "access plus 1 year"
    ExpiresDefault "access plus 2 days"
</IfModule>

# Proteger archivos sensibles
<FilesMatch "^\.">
    Order allow,deny
    Deny from all
</FilesMatch>
```

#### Clase App básica (app/Config/App.php)

Crear el archivo `httpdocs/app/Config/App.php`:

```php
<?php

namespace App\Config;

/**
 * App - Clase principal de la aplicación
 *
 * @package App\Config
 */
class App
{
    /**
     * @var array Configuración de la aplicación
     */
    protected $config;
    
    /**
     * Constructor
     *
     * @param array $config Configuración de la aplicación
     */
    public function __construct(array $config)
    {
        $this->config = $config;
        
        // Configurar zona horaria
        date_default_timezone_set($config['app']['timezone']);
        
        // Configurar gestión de errores
        $this->setupErrorHandling();
    }
    
    /**
     * Configurar gestión de errores
     */
    protected function setupErrorHandling()
    {
        if ($this->config['app']['debug']) {
            error_reporting(E_ALL);
            ini_set('display_errors', 1);
        } else {
            error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
            ini_set('display_errors', 0);
        }
        
        // Directorio de logs
        ini_set('error_log', $this->config['log']['path'] . '/error.log');
    }
    
    /**
     * Ejecutar la aplicación
     */
    public function run()
    {
        // Inicializar el router
        $router = new Router();
        
        // Cargar rutas
        require_once BASE_PATH . '/app/Config/Routes.php';
        
        // Procesar la solicitud
        $router->dispatch();
    }
}
```

#### Archivo de rutas básico (app/Config/Routes.php)

Crear el archivo `httpdocs/app/Config/Routes.php`:

```php
<?php

/**
 * Definición de rutas de la aplicación
 */

use App\Config\Router;

// Obtener la instancia del router
$router = Router::getInstance();

// Rutas públicas
$router->get('/', 'HomeController@index');
$router->get('/about', 'HomeController@about');
$router->get('/pricing', 'HomeController@pricing');
$router->get('/contact', 'HomeController@contact');

// Rutas de autenticación
$router->get('/login', 'Auth\\LoginController@showLoginForm');
$router->post('/login', 'Auth\\LoginController@login');
$router->get('/register', 'Auth\\RegisterController@showRegistrationForm');
$router->post('/register', 'Auth\\RegisterController@register');
$router->get('/password/reset', 'Auth\\PasswordController@showResetForm');
$router->post('/logout', 'Auth\\LoginController@logout');

// Rutas de escaneo
$router->get('/scan', 'ScanController@index');
$router->post('/scan', 'ScanController@create');
$router->get('/scan/{id}', 'ScanController@show');
$router->get('/scan/{id}/report', 'ReportController@show');
$router->get('/scan/{id}/technical', 'ReportController@technical');
$router->get('/scan/{id}/report/download', 'ReportController@download');

// Rutas para documentos legales
$router->get('/documents', 'DocumentController@index');
$router->get('/documents/generate', 'DocumentController@create');
$router->post('/documents/generate', 'DocumentController@store');
$router->get('/documents/{id}', 'DocumentController@show');
$router->get('/documents/{id}/download', 'DocumentController@download');
$router->get('/documents/{id}/preview', 'DocumentController@preview');

// Rutas para el panel de usuario
$router->get('/dashboard', 'UserController@dashboard');
$router->get('/profile', 'UserController@profile');
$router->post('/profile', 'UserController@update');
$router->get('/history', 'UserController@history');
$router->get('/alerts', 'AlertController@index');

// Rutas para API
$router->post('/api/scan', 'API\\ScannerAPIController@scan');
$router->get('/api/scan/{id}', 'API\\ScannerAPIController@getResults');
$router->post('/api/documents/generate', 'API\\DocumentAPIController@generate');
$router->get('/api/websites/{domain}/cookies', 'API\\ScannerAPIController@getCookies');

// Rutas para administración
$router->get('/admin', 'AdminController@index');
$router->get('/admin/users', 'AdminController@users');
$router->get('/admin/scans', 'AdminController@scans');
$router->get('/admin/payments', 'AdminController@payments');
```

#### Archivo de funciones auxiliares (app/Helpers/functions.php)

Crear el archivo `httpdocs/app/Helpers/functions.php`:

```php
<?php

/**
 * Funciones auxiliares globales
 */

if (!function_exists('base_path')) {
    /**
     * Obtener la ruta base de la aplicación
     * 
     * @param string $path Ruta relativa
     * @return string
     */
    function base_path($path = '')
    {
        return BASE_PATH . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }
}

if (!function_exists('config')) {
    /**
     * Obtener un valor de configuración
     * 
     * @param string $key Clave de configuración
     * @param mixed $default Valor por defecto
     * @return mixed
     */
    function config($key, $default = null)
    {
        $config = $GLOBALS['config'] ?? [];
        
        $parts = explode('.', $key);
        $value = $config;
        
        foreach ($parts as $part) {
            if (!isset($value[$part])) {
                return $default;
            }
            $value = $value[$part];
        }
        
        return $value;
    }
}

if (!function_exists('__')) {
    /**
     * Traducir un texto
     * 
     * @param string $key Clave de traducción
     * @param array $params Parámetros de sustitución
     * @return string
     */
    function __($key, $params = [])
    {
        // Implementación básica - se mejorará en la tarea 5
        return $key;
    }
}

if (!function_exists('url')) {
    /**
     * Generar una URL completa
     * 
     * @param string $path Ruta relativa
     * @return string
     */
    function url($path = '')
    {
        $baseUrl = rtrim(config('app.url'), '/');
        $path = ltrim($path, '/');
        
        return $path ? $baseUrl . '/' . $path : $baseUrl;
    }
}

if (!function_exists('asset')) {
    /**
     * Generar una URL para un activo
     * 
     * @param string $path Ruta relativa al directorio public
     * @return string
     */
    function asset($path)
    {
        return url($path);
    }
}

if (!function_exists('redirect')) {
    /**
     * Redireccionar a una URL
     * 
     * @param string $url URL a redireccionar
     * @param int $status Código de estado HTTP
     */
    function redirect($url, $status = 302)
    {
        header('Location: ' . $url, true, $status);
        exit;
    }
}
```

### 4. Configuración de Composer y autoloading

#### Crear composer.json

Crear el archivo `httpdocs/composer.json`:

```json
{
    "name": "legalityframe/legalityframe",
    "description": "Plataforma de auditoría legal para sitios web",
    "type": "project",
    "license": "proprietary",
    "authors": [
        {
            "name": "LegalityFrame Team",
            "email": "info@legalityframe.com"
        }
    ],
    "require": {
        "php": "^8.2",
        "ext-json": "*",
        "ext-mbstring": "*",
        "ext-curl": "*",
        "guzzlehttp/guzzle": "^7.0",
        "openai-php/client": "^0.6.0",
        "stripe/stripe-php": "^10.0",
        "tecnickcom/tcpdf": "^6.6",
        "symfony/dom-crawler": "^6.0",
        "symfony/css-selector": "^6.0",
        "phpmailer/phpmailer": "^6.8"
    },
    "require-dev": {
        "phpunit/phpunit": "^10.0",
        "phpstan/phpstan": "^1.10",
        "friendsofphp/php-cs-fixer": "^3.15"
    },
    "autoload": {
        "psr-4": {
            "App\\": "app/"
        },
        "files": [
            "app/Helpers/functions.php"
        ]
    },
    "autoload-dev": {
        "psr-4": {
            "Tests\\": "tests/"
        }
    },
    "scripts": {
        "post-install-cmd": [
            "@php -r \"file_exists('.env') || copy('.env.example', '.env');\""
        ],
        "test": "phpunit",
        "cs-fix": "php-cs-fixer fix",
        "analyze": "phpstan analyze"
    },
    "config": {
        "optimize-autoloader": true,
        "preferred-install": "dist",
        "sort-packages": true
    },
    "minimum-stability": "stable",
    "prefer-stable": true
}
```

#### Instalar dependencias

Instalar Composer si no está instalado:

```bash
cd /var/www/vhosts/legalityframe.com/httpdocs
curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer
```

Instalar las dependencias:

```bash
composer install --no-dev --optimize-autoloader
```

### 5. Configuración de permisos

Establecer los permisos adecuados:

```bash
# Permisos de directorios
find /var/www/vhosts/legalityframe.com/httpdocs -type d -exec chmod 755 {} \;

# Permisos de archivos
find /var/www/vhosts/legalityframe.com/httpdocs -type f -exec chmod 644 {} \;

# Permisos especiales para directorios que necesitan ser escribibles
chmod -R 775 /var/www/vhosts/legalityframe.com/httpdocs/storage/
chmod -R 775 /var/www/vhosts/legalityframe.com/httpdocs/logs/
chmod -R 775 /var/www/vhosts/legalityframe.com/httpdocs/public/uploads/

# Establecer el propietario correcto
chown -R webuser:psaserv /var/www/vhosts/legalityframe.com/httpdocs/
```

### 6. Configuración de variables de entorno

Crear el archivo de variables de entorno `httpdocs/.env.example`:

```
APP_NAME=LegalityFrame
APP_ENV=production
APP_DEBUG=false
APP_URL=https://legalityframe.com

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=legalityframe
DB_USERNAME=lfuser
DB_PASSWORD=your_password_here

OPENAI_API_KEY=your_api_key_here
OPENAI_MODEL=gpt-4-turbo

STRIPE_PUBLIC_KEY=your_public_key_here
STRIPE_SECRET_KEY=your_secret_key_here
STRIPE_WEBHOOK_SECRET=your_webhook_secret_here

MAIL_DRIVER=smtp
MAIL_HOST=smtp.example.com
MAIL_PORT=587
MAIL_USERNAME=noreply@legalityframe.com
MAIL_PASSWORD=your_mail_password_here
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@legalityframe.com
MAIL_FROM_NAME="${APP_NAME}"
```

Crear el archivo de variables de entorno real `httpdocs/.env` con los valores correctos.

### 7. Configuración de assets frontend

#### Configurar Tailwind CSS

Crear el archivo `httpdocs/tailwind.config.js`:

```javascript
/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./public/**/*.{html,php}",
    "./app/Views/**/*.php",
  ],
  theme: {
    extend: {
      colors: {
        primary: {
          50: '#f0f9ff',
          100: '#e0f2fe',
          200: '#bae6fd',
          300: '#7dd3fc',
          400: '#38bdf8',
          500: '#0ea5e9',
          600: '#0284c7',
          700: '#0369a1',
          800: '#075985',
          900: '#0c4a6e',
          950: '#082f49',
        },
        secondary: {
          50: '#f5f3ff',
          100: '#ede9fe',
          200: '#ddd6fe',
          300: '#c4b5fd',
          400: '#a78bfa',
          500: '#8b5cf6',
          600: '#7c3aed',
          700: '#6d28d9',
          800: '#5b21b6',
          900: '#4c1d95',
          950: '#2e1065',
        },
      },
      fontFamily: {
        sans: ['Inter', 'sans-serif'],
        heading: ['Montserrat', 'sans-serif'],
      },
    },
  },
  plugins: [
    require('@tailwindcss/forms'),
  ],
}
```

#### Crear archivo CSS principal

Crear el archivo `httpdocs/resources/css/app.css`:

```css
@tailwind base;
@tailwind components;
@tailwind utilities;

/* Estilos personalizados */
@layer components {
  .btn {
    @apply px-4 py-2 rounded font-medium transition duration-150 ease-in-out;
  }
  
  .btn-primary {
    @apply bg-primary-600 text-white hover:bg-primary-700 focus:ring-2 focus:ring-primary-500 focus:ring-offset-2;
  }
  
  .btn-secondary {
    @apply bg-secondary-600 text-white hover:bg-secondary-700 focus:ring-2 focus:ring-secondary-500 focus:ring-offset-2;
  }
  
  .form-input {
    @apply block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500;
  }
}
```

### 8. Verificación de la estructura

Verificar que la estructura de directorios se ha creado correctamente:

```bash
find /var/www/vhosts/legalityframe.com/httpdocs -type d | sort
```

Verificar la estructura de archivos principales:

```bash
find /var/www/vhosts/legalityframe.com/httpdocs -type f -name "*.php" | sort
```

### 9. Actualización del documento root en Plesk

En el panel Plesk:
1. Ir a Dominios > legalityframe.com
2. Ir a Configuración web
3. Cambiar "Documento root" a `/httpdocs/public`
4. Guardar cambios

## Resultados esperados

- Estructura de directorios completa según la definición en `estructura.md`
- Archivos base creados para el funcionamiento inicial de la aplicación
- Sistema de autoloading configurado con Composer
- Permisos establecidos correctamente para todos los directorios y archivos
- Configuración básica de entorno y variables de entorno
- Sistema preparado para implementar el enrutamiento y la lógica de la aplicación

## Problemas comunes y soluciones

### Error de permisos

- Verificar que los directorios que necesitan escritura tengan los permisos correctos
- Comprobar que los archivos pertenezcan al usuario correcto del servidor web

### Error de autoloading

- Verificar que se ha ejecutado `composer dump-autoload` después de crear nuevos archivos
- Comprobar que los espacios de nombres siguen la estructura PSR-4

### Error de routing

- Verificar que el documento root apunta al directorio `public`
- Comprobar que el archivo `.htaccess` está configurado correctamente

### Error de configuración

- Verificar que los archivos de configuración están correctamente formateados
- Comprobar que las variables de entorno están definidas en `.env` 