# LegalityFrame

Sistema de automatización de cumplimiento legal para sitios web.

## Descripción

LegalityFrame es una plataforma que permite analizar sitios web para detectar problemas de cumplimiento legal relacionados con cookies, políticas de privacidad, avisos legales y términos de uso. Utilizando inteligencia artificial, la plataforma genera documentos legales personalizados y adaptados a la normativa aplicable.

## Características principales

- Escaneo automático de sitios web
- Detección de cookies y clasificación automática
- Análisis de políticas existentes
- Generación de documentos legales personalizados:
  - Política de privacidad
  - Política de cookies
  - Aviso legal
  - Términos y condiciones
- Dashboard para seguimiento de cumplimiento
- Sistema de alertas para cambios legislativos
- Múltiples idiomas (español e inglés)

## Requisitos técnicos

- PHP 8.0 o superior
- MySQL 8.0 o superior
- Composer
- Extensiones PHP:
  - PDO
  - Curl
  - Mbstring
  - OpenSSL
  - JSON
  - FileInfo

## Instalación

1. Clonar el repositorio:
```
git clone https://github.com/username/legalityframe.git
cd legalityframe
```

2. Instalar dependencias:
```
composer install
```

3. Copiar el archivo de configuración:
```
cp .env.example .env
```

4. Configurar las variables de entorno en el archivo `.env`

5. Crear la base de datos y ejecutar las migraciones:
```
mysql -u root -p -e "CREATE DATABASE legalityframe CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

6. Generar las claves de acceso:
```
php scripts/generate_keys.php
```

7. Configurar el servidor web para apuntar al directorio `public` como raíz

## Estructura del proyecto

- **app/**: Código fuente principal
  - **Config/**: Configuraciones y definiciones
  - **Controllers/**: Controladores
  - **Middlewares/**: Middlewares para procesamiento de solicitudes
  - **Models/**: Modelos de datos
  - **Services/**: Servicios de negocio
  - **Views/**: Plantillas de vista
  - **Helpers/**: Funciones auxiliares
- **config/**: Archivos de configuración
- **public/**: Archivos públicos y punto de entrada
- **storage/**: Almacenamiento de archivos
- **tests/**: Pruebas automatizadas

## Licencia

Todos los derechos reservados. Este software es propiedad de LegalityFrame y no puede ser redistribuido sin autorización expresa. 