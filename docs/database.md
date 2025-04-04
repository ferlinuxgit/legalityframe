# Estructura de la base de datos de LegalityFrame

Este documento detalla la estructura completa de la base de datos MySQL para el sistema LegalityFrame, incluyendo todas las tablas, campos, relaciones, índices y restricciones.

## Diagrama de Relaciones

```
+---------------------+       +----------------------+       +----------------------+
| users               |       | websites             |       | scans                |
+---------------------+       +----------------------+       +----------------------+
| id                  |<----->| id                   |<----->| id                   |
| name                |       | user_id              |       | website_id           |
| email               |       | domain               |       | scan_date            |
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
| created_at          |       | compliance_status    |       | suggestions          |
| updated_at          |       | created_at           |       | created_at           |
+---------------------+       +----------------------+       +----------------------+
```

## Definición de Tablas SQL

A continuación se presenta el código SQL completo para crear todas las tablas con sus relaciones, índices y restricciones.

### 1. Tabla `users`

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

### 2. Tabla `websites`

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

### 3. Tabla `scans`

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

### 4. Tabla `cookies`

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

### 5. Tabla `trackers`

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

### 6. Tabla `form_analysis`

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

### 7. Tabla `documents`

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

### 8. Tabla `payments`

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

### 9. Tabla `alerts`

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

### 10. Tabla `legal_requirements`

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

### 11. Tabla `scan_compliance`

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

### 12. Tabla `legal_texts`

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

### 13. Tabla `password_resets`

```sql
CREATE TABLE `password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  KEY `email_index` (`email`),
  KEY `token_index` (`token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### 14. Tabla `sessions`

```sql
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
```

### 15. Tabla `api_tokens`

```sql
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

## Datos de ejemplo

A continuación se presentan algunos datos de ejemplo para inicializar las tablas principales.

### Datos para `users`

```sql
INSERT INTO `users` (`name`, `email`, `password`, `status`, `role`, `language`)
VALUES
('Admin Usuario', 'admin@legalityframe.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'active', 'admin', 'es'),
('Usuario Ejemplo', 'usuario@ejemplo.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'active', 'user', 'es'),
('John Doe', 'john@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'active', 'user', 'en');
```

### Datos para `legal_requirements`

```sql
INSERT INTO `legal_requirements` (`jurisdiction`, `category`, `name`, `description`, `legislation_reference`, `importance`)
VALUES
('EU', 'privacy', 'Política de Privacidad', 'Se requiere una política de privacidad completa según RGPD', 'GDPR Art. 13, 14', 'mandatory'),
('EU', 'cookies', 'Banner de Cookies', 'Banner de cookies con opción de rechazo y consentimiento previo', 'ePrivacy Directive, GDPR', 'mandatory'),
('Spain', 'privacy', 'Registro AEPD', 'Registro de actividades de tratamiento', 'LOPDGDD Art. 31', 'mandatory'),
('Spain', 'cookies', 'Capa de información por niveles', 'Información por capas en cookies', 'Guía AEPD Cookies', 'recommended'),
('US-CA', 'privacy', 'Aviso Do Not Sell', 'Aviso sobre no vender información personal', 'CCPA Section 1798.135', 'mandatory');
```

## Notas de mantenimiento

### Índices y optimización

La estructura de la base de datos incluye índices cuidadosamente seleccionados para optimizar las consultas más frecuentes:

1. **Índices de clave primaria** en todas las tablas.
2. **Índices de clave foránea** para optimizar las uniones.
3. **Índices compuestos** para consultas específicas frecuentes.
4. **Índices para columnas de estado** que se usan en filtros comunes.

### Particionamiento para tablas grandes

Para tablas que pueden crecer significativamente, como `scans` y `cookies`, se recomienda considerar el particionamiento:

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

### Procedimientos almacenados útiles

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

## Mantenimiento de la base de datos

### Respaldo regular

Se recomienda configurar respaldos automáticos diarios a través de Plesk:

1. Respaldos completos: Semanal
2. Respaldos incrementales: Diario
3. Retención: 30 días

### Verificaciones periódicas

Ejecutar periódicamente las siguientes verificaciones:

```sql
-- Verificar y reparar tablas
CHECK TABLE users, websites, scans, documents;
REPAIR TABLE IF NECESSARY users, websites, scans, documents;

-- Optimizar tablas
OPTIMIZE TABLE users, websites, scans, documents;

-- Analizar tablas para actualizar estadísticas
ANALYZE TABLE users, websites, scans, documents;
```

### Monitor de rendimiento

Configurar monitorización para consultas lentas:

```sql
SET GLOBAL slow_query_log = 'ON';
SET GLOBAL long_query_time = 2;
SET GLOBAL slow_query_log_file = '/var/log/mysql/mysql-slow.log';
``` 