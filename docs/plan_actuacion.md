# Plan de actuación para desarrollo de LegalityFrame

Este documento detalla las 50 tareas necesarias para crear una versión completamente funcional de LegalityFrame, organizadas por fases y con especificación de los métodos y campos de la base de datos que se utilizarán.

# Estado de implementación

## Resumen de progreso
- **Fecha de inicio**: 23/05/2024
- **Tareas completadas**: 5/50
- **Fases completadas**: 1/10
- **Progreso general**: 10%

## Estado por fase
| Fase | Nombre | Estado | Progreso |
|------|--------|--------|----------|
| 1 | Configuración del entorno y estructura base | Completado | 5/5 |
| 2 | Sistema de autenticación y gestión de usuarios | Pendiente | 0/5 |
| 3 | Frontend principal | Pendiente | 0/5 |
| 4 | Motor de escaneo básico | Pendiente | 0/5 |
| 5 | Integración con OpenAI | Pendiente | 0/5 |
| 6 | Presentación de resultados | Pendiente | 0/5 |
| 7 | Generación de documentos legales | Pendiente | 0/5 |
| 8 | Sistema de pagos | Pendiente | 0/5 |
| 9 | Dashboard y alertas | Pendiente | 0/5 |
| 10 | Optimización y finalización | Pendiente | 0/5 |

## Registro de tareas completadas
| # | Tarea | Fecha | Notas |
|---|-------|-------|-------|
| 1 | Configuración del entorno Plesk | 23/05/2024 | Configuración completa del servidor Plesk con PHP 8.2, MySQL 8.0 y certificados SSL |
| 2 | Creación de la estructura de base de datos | 23/05/2024 | Implementación de todas las tablas con índices y relaciones según el diseño |
| 3 | Configuración del sistema de archivos | 23/05/2024 | Estructura de directorios creada, sistema de autoloading configurado |
| 4 | Configuración del sistema de rutas | 24/05/2024 | Creación del Router principal, controladores base y middleware esencial |
| 5 | Implementación de sistema multiidioma | 24/05/2024 | Sistema de traducciones con soporte para español e inglés, middleware de localización |


## Fase 1: Configuración del entorno y estructura base (Tareas 1-5)

### Tarea 1: Configuración del entorno Plesk
- Crear la base de datos MySQL 8.0 con cotejamiento utf8mb4_unicode_ci
- Configurar PHP 8.2 con extensiones requeridas
- Configurar directorios y permisos
- Configurar certificados SSL
- **Campos BD**: N/A
- **Métodos**: N/A
- **Estado**: Completado
- **Responsable**: Administrador de sistemas
- **Documentación**: [Tarea 1: Configuración del entorno Plesk](docs/tareas/tarea1_config_plesk.md)

### Tarea 2: Creación de la estructura de base de datos
- Ejecutar los scripts SQL para crear todas las tablas definidas
- Crear índices y restricciones
- Configurar procedimientos almacenados
- **Tablas**: Todas las definidas en `database.md`
- **Métodos**: Ejecución directa de scripts SQL
- **Estado**: Completado
- **Responsable**: Desarrollador backend
- **Documentación**: [Tarea 2: Creación de la estructura de base de datos](docs/tareas/tarea2_estructura_bd.md)

### Tarea 3: Configuración del sistema de archivos
- Crear la estructura de directorios según `estructura.md`
- Configurar sistema de autoloading
- Establecer permisos
- **Campos BD**: N/A
- **Métodos**: N/A
- **Estado**: Completado
- **Responsable**: Desarrollador backend
- **Documentación**: [Tarea 3: Configuración del sistema de archivos](docs/tareas/tarea3_sistema_archivos.md)

### Tarea 4: Configuración del sistema de rutas
- Crear archivo de rutas principal
- Definir controladores base
- Implementar middleware básico
- **Campos BD**: N/A
- **Métodos**: Definir las rutas para todas las funcionalidades
- **Estado**: Completado
- **Responsable**: Desarrollador backend
- **Documentación**: [Tarea 4: Configuración del sistema de rutas](docs/tareas/tarea4_sistema_rutas.md)

### Tarea 5: Implementación de sistema multiidioma
- Crear archivos de idioma para español e inglés
- Implementar sistema de detección de idioma
- Configurar middleware de localización
- **Campos BD**: `users.language`
- **Métodos**: Sistema de traducciones basado en archivos PHP
- **Estado**: Completado
- **Responsable**: Desarrollador backend
- **Documentación**: [Tarea 5: Implementación de sistema multiidioma](docs/tareas/tarea5_sistema_multiidioma.md)

## Fase 2: Sistema de autenticación y gestión de usuarios (Tareas 6-10)

### Tarea 6: Implementación de registro de usuarios
- Crear formulario de registro
- Validación de datos
- Almacenamiento en base de datos
- Envío de email de verificación
- **Campos BD**: `users.id`, `users.name`, `users.email`, `users.password`, `users.status`, `users.email_verified_at`, `verification_tokens.*`
- **Métodos**: `RegisterController::showRegistrationForm()`, `RegisterController::register()`, `RegisterController::showVerificationNotice()`, `VerificationController::verify()`, `VerificationController::resend()`
- **Estado**: Completado
- **Responsable**: Desarrollador backend
- **Documentación**: [Tarea 6: Implementación de Registro de Usuarios](docs/tareas/tarea6_registro_usuarios.md)

### Tarea 7: Implementación de inicio de sesión
- Crear formulario de login
- Validación de credenciales
- Gestión de sesiones
- **Campos BD**: `users.email`, `users.password`, `users.last_login`, `sessions.*`
- **Métodos**: `AuthController::login()`, `AuthController::authenticate()`
- **Estado**: Pendiente
- **Responsable**: Desarrollador backend

### Tarea 8: Implementación de recuperación de contraseña
- Crear formulario de recuperación
- Generación y envío de tokens
- Reset de contraseña
- **Campos BD**: `password_resets.email`, `password_resets.token`, `users.password`
- **Métodos**: `PasswordController::forgot()`, `PasswordController::reset()`

### Tarea 9: Implementación de perfil de usuario
- Crear página de perfil
- Edición de datos personales
- Cambio de contraseña
- **Campos BD**: `users.name`, `users.email`, `users.password`, `users.language`
- **Métodos**: `UserController::profile()`, `UserController::update()`

### Tarea 10: Sistema de roles y permisos
- Implementar validación de roles
- Middleware de autorización
- **Campos BD**: `users.role`
- **Métodos**: `AuthMiddleware::handle()`, `checkRole()`, `hasPermission()`

## Fase 3: Frontend principal (Tareas 11-15)

### Tarea 11: Implementación de layouts principales
- Crear layout principal
- Crear layout de dashboard
- Crear componentes compartidos (header, footer, nav)
- **Campos BD**: N/A
- **Métodos**: N/A (Archivos de vista)

### Tarea 12: Implementación de página principal
- Diseñar landing page
- Implementar CTA principal
- Responsive design con Tailwind
- **Campos BD**: N/A
- **Métodos**: `HomeController::index()`

### Tarea 13: Implementación de página de precios
- Diseñar página de precios
- Mostrar planes disponibles
- **Campos BD**: N/A (versión estática inicial)
- **Métodos**: `HomeController::pricing()`

### Tarea 14: Implementación de páginas informativas
- Crear página "Acerca de"
- Crear página "Contacto"
- Crear página de preguntas frecuentes
- **Campos BD**: N/A (versión estática inicial)
- **Métodos**: `HomeController::about()`, `HomeController::contact()`, `HomeController::faq()`

### Tarea 15: Implementación de formularios de contacto
- Crear formulario de contacto
- Validación y envío de mensajes
- **Campos BD**: N/A (almacenamiento en sistema externo)
- **Métodos**: `ContactController::index()`, `ContactController::send()`

## Fase 4: Motor de escaneo básico (Tareas 16-20)

### Tarea 16: Implementación del formulario de escaneo
- Crear formulario de entrada de dominio
- Validación de dominio
- Creación de registro de escaneo
- **Campos BD**: `websites.domain`, `websites.user_id`, `scans.website_id`, `scans.status`
- **Métodos**: `ScanController::index()`, `ScanController::create()`

### Tarea 17: Implementación del motor de scraping
- Desarrollar clase para scraping de sitios web
- Detectar textos legales existentes
- **Campos BD**: `scans.website_id`, `scans.status`, `scans.progress`
- **Métodos**: `WebScanner::scan()`, `WebScanner::findLegalPages()`

### Tarea 18: Detector de cookies básico
- Implementar detección de cookies
- Clasificación automática
- **Campos BD**: `cookies.scan_id`, `cookies.name`, `cookies.domain`, `cookies.category`, `cookies.duration`, `cookies.is_essential`
- **Métodos**: `CookieAnalyzer::detect()`, `CookieAnalyzer::classify()`

### Tarea 19: Detector de trackers
- Implementar detección de scripts de seguimiento
- Identificación de proveedores
- **Campos BD**: `trackers.scan_id`, `trackers.name`, `trackers.category`, `trackers.url`, `trackers.provider`
- **Métodos**: `TrackingDetector::analyze()`, `TrackingDetector::identifyProvider()`

### Tarea 20: Detector de formularios
- Implementar análisis de formularios en la web
- Verificar cumplimiento normativo
- **Campos BD**: `form_analysis.scan_id`, `form_analysis.form_url`, `form_analysis.form_type`, `form_analysis.has_checkboxes`, `form_analysis.has_privacy_link`
- **Métodos**: `FormAnalyzer::detect()`, `FormAnalyzer::checkCompliance()`

## Fase 5: Integración con OpenAI (Tareas 21-25)

### Tarea 21: Configuración del servicio de OpenAI
- Implementar clase de servicio para OpenAI
- Configurar API keys y autenticación
- **Campos BD**: N/A (configuración en variables de entorno)
- **Métodos**: `OpenAIService::initialize()`, `OpenAIService::request()`

### Tarea 22: Generador de prompts
- Crear sistema de generación de prompts para análisis
- Templates específicos por tipo de análisis
- **Campos BD**: N/A (lógica interna)
- **Métodos**: `PromptGenerator::forPrivacyPolicy()`, `PromptGenerator::forCookiePolicy()`, `PromptGenerator::forLegalNotice()`

### Tarea 23: Analizador de políticas de privacidad
- Implementar análisis de políticas existentes
- Detección de cumplimiento RGPD y otras normativas
- **Campos BD**: `legal_requirements.jurisdiction`, `legal_requirements.category`, `scan_compliance.legal_requirement_id`, `scan_compliance.status`
- **Métodos**: `DocumentAnalyzer::analyzePrivacyPolicy()`, `ComplianceChecker::checkPrivacyRequirements()`

### Tarea 24: Analizador de políticas de cookies
- Implementar análisis de políticas de cookies existentes
- Verificar conformidad con ePrivacy y RGPD
- **Campos BD**: `legal_requirements.jurisdiction`, `legal_requirements.category`, `scan_compliance.legal_requirement_id`, `scan_compliance.status`
- **Métodos**: `DocumentAnalyzer::analyzeCookiePolicy()`, `ComplianceChecker::checkCookieRequirements()`

### Tarea 25: Analizador de avisos legales
- Implementar análisis de avisos legales
- Verificar requisitos LSSI-CE
- **Campos BD**: `legal_requirements.jurisdiction`, `legal_requirements.category`, `scan_compliance.legal_requirement_id`, `scan_compliance.status`
- **Métodos**: `DocumentAnalyzer::analyzeLegalNotice()`, `ComplianceChecker::checkLegalRequirements()`

## Fase 6: Presentación de resultados (Tareas 26-30)

### Tarea 26: Implementación de página de resultados de escaneo
- Crear vista resumen de resultados
- Visualización de puntuación general
- **Campos BD**: `scans.score`, `scans.results_json`
- **Métodos**: `ReportController::overview()`, `ScanService::calculateScore()`

### Tarea 27: Visualización de análisis de documentos legales
- Implementar sección de análisis de documentos
- Mostrar errores y sugerencias
- **Campos BD**: `scan_compliance.status`, `scan_compliance.details`, `scan_compliance.recommendation`
- **Métodos**: `ReportController::documentAnalysis()`, `ReportService::getDocumentIssues()`

### Tarea 28: Visualización de análisis de cookies y trackers
- Crear vista de cookies detectadas
- Mostrar rastreadores y su cumplimiento
- **Campos BD**: `cookies.*`, `trackers.*`
- **Métodos**: `ReportController::cookiesAnalysis()`, `ReportController::trackersAnalysis()`

### Tarea 29: Visualización de análisis de formularios
- Mostrar formularios detectados
- Indicar problemas de cumplimiento
- **Campos BD**: `form_analysis.*`
- **Métodos**: `ReportController::formsAnalysis()`, `FormAnalysisService::getIssues()`

### Tarea 30: Implementación de generador de PDF
- Crear servicio de generación de PDFs
- Diseñar plantilla de informe
- **Campos BD**: `scans.report_path`
- **Métodos**: `PDFGenerator::generate()`, `ReportController::downloadPdf()`

## Fase 7: Generación de documentos legales (Tareas 31-35)

### Tarea 31: Implementación de generador de políticas de privacidad
- Crear servicio de generación de documentos
- Personalización por parámetros
- **Campos BD**: `documents.type`, `documents.language`, `documents.content`, `legal_texts.*`
- **Métodos**: `DocumentService::generatePrivacyPolicy()`, `DocumentController::createPrivacyPolicy()`

### Tarea 32: Implementación de generador de políticas de cookies
- Generar política adaptada a cookies detectadas
- Personalización multiidioma
- **Campos BD**: `documents.type`, `documents.language`, `documents.content`, `legal_texts.*`, `cookies.*`
- **Métodos**: `DocumentService::generateCookiePolicy()`, `DocumentController::createCookiePolicy()`

### Tarea 33: Implementación de generador de avisos legales
- Generar aviso legal personalizado
- Adaptación por país y jurisdicción
- **Campos BD**: `documents.type`, `documents.language`, `documents.content`, `legal_texts.*`
- **Métodos**: `DocumentService::generateLegalNotice()`, `DocumentController::createLegalNotice()`

### Tarea 34: Implementación de generador de términos y condiciones
- Generar términos adaptados al tipo de negocio
- Personalización multiidioma
- **Campos BD**: `documents.type`, `documents.language`, `documents.content`, `legal_texts.*`
- **Métodos**: `DocumentService::generateTerms()`, `DocumentController::createTerms()`

### Tarea 35: Gestor de descarga de documentos
- Implementar sistema de descarga en múltiples formatos
- Seguimiento de descargas
- **Campos BD**: `documents.download_count`, `documents.downloaded_at`
- **Métodos**: `DocumentController::download()`, `DocumentService::generateFormat()`

## Fase 8: Sistema de pagos (Tareas 36-40)

### Tarea 36: Integración con Stripe
- Implementar servicio de conexión con Stripe
- Configurar webhooks
- **Campos BD**: N/A (configuración en variables de entorno)
- **Métodos**: `StripeService::initialize()`, `StripeService::createSession()`

### Tarea 37: Implementación de checkout
- Crear página de checkout
- Selección de plan
- **Campos BD**: N/A (procesamiento en Stripe)
- **Métodos**: `PaymentController::checkout()`, `PaymentController::processCheckout()`

### Tarea 38: Gestión de pagos
- Implementar registro de pagos
- Seguimiento de transacciones
- **Campos BD**: `payments.user_id`, `payments.amount`, `payments.currency`, `payments.status`, `payments.payment_method`, `payments.transaction_id`
- **Métodos**: `PaymentController::handleWebhook()`, `PaymentService::recordPayment()`

### Tarea 39: Activación de documentos tras pago
- Implementar lógica de activación
- Notificación al usuario
- **Campos BD**: `documents.status`, `documents.is_generated`
- **Métodos**: `PaymentService::activateDocuments()`, `DocumentService::markAsPublished()`

### Tarea 40: Facturación
- Generar facturas automáticas
- Envío por email
- **Campos BD**: `payments.invoice_number`, `payments.invoice_url`
- **Métodos**: `InvoiceService::generate()`, `PaymentController::sendInvoice()`

## Fase 9: Dashboard y alertas (Tareas 41-45)

### Tarea 41: Implementación de dashboard de usuario
- Crear panel principal de usuario
- Resumen de actividad
- **Campos BD**: `websites.*`, `scans.*`, `payments.*`
- **Métodos**: `DashboardController::index()`, `UserService::getActivitySummary()`

### Tarea 42: Historial de escaneos
- Implementar vista de historial
- Comparativa entre escaneos
- **Campos BD**: `websites.id`, `scans.website_id`, `scans.scan_date`, `scans.score`
- **Métodos**: `DashboardController::history()`, `ScanService::getUserScans()`

### Tarea 43: Sistema de alertas
- Implementar notificaciones de cambios legislativos
- Alertas sobre cookies nuevas detectadas
- **Campos BD**: `alerts.user_id`, `alerts.website_id`, `alerts.type`, `alerts.severity`, `alerts.message`, `alerts.read`
- **Métodos**: `AlertService::create()`, `AlertController::index()`, `AlertController::markAsRead()`

### Tarea 44: Calendario de cumplimiento
- Implementar vista de calendario
- Fechas clave para cumplimiento normativo
- **Campos BD**: `legal_requirements.effective_date`, `alerts.expires_at`
- **Métodos**: `ComplianceController::calendar()`, `ComplianceService::getUpcomingEvents()`

### Tarea 45: Implementación de panel de administración
- Crear panel de administración
- Gestión de usuarios y escaneos
- **Campos BD**: `users.*`, `websites.*`, `scans.*`, `payments.*`
- **Métodos**: `AdminController::index()`, `AdminController::users()`, `AdminController::scans()`, `AdminController::payments()`

## Fase 10: Optimización y finalización (Tareas 46-50)

### Tarea 46: Optimización de rendimiento
- Implementar sistema de caché
- Optimizar consultas a base de datos
- **Campos BD**: N/A (optimización)
- **Métodos**: Mejoras en servicios existentes, implementación de caché

### Tarea 47: Implementación de API para integraciones
- Crear endpoints de API
- Implementar autenticación y autorización
- **Campos BD**: `api_tokens.user_id`, `api_tokens.token`, `api_tokens.abilities`
- **Métodos**: `APIController::scan()`, `APIController::getResults()`, `APIController::generateDocuments()`

### Tarea 48: Implementación de plugins para CMS
- Desarrollar plugin para WordPress
- Desarrollar módulo para PrestaShop
- **Campos BD**: N/A (uso de API)
- **Métodos**: N/A (código externo)

### Tarea 49: Pruebas y corrección de errores
- Realizar pruebas de funcionalidad completa
- Solucionar bugs identificados
- **Campos BD**: Todas las tablas
- **Métodos**: Todos los métodos principales

### Tarea 50: Documentación final y despliegue
- Crear documentación técnica
- Preparar guías de usuario
- Despliegue en producción
- **Campos BD**: N/A
- **Métodos**: N/A

## Calendario de implementación

| Fase | Duración estimada | Dependencias |
|------|-------------------|--------------|
| Fase 1 | 1 semana | Ninguna |
| Fase 2 | 1 semana | Fase 1 |
| Fase 3 | 1 semana | Fase 2 |
| Fase 4 | 2 semanas | Fase 3 |
| Fase 5 | 2 semanas | Fase 4 |
| Fase 6 | 1 semana | Fase 5 |
| Fase 7 | 2 semanas | Fase 5, Fase 6 |
| Fase 8 | 1 semana | Fase 7 |
| Fase 9 | 1 semana | Fase 8 |
| Fase 10 | 2 semanas | Todas las fases |

Tiempo total estimado: 14 semanas (3.5 meses)

## Prioridades y ruta crítica

Las tareas más críticas para el funcionamiento mínimo viable del sistema son:
1. Configuración del entorno (Tarea 1-5)
2. Sistema de autenticación básico (Tareas 6-8)
3. Motor de escaneo (Tareas 16-20)
4. Análisis con OpenAI (Tareas 21-25)
5. Presentación de resultados (Tareas 26-30)
6. Generación de documentos (Tareas 31-35)
7. Sistema de pagos (Tareas 36-40)

Estas tareas deberían priorizarse para tener una versión funcional mínima en aproximadamente 10 semanas. 