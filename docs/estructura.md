# Estructura de archivos de LegalityFrame

Este documento describe la estructura de archivos completa del proyecto LegalityFrame, optimizada para despliegue en un entorno Plesk con PHP 8.2, MySQL 8.0, Tailwind CSS y Alpine.js.

## Estructura principal

```
legalityframe/
├── public/                 # Directorio público accesible por web
│   ├── index.php           # Punto de entrada principal
│   ├── assets/             # Recursos estáticos
│   │   ├── css/            # Estilos compilados y optimizados
│   │   │   ├── app.css     # Estilos principales compilados con Tailwind
│   │   │   └── print.css   # Estilos específicos para impresión
│   │   ├── js/             # Scripts JavaScript
│   │   │   ├── app.js      # JavaScript principal
│   │   │   ├── alpine.min.js    # Alpine.js minificado
│   │   │   └── modules/    # Módulos JS específicos
│   │   │       ├── scanner.js       # Módulo de escaneo web
│   │   │       ├── pdf-generator.js # Generador de PDFs
│   │   │       ├── cookie-analyzer.js   # Analizador de cookies
│   │   │       └── payment.js       # Integración con Stripe
│   │   ├── images/         # Imágenes y gráficos
│   │   ├── fonts/          # Fuentes web
│   │   └── icons/          # Iconos SVG
│   ├── uploads/            # Archivos subidos por usuarios (privado vía .htaccess)
│   └── .htaccess           # Configuración de Apache
├── app/                    # Lógica principal de la aplicación
│   ├── Config/             # Configuración de la aplicación
│   │   ├── App.php         # Configuración general
│   │   ├── Database.php    # Configuración de base de datos
│   │   ├── Routes.php      # Definición de rutas
│   │   ├── Security.php    # Configuración de seguridad
│   │   └── Services.php    # Servicios externos (OpenAI, Stripe, etc.)
│   ├── Controllers/        # Controladores MVC
│   │   ├── BaseController.php      # Controlador base
│   │   ├── HomeController.php      # Controlador de inicio
│   │   ├── ScanController.php      # Controlador de escaneo
│   │   ├── ReportController.php    # Controlador de informes
│   │   ├── DocumentController.php  # Controlador de documentos legales
│   │   ├── PaymentController.php   # Controlador de pagos
│   │   ├── API/                    # Controladores de API
│   │   │   ├── ScannerAPIController.php
│   │   │   └── DocumentAPIController.php
│   │   └── Auth/                   # Controladores de autenticación
│   │       ├── LoginController.php
│   │       └── RegisterController.php
│   ├── Models/             # Modelos de datos
│   │   ├── UserModel.php             # Modelo de usuarios
│   │   ├── WebsiteModel.php          # Modelo de sitios web escaneados
│   │   ├── ScanModel.php             # Modelo de escaneos
│   │   ├── DocumentModel.php         # Modelo de documentos legales
│   │   ├── CookieModel.php           # Modelo de cookies detectadas
│   │   ├── TrackerModel.php          # Modelo de rastreadores
│   │   ├── PaymentModel.php          # Modelo de pagos
│   │   └── AlertModel.php            # Modelo de alertas y notificaciones
│   ├── Views/              # Vistas y templates
│   │   ├── layouts/                  # Layouts principales
│   │   │   ├── default.php           # Layout por defecto
│   │   │   ├── dashboard.php         # Layout para dashboard
│   │   │   └── pdf.php               # Layout para PDFs
│   │   ├── partials/                 # Componentes parciales
│   │   │   ├── header.php            # Cabecera
│   │   │   ├── footer.php            # Pie de página
│   │   │   ├── navigation.php        # Navegación
│   │   │   └── alerts.php            # Alertas y notificaciones
│   │   ├── home/                     # Vistas de inicio
│   │   │   ├── index.php             # Página principal
│   │   │   ├── pricing.php           # Precios
│   │   │   └── about.php             # Acerca de
│   │   ├── scan/                     # Vistas de escaneo
│   │   │   ├── form.php              # Formulario de escaneo
│   │   │   ├── results.php           # Resultados del escaneo
│   │   │   └── technical.php         # Análisis técnico
│   │   ├── report/                   # Vistas de informes
│   │   │   ├── overview.php          # Resumen del informe
│   │   │   ├── details.php           # Detalles del informe
│   │   │   └── pdf_template.php      # Plantilla para PDF
│   │   ├── documents/                # Vistas de documentos legales
│   │   │   ├── generator.php         # Generador de documentos
│   │   │   ├── preview.php           # Vista previa
│   │   │   └── download.php          # Descarga
│   │   ├── user/                     # Vistas de usuario
│   │   │   ├── dashboard.php         # Panel de control
│   │   │   ├── profile.php           # Perfil
│   │   │   └── history.php           # Historial
│   │   ├── auth/                     # Vistas de autenticación
│   │   │   ├── login.php             # Inicio de sesión
│   │   │   └── register.php          # Registro
│   │   └── error/                    # Vistas de error
│   │       ├── 404.php               # Error 404
│   │       └── 500.php               # Error 500
│   ├── Helpers/            # Funciones auxiliares
│   │   ├── FormHelper.php            # Ayudante para formularios
│   │   ├── TextHelper.php            # Ayudante para manipulación de texto
│   │   ├── ScanHelper.php            # Ayudante para escaneo
│   │   ├── PDFHelper.php             # Ayudante para generar PDFs
│   │   ├── LanguageHelper.php        # Ayudante para multiidioma
│   │   └── SecurityHelper.php        # Ayudante para seguridad
│   ├── Libraries/          # Librerías personalizadas
│   │   ├── Scanner/                  # Librería de escaneo
│   │   │   ├── WebScanner.php        # Escáner de sitios web
│   │   │   ├── CookieAnalyzer.php    # Analizador de cookies
│   │   │   └── TrackingDetector.php  # Detector de rastreadores
│   │   ├── LegalAnalysis/            # Análisis legal
│   │   │   ├── DocumentAnalyzer.php  # Analizador de documentos
│   │   │   ├── ComplianceChecker.php # Verificador de cumplimiento
│   │   │   └── LegalRequirements.php # Requisitos legales
│   │   ├── AIServices/               # Servicios de IA
│   │   │   ├── OpenAIService.php     # Servicio de OpenAI
│   │   │   ├── PromptGenerator.php   # Generador de prompts
│   │   │   └── ContentAnalyzer.php   # Analizador de contenido
│   │   ├── PDF/                      # Generación de PDFs
│   │   │   ├── PDFGenerator.php      # Generador de PDFs
│   │   │   └── ReportBuilder.php     # Constructor de informes
│   │   └── Payment/                  # Procesamiento de pagos
│   │       └── StripeService.php     # Servicio de Stripe
│   ├── Services/           # Servicios de aplicación
│   │   ├── ScanService.php           # Servicio de escaneo
│   │   ├── ReportService.php         # Servicio de informes
│   │   ├── DocumentService.php       # Servicio de documentos
│   │   ├── UserService.php           # Servicio de usuarios
│   │   ├── EmailService.php          # Servicio de emails
│   │   └── AlertService.php          # Servicio de alertas
│   ├── Middlewares/        # Middlewares
│   │   ├── AuthMiddleware.php        # Middleware de autenticación
│   │   ├── CSRFMiddleware.php        # Middleware CSRF
│   │   ├── RateLimitMiddleware.php   # Middleware de límite de peticiones
│   │   └── LocaleMiddleware.php      # Middleware de localización
│   └── Exceptions/         # Excepciones personalizadas
│       ├── APIException.php          # Excepciones de API
│       ├── ScanException.php         # Excepciones de escaneo
│       └── PaymentException.php      # Excepciones de pago
├── resources/              # Recursos de desarrollo
│   ├── css/                # Archivos fuente CSS
│   │   ├── app.css               # Archivo principal
│   │   └── components/           # Componentes CSS
│   ├── js/                 # Archivos fuente JavaScript
│   │   ├── app.js                # Archivo principal
│   │   └── components/           # Componentes JS
│   └── lang/               # Archivos de traducción
│       ├── es/                   # Español
│       │   ├── general.php       # Traducciones generales
│       │   ├── validation.php    # Mensajes de validación
│       │   └── legal.php         # Términos legales
│       ├── en/                   # Inglés
│       ├── fr/                   # Francés
│       ├── de/                   # Alemán
│       └── ...                   # Otros idiomas
├── database/               # Recursos de base de datos
│   ├── migrations/               # Migraciones
│   │   ├── 001_create_users.php
│   │   ├── 002_create_websites.php
│   │   └── ...
│   └── seeds/                    # Datos de prueba
│       ├── UserSeeder.php
│       └── ...
├── config/                # Configuración del entorno
│   ├── app.php                   # Configuración global
│   ├── database.php              # Configuración de BD
│   ├── services.php              # Configuración de servicios externos
│   └── .env                      # Variables de entorno (no en control de versiones)
├── tests/                 # Tests automatizados
│   ├── Unit/                     # Tests unitarios
│   ├── Integration/              # Tests de integración
│   └── E2E/                      # Tests end-to-end
├── logs/                  # Registros de la aplicación
│   ├── error.log                 # Registro de errores
│   ├── access.log                # Registro de accesos
│   └── payment.log               # Registro de pagos
├── storage/               # Almacenamiento privado
│   ├── app/                      # Archivos de aplicación
│   ├── cache/                    # Archivos de caché
│   └── logs/                     # Logs adicionales
├── vendor/                # Dependencias (Composer)
├── node_modules/          # Dependencias JavaScript (npm)
├── tools/                 # Herramientas y scripts
│   ├── setup.php                 # Script de configuración
│   ├── deploy.php                # Script de despliegue
│   └── maintenance.php           # Script de mantenimiento
├── composer.json          # Dependencias PHP
├── package.json           # Dependencias JavaScript
├── tailwind.config.js     # Configuración de Tailwind
├── webpack.config.js      # Configuración de Webpack
├── .htaccess              # Configuración de Apache
├── .gitignore             # Archivos ignorados por Git
└── README.md              # Documentación del proyecto
```

## Estructura de la base de datos

```
+---------------------+       +----------------------+       +----------------------+
| users               |       | websites             |       | scans                |
+---------------------+       +----------------------+       +----------------------+
| id                  |<----->| id                   |<----->| id                   |
| name                |       | user_id              |       | website_id           |
| email               |       | domain               |       | date                 |
| password            |       | created_at           |       | status               |
| status              |       | updated_at           |       | results_json         |
| last_login          |       | settings_json        |       | report_path          |
| created_at          |       +----------------------+       | created_at           |
| updated_at          |                                      | updated_at           |
+---------------------+                                      +----------------------+
        |                                                              |
        |                                                              |
        v                                                              v
+---------------------+       +----------------------+       +----------------------+
| payments            |       | documents            |       | cookies              |
+---------------------+       +----------------------+       +----------------------+
| id                  |       | id                   |       | id                   |
| user_id             |       | scan_id              |       | scan_id              |
| amount              |       | type                 |       | name                 |
| currency            |       | language             |       | domain               |
| status              |       | content              |       | category             |
| payment_method      |       | generated_at         |       | duration             |
| transaction_id      |       | downloaded_at        |       | purpose              |
| created_at          |       | created_at           |       | is_essential         |
| updated_at          |       | updated_at           |       | created_at           |
+---------------------+       +----------------------+       +----------------------+
                                                                      |
                                                                      |
                                                                      v
+---------------------+       +----------------------+       +----------------------+
| alerts              |       | trackers             |       | form_analysis        |
+---------------------+       +----------------------+       +----------------------+
| id                  |       | id                   |       | id                   |
| user_id             |       | scan_id              |       | scan_id              |
| website_id          |       | name                 |       | form_url             |
| type                |       | category             |       | has_checkboxes       |
| message             |       | url                  |       | has_privacy_link     |
| read                |       | purpose              |       | compliant            |
| created_at          |       | compliance_status    |       | created_at           |
| updated_at          |       | created_at           |       | created_at           |
+---------------------+       +----------------------+       +----------------------+
```

## Rutas principales

```
# Rutas públicas
GET  /                           # Página de inicio
GET  /about                      # Acerca de
GET  /pricing                    # Precios
GET  /contact                    # Contacto
GET  /blog                       # Blog
GET  /legal/{document}           # Documentos legales del sitio

# Autenticación
GET  /login                      # Página de inicio de sesión
POST /login                      # Procesar inicio de sesión
GET  /register                   # Página de registro
POST /register                   # Procesar registro
GET  /password/reset             # Restablecer contraseña
POST /logout                     # Cerrar sesión

# Escáner
GET  /scan                       # Formulario de escaneo
POST /scan                       # Procesar escaneo
GET  /scan/{id}                  # Ver resultados de escaneo
GET  /scan/{id}/report           # Ver informe completo
GET  /scan/{id}/technical        # Ver análisis técnico
GET  /scan/{id}/report/download  # Descargar informe PDF

# Documentos legales
GET  /documents                  # Listado de documentos
GET  /documents/generate         # Generador de documentos
POST /documents/generate         # Procesar generación
GET  /documents/{id}             # Ver documento
GET  /documents/{id}/download    # Descargar documento
GET  /documents/{id}/preview     # Vista previa del documento

# Panel de usuario
GET  /dashboard                  # Panel principal
GET  /profile                    # Perfil de usuario
POST /profile                    # Actualizar perfil
GET  /history                    # Historial de escaneos
GET  /alerts                     # Alertas y notificaciones

# API
POST /api/scan                   # API de escaneo
GET  /api/scan/{id}              # Obtener resultados de escaneo
POST /api/documents/generate     # API de generación de documentos
GET  /api/websites/{domain}/cookies # Obtener cookies de un dominio

# Admin (acceso restringido)
GET  /admin                      # Panel de administración
GET  /admin/users                # Gestión de usuarios
GET  /admin/scans                # Gestión de escaneos
GET  /admin/payments             # Gestión de pagos
GET  /admin/stats                # Estadísticas
```

## Configuración del entorno Plesk

### Requisitos del servidor

- PHP 8.2 o superior
- MySQL 8.0 o superior
- Extensiones PHP requeridas:
  - mysqli
  - curl
  - gd
  - mbstring
  - xml
  - zip
  - intl
  - opcache
  - apcu
- Módulos Apache:
  - mod_rewrite
  - mod_headers
  - mod_ssl

### Configuración recomendada en Plesk

1. **Crear base de datos**:
   - Nombre: `legalityframe`
   - Cotejamiento: `utf8mb4_unicode_ci`
   - Usuario con todos los privilegios

2. **Configuración PHP**:
   - Versión: PHP 8.2
   - Modo: PHP-FPM
   - Límite de memoria: 256M mínimo
   - Tiempo máximo de ejecución: 120 segundos
   - Tamaño máximo de subida: 16M

3. **Configuración Apache**:
   - Activar HTTPS con Let's Encrypt
   - Redirigir HTTP a HTTPS
   - Habilitar compresión GZIP
   - Activar HTTP/2

4. **Programar tareas (Cron)**:
   - Actualización de datos legales: Diariamente
   - Limpieza de archivos temporales: Cada 6 horas
   - Backup de base de datos: Diaria
   - Monitoreo de cambios legislativos: Semanal
   - Escaneo de seguridad: Semanal

## Instrucciones de despliegue

1. Clonar el repositorio en el directorio web:
   ```bash
   git clone https://github.com/tuorganizacion/legalityframe.git /var/www/vhosts/tudominio.com/httpdocs/
   ```

2. Instalar dependencias PHP:
   ```bash
   cd /var/www/vhosts/tudominio.com/httpdocs/
   composer install --no-dev --optimize-autoloader
   ```

3. Instalar dependencias JavaScript y compilar assets:
   ```bash
   npm install
   npm run production
   ```

4. Configurar el archivo `.env`:
   ```bash
   cp config/.env.example config/.env
   nano config/.env
   ```

5. Ejecutar migraciones de base de datos:
   ```bash
   php tools/migrate.php
   ```

6. Configurar permisos:
   ```bash
   chmod -R 755 public/
   chmod -R 775 storage/ logs/ public/uploads/
   chown -R webuser:psaserv storage/ logs/ public/uploads/
   ```

7. Configurar el sitio en Plesk:
   - Apuntar el documento raíz a la carpeta `public/`
   - Activar HTTPS con Let's Encrypt
   - Configurar PHP según las recomendaciones anteriores

8. Ejecutar script de configuración inicial:
   ```bash
   php tools/setup.php
   ```

9. Verificar la instalación:
   ```bash
   php tools/test.php
   ``` 