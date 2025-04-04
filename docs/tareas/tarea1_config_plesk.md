# Tarea 1: Configuración del entorno Plesk

## Objetivo
Configurar el entorno Plesk en el VPS para soportar el desarrollo y despliegue de LegalityFrame.

## Pasos detallados

### 1. Acceso al panel Plesk
- Acceder a la URL del panel Plesk (típicamente https://tu-ip-o-dominio:8443)
- Iniciar sesión con las credenciales de administrador

### 2. Creación del dominio para LegalityFrame
- En el panel lateral, ir a "Dominios"
- Hacer clic en "Añadir dominio"
- Completar la información:
  - Nombre de dominio: `legalityframe.com` (o el dominio que corresponda)
  - Seleccionar "Crear y alojar el sitio web físicamente en este servidor"
  - Directorio del documento: `/httpdocs` (predeterminado)
  - Crear base de datos y cuenta FTP: Sí

### 3. Configuración de PHP 8.2
- Ir al dominio recién creado
- En "Configuración web", seleccionar la pestaña "PHP"
- Cambiar la versión a PHP 8.2
- Establecer el modo de PHP-FPM para mejor rendimiento
- Configurar los siguientes límites:
  - Límite de memoria: 256M
  - Tiempo máximo de ejecución: 120 segundos
  - Tamaño máximo de subida: 16M
- Guardar cambios

### 4. Habilitar extensiones PHP requeridas
- En la misma sección de PHP, ir a "Extensiones PHP"
- Habilitar las siguientes extensiones:
  - mysqli
  - curl
  - gd
  - mbstring
  - xml
  - zip
  - intl
  - opcache
  - apcu
- Guardar cambios

### 5. Creación de la base de datos MySQL 8.0
- Ir a "Bases de datos"
- Hacer clic en "Añadir base de datos"
- Completar la información:
  - Nombre de base de datos: `legalityframe`
  - Tipo: MySQL 8.0
  - Cotejamiento: utf8mb4_unicode_ci
  - Nuevo usuario:
    - Nombre: `lfuser` (o el que prefieras)
    - Contraseña: [generar contraseña segura]
  - Privilegios: Todos
- Guardar cambios

### 6. Configuración de HTTPS con Let's Encrypt
- Ir a "Certificados SSL/TLS"
- Hacer clic en "Let's Encrypt"
- Seleccionar el dominio y los subdominios
- Habilitar "Redirigir HTTP a HTTPS"
- Activar renovación automática
- Hacer clic en "Obtener"

### 7. Configuración de directorio del proyecto
- Acceder al servidor vía SSH
- Navegar al directorio del dominio:
  ```bash
  cd /var/www/vhosts/legalityframe.com/
  ```
- Limpiar el directorio httpdocs si tiene contenido por defecto:
  ```bash
  rm -rf httpdocs/*
  ```
- Configurar Git para clonar el repositorio (opcional):
  ```bash
  git clone [URL_DEL_REPOSITORIO] httpdocs/
  ```
- O crear la estructura de directorios manualmente:
  ```bash
  mkdir -p httpdocs/{public,app,resources,config,database,storage,logs}
  ```

### 8. Configuración de permisos
- Establecer permisos adecuados:
  ```bash
  find httpdocs/ -type d -exec chmod 755 {} \;
  find httpdocs/ -type f -exec chmod 644 {} \;
  chmod -R 775 httpdocs/storage/ httpdocs/logs/ httpdocs/public/uploads/
  chown -R [USUARIO_PLESK]:[GRUPO_PLESK] httpdocs/
  ```

### 9. Configuración de Apache
- En el panel Plesk, ir a "Configuración Apache y nginx"
- Habilitar la compresión GZIP
- Activar HTTP/2
- Añadir al archivo .htaccess para redirección y seguridad:
  ```
  <IfModule mod_rewrite.c>
      RewriteEngine On
      RewriteCond %{HTTPS} off
      RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
      
      RewriteCond %{REQUEST_FILENAME} !-d
      RewriteCond %{REQUEST_FILENAME} !-f
      RewriteRule ^ index.php [L]
  </IfModule>
  
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
  ```

### 10. Programación de tareas (Cron)
- En el panel Plesk, ir a "Tareas programadas"
- Añadir las siguientes tareas:
  - Actualización de datos legales: Diaria a las 01:00
    ```
    php /var/www/vhosts/legalityframe.com/httpdocs/tools/update_legal_data.php
    ```
  - Limpieza de archivos temporales: Cada 6 horas
    ```
    php /var/www/vhosts/legalityframe.com/httpdocs/tools/cleanup.php
    ```
  - Backup de base de datos: Diaria a las 03:00
    ```
    php /var/www/vhosts/legalityframe.com/httpdocs/tools/backup_db.php
    ```
  - Monitoreo de cambios legislativos: Semanal (Lunes a las 02:00)
    ```
    php /var/www/vhosts/legalityframe.com/httpdocs/tools/check_legislation.php
    ```

### 11. Verificación de la configuración
- PHP: Verificar la versión y las extensiones
  ```bash
  php -v
  php -m
  ```
- Base de datos: Verificar la conexión
  ```bash
  mysql -u lfuser -p legalityframe
  ```
- Servidor web: Verificar que Apache está ejecutándose
  ```bash
  systemctl status apache2
  ```

## Configuración adicional (opcional)

### Optimización de MySQL
- En "Herramientas y configuración" > "Configuración MySQL"
- Ajustar según las necesidades del proyecto:
  - Tamaño de buffer pool: 128M mínimo
  - Tamaño del caché de consultas: 32M

### Configuración de correo electrónico
- En "Correo" > "Configuración de correo"
- Configurar cuentas de correo para notificaciones del sistema

### Configuración de seguridad adicional
- En "Herramientas y configuración" > "Seguridad"
- Habilitar el Firewall de Plesk
- Configurar reglas para proteger servicios

## Resultado esperado
Una vez completados estos pasos, tendremos un servidor Plesk completamente configurado con:
- Dominio configurado con SSL
- PHP 8.2 con todas las extensiones necesarias
- Base de datos MySQL 8.0 con cotejamiento utf8mb4_unicode_ci
- Estructura de directorios y permisos adecuados
- Tareas programadas para mantenimiento
- Optimizaciones de servidor web y base de datos

## Problemas comunes y soluciones

### Error "Unable to allocate memory"
- Aumentar el límite de memoria en la configuración de PHP
- Optimizar la configuración de MySQL

### Error "Permission denied"
- Verificar los permisos de los directorios
- Asegurarse de que los archivos pertenezcan al usuario correcto

### Error de conexión a la base de datos
- Verificar las credenciales de la base de datos
- Comprobar que el servicio MySQL está funcionando

### Certificado SSL no se renueva automáticamente
- Verificar la configuración de Let's Encrypt en Plesk
- Comprobar que el dominio resuelve correctamente a la IP del servidor 