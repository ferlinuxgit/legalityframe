# Tarea 5: Implementación de sistema multiidioma

## Descripción

Esta tarea implica la creación del sistema de internacionalización de la aplicación, permitiendo que todo el contenido esté disponible en múltiples idiomas (inicialmente español e inglés) y facilitando la expansión a otros idiomas en el futuro.

## Componentes implementados

1. **Clase Lang**
   - Clase principal para la gestión de traducciones
   - Carga de archivos de idioma según la configuración
   - Métodos para traducir cadenas y textos
   - Soporte para variables en textos traducidos

2. **Archivos de idioma**
   - Archivos PHP que contienen arrays asociativos con textos traducidos
   - Organización por secciones: general, auth, validation, emails, admin
   - Implementación inicial en español e inglés

3. **Middleware de localización**
   - Detección del idioma preferido del usuario
   - Establecimiento del idioma en la sesión
   - Posibilidad de cambiar de idioma en la aplicación

4. **Integración en vistas**
   - Helper global `__()` para acceder a las traducciones
   - Integración en todas las vistas para mostrar textos en el idioma seleccionado

5. **Soporte en modelos y validación**
   - Mensajes de error de validación traducidos
   - Campos de base de datos con soporte para contenido multiidioma

## Estructura de directorios

```
app/
  Config/
    Lang.php
  Middlewares/
    LocaleMiddleware.php
  Helpers/
    functions.php (ampliado con funciones de traducción)
  Views/
    partials/
      language_selector.php
resources/
  lang/
    es/
      general.php
      auth.php
      validation.php
      emails.php
      admin.php
    en/
      general.php
      auth.php
      validation.php
      emails.php
      admin.php
```

## Patrones de diseño utilizados

- **Singleton** para la instancia de Lang
- **Strategy** para la carga de diferentes archivos de idioma
- **Adapter** para manejar diferentes formatos de texto (HTML, texto plano, etc.)

## Flujo de procesamiento de idioma

1. El middleware `LocaleMiddleware` detecta el idioma del usuario:
   - Por parámetro explícito en la URL
   - Por preferencia guardada en sesión
   - Por cabecera HTTP Accept-Language
   - Por defecto según configuración de la aplicación

2. El idioma seleccionado se almacena en la sesión

3. La clase `Lang` carga los archivos de traducción necesarios

4. Las vistas utilizan la función helper `__()` para mostrar textos traducidos

5. El usuario puede cambiar el idioma mediante un selector en la interfaz

## Uso del sistema de idiomas

### Definición de traducciones
```php
// En resources/lang/es/general.php
return [
    'welcome' => 'Bienvenido a LegalityFrame',
    'about_us' => 'Sobre nosotros',
    'contact' => 'Contacto',
    'login' => 'Iniciar sesión',
    'register' => 'Registrarse',
];

// En resources/lang/en/general.php
return [
    'welcome' => 'Welcome to LegalityFrame',
    'about_us' => 'About us',
    'contact' => 'Contact',
    'login' => 'Login',
    'register' => 'Register',
];
```

### Uso en vistas
```php
<h1><?= __('general.welcome') ?></h1>
<a href="/about"><?= __('general.about_us') ?></a>
```

### Uso con variables
```php
// En resources/lang/es/auth.php
return [
    'hello_user' => 'Hola, :name',
    'last_login' => 'Tu último acceso fue :time',
];

// En una vista
<p><?= __('auth.hello_user', ['name' => $user->name]) ?></p>
```

### Cambio de idioma
```php
// En el controlador
public function switchLanguage($lang)
{
    if (array_key_exists($lang, AVAILABLE_LANGUAGES)) {
        $_SESSION['lang'] = $lang;
    }
    return redirect()->back();
}
```

## Rendimiento y optimización

- Implementación de caché para archivos de idioma cargados
- Carga bajo demanda de secciones de idioma para optimizar memoria
- Estructura de archivos diseñada para facilitar su mantenimiento y actualización

## Estado actual

Sistema de internacionalización completamente implementado y funcional, permitiendo la gestión de múltiples idiomas en toda la aplicación. Actualmente soporta español e inglés, con una estructura diseñada para facilitar la adición de más idiomas en el futuro. 