# Tarea 2: Creación de la estructura de base de datos

## Objetivo
Crear toda la estructura de la base de datos para LegalityFrame según la definición en `database.md`, incluyendo tablas, índices, restricciones y procedimientos almacenados.

## Pasos detallados

### 1. Preparación del entorno
- Acceso a la base de datos vía Plesk o terminal:
  ```bash
  mysql -u lfuser -p legalityframe
  ```
- O mediante phpMyAdmin desde el panel de Plesk

### 2. Crear las tablas principales

#### Tabla `users`
```sql
CREATE TABLE `users` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `status` enum('active','inactive','suspended') NOT NULL DEFAULT 'active',
  `role` enum('user','admin','superadmin') NOT NULL DEFAULT 'user',
  `last_login` datetime DEFAULT NULL,
  `email_verified_at` datetime DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `language` varchar(10) NOT NULL DEFAULT 'es',
  `settings_json` json DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email_unique` (`email`),
  KEY `status_index` (`status`),
  KEY `role_index` (`role`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

#### Tabla `websites`
```sql
CREATE TABLE `websites` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(11) UNSIGNED NOT NULL,
  `domain` varchar(255) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text,
  `status` enum('active','inactive','analyzing') NOT NULL DEFAULT 'active',
  `last_scan_id` int(11) UNSIGNED DEFAULT NULL,
  `settings_json` json DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `domain_index` (`domain`),
  KEY `user_id_foreign` (`user_id`),
  KEY `status_index` (`status`),
  CONSTRAINT `websites_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

#### Tabla `scans`
```sql
CREATE TABLE `scans` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `website_id` int(11) UNSIGNED NOT NULL,
  `scan_date` datetime NOT NULL,
  `status` enum('pending','processing','completed','failed') NOT NULL DEFAULT 'pending',
  `progress` tinyint(3) UNSIGNED DEFAULT 0,
  `score` tinyint(3) UNSIGNED DEFAULT NULL,
  `results_json` json DEFAULT NULL,
  `report_path` varchar(255) DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `error_message` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `website_id_foreign` (`website_id`),
  KEY `status_index` (`status`),
  KEY `scan_date_index` (`scan_date`),
  CONSTRAINT `scans_website_id_foreign` FOREIGN KEY (`website_id`) REFERENCES `websites` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### 3. Crear las tablas para análisis

#### Tabla `cookies`
```sql
CREATE TABLE `cookies` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `scan_id` int(11) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `domain` varchar(255) NOT NULL,
  `category` enum('necessary','preferences','statistics','marketing','unclassified') NOT NULL DEFAULT 'unclassified',
  `duration` varchar(100) DEFAULT NULL,
  `duration_days` int(11) DEFAULT NULL,
  `purpose` text,
  `provider` varchar(255) DEFAULT NULL,
  `is_essential` tinyint(1) NOT NULL DEFAULT 0,
  `is_secure` tinyint(1) NOT NULL DEFAULT 0,
  `is_httponly` tinyint(1) NOT NULL DEFAULT 0,
  `is_session` tinyint(1) NOT NULL DEFAULT 0,
  `path` varchar(255) DEFAULT '/',
  `value_sample` text,
  `compliance_issues` text,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `scan_id_foreign` (`scan_id`),
  KEY `category_index` (`category`),
  KEY `name_domain_index` (`name`, `domain`),
  CONSTRAINT `cookies_scan_id_foreign` FOREIGN KEY (`scan_id`) REFERENCES `scans` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

#### Tabla `trackers`
```sql
CREATE TABLE `trackers` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `scan_id` int(11) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `category` enum('analytics','advertising','social','essential','other') NOT NULL DEFAULT 'other',
  `url` varchar(255) DEFAULT NULL,
  `script_source` text,
  `purpose` text,
  `provider` varchar(255) DEFAULT NULL,
  `compliance_status` enum('compliant','non_compliant','unknown') NOT NULL DEFAULT 'unknown',
  `compliance_issues` text,
  `privacy_policy_url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `scan_id_foreign` (`scan_id`),
  KEY `category_index` (`category`),
  CONSTRAINT `trackers_scan_id_foreign` FOREIGN KEY (`scan_id`) REFERENCES `scans` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

#### Tabla `form_analysis`
```sql
CREATE TABLE `form_analysis` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `scan_id` int(11) UNSIGNED NOT NULL,
  `form_url` varchar(255) NOT NULL,
  `form_id` varchar(100) DEFAULT NULL,
  `form_type` enum('contact','newsletter','registration','checkout','other') NOT NULL DEFAULT 'other',
  `has_checkboxes` tinyint(1) NOT NULL DEFAULT 0,
  `has_privacy_link` tinyint(1) NOT NULL DEFAULT 0,
  `has_checkbox_default_checked` tinyint(1) NOT NULL DEFAULT 0,
  `has_clear_purpose` tinyint(1) NOT NULL DEFAULT 0,
  `compliant` tinyint(1) NOT NULL DEFAULT 0,
  `fields_json` json DEFAULT NULL,
  `suggestions` text,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `scan_id_foreign` (`scan_id`),
  KEY `form_type_index` (`form_type`),
  CONSTRAINT `form_analysis_scan_id_foreign` FOREIGN KEY (`scan_id`) REFERENCES `scans` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### 4. Crear las tablas para documentos y cumplimiento

#### Tabla `documents`
```sql
CREATE TABLE `documents` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `scan_id` int(11) UNSIGNED DEFAULT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `type` enum('privacy_policy','cookie_policy','terms_of_service','legal_notice','return_policy','form_clauses','other') NOT NULL,
  `language` varchar(10) NOT NULL DEFAULT 'es',
  `title` varchar(255) NOT NULL,
  `content` longtext NOT NULL,
  `status` enum('draft','published','archived') NOT NULL DEFAULT 'draft',
  `version` varchar(10) DEFAULT '1.0',
  `is_generated` tinyint(1) NOT NULL DEFAULT 0,
  `is_custom` tinyint(1) NOT NULL DEFAULT 0,
  `generated_at` datetime DEFAULT NULL,
  `downloaded_at` datetime DEFAULT NULL,
  `download_count` int(11) UNSIGNED DEFAULT 0,
  `params_json` json DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `scan_id_foreign` (`scan_id`),
  KEY `user_id_foreign` (`user_id`),
  KEY `type_language_index` (`type`, `language`),
  KEY `status_index` (`status`),
  CONSTRAINT `documents_scan_id_foreign` FOREIGN KEY (`scan_id`) REFERENCES `scans` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `documents_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

#### Tabla `legal_requirements`
```sql
CREATE TABLE `legal_requirements` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `jurisdiction` varchar(100) NOT NULL,
  `category` enum('privacy','cookies','ecommerce','accessibility','ageVerification','other') NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `legislation_reference` varchar(255) DEFAULT NULL,
  `importance` enum('mandatory','recommended','optional') NOT NULL DEFAULT 'mandatory',
  `effective_date` date DEFAULT NULL,
  `expiry_date` date DEFAULT NULL,
  `details_json` json DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `jurisdiction_index` (`jurisdiction`),
  KEY `category_index` (`category`),
  KEY `importance_index` (`importance`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

#### Tabla `scan_compliance`
```sql
CREATE TABLE `scan_compliance` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `scan_id` int(11) UNSIGNED NOT NULL,
  `legal_requirement_id` int(11) UNSIGNED NOT NULL,
  `status` enum('compliant','non_compliant','partially_compliant','not_applicable') NOT NULL,
  `score` tinyint(3) UNSIGNED DEFAULT NULL,
  `details` text,
  `recommendation` text,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `scan_requirement_unique` (`scan_id`, `legal_requirement_id`),
  KEY `scan_id_foreign` (`scan_id`),
  KEY `legal_requirement_id_foreign` (`legal_requirement_id`),
  KEY `status_index` (`status`),
  CONSTRAINT `scan_compliance_scan_id_foreign` FOREIGN KEY (`scan_id`) REFERENCES `scans` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `scan_compliance_legal_requirement_id_foreign` FOREIGN KEY (`legal_requirement_id`) REFERENCES `legal_requirements` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

#### Tabla `legal_texts`
```sql
CREATE TABLE `legal_texts` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `type` enum('privacy_policy','cookie_policy','terms_of_service','legal_notice','return_policy','form_clauses','other') NOT NULL,
  `jurisdiction` varchar(100) NOT NULL,
  `language` varchar(10) NOT NULL,
  `section_key` varchar(100) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `required` tinyint(1) NOT NULL DEFAULT 1,
  `optional_parameters_json` json DEFAULT NULL,
  `order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `type_jurisdiction_language_section_unique` (`type`, `jurisdiction`, `language`, `section_key`),
  KEY `type_index` (`type`),
  KEY `jurisdiction_language_index` (`jurisdiction`, `language`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### 5. Crear tablas de sistema y utilidades

#### Tabla `payments`
```sql
CREATE TABLE `payments` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(11) UNSIGNED NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `currency` varchar(3) NOT NULL DEFAULT 'EUR',
  `status` enum('pending','completed','failed','refunded') NOT NULL DEFAULT 'pending',
  `payment_method` varchar(50) DEFAULT NULL,
  `transaction_id` varchar(255) DEFAULT NULL,
  `order_id` varchar(255) DEFAULT NULL,
  `product_id` varchar(100) DEFAULT NULL,
  `product_name` varchar(255) DEFAULT NULL,
  `invoice_number` varchar(50) DEFAULT NULL,
  `invoice_url` varchar(255) DEFAULT NULL,
  `metadata_json` json DEFAULT NULL,
  `billing_address_json` json DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id_foreign` (`user_id`),
  KEY `status_index` (`status`),
  KEY `transaction_id_index` (`transaction_id`),
  CONSTRAINT `payments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

#### Tabla `alerts`
```sql
CREATE TABLE `alerts` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(11) UNSIGNED NOT NULL,
  `website_id` int(11) UNSIGNED DEFAULT NULL,
  `type` enum('law_change','cookie_update','tracker_update','document_expiry','security','system') NOT NULL,
  `severity` enum('info','warning','critical') NOT NULL DEFAULT 'info',
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `read` tinyint(1) NOT NULL DEFAULT 0,
  `action_url` varchar(255) DEFAULT NULL,
  `action_text` varchar(50) DEFAULT NULL,
  `data_json` json DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id_foreign` (`user_id`),
  KEY `website_id_foreign` (`website_id`),
  KEY `type_index` (`type`),
  KEY `read_index` (`read`),
  KEY `severity_index` (`severity`),
  CONSTRAINT `alerts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `alerts_website_id_foreign` FOREIGN KEY (`website_id`) REFERENCES `websites` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

#### Tablas de autenticación y seguridad
```sql
CREATE TABLE `password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  KEY `email_index` (`email`),
  KEY `token_index` (`token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` int(11) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text,
  `payload` text NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id_index` (`user_id`),
  KEY `last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `api_tokens` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(11) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text,
  `last_used_at` datetime DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `token_unique` (`token`),
  KEY `user_id_foreign` (`user_id`),
  CONSTRAINT `api_tokens_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### 6. Crear procedimientos almacenados

#### Procedimiento para limpiar datos antiguos
```sql
DELIMITER //

CREATE PROCEDURE `cleanup_old_data`()
BEGIN
    -- Eliminar escaneos temporales antiguos (más de 30 días)
    DELETE FROM `scans` WHERE `status` = 'pending' AND `created_at` < DATE_SUB(NOW(), INTERVAL 30 DAY);
    
    -- Marcar alertas antiguas como leídas (más de 60 días)
    UPDATE `alerts` SET `read` = 1 WHERE `created_at` < DATE_SUB(NOW(), INTERVAL 60 DAY) AND `read` = 0;
    
    -- Otras tareas de limpieza según sea necesario
END //

DELIMITER ;
```

#### Procedimiento para generar informe de cumplimiento
```sql
DELIMITER //

CREATE PROCEDURE `generate_compliance_report`(IN website_id INT)
BEGIN
    SELECT 
        lr.category,
        lr.name,
        COUNT(CASE WHEN sc.status = 'compliant' THEN 1 END) as compliant_count,
        COUNT(CASE WHEN sc.status = 'non_compliant' THEN 1 END) as non_compliant_count,
        COUNT(CASE WHEN sc.status = 'partially_compliant' THEN 1 END) as partially_compliant_count,
        COUNT(CASE WHEN sc.status = 'not_applicable' THEN 1 END) as not_applicable_count
    FROM 
        legal_requirements lr
    LEFT JOIN 
        scan_compliance sc ON lr.id = sc.legal_requirement_id
    LEFT JOIN 
        scans s ON sc.scan_id = s.id
    WHERE 
        s.website_id = website_id
    GROUP BY 
        lr.category, lr.name;
END //

DELIMITER ;
```

### 7. Insertar datos de ejemplo para pruebas

#### Datos de ejemplo para `users`
```sql
INSERT INTO `users` (`name`, `email`, `password`, `status`, `role`, `language`)
VALUES
('Admin Usuario', 'admin@legalityframe.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'active', 'admin', 'es'),
('Usuario Ejemplo', 'usuario@ejemplo.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'active', 'user', 'es'),
('John Doe', 'john@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'active', 'user', 'en');
```

#### Datos de ejemplo para `legal_requirements`
```sql
INSERT INTO `legal_requirements` (`jurisdiction`, `category`, `name`, `description`, `legislation_reference`, `importance`)
VALUES
('EU', 'privacy', 'Política de Privacidad', 'Se requiere una política de privacidad completa según RGPD', 'GDPR Art. 13, 14', 'mandatory'),
('EU', 'cookies', 'Banner de Cookies', 'Banner de cookies con opción de rechazo y consentimiento previo', 'ePrivacy Directive, GDPR', 'mandatory'),
('Spain', 'privacy', 'Registro AEPD', 'Registro de actividades de tratamiento', 'LOPDGDD Art. 31', 'mandatory'),
('Spain', 'cookies', 'Capa de información por niveles', 'Información por capas en cookies', 'Guía AEPD Cookies', 'recommended'),
('US-CA', 'privacy', 'Aviso Do Not Sell', 'Aviso sobre no vender información personal', 'CCPA Section 1798.135', 'mandatory');
```

### 8. Configuración de particionamiento (opcional)

Para tablas que pueden crecer significativamente, como `scans` y `cookies`, se recomienda configurar el particionamiento:

```sql
-- Ejemplo de particionamiento para la tabla 'scans' por mes
ALTER TABLE `scans` 
PARTITION BY RANGE (TO_DAYS(scan_date)) (
    PARTITION p_2023_01 VALUES LESS THAN (TO_DAYS('2023-02-01')),
    PARTITION p_2023_02 VALUES LESS THAN (TO_DAYS('2023-03-01')),
    -- Añadir más particiones según sea necesario
    PARTITION p_future VALUES LESS THAN MAXVALUE
);
```

### 9. Verificar la estructura y relaciones

Ejecutar las siguientes consultas para verificar que todas las tablas y relaciones se han creado correctamente:

```sql
-- Listar todas las tablas
SHOW TABLES;

-- Verificar estructura de tablas específicas
DESCRIBE users;
DESCRIBE websites;
DESCRIBE scans;

-- Verificar relaciones
SELECT 
    TABLE_NAME,
    COLUMN_NAME,
    CONSTRAINT_NAME,
    REFERENCED_TABLE_NAME,
    REFERENCED_COLUMN_NAME
FROM
    INFORMATION_SCHEMA.KEY_COLUMN_USAGE
WHERE
    REFERENCED_TABLE_SCHEMA = 'legalityframe'
ORDER BY 
    TABLE_NAME, COLUMN_NAME;
```

### 10. Optimizar para rendimiento

Ejecutar las siguientes consultas para optimizar la base de datos:

```sql
-- Analizar tablas para actualizar estadísticas
ANALYZE TABLE users, websites, scans, documents;

-- Optimizar tablas
OPTIMIZE TABLE users, websites, scans, documents;
```

## Script completo para ejecución

Para facilitar la implementación, se puede crear un archivo SQL único con todas las consultas y ejecutarlo directamente en la base de datos:

1. Crear un archivo `setup_db.sql` con todas las consultas anteriores
2. Ejecutar el script mediante:
   ```bash
   mysql -u lfuser -p legalityframe < setup_db.sql
   ```

## Problemas comunes y soluciones

### Error de sintaxis SQL
- Verificar la versión de MySQL (debe ser 8.0+)
- Comprobar que el script no contiene caracteres especiales inadecuados

### Error "Table already exists"
- Añadir `DROP TABLE IF EXISTS` antes de cada `CREATE TABLE`
- O ejecutar primero un script para eliminar todas las tablas existentes

### Error de clave foránea
- Asegurarse de crear las tablas en el orden correcto (primero las tablas padre)
- Verificar que los tipos de datos coincidan en las relaciones

### Error "JSON column is not supported"
- Verificar que la versión de MySQL soporta JSON (5.7.8+ o preferiblemente 8.0+)

## Resultado esperado

Una vez completados estos pasos, tendremos una base de datos completamente configurada con:
- Todas las tablas necesarias para la aplicación
- Índices optimizados para consultas frecuentes
- Relaciones entre tablas correctamente establecidas
- Procedimientos almacenados para tareas comunes
- Datos de ejemplo para pruebas iniciales

Este esquema de base de datos proporciona una estructura sólida para todas las funcionalidades de LegalityFrame, incluyendo:
- Gestión de usuarios y autenticación
- Escaneo y análisis de sitios web
- Detección y clasificación de cookies y rastreadores
- Análisis de formularios y cumplimiento normativo
- Generación y gestión de documentos legales
- Procesamiento de pagos
- Sistema de alertas y notificaciones
- APIs para integraciones externas 