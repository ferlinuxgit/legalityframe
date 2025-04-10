# 🧠 Generador de Textos Legales con IA – Documentación Técnica

## 📌 Descripción general

Esta aplicación web permite generar textos legales personalizados para cualquier sitio web mediante inteligencia artificial (IA), utilizando una arquitectura basada en **PHP** y **JavaScript**, con llamadas a la API de OpenAI.

El sistema está dividido en fases: análisis del sitio web, generación de textos legales, pasarela de pago, y entrega del contenido generado. Está diseñada para que sea **autónoma, rápida de usar y escalable**.

---

## 🔧 Tecnologías utilizadas

- **Backend**: PHP (7.4+)
- **Frontend**: HTML5, CSS3, JavaScript (JS Vanilla)
- **IA**: OpenAI API (GPT-4 o modelo compatible)
- **Pagos**: Stripe o PayPal
- **Formato salida**: HTML + PDF + Word (.docx) empaquetados en ZIP
- **Almacenamiento temporal**: Archivos generados en `/storage/generated/`

---

## 🧩 Estructura del sistema

```
/legalgen/
│
├── index.php              → Formulario para analizar sitio web
├── analyze.php            → Analiza la URL del usuario
├── result.php             → Muestra el diagnóstico legal de la web
├── generate.php           → Genera textos legales con IA
├── checkout.php           → Proceso de pago
├── success.php            → Entrega final del pack legal
│
├── assets/
│   ├── style.css          → Estilos de frontend
│   └── script.js          → Validaciones y UX frontend
│
├── includes/
│   ├── config.php         → Claves API, parámetros globales
│   └── helpers.php        → Funciones auxiliares de análisis, logging y AI
│
├── templates/
│   ├── legal_notice.php       → Plantilla: Aviso Legal
│   ├── privacy_policy.php     → Plantilla: Política de Privacidad
│   ├── terms_of_service.php   → Plantilla: Términos y Condiciones
│   ├── cookies_policy.php     → Plantilla: Política de Cookies
│   └── return_policy.php      → Plantilla: Política de Devoluciones
│
├── storage/
│   └── generated/         → Archivos generados por usuario
│
├── .htaccess              → Seguridad + rutas limpias
└── README.md              → Documentación del proyecto
```

---

## ⚙️ Funcionalidades principales

### 1. 📝 Formulario de entrada (index.php)
- El usuario introduce la URL de su sitio web.
- Se valida el formato y se redirige al análisis.

### 2. 🔍 Análisis legal automático (analyze.php + helpers.php)
- El sistema descarga el HTML del sitio.
- Detecta:
  - Presencia de textos legales ya existentes
  - Uso de cookies, GA, Facebook Pixel
  - Formularios de contacto o suscripción
  - CMS o ecommerce activos (WooCommerce, Shopify, etc.)
  - SSL y botones de pago

### 3. ✅ Muestra de resultados (result.php)
- Vista previa de cumplimiento legal
- Evaluación visual con ✅❌
- Recomendación de qué textos faltan
- Botón para continuar con la generación

### 4. 🤖 Generación con IA (generate.php)
- Se construye un prompt dinámico a partir del análisis
- Se generan los textos con OpenAI API
- Los textos son personalizados con el nombre, URL y detalles del negocio
- Se guardan en HTML y preparados para exportación

### 5. 💳 Sistema de pago (checkout.php + success.php)
- Stripe o PayPal (según integración)
- Solo tras el pago se desbloquea la descarga
- Se asocia cada pago con una sesión generada

### 6. 📦 Entrega del pack legal (success.php)
- Se generan archivos PDF y/o Word
- Se empaquetan en un ZIP
- Opción de descarga directa o envío por email
- El pack puede incluir:
  - Aviso legal
  - Política de privacidad
  - Política de cookies
  - Términos y condiciones
  - Política de devoluciones

---

## 🔐 Seguridad y control

- Validación estricta de entradas de usuario
- Logging de errores para depuración
- Protección de descargas mediante tokens o hashes
- Opcional: eliminación automática de archivos viejos en `/storage/generated/`

---

## 🧱 Escalabilidad futura

- Multiidioma (ES, EN, FR…)
- Generación de contratos (freelance, NDA, etc.)
- Integración con WordPress / Shopify
- SaaS con suscripción mensual
- Panel de control para usuarios y administradores
- Funcionalidad API pública para integradores/agencias

---

## 🧪 Tests recomendados

- Análisis de distintas webs reales
- Generación en frío (sin analizar) con datos manuales
- Pruebas con y sin conexión OpenAI
- Validación de compatibilidad PDF / Word
- Pruebas de pago con sandbox de Stripe

---

## 📦 Dependencias sugeridas

- `phpword` o `dompdf` para generación de documentos
- `cURL` habilitado en servidor
- Cuenta OpenAI con acceso a API
- (opcional) `.env` para configuración externa

---

## 🧠 Contribución y mejoras

Este proyecto está pensado para ser generado y ampliado con ayuda de IA. Se recomienda seguir un flujo de desarrollo por fases, priorizando primero el análisis, luego la generación, y finalmente la integración del pago y exportación.

