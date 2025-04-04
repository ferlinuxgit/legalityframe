# Explicación detallada de LegalityFrame

## ¿Qué es LegalityFrame?

LegalityFrame es una aplicación web que permite a cualquier propietario de un sitio web realizar una auditoría legal básica y recibir textos legales personalizados mediante inteligencia artificial. Está orientada a pequeñas empresas, emprendedores y agencias que necesitan cumplir con las normativas europeas y españolas (RGPD, LSSI-CE, Ley de Consumidores, etc.), así como con regulaciones internacionales adaptadas a cada mercado.

---

## Objetivo de la app

El objetivo de LegalityFrame es automatizar dos procesos:
1. La **auditoría legal básica** de un sitio web, adaptable a múltiples jurisdicciones e idiomas.
2. La **generación de textos legales personalizados** mediante IA, ajustados a las necesidades de cada cliente y a la normativa específica de su mercado objetivo, a cambio de un pago único.

---

## Funcionalidades principales

### 1. Analizador legal gratuito
- El usuario introduce su dominio (ej. https://miempresa.com).
- Selecciona idioma y jurisdicción para el análisis.
- La app realiza un escaneo de la web:
    - Detecta enlaces hacia Aviso Legal, Política de Privacidad, Política de Cookies y Términos y Condiciones.
    - Descarga el contenido real de esos textos legales.
    - Identifica automáticamente el idioma de los documentos existentes.
    - Analiza el contenido con OpenAI GPT-4 Turbo para detectar:
        - Si cumplen con la normativa seleccionada.
        - Qué elementos faltan o están mal redactados.
        - Nivel de cumplimiento: Alto, Medio o Bajo.
        - Análisis detallado por secciones de cada documento.
        - Conflictos entre diferentes normativas aplicables.
    - **Realiza un análisis técnico completo**:
        - Detecta la presencia o ausencia de banner de cookies y su conformidad.
        - Identifica todas las cookies implementadas en el sitio clasificadas por:
            - Tipo (técnicas, analíticas, publicitarias, de terceros)
            - Duración
            - Dominio de origen
            - Finalidad 
        - Analiza la presencia de píxeles de seguimiento (Facebook, Google, etc.).
        - Detecta scripts de análisis (Google Analytics, Hotjar, etc.).
        - Verifica la existencia de formularios de contacto y su cumplimiento:
            - Presencia de casillas de verificación para consentimiento.
            - Información sobre tratamiento de datos.
            - Enlaces a políticas de privacidad.
        - Comprueba si hay sistemas de newsletter y su conformidad legal.
        - Verifica SSL/TLS y nivel de seguridad del sitio.
        - Analiza transferencias internacionales de datos detectables.
        - Evalúa la accesibilidad web y su impacto legal.
        - Revisa mecanismos de verificación de edad cuando sean necesarios.
- Se genera un **informe visual interactivo** con:
    - Checklist general con códigos de colores.
    - Checklist específico de cada documento.
    - Nota de cumplimiento con explicaciones detalladas.
    - Recomendaciones de mejora paso a paso.
    - Comparativa con sitios similares del mismo sector.
    - Alertas de conflictos entre normativas de diferentes jurisdicciones.
    - **Sección técnica detallada**:
        - Mapa completo de cookies con detalles técnicos y legales.
        - Recomendaciones para configurar correctamente el banner de cookies.
        - Listado de herramientas de seguimiento detectadas.
        - Propuestas para mejorar formularios y procesos de captación de datos.
        - Diagrama de transferencias internacionales de datos identificadas.

### 2. Generación de informe PDF
- El usuario puede descargar el informe de auditoría en formato PDF:
    - Disponible en múltiples idiomas (español, inglés, francés, alemán, italiano, portugués).
    - Incluye logo, colores corporativos y CTA final.
    - Checklist completo y puntuación.
    - Gráficos comparativos y visualización de datos.
    - Resumen ejecutivo y recomendaciones urgentes.
    - Preparado para presentación o archivo.
    - Marca de agua personalizada.
    - Referencias a la legislación específica aplicable según jurisdicción.
    - **Anexo técnico** con detalles sobre cookies, rastreadores y herramientas detectadas.

### 3. Venta de Pack de Textos Legales
- Al finalizar la auditoría se ofrece al usuario un **pack completo**:
    - Aviso Legal.
    - Política de Privacidad.
    - Política de Cookies.
    - Términos y Condiciones (opcional).
    - Política de Devoluciones (opcional).
    - Cláusulas para formularios de contacto.
    - Templates para correos de confirmación de suscripción.
    - Políticas específicas según sector (e-commerce, SaaS, contenidos, etc.).
    - Documentos específicos por jurisdicción (CCPA para California, LGPD para Brasil, etc.).
    - **Scripts personalizados** para implementar banner de cookies adaptado a las cookies detectadas.
- Generados mediante IA con información personalizada:
    - Nombre de empresa.
    - Actividad.
    - Datos de contacto y registro mercantil.
    - Tipos de cookies utilizadas (basado en el análisis técnico).
    - Bases legales del tratamiento.
    - Transferencias internacionales de datos.
    - Proveedores de servicios y encargados del tratamiento.
    - Adaptación a requisitos específicos por país/región.
- Disponibles en múltiples idiomas:
    - Español (España y Latinoamérica).
    - Inglés (UK, EE.UU, Australia).
    - Francés.
    - Alemán.
    - Italiano.
    - Portugués (Portugal y Brasil).
    - Catalán, Euskera y Gallego.
    - Holandés.
    - Sueco.
    - Polaco.

### 4. Generación automática del Pack
- Si el usuario paga (Stripe Checkout):
    - Accede al generador de textos con asistente interactivo.
    - Selección de idiomas y jurisdicciones.
    - Obtiene sus documentos listos para copiar o descargar en múltiples formatos (HTML, PDF, Word).
    - Recibe instrucciones de implementación paso a paso.
    - Obtiene snippets de código para implementar banner de cookies personalizado según las cookies detectadas.
    - Puede solicitar traducciones certificadas para documentos oficiales.
    - Recibe instrucciones técnicas para corregir problemas de tracking y cookies.

### 5. Panel de control y seguimiento
- Dashboard personalizado para cada usuario:
    - Historial de análisis realizados.
    - Alertas de cambios legislativos relevantes por jurisdicción.
    - Recordatorios de actualizaciones necesarias.
    - Calendario de cumplimiento normativo multinacional.
    - Matriz de cumplimiento por país/región.
    - Indicador de riesgo legal por mercado.
    - **Monitor técnico**:
        - Seguimiento periódico de cambios en cookies y rastreadores.
        - Alertas cuando se detectan nuevas herramientas de seguimiento.
        - Recomendaciones de actualización cuando cambian cookies o scripts.

### 6. Implementación automática
- Opción de implementación directa en plataformas populares:
    - WordPress (plugin dedicado)
    - Wix
    - Shopify
    - PrestaShop
    - Magento
    - Joomla
    - Custom PHP sites
    - **Implementación técnica automatizada**:
        - Despliegue directo del banner de cookies personalizado.
        - Instalación de scripts para gestión de consentimientos.
        - Configuración de sistemas de auditoría periódica.

---

## Herramientas técnicas de análisis

La plataforma utiliza diversas tecnologías para realizar el análisis técnico completo:

### Análisis de cookies
- **Scanner de cookies** propio que detecta:
  - Cookies de primera parte
  - Cookies de terceros
  - Cookies persistentes vs. de sesión
  - Cookies esenciales vs. no esenciales
  - Cookies sincronizadas
- **Clasificación automática** según propósito:
  - Técnicas/esenciales
  - Preferencias
  - Estadísticas/analíticas
  - Marketing/publicidad
- **Validación de consentimiento**:
  - Verificación de almacenamiento de consentimiento
  - Análisis de cookies previas al consentimiento
  - Validación de rechazos de cookies

### Análisis de rastreadores
- **Identificación de scripts** de seguimiento:
  - Google Analytics/Tag Manager
  - Facebook Pixel
  - LinkedIn Insight
  - HotJar/CrazyEgg
  - Herramientas de heatmap
- **Evaluación de cumplimiento** RGPD/ePrivacy para cada rastreador
- **Identificación de alternativas** privacy-friendly

### Análisis de formularios
- **Revisión de formularios** de captura de datos:
  - Formularios de contacto
  - Registros de usuario
  - Suscripciones a newsletter
  - Procesos de checkout
- **Validación de elementos legales**:
  - Presencia de casillas de verificación no premarcadas
  - Información clara sobre tratamiento
  - Enlaces a política de privacidad
  - Mecanismos de baja/revocación

### Evaluación de seguridad
- **Verificación de certificados** SSL/TLS
- **Análisis de vulnerabilidades** básicas
- **Comprobación de transferencias** de datos seguras

---

## Soporte para normativas internacionales

LegalityFrame está diseñado para cubrir las principales normativas globales de protección de datos y comercio electrónico:

### Europa
- **RGPD** (Reglamento General de Protección de Datos)
- **ePrivacy Directive** (Directiva sobre Cookies)
- **LSSI-CE** (Ley de Servicios de la Sociedad de la Información - España)
- **LOPD-GDD** (Ley Orgánica de Protección de Datos - España)
- **DPA 2018** (Data Protection Act - Reino Unido)

### América
- **CCPA/CPRA** (California Consumer Privacy Act/California Privacy Rights Act)
- **LGPD** (Lei Geral de Proteção de Dados - Brasil)
- **PIPEDA** (Personal Information Protection and Electronic Documents Act - Canadá)
- **LFPDPPP** (Ley Federal de Protección de Datos Personales en Posesión de Particulares - México)

### Asia-Pacífico
- **PDPA** (Personal Data Protection Act - Singapur)
- **Privacy Act** (Australia)
- **APPI** (Act on the Protection of Personal Information - Japón)

### Oriente Medio y África
- **POPIA** (Protection of Personal Information Act - Sudáfrica)
- **PDPL** (Personal Data Protection Law - Dubái)

### Normativas sectoriales
- **HIPAA** (Health Insurance Portability and Accountability Act - Salud)
- **COPPA** (Children's Online Privacy Protection Act - Menores)
- **PCI DSS** (Payment Card Industry Data Security Standard - Pagos)

---

## Capacidades multiidioma

La plataforma está completamente preparada para operar en múltiples idiomas:

### Interfaz de usuario
- Sistema de internacionalización basado en archivos de idioma
- Detección automática del idioma del navegador
- Posibilidad de cambiar manualmente el idioma
- Soporte RTL para idiomas como árabe y hebreo

### Base de datos de conocimiento legal
- Repositorio de normativas por país/región
- Actualización periódica con cambios legislativos
- Referencias cruzadas entre normativas equivalentes
- Interpretación jurídica por jurisdicción

### Motor de generación de textos
- Templates base en cada idioma revisados por expertos legales
- Adaptación contextual a particularidades lingüísticas
- Terminología legal específica por jurisdicción
- Sistema de control de calidad multiidioma

### Sistema de traducción
- Motor de IA específicamente entrenado en terminología legal
- Opción de traducción automática con revisión humana (premium)
- Validación de coherencia entre versiones de diferentes idiomas
- Certificación de traducciones para documentos oficiales

---

## Flujo de la aplicación

1. El usuario introduce su dominio y selecciona idioma/jurisdicción.
2. Se escanean y analizan los textos legales existentes y elementos técnicos (cookies, rastreadores, etc.).
3. Se muestra un informe gratuito con fallos detectados según normativa aplicable, incluyendo reporte técnico.
4. Se ofrece el pack premium con distintos niveles (básico, estándar, premium) y opciones de idiomas.
5. El usuario puede descargar informe en PDF en su idioma preferido.
6. Puede proceder al pago y obtener textos legales correctos en todos los idiomas seleccionados.
7. Puede activar alertas de seguimiento y actualizaciones por jurisdicción.
8. Recibe asistencia para implementación en su plataforma, incluyendo código personalizado para gestión de cookies.

---

## Arquitectura técnica

### Stack tecnológico
- **Frontend**: 
  - HTML5, JavaScript y Tailwind CSS
  - Alpine.js para interactividad en el cliente
  - Librerías: Chart.js para visualizaciones, PDF.js para previsualización
  - Sistema i18n para internacionalización completa
- **Backend**: 
  - PHP 8.2 con arquitectura MVC
  - API RESTful para comunicación con servicios externos
  - Sistema de caché con APCu y Memcached
  - Middleware de localización e internacionalización
  - Módulos de scraping y análisis técnico web
  - Configuración optimizada para entorno Plesk
- **Base de datos**: 
  - MySQL 8.0 para almacenamiento principal
  - Índices optimizados para búsquedas rápidas
  - Esquema relacional completo para usuarios, análisis y textos generados
  - Soporte para caracteres UTF-8MB4 (soporte completo unicode)
  - Tablas específicas para clasificación de cookies y rastreadores
  - Particionamiento de tablas para mejor rendimiento
  - Procedimientos almacenados para operaciones complejas
  - Backup automatizado nativo de Plesk
- **Servicios externos**:
  - OpenAI API (GPT-4 Turbo) para análisis y generación
  - DeepL API para verificación de traducciones
  - Stripe para procesamiento de pagos
  - AWS S3 para almacenamiento de informes y backups
  - Servicios de análisis técnico web (detección de cookies y scripts)
- **Infraestructura**:
  - Plesk Obsidian como panel de control
  - Servidor web Apache con mod_php
  - Optimización de PHP-FPM para alta concurrencia
  - Monitorización mediante herramientas nativas de Plesk
  - Certificados SSL Let's Encrypt gestionados por Plesk
  - CDN con puntos de presencia globales
  - Copias de seguridad programadas a través de Plesk

### Configuración MySQL optimizada para Plesk
- InnoDB como motor de almacenamiento predeterminado
- Optimización de buffer pool y caché de consultas
- Configuración específica para entorno compartido o VPS según requisitos
- Índices compuestos para consultas frecuentes
- Monitorización de rendimiento integrada
- Exportación/importación de datos simplificada mediante herramientas de Plesk
- Usuarios y permisos específicos para la aplicación
- Conexiones SSL para comunicación segura con la base de datos

### Seguridad implementada
- Encriptación SSL/TLS en todas las comunicaciones
- Almacenamiento cifrado de datos sensibles
- Autenticación de doble factor
- Protección contra CSRF, XSS y SQL Injection
- Cumplimiento RGPD en el tratamiento de datos
- Aislamiento de datos por región cuando sea necesario
- Firewall de aplicaciones web integrado con Plesk
- Protección contra ataques de fuerza bruta
- Actualizaciones automáticas de seguridad

### Optimización para entorno de producción
- Compresión de archivos estáticos
- Caché de aplicación en múltiples niveles
- Limitación de tasas para prevenir abusos de la API
- Registro de eventos para auditoría y resolución de problemas
- Escalado vertical fácil a través de Plesk
- Scripts de mantenimiento programados
- Gestión de dominios y subdominios simplificada
- Sistema de monitorización y alertas en tiempo real

---

## Beneficios para el usuario

- Descubre gratis si su web cumple la ley en múltiples jurisdicciones.
- Recibe un informe claro y detallado en su idioma.
- Obtiene textos legales completos por un pago único, adaptados a cada mercado objetivo.
- Ahorra tiempo y dinero frente a consultorías tradicionales.
- Actualiza sus documentos automáticamente ante cambios legislativos en cualquier jurisdicción.
- Recibe alertas personalizadas sobre nuevas obligaciones por país.
- Implementación sencilla sin conocimientos técnicos.
- Expansión internacional con seguridad jurídica.
- **Beneficios técnicos adicionales**:
  - Conoce exactamente qué cookies y rastreadores tiene implementados.
  - Recibe código personalizado para gestionar correctamente las cookies.
  - Mejora la transparencia con sus usuarios.
  - Reduce el riesgo de sanciones por implementaciones técnicas incorrectas.

---

## Valor añadido

LegalityFrame no solo automatiza la generación de textos legales, sino que educa al usuario mostrándole por qué su web no cumple y cómo solucionarlo de forma sencilla y directa, facilitando su expansión internacional con total seguridad jurídica. El análisis técnico profundo proporciona una visión completa de la situación real de cumplimiento, más allá de la simple existencia de textos legales.

---

## Estrategia de crecimiento

### Etapa 1: Lanzamiento
- Versión básica con funcionalidades core en español e inglés
- Soporte inicial para normativas UE y España
- Captación de primeros usuarios mediante marketing digital
- Recogida de feedback para mejoras

### Etapa 2: Expansión
- Incorporación de idiomas adicionales (francés, alemán, italiano, portugués)
- Adaptación a normativas de principales países europeos y CCPA (EE.UU.)
- Desarrollo de integraciones con CMS populares
- Expansión del equipo de expertos legales internacionales
- **Incorporación de análisis técnico avanzado**:
  - Detección profunda de cookies y rastreadores
  - Implementación automatizada de soluciones

### Etapa 3: Globalización
- Cobertura completa de normativas en América, Asia-Pacífico y Oriente Medio
- Soporte para más de 15 idiomas
- Implementación de modelo de suscripción para actualizaciones periódicas multinacionales
- Sistema de afiliados para profesionales del sector legal en diferentes países
- Desarrollo de API para integración con otras plataformas

### Etapa 4: Especialización sectorial
- Desarrollo de módulos específicos por industria (salud, finanzas, educación)
- Alianzas con despachos internacionales para validación avanzada
- Herramientas de compliance específicas por sector
- Expansión a nuevos productos legales automatizados
- **Soluciones técnicas sectoriales**:
  - Configuraciones específicas para e-commerce
  - Módulos especializados para análisis de apps
  - Herramientas para cumplimiento IoT y wearables

---

## Métricas de éxito

- Número de análisis gratuitos realizados por idioma/país
- Tasa de conversión a compras por región
- Valoración media de los textos generados por idioma
- Tiempo medio de permanencia en la plataforma
- NPS (Net Promoter Score) por mercado
- Reducción de tiempo en comparación con procesos manuales
- Expansión de usuarios por jurisdicciones
- Adopción de funcionalidades multiidioma
- **Métricas técnicas**:
  - Precisión en la detección de cookies y rastreadores
  - Tasa de implementación de soluciones técnicas propuestas
  - Mejora en puntuaciones de cumplimiento tras implementación

